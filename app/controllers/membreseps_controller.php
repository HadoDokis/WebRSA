<?php
	class MembresepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Ajax' );

		public function beforeFilter() {
		}


		protected function _setOptions() {
			$options = $this->Membreep->enums();
			if( $this->action != 'index' ) {
				$options['Membreep']['fonctionmembreep_id'] = $this->Membreep->Fonctionmembreep->find( 'list' );
				$options['Membreep']['ep_id'] = $this->Membreep->Ep->find( 'list' );
				$optionTypevoie['typevoie'] = ClassRegistry::init( 'Option' )->typevoie();
				$options = Set::merge( $options, $optionTypevoie );
			}
			$enums = $this->Membreep->CommissionepMembreep->enums();
			$options['CommissionepMembreep'] = $enums['CommissionepMembreep'];


			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Membreep.id',
					'Fonctionmembreep.name',
					'Membreep.qual',
					'Membreep.nom',
					'Membreep.prenom',
					'Membreep.numvoie',
					'Membreep.typevoie',
					'Membreep.nomvoie',
					'Membreep.compladr',
					'Membreep.codepostal',
					'Membreep.ville',
					'Membreep.organisme',
					'Membreep.tel',
					'Membreep.mail'
				),
				'contain' => array(
					'Fonctionmembreep'
				),
				'limit' => 10
			);
			$membreseps = $this->paginate( $this->Membreep );

			$typesvoies = ClassRegistry::init( 'Option' )->typevoie();
			foreach( $membreseps as &$membreep) {
				$typevoie = Set::enum( Set::classicExtract( $membreep, 'Membreep.typevoie' ), $typesvoies );
				$membreep['Membreep']['nomcomplet'] = implode ( ' ', array( $membreep['Membreep']['qual'], $membreep['Membreep']['nom'], $membreep['Membreep']['prenom']) );
				$membreep['Membreep']['adresse'] = implode ( ' ', array( $membreep['Membreep']['numvoie'], $typevoie, $membreep['Membreep']['nomvoie'], $membreep['Membreep']['compladr'], $membreep['Membreep']['codepostal'], $membreep['Membreep']['ville']  ) );

			}

			$this->_setOptions();

			$compteurs = array(
				'Fonctionmembreep' => $this->Membreep->Fonctionmembreep->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );

			$this->set( compact( 'membreseps' ) );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Membreep->create( $this->data );
				$success = $this->Membreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $this->Membreep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Membreep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Membreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}

		/**
		* Dresse la liste de tous les membres de l'EP pour enregistrer ceux, parmis-eux, qui participeront à la séance.
		* @param integer $ep_id Index de l'EP dont on veut récupérer tous les membres.
		*/
		public function editliste( $commissionep_id ) {
			$commissionep = $this->Membreep->CommissionepMembreep->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);
			$ep_id = $commissionep['Commissionep']['ep_id'];

			$membres = $this->Membreep->find(
				'all',
				array(
					'fields' => array(
						'Membreep.id',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'Membreep.tel',
						'Membreep.mail',
						'Membreep.fonctionmembreep_id',
						'CommissionepMembreep.reponse'
					),
					'joins' => array(
						array(
							'table' => 'commissionseps_membreseps',
							'alias' => 'CommissionepMembreep',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = CommissionepMembreep.membreep_id',
								'CommissionepMembreep.commissionep_id' => $commissionep_id
							)
						),
						array(
							'table' => 'commissionseps',
							'alias' => 'Commissionep',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Commissionep.id = CommissionepMembreep.commissionep_id'
							)
						),
						array(
							'table' => 'eps_membreseps',
							'alias' => 'EpMembreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id',
								'EpMembreep.ep_id' => $ep_id
							)
						),
						array(
							'table' => 'eps',
							'alias' => 'Ep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Ep.id = EpMembreep.ep_id'
							)
						)
					),
					'contain'=>false
				)
			);
			$this->set('membres', $membres);

			if( !empty( $this->data ) ) {
				$success = true;
				if ( !$this->Membreep->CommissionepMembreep->checkDoublon( $this->data['CommissionepMembreep'] ) ) {
					$this->Membreep->CommissionepMembreep->begin();
					$reponsesMembres = array();
					foreach( $this->data['CommissionepMembreep'] as $membreep_id => $reponse ) {
						$existeEnBase = $this->Membreep->CommissionepMembreep->find(
							'first',
							array(
								'conditions'=>array(
									'CommissionepMembreep.membreep_id'=>$membreep_id,
									'CommissionepMembreep.commissionep_id'=>$commissionep_id
								),
								'contain' => false
							)
						);

						if (!empty($existeEnBase)) {
							$existeEnBase['CommissionepMembreep']['reponse'] = $reponse['reponse'];
							$existeEnBase['CommissionepMembreep']['reponsesuppleant_id'] = null;
							if ( $reponse['reponse'] == 'remplacepar' ) {
								if ( isset( $reponse['reponsesuppleant_id'] ) && !empty( $reponse['reponsesuppleant_id'] ) ) {
									$existeEnBase['CommissionepMembreep']['reponsesuppleant_id'] = $reponse['reponsesuppleant_id'];
								}
							}
							$reponsesMembres[$membreep_id] = $existeEnBase;
// 							$this->Membreep->CommissionepMembreep->create( $existeEnBase );
// 							$success = $this->Membreep->CommissionepMembreep->save() && $success;
						}
						else {
							$nouvelleEntree['CommissionepMembreep']['commissionep_id'] = $commissionep_id;
							$nouvelleEntree['CommissionepMembreep']['membreep_id'] = $membreep_id;
							$nouvelleEntree['CommissionepMembreep']['reponse'] = $reponse['reponse'];
							$nouvelleEntree['CommissionepMembreep']['reponsesuppleant_id'] = null;
							if ( $reponse['reponse'] == 'remplacepar' ) {
								if ( isset( $reponse['reponsesuppleant_id'] ) && !empty( $reponse['reponsesuppleant_id'] ) ) {
									$nouvelleEntree['CommissionepMembreep']['reponsesuppleant_id'] = $reponse['reponsesuppleant_id'];
								}
							}
							$reponsesMembres[$membreep_id] = $nouvelleEntree;
// 							$this->Membreep->CommissionepMembreep->create($nouvelleEntree);
// 							$success = $this->Membreep->CommissionepMembreep->save() && $success;
						}
					}
					
					$success = $this->Membreep->CommissionepMembreep->saveAll( $reponsesMembres, array( 'validate' => 'first', 'atomic' => false ) ) && $success;
					$success = $this->Membreep->CommissionepMembreep->Commissionep->changeEtatCreeAssocie( $commissionep_id ) && $success;
				}
				else {
					$success = false;
					
				}

				$this->_setFlashResult( 'Save', $success );
				if ( $success ) {
					$this->Membreep->CommissionepMembreep->commit();
					$this->redirect(array('controller'=>'commissionseps', 'action'=>'view', $commissionep_id));
				}
				else {
					$this->Membreep->CommissionepMembreep->rollback();
				}
			}
			else {
				$this->data = array();
				foreach( $membres as $membre ) {
					$this->data['CommissionepMembreep'][$membre['Membreep']['id']]['reponse'] = $membre['CommissionepMembreep']['reponse'];
				}
			}

			$fonctionsmembres = $this->Membreep->Fonctionmembreep->find(
				'all',
				array(
					'fields' => array(
						'Fonctionmembreep.id',
						'Fonctionmembreep.name'
					),
					'joins' => array(
						array(
							'table' => 'membreseps',
							'alias' => 'Membreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Fonctionmembreep.id = Membreep.fonctionmembreep_id'
							)
						),
						array(
							'table' => 'eps_membreseps',
							'alias' => 'EpMembreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id',
								'EpMembreep.ep_id' => $ep_id
							)
						),
						array(
							'table' => 'eps',
							'alias' => 'Ep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Ep.id = EpMembreep.ep_id'
							)
						)
					),
					'contain'=>false,
					'group' => array(
						'Fonctionmembreep.id',
						'Fonctionmembreep.name'
					)
				)
			);
			$this->set('fonctionsmembres', $fonctionsmembres);

			$listemembres = $this->Membreep->find(
				'all',
				array(
					'fields' => array(
						'Membreep.id',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'Membreep.fonctionmembreep_id'
					),
					'conditions' => array(
						'Membreep.id NOT IN ( '.$this->Membreep->EpMembreep->sq(
							array(
								'fields' => array(
									'eps_membreseps.membreep_id'
								),
								'alias' => 'eps_membreseps',
								'conditions' => array(
									'eps_membreseps.ep_id' => $ep_id
								)
							)
						).' )'
					),
					'contain' => false
				)
			);

			$membres_fonction = array();
			foreach( $listemembres as $membreep ) {
				$membres_fonction[$membreep['Membreep']['fonctionmembreep_id']][$membreep['Membreep']['id']] = implode( ' ', array( $membreep['Membreep']['qual'], $membreep['Membreep']['nom'], $membreep['Membreep']['prenom'] ) );
			}
			$this->set( 'membres_fonction', $membres_fonction );

			$this->set('seance_id', $commissionep_id);
			$this->set('ep_id', $ep_id);
			$this->_setOptions();
		}

		public function editpresence( $commissionep_id ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Membreep->CommissionepMembreep->begin();
				$reponsesMembres = array();
				foreach($this->data['CommissionepMembreep'] as $membreep_id => $reponse) {
					$existeEnBase = $this->Membreep->CommissionepMembreep->find(
						'first',
						array(
							'conditions'=>array(
								'CommissionepMembreep.membreep_id' => $membreep_id,
								'CommissionepMembreep.commissionep_id' => $commissionep_id
							),
							'contain' => false
						)
					);
					if (!empty($existeEnBase)) {
						$existeEnBase['CommissionepMembreep']['presence'] = $reponse['presence'];
						if ( $reponse['presence'] == 'remplacepar' ) {
							$existeEnBase['CommissionepMembreep']['presencesuppleant_id'] = null;
							if ( isset( $reponse['presencesuppleant_id'] ) && !empty( $reponse['presencesuppleant_id'] ) ) {
								$existeEnBase['CommissionepMembreep']['presencesuppleant_id'] = $reponse['presencesuppleant_id'];
							}
						}
						$reponsesMembres[$membreep_id] = $existeEnBase;
// 						$this->Membreep->CommissionepMembreep->create( $existeEnBase );
// 						$success = $this->Membreep->CommissionepMembreep->save() && $success;
					}
					else {
						$nouvelleEntree['CommissionepMembreep']['commissionep_id'] = $commissionep_id;
						$nouvelleEntree['CommissionepMembreep']['membreep_id'] = $membreep_id;
						$nouvelleEntree['CommissionepMembreep']['presence'] = $reponse['presence'];
						if ( $reponse['presence'] == 'remplacepar' ) {
							$existeEnBase['CommissionepMembreep']['presencesuppleant_id'] = null;
							if ( isset( $reponse['presencesuppleant_id'] ) && !empty( $reponse['presencesuppleant_id'] ) ) {
								$nouvelleEntree['CommissionepMembreep']['presencesuppleant_id'] = $reponse['presencesuppleant_id'];
							}
						}
						$reponsesMembres[$membreep_id] = $nouvelleEntree;
// 						$this->Membreep->CommissionepMembreep->create($nouvelleEntree);
// 						$success = $this->Membreep->CommissionepMembreep->save() && $success;
					}
				}

				$success = $this->Membreep->CommissionepMembreep->saveAll( $reponsesMembres, array( 'validate' => 'first', 'atomic' => false ) ) && $success;
				$success = $this->Membreep->CommissionepMembreep->Commissionep->changeEtatAssociePresence( $commissionep_id ) && $success;

				$this->_setFlashResult( 'Save', $success );
				if ($success) {
					$this->Membreep->CommissionepMembreep->commit();
					$this->redirect(array('controller'=>'commissionseps', 'action'=>'traiterep', $commissionep_id));
				}
				else {
					$this->Membreep->CommissionepMembreep->rollback();
				}
			}

			$commissionep = $this->Membreep->CommissionepMembreep->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);
			$ep_id = $commissionep['Commissionep']['ep_id'];

			$membres = $this->Membreep->find(
				'all',
				array(
					'fields' => array(
						'Membreep.id',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'Membreep.tel',
						'Membreep.mail',
						'Membreep.fonctionmembreep_id',
						'CommissionepMembreep.reponse',
						'CommissionepMembreep.presence',
						'CommissionepMembreep.reponsesuppleant_id',
						'CommissionepMembreep.presencesuppleant_id'
					),
					'joins' => array(
						array(
							'table' => 'commissionseps_membreseps',
							'alias' => 'CommissionepMembreep',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = CommissionepMembreep.membreep_id',
								'CommissionepMembreep.commissionep_id' => $commissionep_id
							)
						),
						array(
							'table' => 'commissionseps',
							'alias' => 'Commissionep',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Commissionep.id = CommissionepMembreep.commissionep_id'
							)
						),
						array(
							'table' => 'eps_membreseps',
							'alias' => 'EpMembreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id',
								'EpMembreep.ep_id' => $ep_id
							)
						),
						array(
							'table' => 'eps',
							'alias' => 'Ep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Ep.id = EpMembreep.ep_id'
							)
						)
					),
					'contain'=>false
				)
			);
			$this->set('membres', $membres);

			$fonctionsmembres = $this->Membreep->Fonctionmembreep->find(
				'all',
				array(
					'fields' => array(
						'Fonctionmembreep.id',
						'Fonctionmembreep.name'
					),
					'joins' => array(
						array(
							'table' => 'membreseps',
							'alias' => 'Membreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Fonctionmembreep.id = Membreep.fonctionmembreep_id'
							)
						),
						array(
							'table' => 'eps_membreseps',
							'alias' => 'EpMembreep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id',
								'EpMembreep.ep_id' => $ep_id
							)
						),
						array(
							'table' => 'eps',
							'alias' => 'Ep',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Ep.id = EpMembreep.ep_id'
							)
						)
					),
					'contain'=>false,
					'group' => array(
						'Fonctionmembreep.id',
						'Fonctionmembreep.name'
					)
				)
			);
			$this->set('fonctionsmembres', $fonctionsmembres);

			$listemembres = $this->Membreep->find(
				'all',
				array(
					'fields' => array(
						'Membreep.id',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'Membreep.fonctionmembreep_id'
					),
					'conditions' => array(
						'Membreep.id NOT IN ( '.$this->Membreep->EpMembreep->sq(
							array(
								'fields' => array(
									'eps_membreseps.membreep_id'
								),
								'alias' => 'eps_membreseps',
								'conditions' => array(
									'eps_membreseps.ep_id' => $ep_id
								)
							)
						).' )'
					),
					'contain' => false
				)
			);

			$membres_fonction = array();
			foreach( $listemembres as $membreep ) {
				$membres_fonction[$membreep['Membreep']['fonctionmembreep_id']][$membreep['Membreep']['id']] = implode( ' ', array( $membreep['Membreep']['qual'], $membreep['Membreep']['nom'], $membreep['Membreep']['prenom'] ) );
			}
			$this->set( 'membres_fonction', $membres_fonction );

			$this->set('seance_id', $commissionep_id);
			$this->set('ep_id', $ep_id);
			$this->_setOptions();
		}

	}

?>
