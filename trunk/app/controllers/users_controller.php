<?php
    class UsersController extends AppController
    {
        var $name = 'Users';
        var $uses = array( 'Group', 'Zonegeographique', 'User', 'Serviceinstructeur', 'Connection' );
        var $aucunDroit = array('login', 'logout');

        /**
        *  The AuthComponent provides the needed functionality
        *  for login, so you can leave this function blank.
        */
        function login() {
           if( $this->Auth->user() ) {
                /* Lecture de l'utilisateur authentifié */
                $authUser = $this->Auth->user();

                // Utilisateurs concurrents
                if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
                    $this->Connection->begin();
                    // Suppression des connections dépassées
                    $this->Connection->deleteAll(
                        array(
                            '"Connection"."modified" <' => strftime( '%Y-%m-%d %H:%M:%S', strtotime( '-'.readTimeout().' seconds' ) )
                        )
                    );
                    if( $this->Connection->find( 'count', array( 'conditions' => array( 'Connection.user_id' => $authUser['User']['id'] ) ) ) == 0 ) {
                        $connection = array(
                            'Connection' => array(
                                'user_id' => $authUser['User']['id'],
                                'php_sid' => session_id()
                            )
                        );

                        $this->Connection->set( $connection );
                        if( $this->Connection->save( $connection ) ) {
                            $this->Connection->commit();
                        }
                        else {
                            $this->Connection->rollback();
                        }
                    }
                    else {
                        $otherConnection = $this->Connection->findByUserId( $authUser['User']['id'] );
                        $this->Session->delete( 'Auth' );
                        $this->Session->setFlash( 'Utilisateur déjà connecté jusqu\'au '.strftime( '%d/%m/%Y à %H:%M:%S', ( strtotime( $otherConnection['Connection']['modified'] ) + readTimeout() ) ), 'flash/error' );
                        $this->redirect( $this->Auth->logout() );
                    }
                }
                // Fin utilisateurs concurrents

                /* lecture du service de l'utilisateur authentifié */
                $this->Utilisateur->Service->recursive=-1;
                $group =  $this->Group->findById($authUser['User']['group_id']);
                //$authUser['aroAlias'] = $group['Group']['name'].':'. $authUser['User']['username'];
                $authUser['User']['aroAlias'] = 'Utilisateur:'. $authUser['User']['username'];
                /* lecture de la collectivite de l'utilisateur authentifié */
                $this->Session->write( 'Auth', $authUser );
                $this->redirect( $this->Auth->redirect() );
            }
        }

        function logout() {
            if( $user_id = $this->Session->read( 'Auth.User.id' ) ) {
                if( valid_int( $user_id ) ) {
                    $this->Jeton = ClassRegistry::init( 'Jeton' ); // FIXME: dans Jetons
                    $this->Jeton->deleteAll(
                        array(
                            '"Jeton"."user_id"' => $user_id
                        )
                    );
                    // Utilisateurs concurrents
                    if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
                        $this->Connection->deleteAll( array( 'Connection.user_id' => $user_id ) );
                    }
                    // Fin utilisateurs concurrents
                }
            }
            $this->Session->delete( 'Auth' );
            $this->redirect( $this->Auth->logout() );
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

        function _setNewPermissions( $group_id, $group_name, $user_id, $username ) {
            $group = $this->User->Group->findById( $group_id, null, null, -1 );
            $aroGroup = $this->Acl->Aro->findByAlias( 'Group:'.$group_name, null, null, 2 );

            $aroAlias = 'Utilisateur:'.$username;
            $this->Acl->Aro->create( array( 'parent_id' => $aroGroup['Aro']['id'], 'foreign_key' => $user_id, 'alias' => $aroAlias ) );
            $saved = $this->Acl->Aro->save();

            $permissions = Set::combine( $aroGroup, 'Aco.{n}.alias', 'Aco.{n}.Permission._create' );
            foreach( $permissions as $acoAlias => $permission ) {
                if( $permission == 1 ) {
                    $saved = $this->Acl->allow( $aroAlias, $acoAlias ) && $saved;
                }
                else {
                    $saved = $this->Acl->deny( $aroAlias, $acoAlias ) && $saved;
                }
            }

            return $saved;
        }

        // FIXME: à l'ajout, on n'obtient pas toutes les acl de son groupe
        function add() {
            $this->set( 'zglist', $this->Zonegeographique->find( 'list' ) );
            $this->set( 'gp', $this->Group->find( 'list' ) );
            $this->set( 'si', $this->Serviceinstructeur->find( 'list' ) );

            if( !empty( $this->data ) ) {
                $this->User->begin();
                if( $this->User->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

                    // Définition des nouvelles permissions
                    $saved = $this->_setNewPermissions(
                        $this->data['User']['group_id'],
                        $group['Group']['name'],
                        $this->User->id,
                        $this->data['User']['username']
                    );

                    if( $saved ) {
                        $this->User->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                    }
                    else {
                        $this->User->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
                else {
                    $this->User->rollback();
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $user_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $user_id ), 'error404' );

            $userDb = $this->User->findById( $user_id );
            $this->assert( !empty( $userDb ), 'error404' );

            $this->set( 'zglist', $this->Zonegeographique->find( 'list' ) );
            $this->set( 'gp', $this->Group->find( 'list' ) );
            $this->set( 'si', $this->Serviceinstructeur->find( 'list' ) );

            if( !empty( $this->data ) ) {
                $this->User->begin();
                if( $this->User->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
                    if( $userDb['User']['group_id'] != $this->data['User']['group_id'] ) {
                        $group = $this->User->Group->findById( $this->data['User']['group_id'], null, null, -1 );
                        $aroGroup = $this->Acl->Aro->findByAlias( 'Group:'.$group['Group']['name'], null, null, -1 );

                        $aroUserDb = $this->Acl->Aro->findByForeignKey( $this->data['User']['id'], null, null, 2 );

                        $aroUserData = array( 'Aro' => $aroUserDb['Aro'] );
                        $aroUserData['Aro']['parent_id'] = $aroGroup['Aro']['id'];
                        // Utile SSI l'utilisateur change de username
                        $aroUserData['Aro']['alias'] = 'Utilisateur:'.$this->data['User']['username'];

                        // Sauvegarde des bonnes données liées à l'uilisateur et à son groupe dans la table Aros
                        $this->Acl->Aro->create( $aroUserData );
                        $saved = $this->Acl->Aro->save();

                        // Suppression des anciennes entrées liées à cet utilisateur dans la table aros_acos
                        // FIXME: celà ne pose-t'il pas de problème ?
                        $arosAcosDb = Set::extract( 'Aco.{n}.Permission.id', $aroUserDb );
                        if( !empty( $arosAcosDb ) ) {
                            $saved = $this->Acl->Aro->query( 'DELETE FROM aros_acos WHERE id IN ('.implode( ', ', $arosAcosDb ).');' ) && $saved;
                        }

                        // Ajout des nouvelles entrées liées à cet groupe (dont on descend) dans la table aros_acos
                        $saved = $this->_setNewPermissions(
                            $this->data['User']['group_id'],
                            $group['Group']['name'],
                            $this->User->id,
                            $this->data['User']['username']
                        );

                        if( $saved ) {
                            $this->User->commit();
                            $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                            $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                        }
                        else {
                            $this->User->rollback();
                            $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                        }
                    }
                    else {
                        $this->User->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
                    }
                }
                else {
                    $this->User->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }
            else {
                $this->data = $userDb;
            }
            $this->render( $this->action, null, 'add_edit' );
        }


        function delete( $user_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $user_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $user = $this->User->find(
                'first',
                array( 'conditions' => array( 'User.id' => $user_id )
                )
            );

            // Mauvais paramètre
            if( empty( $user_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->User->delete( array( 'User.id' => $user_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée' );
                $this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
            }
        }
    }
?>
