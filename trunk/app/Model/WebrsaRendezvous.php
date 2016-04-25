<?php
	/**
	 * Code source de la classe WebrsaRendezvous.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaAbstractLogic', 'Model');

	/**
	 * La classe WebrsaRendezvous possède la logique métier web-rsa
	 *
	 * @todo WebrsaLogicRendezvous ?
	 *
	 * @package app.Model
	 */
	class WebrsaRendezvous extends WebrsaAbstractLogic
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRendezvous';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Rendezvous');

		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array()) {
			$fields = array(
				'dernier' => $this->Rendezvous->sqVirtualField('dernier'),
			);
			
			return Hash::merge($query, array('fields' => array_values($fields)));
		}
		
		/**
		 * Permet d'obtenir le nécéssaire pour calculer les droits d'accès métier à une action
		 * 
		 * @param array $conditions
		 * @return array
		 */
		public function getDataForAccess(array $conditions) {
			$query = array(
				'fields' => array(
					'Rendezvous.id',
					'Rendezvous.personne_id',
				),
				'conditions' => $conditions,
				'contain' => false,
				'order' => array(
					'Rendezvous.daterdv DESC',
					'Rendezvous.heurerdv DESC'
				)
			);
			
			$results = $this->Rendezvous->find('all', $this->completeVirtualFieldsForAccess($query));
			return $results;
		}
		
		/**
		 * Permet de savoir si il est possible d'ajouter un enregistrement
		 * 
		 * @param integer $id
		 * @param String $modelName - nom du modèle qui désigne id : Personne : $id = $personne_id
		 * @return boolean
		 */
		public function ajoutPossible($id, $modelName = 'Personne') {
			if ((int)Configure::read('Cg.departement') !== 66) {
				return true;
			}
			
			$query = array(
				'fields' => 'Rendezvous.statutrdv_id',
				'contain' => false,
				'order' => array(
					'Rendezvous.daterdv DESC',
					'Rendezvous.heurerdv DESC'
				)
			);
			
			// On connait l'id de la Personne
			if ($modelName === 'Personne') {
				$query['conditions'] = array(
					'Rendezvous.personne_id' => $id,
				);
				$result = $this->Rendezvous->find('first', $query);
				$statutrdv_id = Hash::get($result, 'Rendezvous.statutrdv_id');
				
			// On ne connait que l'id du Rendezvous
			} elseif ($modelName === 'Rendezvous') {
				$query['conditions'] = array(
					'Rendezvous2.id' => $id,
				);
				$query['joins'] = array(
					array(
						'alias' => 'Rendezvous2',
						'table' => 'rendezvous',
						'conditions' => array(
							'Rendezvous2.personne_id = Rendezvous.personne_id',
						),
						'type' => 'INNER'
					)
				);
				$result = $this->Rendezvous->find('first', $query);
				$statutrdv_id = Hash::get($result, 'Rendezvous.statutrdv_id');
				
			// On connait déja l'id du Statutrdv
			} elseif ($modelName === 'Statutrdv') {
				$statutrdv_id = $id;
				
			// Erreur
			} else {
				trigger_error("modelName doit contenir Personne, Rendezvous ou Statutrdv");
				return false;
			}
			
			return !in_array(
				$statutrdv_id, (array)Configure::read('Rendezvous.Ajoutpossible.statutrdv_id')
			);
		}
		
		/**
		 * Vérifi si un Dossier de commission lié au rendez-vous existe
		 * 
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function haveDossiercommissionLie($personne_id) {
			if ((int)Configure::read('Cg.departement') !== 58) {
				return false;
			}
			
			$query = array(
				'fields' => 'Rendezvous.id',
				'conditions' => array(
					'Rendezvous.personne_id' => $personne_id
				),
				'contain' => false,
				'order' => array(
					'Rendezvous.daterdv' => 'DESC',
					'Rendezvous.heurerdv' => 'DESC',
				)
			);
			$record = $this->Rendezvous->find('first', $query);
			$lastrdv_id = Hash::get($record, 'Rendezvous.id');
			
			$dossierepLie = $this->Rendezvous->Personne->Dossierep->find(
				'first',
				array(
					'fields' => array(
						'Dossierep.id'
					),
					'conditions' => array(
						'Dossierep.id IN ( '.
						$this->Rendezvous->Personne->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'alias' => 'passagescommissionseps',
									'conditions' => array(
										'passagescommissionseps.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'traite', 'annule', 'reporte' )
									)
								)
						)
						.' )'
					),
					'joins' => array(
						array(
							'table' => 'sanctionsrendezvouseps58',
							'alias' => 'Sanctionrendezvousep58',
							'type' => 'INNER',
							'conditions' => array(
								'Sanctionrendezvousep58.dossierep_id = Dossierep.id',
								'Sanctionrendezvousep58.rendezvous_id' => $lastrdv_id
							)
						)
					),
					'order' => array( 'Dossierep.created ASC' )
				)
			);
			
			if (Hash::get($dossierepLie, 'Dossierep.id')) {
				return true;
			}

			$dossiercovLie = $this->Rendezvous->Personne->Dossiercov58->find(
				'first',
				array(
					'fields' => array(
						'Dossiercov58.id'
					),
					'conditions' => array(
						'OR' => array(
							'Dossiercov58.id IS NULL',
							'Dossiercov58.id IN ( '.
								$this->Rendezvous->Personne->Dossiercov58->Passagecov58->sq(
									array(
										'fields' => array(
											'passagescovs58.dossiercov58_id'
										),
										'alias' => ' passagescovs58',
										'conditions' => array(
											'passagescovs58.etatdossiercov' => array( 'cree', 'associe', 'annule', 'reporte' )
										)
									)
								)
							.' )',
						),
						'Propoorientsocialecov58.rendezvous_id' => $lastrdv_id
					),
					'joins' => array(
						$this->Rendezvous->Personne->Dossiercov58->join( 'Propoorientsocialecov58', array( 'type' => 'LEFT OUTER' ) ),
						$this->Rendezvous->Personne->Dossiercov58->Propoorientsocialecov58->join( 'Rendezvous', array( 'type' => 'LEFT OUTER' ) )
					),
					'order' => array( 'Dossiercov58.created ASC' ),
					'contain' => false
				)
			);
			
			if (Hash::get($dossiercovLie, 'Dossiercov58.id')) {
				return true;
			} else {
				return false;
			}
		}
	}