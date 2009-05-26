<?php
    class UsersController extends AppController
    {

        var $name = 'Users';
        var $uses = array('Group', 'Zonegeographique', 'User', 'Serviceinstructeur');
        var $aucunDroit = array('login', 'logout');

        /**
        *  The AuthComponent provides the needed functionality
        *  for login, so you can leave this function blank.
        */
        function login() {
           if( $this->Auth->user() ) {
                /* Lecture de l'utilisateur authentifié */
                $authUser = $this->Auth->user();
                /* lecture du service de l'utilisateur authentifié */
                $this->Utilisateur->Service->recursive=-1;
                $group =  $this->Group->findById($authUser['User']['group_id']);
                //$authUser['aroAlias'] = $group['Group']['name'].':'. $authUser['User']['username'];
                $authUser['User']['aroAlias'] = 'Utilisateur:'. $authUser['User']['username'];
                /* lecture de la collectivite de l'utilisateur authentifié */
                $this->Session->write( 'Auth', $authUser);
                $this->redirect($this->Auth->redirect());
    	    }
        }

        function logout() {
            $this->redirect($this->Auth->logout());
        }

        function index() {

            $users = $this->User->find(
                'all',
                array(
                    'recursive' => 1
                )

            );

            $this->set('users', $users);
        }

        function add() {

            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            $gp = $this->Group->find(
                'list',
                array(
                    'fields' => array(
                       // 'Group.id',
                        'Group.name'
                    )
                )
            );
            $this->set( 'gp', $gp );

            $si = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        //'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'si', $si );


            if( !empty( $this->data ) ) {
                if( $this->User->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $user_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $user_id ), 'error404' );

            $zg = $this->Zonegeographique->find(
                'list',
                array(
                    'fields' => array(
                        'Zonegeographique.id',
                        'Zonegeographique.libelle'
                    )
                )
            );
            $this->set( 'zglist', $zg );

            $gp = $this->Group->find(
                'list',
                array(
                    'fields' => array(
                        //'Group.id',
                        'Group.name'
                    )
                )
            );
            $this->set( 'gp', $gp );

            $si = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        //'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    )
                )
            );
            $this->set( 'si', $si );

            if( !empty( $this->data ) ) {
                if( $this->User->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                }
            }
            else {

               $user = $this->User->find(
                    'first',
                    array(
                        'conditions' => array(
                            'User.id' => $user_id,
                        )
                    )
                );
                $this->data = $user;
            }
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
