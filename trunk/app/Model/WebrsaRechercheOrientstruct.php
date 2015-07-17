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
							$Orientstruct->Typeorient->Structurereferente
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Orientstruct.id',
						'Orientstruct.personne_id'
					)
				);
				
				// 2. Jointure
				$query['joins'] = array_merge(
					$query['joins'],
					array($Orientstruct->join( 'Structurereferente', array( 'type' => $types['Structurereferente'] ) ))
				);

				// 3. Tri par défaut: date, heure, id
				$query['order'] = array(
					'Personne.nom' => 'ASC',
					'Personne.prenom' => 'ASC',
					'Orientstruct.id' => 'ASC'
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
				'Orientstruct.derniere',
				'Orientstruct.structureorientante_id',
				'Orientstruct.referentorientant_id',
				'Orientstruct.typeorient_id',
				'Orientstruct.structurereferente_id',
				'Orientstruct.statut_orient',
				'Orientstruct.serviceinstructeur_id',
				'PersonneReferent.structurereferente_id',
				'PersonneReferent.referent_id',
			);

			$pathsDate = array(
				'Orientstruct.date_valid',
			);

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $pathsDate );

			return $query;
		}
	}
?>