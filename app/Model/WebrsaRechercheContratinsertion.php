<?php
	/**
	 * Code source de la classe WebrsaRechercheContratinsertion.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheContratinsertion ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheContratinsertion extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheContratinsertion';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Contratsinsertion.search.fields',
			'Contratsinsertion.search.innerTable',
			'Contratsinsertion.exportcsv'
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
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'INNER',
				'Typeorient' => 'LEFT OUTER',
				'Structurereferente' => 'LEFT OUTER',
				'Structurereferenteparcours' => 'LEFT OUTER',
				'Orientstruct' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
			);
			
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$Contratinsertion = ClassRegistry::init( 'Contratinsertion' );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Allocataire->searchQuery( $types, 'Contratinsertion' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Contratinsertion,
							$Contratinsertion->Referent,
							$Contratinsertion->Personne->PersonneReferent,
							$Contratinsertion->Structurereferente
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Personne.nom',
						'Personne.prenom',
					)
				);
				
				// 2. Jointure
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$Contratinsertion->join( 'Structurereferente', array( 'type' => $types['Structurereferente'] ) ),
						$Contratinsertion->join( 'Referent', array( 'type' => $types['Referent'] ) ),
						$Contratinsertion->Structurereferente->join( 'Typeorient', array( 'type' => $types['Typeorient'] ) ),
						$Contratinsertion->Personne->join( 'Orientstruct', array( 'type' => $types['Orientstruct'] ) )
					)
				);

				// 3. Tri par défaut: date, heure, id
				$query['order'] = array(
//					'Personne.nom' => 'ASC',
//					'Personne.prenom' => 'ASC',
//					'Contratinsertion.id' => 'ASC'
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
			$Contratinsertion = ClassRegistry::init( 'Contratinsertion' );

			$query = $Allocataire->searchConditions( $query, $search );
			
			$query['conditions'][] = array(
				'OR' => array(
					'Orientstruct.id IS NULL',
					'Orientstruct.id IN (SELECT id FROM orientsstructs AS orientsstructs WHERE orientsstructs.personne_id = Contratinsertion.personne_id AND orientsstructs.statut_orient = \'Orienté\' AND orientsstructs.date_valid IS NOT NULL ORDER BY orientsstructs.date_valid DESC, orientsstructs.id DESC LIMIT 1)'
				)
			);

			$paths = array(
				'Contratinsertion.forme_ci',
				'Contratinsertion.structurereferente_id',
				'Contratinsertion.referent_id',
				'Contratinsertion.decision_ci',
				'Contratinsertion.positioncer',
				'Contratinsertion.duree_engag',
				'Orientstruct.typeorient_id',
			);

			$pathsDate = array(
				'Contratinsertion.created',
				'Contratinsertion.datevalidation_ci',
				'Contratinsertion.dd_ci',
				'Contratinsertion.df_ci',
			);
			
			if ($search['Contratinsertion']['dernier']) {
				$query['conditions'][] = array(
					"Contratinsertion.id IN (SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							contratsinsertion.personne_id = Contratinsertion.personne_id
						ORDER BY
							contratsinsertion.dd_ci DESC,
							contratsinsertion.id DESC
						LIMIT 1)"
				);
			}
			if ($search['Contratinsertion']['periode_validite']) {
				$debutValidite = date_cakephp_to_sql($search['Contratinsertion']['periode_validite_from']);
				$finValidite = date_cakephp_to_sql($search['Contratinsertion']['periode_validite_to']);
				$query['conditions'][] = array(
					'Contratinsertion.decision_ci' => 'V',
					'OR' => array(
						// Date de debut dans les clous
						array(
							'Contratinsertion.dd_ci >=' => $debutValidite,
							'Contratinsertion.dd_ci <=' => $finValidite,
						),
						// Date de fin dans les clous
						array(
							'Contratinsertion.df_ci >=' => $debutValidite,
							'Contratinsertion.df_ci <=' => $finValidite,
						),
					)
				);
			}
			if ($search['Contratinsertion']['arriveaecheance']) {
				$query['conditions'][] = 'Contratinsertion.id IN (
					SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							date_trunc( \'day\', contratsinsertion.df_ci ) <= DATE( NOW() )
 				)';
			}
			if ($search['Contratinsertion']['echeanceproche']) {
				$query['conditions'][] = 'Contratinsertion.id IN (
					SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							date_trunc( \'day\', contratsinsertion.df_ci ) >= DATE( NOW() )
							AND date_trunc( \'day\', contratsinsertion.df_ci ) <= ( DATE( NOW() ) + INTERVAL \''.Configure::read( 'Criterecer.delaiavanteecheance' ).'\' )
 				)';
			}
			if ($search['Contratinsertion']['istacitereconduction']) {
				$query['conditions'][] = 'Contratinsertion.datetacitereconduction IS NULL';
			}
			
			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $pathsDate );

			return $query;
		}
	}
?>