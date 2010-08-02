<?php
    class GroupsController extends AppController
    {

        var $name = 'Groups';
        var $uses = array( 'Group', 'User' );
        var $helpers = array( 'Xform' );
        var $components = array('Menu','Dbdroits');
        
		var $commeDroit = array(
			'add' => 'Groups:edit'
		);

		function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '512M');
			parent::beforeFilter();
			//debug(ini_get('maximum_execution_time'));
		}

        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
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

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Group->saveAll( $this->data ) ) {
		            if ($this->data['Group']['parent_id']!=0) {
						$this->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$this->data['Group']['parent_id']));
						$this->Dbdroits->MajCruDroits(
							array(
								'model'=>'Group',
								'foreign_key'=>$this->Group->id,
								'alias'=>$this->data['Group']['name']
							),
							array (
								'model'=>'Group',
								'foreign_key'=>$this->data['Group']['parent_id']
							),
							$this->data['Droits']
						);
		        	}
		        	else {
			        	$this->Dbdroits->AddCru(
							array(
								'model'=>'Group',
								'foreign_key'=>$this->Group->id,
								'alias'=>$this->data['Group']['name']
							),
							null
						);
					}
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $group_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $group_id ), 'error404' );


            if( !empty( $this->data ) ) {
            	$group=$this->Group->read(null,$group_id);
            	$new_droit= Set::diff($this->data['Droits'],$this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$group_id)));
                if( $this->Group->saveAll( $this->data ) ) {
                	if ($group['Group']['parent_id']!=$this->data['Group']['parent_id']) {
		            	$this->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$this->data['Group']['parent_id']));
						$this->Dbdroits->MajCruDroits(
							array('model'=>'Group','foreign_key'=>$this->data['Group']['id'],'alias'=>$this->data['Group']['name']),
							array('model'=>'Group','foreign_key'=>$this->data['Group']['parent_id']),
							$this->data['Droits']
						);
                	}
		        	elseif ($group['Group']['parent_id']!=0) {
			        	$this->Dbdroits->MajCruDroits(
							array('model'=>'Group','foreign_key'=>$this->data['Group']['id'],'alias'=>$this->data['Group']['name']),
							array('model'=>'Group','foreign_key'=>$this->data['Group']['parent_id']),
							$new_droit
						);
					}
					else {
			        	$this->Dbdroits->MajCruDroits(
							array('model'=>'Group','foreign_key'=>$this->data['Group']['id'],'alias'=>$this->data['Group']['name']),
							null,
							$new_droit
						);
					}
					$Users=$this->User->find('all',array('conditions'=>array('User.group_id'=>$this->data['Group']['id']),'recursive'=>-1));
					foreach($Users as $User) {
						$new_droit= Set::diff($this->data['Droits'],$this->Dbdroits->litCruDroits(array('model'=>'Utilisateur','foreign_key'=>$User['User']['id'])));
						$this->Dbdroits->MajCruDroits(
							array(
								'model'=>'Utilisateur',
								'foreign_key'=>$User['User']['id'],
								'alias'=>$User['User']['username']
							),
							array (
								'model'=>'Group',
								'foreign_key'=>$User['User']['group_id']
							),
							$new_droit
						);
					}
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
            $this->data = $group;

			$this->set('listeCtrlAction', $this->Menu->menuCtrlActionAffichage());
			$this->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$group_id));
            $this->render( $this->action, null, 'add_edit' );
        }

        function delete( $group_id = null ) {
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
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
            }
        }
    }

?>
