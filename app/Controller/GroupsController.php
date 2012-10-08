<?php
	class GroupsController extends AppController
	{

		public $name = 'Groups';
		public $uses = array( 'Group', 'User', 'Aro' );
		public $helpers = array( 'Xform' );
		public $components = array('Menu','Dbdroits');

		public $commeDroit = array(
			'add' => 'Groups:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
			parent::beforeFilter();
		}

		/**
		*
		*/

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
			$groups = $this->Group->find(
				'all',
				array(
					'recursive' => -1
				)
			);
			$this->set('groups', $groups);
		}

		/**
		*
		*/

		public function add() {
			if( !empty( $this->request->data ) ) {
				if( $this->Group->saveAll( $this->request->data ) ) {
					if ($this->request->data['Group']['parent_id']!=0) {
						$this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$this->request->data['Group']['parent_id']));
						$this->Dbdroits->MajCruDroits(
							array(
								'model'=>'Group',
								'foreign_key'=>$this->Group->id,
								'alias'=>$this->request->data['Group']['name']
							),
							array (
								'model'=>'Group',
								'foreign_key'=>$this->request->data['Group']['parent_id']
							),
							$this->request->data['Droits']
						);
					}
					else {
						$this->Dbdroits->AddCru(
							array(
								'model'=>'Group',
								'foreign_key'=>$this->Group->id,
								'alias'=>$this->request->data['Group']['name']
							),
							null
						);
					}
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
				}
			}
			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function edit( $group_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $group_id ), 'error404' );


			if( !empty( $this->request->data ) ) {
				$group=$this->Group->read(null,$group_id);
				if( $this->Group->saveAll( $this->request->data ) ) {
					$new_droit=array();
					if ($group['Group']['parent_id']!=$this->request->data['Group']['parent_id']) {
						$new_droit = Set::diff(
							$this->Dbdroits->litCruDroits(
								array(
									'model'=>'Group',
									'foreign_key'=>$this->request->data['Group']['parent_id']
								)
							),
							$this->Dbdroits->litCruDroits(
								array(
									'model'=>'Group',
									'foreign_key'=>$group_id
								)
							)
						);
						$this->Dbdroits->MajCruDroits(
							array('model'=>'Group','foreign_key'=>$group_id,'alias'=>$this->request->data['Group']['name']),
							array('model'=>'Group','foreign_key'=>$this->request->data['Group']['parent_id']),
							$new_droit
						);
					}
					elseif ($this->request->data['Group']['parent_id']==0) {
						$new_droit = Set::diff($this->request->data['Droits'],$this->Dbdroits->litCruDroits(array('model'=>'Group', 'foreign_key'=>$group_id)));
							$this->Dbdroits->MajCruDroits(
							array('model'=>'Group','foreign_key'=>$group_id,'alias'=>$this->request->data['Group']['name']),
							null,
							$new_droit
						);
					}
					else {
						$new_droit = Set::diff($this->request->data['Droits'],$this->Dbdroits->litCruDroits(array('model'=>'Group', 'foreign_key'=>$group_id)));
							$this->Dbdroits->MajCruDroits(
							array('model'=>'Group','foreign_key'=>$group_id,'alias'=>$this->request->data['Group']['name']),
							array('model'=>'Group','foreign_key'=>$this->request->data['Group']['parent_id']),
							$new_droit
						);
					}
					$this->Dbdroits->restreintCruEnfantsDroits(
						array('model'=>'Group','foreign_key'=>$group_id),
						$new_droit
					);

					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$group = $this->Group->find(
				'first',
				array(
					'conditions' => array(
						'Group.id' => $group_id,
					)
				)
			);
			$this->request->data = $group;

			$this->set('listeCtrlAction', $this->Menu->menuCtrlActionAffichage());
			$this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$group_id));
			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $group_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $group_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$group = $this->Group->find(
				'first',
				array( 'conditions' => array( 'Group.id' => $group_id )
				)
			);

			// Mauvais paramètre
			if( empty( $group_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Group->delete( array( 'Group.id' => $group_id ) ) ) {
				$aro_id = $this->Aro->find('first',array('conditions'=>array('model'=>'Group', 'foreign_key'=> $group_id ),'fields'=>array('id')));
				$this->Aro->delete($aro_id['Aro']['id']);
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
			}
		}
	}
?>