<?php
	/**
	 * Code source de la classe WebrsaRechercheOrientstruct.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheOrientstruct ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheOrientstruct extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheOrientstruct';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Orientstructs.search.fields',
			'Orientstructs.search.innerTable',
			'Orientstructs.exportcsv'
		);

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$cgDepartement = Configure::read( 'Cg.departement' );
			$types += array(
				'Calculdroitrsa' => 'INNER',
				'Foyer' => 'INNER',
				'Prestation' => 'LEFT OUTER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'LEFT OUTER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'INNER',

				'Informationpe' => 'LEFT OUTER',
				'Historiqueetatpe' => 'LEFT OUTER',
				'Modecontact' => 'LEFT OUTER',
				'Typeorient' => 'LEFT OUTER',
				'Structurereferente' => 'LEFT OUTER',
				'Structurereferenteparcours' => 'LEFT OUTER',
				'Suiviinstruction' => 'LEFT OUTER',
				'Serviceinstructeur' => 'LEFT OUTER',
				'Referentparcours' => 'LEFT OUTER',
			);

			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$Orientstruct = ClassRegistry::init( 'Orientstruct' );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Allocataire->searchQuery( $types, 'Orientstruct' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Orientstruct,
							$Orientstruct->Personne->PersonneReferent,
							$Orientstruct->Typeorient,
							$Orientstruct->Typeorient->Structurereferente
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Orientstruct.id',
						'Orientstruct.personne_id',
						'Orientstruct.date_propo',
					)
				);

				// 2. Jointure
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$Orientstruct->join( 'Typeorient', array( 'type' => $types['Typeorient'] ) ),
						$Orientstruct->join( 'Structurereferente', array( 'type' => $types['Structurereferente'] ) )
					)
					// array($Orientstruct->join( 'Serviceinstructeur', array( 'type' => $types['Serviceinstructeur'] ) )) // Inutile ???
				);

				// 3. Tri par défaut: date, heure, id
				$query['order'] = array(
					'Orientstruct.date_propo' => 'DESC'
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
			$Orientstruct = ClassRegistry::init( 'Orientstruct' );

			$query = $Allocataire->searchConditions( $query, $search );

			$paths = array(
				'Orientstruct.origine',
				'Orientstruct.structureorientante_id',
				'Orientstruct.typeorient_id',
				'Orientstruct.statut_orient',
				'Orientstruct.serviceinstructeur_id',
				'PersonneReferent.structurereferente_id',
				'PersonneReferent.referent_id',
			);

			// Fils de dependantSelect
			$pathsToExplode = array(
				'Orientstruct.referentorientant_id',
				'Orientstruct.structurereferente_id',
			);

			$pathsDate = array(
				'Orientstruct.date_valid',
			);

			if ($search['Orientstruct']['derniere']) {
				$query['conditions'][] = array(
					"Orientstruct.id IN (SELECT
						orientsstructs.id
					FROM
						orientsstructs
						WHERE
							orientsstructs.personne_id = Orientstruct.personne_id
						ORDER BY
							orientsstructs.date_valid DESC,
							orientsstructs.id DESC
						LIMIT 1)"
				);
			}

			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			foreach( $pathsToExplode as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' && strpos($value, '_') > 0 ) {
					list(,$value) = explode('_', $value);
					$query['conditions'][$path] = $value;
				}
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $pathsDate );

			return $query;
		}
	}
?>