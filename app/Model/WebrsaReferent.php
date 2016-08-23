<?php
	/**
	 * Code source de la classe WebrsaReferent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaAbstractLogic', 'Model');
	App::uses('WebrsaLogicAccessInterface', 'Model/Interface');

	/**
	 * La classe WebrsaReferent possède la logique métier web-rsa
	 *
	 * @package app.Model
	 */
	class WebrsaReferent extends WebrsaAbstractLogic
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaReferent';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Referent');
		
		public function search( $criteres ) {
			/// Conditions de base
			$conditions = array();

			// Critères sur une personne du foyer - nom, prénom, nom de naissance -> FIXME: seulement demandeur pour l'instant
			$filtersReferent = array();
			foreach( array( 'nom', 'prenom', 'fonction' ) as $critereReferent ) {
				if( isset( $criteres['Referent'][$critereReferent] ) && !empty( $criteres['Referent'][$critereReferent] ) ) {
					$conditions[] = 'Referent.'.$critereReferent.' ILIKE \''.$this->Referent->wildcard( $criteres['Referent'][$critereReferent] ).'\'';
				}
			}

			if( isset( $criteres['Referent']['id'] ) && !empty( $criteres['Referent']['id'] ) ) {
				$conditions[] = array( 'Referent.id' => $criteres['Referent']['id'] );
			}

			// Critère sur la structure référente de l'utilisateur
			if( isset( $criteres['Referent']['structurereferente_id'] ) && !empty( $criteres['Referent']['structurereferente_id'] ) ) {
				$conditions[] = array( 'Referent.structurereferente_id' => $criteres['Referent']['structurereferente_id'] );
			}

			if( false === $this->Referent->Behaviors->attached( 'Occurences' ) ) {
				$this->Referent->Behaviors->attach( 'Occurences' );
			}

			$query = array(
				'fields' => array_merge(
					$this->Referent->fields(),
					$this->Referent->Structurereferente->fields(),
					array(
						$this->Referent->PersonneReferent->sqNbLiesActifs( $this->Referent, 'Referent.id', 'nb_referents_lies' ),
						$this->Referent->sqHasLinkedRecords(true, array('derniersreferents'))
					)
				),
				'order' => array( 'Referent.nom ASC', 'Referent.prenom ASC' ),
				'joins' => array(
					$this->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) )
				),
				'recursive' => -1,
				'conditions' => $conditions
			);

			return $query;
		}


		/**
		 * Renvoit une liste clé / valeur avec clé qui est l'id de la structure référente underscore l'id du référent
		 * et la valeur qui est qual, nom, prénom du référent.
		 * Utilisé pour les valeurs des input select.
		 *
		 * @return array
		 */
		public function listOptions() {
			$cacheKey = 'referent_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$tmp = $this->Referent->find(
					'all',
					array (
						'fields' => array(
							'Referent.id',
							'Referent.structurereferente_id',
							'Referent.qual',
							'Referent.nom',
							'Referent.prenom'
						),
						'contain' => false,
						'order' => 'Referent.nom ASC',
						'conditions' => array(
							'Referent.actif' => 'O'
						)
					)
				);

				$results = array();
				foreach( $tmp as $key => $value ) {
					$results[$value['Referent']['structurereferente_id'].'_'.$value['Referent']['id']] = $value['Referent']['qual'].' '.$value['Referent']['nom'].' '.$value['Referent']['prenom'];
				}

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Referent', 'Structurereferente', 'Typeorient' ) );
			}

			return $results;
		}

		/**
		*   Retourne la liste des Referents
		*/

		public function referentsListe( $structurereferente_id = null ) {
			// Population du select référents liés aux structures
			$conditions = array();
			$conditions = array( 'Referent.actif' => 'O' );
			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = $structurereferente_id;
			}

			$referents = $this->Referent->find(
				'all',
				array(
					'recursive' => -1,
					'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
					'conditions' => $conditions
				)
			);

			if( !empty( $referents ) ) {
				$ids = Set::extract( $referents, '/Referent/id' );
				$values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
				$referents = array_combine( $ids, $values );
			}
			return $referents;
		}

		/**
		* Retourne l'id du référent lié à une personne
		*/

		public function readByPersonneId( $personne_id ) {
			$referent_id = null;

			// Valeur par défaut préférée: à partir de personnes_referents
			$referent = $this->Referent->PersonneReferent->find(
				'first',
				array(
					'conditions' => array( 'personne_id' => $personne_id ), // FIXME ddesignation / dfdesignation
					'order' => array( 'dddesignation ASC' ),
					'recursive' => -1
				)
			);
			$referent_id = Set::classicExtract( $referent, 'PersonneReferent.referent_id' );

			// Valeur par défaut de substitution: à partir de orientsstructs
			if( empty( $referent_id ) ) {
				$orientstruct = $this->Referent->Personne->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'personne_id' => $personne_id,
							'statut_orient' => 'Orienté',
							'date_valid IS NOT NULL'
						),
						'order' => array( 'date_valid ASC' ),
						'recursive' => -1
					)
				);

				if( !empty( $orientstruct ) ) {
					$referent_id = Set::classicExtract( $orientstruct, 'Orientstruct.referent_id' );
					$structurereferente_id = Set::classicExtract( $orientstruct, 'Orientstruct.structurereferente_id' );
					$count = $this->Referent->find(
						'count',
						array(
							'conditions' => array( 'structurereferente_id' => $structurereferente_id ),
							'recursive' => -1
						)
					);

					if( empty( $referent_id ) && !empty( $structurereferente_id ) && ( $count == 1 ) ) {
						$referent = $this->Referent->find(
							'first',
							array(
								'conditions' => array( 'structurereferente_id' => $structurereferente_id ),
								'order' => array( 'id ASC' ),
								'recursive' => -1
							)
						);
						$referent_id = Set::classicExtract( $referent, 'Referent.id' );
					}
				}
			}

			if( !empty( $referent_id ) ) {
				return $this->Referent->find(
					'first',
					array(
						'conditions' => array( 'id' => $referent_id ),
						'recursive' => -1
					)
				);
			}
			return null;
		}

		/**
		 * Récupère la liste des référents groupés par structure référente
		 * Cette liste est mise en cache et on se sert de la classe ModelCache
		 * pour savoir quelles clés de cache supprimer lorsque les données de ce
		 * modèle changent.
		 *
		 * @return array
		 */
		public function listOptionsParStructure() {
			$cacheKey = 'referentparstructure_list_options';
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->Referent->find(
					'list',
					array(
						'fields' => array(
							'Referent.id',
							'Referent.nom_complet',
							'Structurereferente.lib_struc',
						),
						'recursive' => -1,
						'joins' => array(
							$this->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) )
						),
						'order' => array(
							'Structurereferente.lib_struc ASC',
							'Referent.nom_complet_court ASC'
						),
						'conditions' => array(
							'Structurereferente.actif' => 'O'
						)
					)
				);
				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Referent', 'Structurereferente', 'Typeorient' ) );
			}
			return $results;
		}		
	}