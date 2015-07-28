<?php
	/**
	 * Code source de la classe WebrsaRechercheActioncandidatPersonne.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheActioncandidatPersonne ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheActioncandidatPersonne extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheActioncandidatPersonne';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'ActionscandidatsPersonnes.search.fields',
			'ActionscandidatsPersonnes.search.innerTable',
			'ActionscandidatsPersonnes.exportcsv'
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
				'Calculdroitrsa' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'LEFT OUTER',
				'Adressefoyer' => 'INNER',
				'Dossier' => 'INNER',
				'Adresse' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'INNER',
				'Typeorient' => 'LEFT OUTER',
				'Structurereferente' => 'INNER',
				'Structurereferenteparcours' => 'LEFT OUTER',
				'Orientstruct' => 'LEFT OUTER',
				'Referent' => 'INNER',
				'Actioncandidat' => 'INNER',
				'Contactpartenaire' => 'INNER',
				'Partenaire' => 'LEFT OUTER',
			);
			
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Allocataire->searchQuery( $types, 'ActioncandidatPersonne' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$ActioncandidatPersonne,
							$ActioncandidatPersonne->Referent,
							$ActioncandidatPersonne->Actioncandidat,
							$ActioncandidatPersonne->Actioncandidat->Contactpartenaire->Partenaire,
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'ActioncandidatPersonne.id',
						'ActioncandidatPersonne.personne_id',
						'ActioncandidatPersonne.datebilan'
					)
				);
				
				$joinActionPartenaire = array(
					'table' => '"partenaires"',
					'alias' => 'Partenaire',
					'type' => 'LEFT OUTER',
					'conditions' => '"Partenaire"."actioncandidat_id" = {$__cakeID__$} AND "Partenaire"."partenaire_id" = .id'
				);
				
				// 2. Jointure
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$ActioncandidatPersonne->join( 'Referent', array( 'type' => $types['Referent'] ) ),
						$ActioncandidatPersonne->join( 'Actioncandidat', array( 'type' => $types['Actioncandidat'] ) ),
						$ActioncandidatPersonne->Actioncandidat->join( 'Contactpartenaire', array( 'type' => $types['Contactpartenaire'] ) ),
						$ActioncandidatPersonne->Actioncandidat->Contactpartenaire->join( 'Partenaire', array( 'type' => $types['Partenaire'] ) ),
					)
				);

				// 3. Tri par défaut: date, heure, id
				$query['order'] = array(
					'ActioncandidatPersonne.datebilan' => 'DESC'
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
			$ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );

			$query = $Allocataire->searchConditions( $query, $search );
			
			/**
			 * Generateur de conditions
			 */
			$paths = array(
				'Contactpartenaire.partenaire_id',
				'ActioncandidatPersonne.referent_id',
				'ActioncandidatPersonne.positionfiche',
			);
			
			// Fils de dependantSelect
			$pathsToExplode = array(
				'ActioncandidatPersonne.actioncandidat_id',
			);

			$pathsDate = array(
				'ActioncandidatPersonne.datesignature',
			);
			
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