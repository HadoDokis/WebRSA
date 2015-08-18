<?php
	/**
	 * Code source de la classe WebrsaRechercheTraitementpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheTraitementpcg66 ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheTraitementpcg66 extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheTraitementpcg66';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Traitementspcgs66.search.fields',
			'Traitementspcgs66.search.innerTable',
			'Traitementspcgs66.exportcsv'
		);

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types += array(
				'Calculdroitrsa' => 'INNER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'INNER',
				'Structurereferente' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
				'Traitementpcg66' => 'INNER',
				'Personnepcg66' => 'INNER',
				'Dossierpcg66' => 'INNER',
				'Situationpdo' => 'LEFT OUTER',
				'Detailcalculdroitrsa' => 'LEFT OUTER',
				'User' => 'LEFT OUTER',
				'Descriptionpdo' => 'LEFT OUTER',
			);
			
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			
			$Personnepcg66 = ClassRegistry::init( 'Personnepcg66' );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Allocataire->searchQuery( $types, 'Personnepcg66' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Personnepcg66,
							$Personnepcg66->Traitementpcg66,
							$Personnepcg66->Personne->PersonneReferent,
							$Personnepcg66->Dossierpcg66,
							$Personnepcg66->Dossierpcg66->User,
							$Personnepcg66->Personnepcg66Situationpdo->Situationpdo
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Personnepcg66.id',
						'Personnepcg66.personne_id',
						'Dossierpcg66.id',
						
						'(SELECT COUNT("fichiermodule"."id") '
						. 'FROM "fichiersmodules" AS "fichiermodule" '
						. 'WHERE "fichiermodule"."modele" = \'Foyer\' '
						. 'AND "fichiermodule"."fk_value" = "Foyer"."id") '
						. 'AS "Fichiermodule__nb_fichiers_lies"',
					)
				);
				
				// 2. Jointure
				$query['joins'] = array_merge(
					array(
						$Personnepcg66->Traitementpcg66->join('Personnepcg66', array('type' => $types['Personnepcg66']))
					),
					$query['joins'],
					array(
						$Personnepcg66->join('Dossierpcg66', array('type' => $types['Dossierpcg66'])),
						$Personnepcg66->Traitementpcg66->join('Personnepcg66Situationpdo', array('type' => $types['Situationpdo'])),
						$Personnepcg66->Personnepcg66Situationpdo->join('Situationpdo', array('type' => $types['Situationpdo'])),
						$Personnepcg66->Dossierpcg66->Foyer->Dossier->Detaildroitrsa->join('Detailcalculdroitrsa', array('type' => $types['Detailcalculdroitrsa'])),
						$Personnepcg66->Dossierpcg66->join('User', array('type' => $types['User'])),
						$Personnepcg66->Traitementpcg66->join('Descriptionpdo', array('type' => $types['Descriptionpdo'])),
					)
				);
				
				// 3. Tri par défaut: date, heure, id
				$query['order'] = array(
				);

				// 4. Si on utilise les cantons, on ajoute une jointure
				if( Configure::read( 'CG.cantons' ) ) {
					$Canton = ClassRegistry::init( 'Canton' );
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $Canton->joinAdresse();
				}

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$Traitementpcg66 = ClassRegistry::init( 'Traitementpcg66' );

			$query = $Allocataire->searchConditions( $query, $search );
			
			/**
			 * Conditions obligatoire
			 */
			$query['conditions'][] = array(
				'OR' => array(
					'Detailcalculdroitrsa.id IS NULL',
					'Detailcalculdroitrsa.id IN (SELECT "detailscalculsdroitsrsa"."id" AS detailscalculsdroitsrsa__id FROM detailscalculsdroitsrsa AS detailscalculsdroitsrsa WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC LIMIT 1)'
				)
			);
			
			/**
			 * Generateur de conditions
			 */
			$paths = array(
				'Dossierpcg66.poledossierpcg66_id',
				'Dossierpcg66.user_id',
				'Traitementpcg66.descriptionpdo_id',
				'Traitementpcg66.typetraitement',
				'Traitementpcg66.clos',
				'Traitementpcg66.annule',
				'Traitementpcg66.regime',
				'Traitementpcg66.saisonnier',
				'Traitementpcg66.nrmrcs',
			);
			
			$pathsDate = array(
				'Dossierpcg66.dateaffectation',
				'Traitementpcg66.dateecheance',
				'Traitementpcg66.daterevision',
				'Traitementpcg66.created',
			);
			
			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $pathsDate );
			
			/**
			 * Conditions spéciales
			 */
			if (isset($search['Fichiermodule']['exists']) && in_array($search['Fichiermodule']['exists'], array('0', '1')) ) {
				$condition = '('
						. 'SELECT count("fichiersmodules"."id") '
						. 'FROM fichiersmodules '
						. 'WHERE "fichiersmodules"."modele" = \'Foyer\' '
						. 'AND "fichiersmodules"."fk_value" = "Foyer"."id")';
				
				switch ($search['Fichiermodule']['exists']) {
					case '0': $condition = $condition . ' = 0'; break;
					case '1': $condition = $condition . ' > 0'; break;
				}
				
				$query['conditions'][] = $condition;
			}
			if ($search['Traitementpcg66']['situationpdo_id']) {
				$query['conditions'][] = array(
					 'Personnepcg66.id IN ('
					. 'SELECT "personnespcgs66_situationspdos"."personnepcg66_id" AS personnespcgs66_situationspdos__personnepcg66_id '
					. 'FROM personnespcgs66_situationspdos AS personnespcgs66_situationspdos '
					. 'INNER JOIN "public"."situationspdos" AS situationspdos '
					. 'ON ("personnespcgs66_situationspdos"."situationpdo_id" = "situationspdos"."id") '
					. 'WHERE "personnespcgs66_situationspdos"."situationpdo_id" IN ('.implode(', ', $search['Traitementpcg66']['situationpdo_id']).'))'
				);
			}
			if ($search['Traitementpcg66']['statutpdo_id']) {
				$query['conditions'][] = array(
					 '"Personnepcg66"."id" IN ('
					. 'SELECT "personnespcgs66_statutspdos"."personnepcg66_id" AS personnespcgs66_statutspdos__personnepcg66_id '
					. 'FROM personnespcgs66_statutspdos AS personnespcgs66_statutspdos '
					. 'INNER JOIN "public"."statutspdos" AS statutspdos '
					. 'ON ("personnespcgs66_statutspdos"."statutpdo_id" = "statutspdos"."id") '
					. 'WHERE "personnespcgs66_statutspdos"."statutpdo_id" IN ('.implode(', ', $search['Traitementpcg66']['statutpdo_id']).'))'
				);
			}

			return $query;
		}
	}
?>