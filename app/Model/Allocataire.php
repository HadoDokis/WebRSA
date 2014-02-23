<?php
	/**
	 * Code source de la classe Allocataire.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Allocataire comporte des méthodes de base pour les recherches,
	 * les formaulaires, les exportcsv, .. liées à des allocataires du RSA.
	 *
	 * @package app.Model
	 */
	class Allocataire extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Allocataire';

		/**
		 * Ce modèle n'est pas lié à une table.
		 *
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Conditionnable'
		);

		/**
		 *
		 * @return array
		 */
		public function searchQuery() {
			$Personne = ClassRegistry::init( 'Personne' );

			$cacheKey = Inflector::underscore( $Personne->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = array(
					'fields' => Hash::merge(
						$Personne->fields(),
						$Personne->Calculdroitrsa->fields(),
						$Personne->Foyer->fields(),
						$Personne->Prestation->fields(),
						$Personne->Foyer->Adressefoyer->fields(),
						$Personne->Foyer->Adressefoyer->Adresse->fields(),
						$Personne->Foyer->Dossier->fields(),
						$Personne->Foyer->Dossier->Situationdossierrsa->fields(),
						$Personne->Foyer->Dossier->Detaildroitrsa->fields()
					),
					'joins' => array(
						$Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
						$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$Personne->join(
							'Prestation',
							array(
								'type' => 'INNER',
								'conditions' => array(
									'Prestation.rolepers' => array( 'DEM', 'CJT' )
								)
							)
						),
						$Personne->Foyer->join(
							'Adressefoyer',
							array(
								'type' => 'INNER',
								'conditions' => array(
									'Adressefoyer.id IN( '.$Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
								)
							)
						),
						$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
						$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
						$Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					),
					'contain' => false,
					'conditions' => array(),
					'order' => array(
						'Personne.nom ASC',
						'Personne.prenom ASC',
					)
				);

				$query = $Personne->PersonneReferent->completeSearchQueryReferentParcours( $query );

				// Enregistrement dans le cache
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
			$query['conditions'] = $this->conditionsAdresse( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsPersonneFoyerDossier( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsDernierDossierAllocataire( $query['conditions'], $search );

			$Personne = ClassRegistry::init( 'Personne' );
			$query = $Personne->PersonneReferent->completeSearchConditionsReferentParcours( $query, $search );

			return $query;
		}

		/**
		 * Moteur de recherche de base, avec la searchQuery d'Allocataire et les
		 * conditions de base sur la personne, son foyer, son dossier, ....
		 *
		 * @return array
		 */
		public function search( array $search = array() ) {
			$query = $this->searchQuery();

			$query = $this->searchConditions( $query, $search );

			return $query;
		}

		/**
		 * Retourne les options nécessaires au formulaire de recherche, aux
		 * impressions, ...
		 *
		 * @return array
		 */
		public function options() {
			$Option = ClassRegistry::init( 'Option' );

			$options = array(
				'Adresse' => array(
					'pays' => $Option->pays(),
					'typeres' => $Option->typeres(),
					'typevoie' => $Option->typevoie(),
				),
				'Adressefoyer' => array(
					'rgadr' => $Option->rgadr(),
					'typeadr' => $Option->typeadr(),
				),
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => $Option->toppersdrodevorsa(true),
				),
				'Detaildroitrsa' => array(
					'oridemrsa' => $Option->oridemrsa(),
					'topfoydrodevorsa' => $Option->topfoydrodevorsa(),
					'topsansdomfixe' => $Option->topsansdomfixe(),
				),
				'Dossier' => array(
					'fonorgcedmut' => $Option->fonorgcedmut(),
					'fonorgprenmut' => $Option->fonorgprenmut(),
					'numorg' => $Option->numorg(),
					'statudemrsa' => $Option->statudemrsa(),
					'typeparte' => $Option->typeparte(),
				),
				'Foyer' => array(
					'sitfam' => $Option->sitfam(),
					'typeocclog' => $Option->typeocclog(),
				),
				'Personne' => array(
					'pieecpres' => $Option->pieecpres(),
					'qual' => $Option->qual(),
					'sexe' => $Option->sexe(),
					'typedtnai' => $Option->typedtnai(),
				),
				'Prestation' => array(
					'rolepers' => $Option->rolepers(),
				),
				'Referentparcours' => array(
					'qual' => $Option->qual(),
				),
				'Structurereferenteparcours' => array(
					'type_voie' => $Option->typevoie(),
				),
				'Situationdossierrsa' => array(
					'etatdosrsa' => $Option->etatdosrsa(),
					'moticlorsa' => $Option->moticlorsa(),
				),
			);

			return $options;
		}

		/**
		 * Permet de test l'ajout de conditions supplémentaires à la requête de
		 * base.
		 *
		 * @param string|array $conditions
		 * @return array
		 */
		public function testSearchConditions( $conditions = null ) {
			$query = $this->searchQuery();
			$query['conditions'][] = $conditions;

			$Personne = ClassRegistry::init( 'Personne' );
			try {
				$Personne->find( 'first', $query );
				$return = array(
					'success' => true,
					'message' => null,
					'sql' => $Personne->sq( $query )
				);
			} catch( PDOException $Exception ) {
				$return = array(
					'success' => false,
					'message' => $Exception->getMessage(),
					'sql' => $Personne->sq( $query )
				);
			}

			return $return;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$query = $this->searchQuery();
			return !empty( $query );
		}
	}
?>