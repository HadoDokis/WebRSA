<?php
    class GroupsController extends AppController
    {

        var $name = 'Groups';
        var $uses = array( 'Group', 'User' );
        var $helpers = array( 'Xform' );
        var $components = array('Menu','Dbdroits');

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
                if( $this->Group->saveAll( $this->data ) ) {
                	$this->Dbdroits->MajCruDroits(
						array(
							'model'=>'Group',
							'foreign_key'=>$this->data['Group']['id'],
							'alias'=>$this->data['Group']['name']
						),
						null,
						$this->data['Droits']
					);
					$Users=$this->User->find('all',array('conditions'=>array('User.group_id'=>$this->data['Group']['id']),'recursive'=>-1));
					foreach($Users as $User) {
						$this->Dbdroits->MajCruDroits(
							array(
								'model'=>'Utilisateur',
								'foreign_key'=>$User['User']['id'],
								'alias'=>$User['User']['username']
							),
							null,
							$this->data['Droits']
						);
					}
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
                }
            }
            else {
                $group = $this->Group->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Group.id' => $group_id,
                        )
                    )
                );
                $this->data = $group;
            }

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
