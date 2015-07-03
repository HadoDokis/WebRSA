<?php
	/**
	 * Code source de la classe Critereentretien.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe Critereentretien fournit le moteur de recherche par entretiens,
	 * au moyen du plugin ConfigurableQuery.
	 *
	 * @todo Interface (ConfigurableQuery)
	 *
	 * @package app.Model
	 */
	class Critereentretien extends AppModel
	{
		public $name = 'Critereentretien';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types = array(
				'Calculdroitrsa' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER'
				// TODO: tous les types
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );
				$Entretien = ClassRegistry::init( 'Entretien' );
				$query = $Allocataire->searchQuery( $types, 'Entretien' );

				// Ajout des spécificités du moteur de recherche
				$departement = (int)Configure::read( 'Cg.departement' );

				$query['fields'] = array_merge(
					array(
						'Dossier.id',
						'Entretien.id',
						'Personne.id',
						'Entretien.personne_id'
					),
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Entretien,
							$Entretien->Objetentretien,
							$Entretien->Referent,
							$Entretien->Structurereferente,
							$Entretien->Typerdv
						)
					)
				);

				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$Entretien->join( 'Objetentretien', array( 'type' => 'INNER' ) ),
						$Entretien->join( 'Referent', array( 'type' => 'INNER' ) ),
						$Entretien->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$Entretien->join( 'Typerdv', array( 'type' => 'LEFT OUTER' ) )
					)
				);

				if( $departement === 66 ) {
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields(
							array(
								$Entretien->Actioncandidat
							)
						)
					);

					$query['joins'] = array_merge(
						$query['joins'],
						array(
							$Entretien->join( 'Actioncandidat', array( 'type' => 'LEFT OUTER' ) )
						)
					);
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
			$query = $Allocataire->searchConditions( $query, $search );

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, 'Entretien.dateentretien' );

			foreach( array( 'Entretien.structurereferente_id', 'Entretien.referent_id' ) as $path ) {
				$value = (string)suffix( Hash::get( $search, $path ) );
				if( $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			$from = (array)Hash::get( $search, 'Entretien.arevoirle' ) + array( 'day' => '01' );
			if( valid_date( $from ) ) {
				$to = strtotime( '+1 month -1day', strtotime( "{$from['year']}-{$from['month']}-{$from['day']}" ) );
				$to = date_sql_to_cakephp( strftime( "%Y-%m-%d", $to ) );
				$entretien = array(
					'Entretien' => array(
						'arevoirle' => '1',
						'arevoirle_from' => $from,
						'arevoirle_to' => $to
					)
				);
				$query['conditions'] = $this->conditionsDates( $query['conditions'], $entretien, 'Entretien.arevoirle' );
			}

			return $query;
		}

		/**
		 * Retourne un querydata, en fonction du département, prenant en compte
		 * les différents filtres du moteur de recherche.
		 *
		 * @todo ConfigureQueryKeys + cache ... ou plutôt dans la fonction factorisée du contrôleur
		 *
		 * @param array $params
		 * @return array
		 */
		public function search( $search ) {
			$query = $this->searchQuery();
			$query = $this->searchConditions( $query, $search );

			return $query;
		}

		// @todo: prechargement + Interface
	}
?>