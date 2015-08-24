<?php
	/**
	 * Code source de la classe WebrsaRechercheRendezvous.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheRendezvous ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheRendezvous extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheRendezvous';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Rendezvous.search.fields',
			'Rendezvous.search.innerTable',
			'Rendezvous.exportcsv'
		);

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Allocataire', 'Rendezvous', 'Canton' );

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
				'Prestation' => 'INNER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				// Types propres à ce moteur de recherche
				'Structurereferente' => 'INNER',
				'Referent' => 'LEFT OUTER',
				'Typerdv' => 'LEFT OUTER',
				'Statutrdv' => 'LEFT OUTER',
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Rendezvous' );

				// Ajout des spécificités du moteur de recherche
				$departement = (int)Configure::read( 'Cg.departement' );

				$query['fields'] = array_merge(
					array(
						'Rendezvous.id',
						'Rendezvous.personne_id'
					),
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Rendezvous,
							$this->Rendezvous->Referent,
							$this->Rendezvous->Statutrdv,
							$this->Rendezvous->Structurereferente,
							$this->Rendezvous->Typerdv
						)
					)
				);

				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Rendezvous->join( 'Referent', array( 'type' => $types['Referent'] ) ),
						$this->Rendezvous->join( 'Statutrdv', array( 'type' => $types['Statutrdv'] ) ),
						$this->Rendezvous->join( 'Structurereferente', array( 'type' => $types['Structurereferente'] ) ),
						$this->Rendezvous->join( 'Typerdv', array( 'type' => $types['Typerdv'] ) )
					)
				);

				if( Configure::read( 'CG.cantons' ) ) {
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $this->Canton->joinAdresse();
				}

				// Si on utilise les thématiques de RDV, ajout du champ virtuel
				if( Configure::read('Rendezvous.useThematique' ) ) {
					$query['fields']['Rendezvous.thematiques'] = '( '.$this->Rendezvous->vfListeThematiques( null ).' ) AS "Rendezvous__thematiques"';
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
			// FIXME: les critères ajoutés à la main dans le bloc dossier
			$query = $this->Allocataire->searchConditions( $query, $search );

			$foreignKeys = array( 'structurereferente_id', 'referent_id', 'permanence_id', 'typerdv_id' );
			foreach( $foreignKeys as $foreignKey ) {
				$path = 'Rendezvous.'.$foreignKey;
				$value = suffix( (string)Hash::get( $search, $path ) );
				if( $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			$value = Hash::get( $search, 'Rendezvous.statutrdv_id' );
			if( !empty( $value ) ) {
				$query['conditions']['Rendezvous.statutrdv_id'] = $value;
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, 'Rendezvous.daterdv' );

			// Recherche par thématique de rendez-vous si nécessaire
			$query['conditions'] = $this->Rendezvous->conditionsThematique( $query['conditions'], $search, 'Rendezvous.thematiquerdv_id' );

			return $query;
		}
	}
?>