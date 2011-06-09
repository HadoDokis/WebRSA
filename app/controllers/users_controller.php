<?php
	class UsersController extends AppController
	{
		public $name = 'Users';
		public $uses = array( 'User', 'Option' );
		public $aucunDroit = array( 'login', 'logout' );
		public $helpers = array( 'Xform', 'Default2' );
		public $components = array( 'Menu','Dbdroits', 'Prg' => array( 'actions' => array( 'index' ) ) );


		public $commeDroit = array(
			'add' => 'Users:edit'
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
        public function _setOptions() {
            $options['Serviceinstructeur'] =  $this->User->Serviceinstructeur->listOptions();
            $options['Groups'] = $this->User->Group->find( 'list' );
            $this->set( compact( 'options' ) );
        }



		/**
		*
		*/

		public function login() {
			if( $this->Auth->user() ) {
				/* Lecture de l'utilisateur authentifié */
				$authUser = $this->Auth->user();

				// Utilisateurs concurrents
				if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
					$this->User->Connection->begin();
					// Suppression des connections dépassées
					$this->User->Connection->deleteAll(
						array(
							'Connection.modified <' => strftime( '%Y-%m-%d %H:%M:%S', strtotime( '-'.readTimeout().' seconds' ) )
						)
					);
					if( $this->User->Connection->find( 'count', array( 'conditions' => array( 'Connection.user_id' => $authUser['User']['id'] ) ) ) == 0 ) {
						$connection = array(
							'Connection' => array(
								'user_id' => $authUser['User']['id'],
								'php_sid' => session_id()
							)
						);

						$this->User->Connection->set( $connection );
						if( $this->User->Connection->save( $connection ) ) {
							$this->User->Connection->commit();
						}
						else {
							$this->User->Connection->rollback();
						}
					}
					else {
						$otherConnection = $this->User->Connection->findByUserId( $authUser['User']['id'] );
						$this->Session->delete( 'Auth' );
						$this->Session->setFlash(
							sprintf(
								'Utilisateur déjà connecté jusqu\'au %s (nous sommes actuellement le %s)',
								strftime( '%d/%m/%Y à %H:%M:%S', ( strtotime( $otherConnection['Connection']['modified'] ) + readTimeout() ) ),
								strftime( '%d/%m/%Y à %H:%M:%S' )
							),
							'flash/error'
						);
						$this->redirect( $this->Auth->logout() );
					}
				}
				// Fin utilisateurs concurrents

				/* lecture du service de l'utilisateur authentifié */
				$this->User->Service->recursive = -1;
				$group =  $this->User->Group->find(
					'first',
					array(
						'conditions' => array(
							'Group.id' => $authUser['User']['group_id']
						),
						'contain' => false
					)
				);
				//$authUser['aroAlias'] = $group['Group']['name'].':'. $authUser['User']['username'];
				$authUser['User']['aroAlias'] = $authUser['User']['username'];
				/* lecture de la collectivite de l'utilisateur authentifié */
				$this->Session->write( 'Auth', $authUser );

				// Supprimer la vue cachée du menu
				$this->_deleteCachedMenu();

				$this->redirect( $this->Auth->redirect() );
			}
		}

		/**
		*
		*/

		public function logout() {
			if( $user_id = $this->Session->read( 'Auth.User.id' ) ) {
				if( valid_int( $user_id ) ) {
					// Supprimer la vue cachée du menu
					$this->_deleteCachedMenu();

					$this->_deleteTemporaryFiles();

					// Supprime les jetons si besoin
					// FIXME: dans JetonsComponent ou dans le modèle Jeton
					if( !Configure::read( 'Jetons.disabled' ) ) {
						$this->User->Jeton->deleteAll(
							array(
								'Jeton.user_id' => $user_id,
								'Jeton.php_sid' => session_id()
							)
						);
					}

					// Utilisateurs concurrents
					/*if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
						$this->User->Connection->deleteAll( array( 'Connection.user_id' => $user_id ) );
					}*/
					// Fin utilisateurs concurrents
					$this->User->Connection->deleteAll(
						array(
							'Connection.user_id' => $user_id,
							'Connection.php_sid' => session_id() // FIXME, si la session n'est pas gérée par PHP ?
						)
					);
				}
			}

			foreach( array_keys( $this->Session->read() ) as $key ) {
				if( !in_array( $key, array( 'Config', 'Message' ) ) ) {
					$this->Session->delete( $key );
				}
			}

			$this->redirect( $this->Auth->logout() );
		}

		/**
		*
		*/

		//TODO: il doit y avoir une façon de faire à la CakePHP mais en attendant de trouver on fait comme ça
		protected function _deleteCachedMenu() {
			$file = TMP.'cache'.DS.'views'.DS.'element_'.$this->Session->read( 'Auth.User.username' ).'_menu';
			if (file_exists($file))
				unlink($file);
		}

		/**
		*
		*/

		protected function _deleteTemporaryFiles() {
			App::import ('Core', 'File' );

			foreach( array( 'files', 'pdf' ) as $subdir ) {
				$oFolder = new Folder( TMP.$subdir.DS.session_id(), true, 0777 );
				$oFolder->delete();
			}
		}

		/**
		*
		*/

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

            if( !empty( $this->data ) ) {
                $queryData = $this->User->search( $this->data );
                $queryData['limit'] = 10;
                $this->paginate = $queryData;
                $users = $this->paginate( 'User' );
                $this->set( 'users', $users  );
            }
            $this->_setOptions();
            $this->render( null, null, 'index' );


// 			$this->paginate = array(
// 				'User' => array(
// 					'fields' => array(
// 						'User.id',
// 						'User.nom',
// 						'User.prenom',
// 						'User.username',
// 						'User.date_naissance',
// 						'User.numtel',
// 						'User.date_deb_hab',
// 						'User.date_fin_hab',
// 						'Group.name',
// 						'Serviceinstructeur.lib_service'
// 					),
// 					'recursive' => 0,
// 					'limit' => 10
// 				)
// 			);


		}

		/**
		*
		*/

		protected function _setNewPermissions( $group_id, $user_id, $username ) {
			$group = $this->User->Group->findById( $group_id, null, null, -1 );
			$aroGroup = $this->Acl->Aro->findByAlias( $group['Group']['name'], null, null, 2 );

			$aroAlias = $username;
			$aroUser = $this->Acl->Aro->find( 'first', array( 'conditions' => array( 'Aro.foreign_key' => $user_id, 'Aro.alias' => $aroAlias ), 'recursive' => -1 ) );

			if( empty( $aroUser ) ) {
				$aroUser = array();
			}

			$aroUser['Aro']['parent_id'] = $aroGroup['Aro']['id'];
			$aroUser['Aro']['foreign_key'] = $user_id;
			$aroUser['Aro']['alias'] = $aroAlias;

			$this->Acl->Aro->create( $aroUser );
			$saved = $this->Acl->Aro->save();

			// Permissions héritées du groupe
			if( !empty( $aroGroup['Aco'] ) ) {
				$permissions = Set::combine( $aroGroup, 'Aco.{n}.alias', 'Aco.{n}.Permission._create' );
				foreach( $permissions as $acoAlias => $permission ) {
					if( $permission == 1 ) {
						$saved = $this->Acl->allow( $aroAlias, $acoAlias ) && $saved;
					}
					else {
						$saved = $this->Acl->deny( $aroAlias, $acoAlias ) && $saved;
					}
				}
			}

			return $saved;
		}

		/**
		*
		*/

		// FIXME: à l'ajout, on n'obtient pas toutes les acl de son groupe
		public function add() {
			$this->set( 'zglist', $this->User->Zonegeographique->find( 'list' ) );
			$this->set( 'gp', $this->User->Group->find( 'list' ) );
			$this->set( 'si', $this->User->Serviceinstructeur->find( 'list' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'options', $this->User->allEnumLists() );

			if( !empty( $this->data ) ) {
				$this->User->begin();
	//                     debug($this->data);
	//                     die();
				if( $this->User->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					// Définition des nouvelles permissions

					$this->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$this->data['User']['group_id']));
					$this->Dbdroits->MajCruDroits(
						array(
							'model'         =>  'Utilisateur',
							'foreign_key'   =>   $this->User->id,
							'alias'         =>  $this->data['User']['username']
						),
						array(
							'model'         =>  'Group',
							'foreign_key'   =>  $this->data['User']['group_id']
						),
						$this->data['Droits']
					);

					/*$saved = $this->_setNewPermissions(
						$this->data['User']['group_id'],
						$this->User->id,
						$this->data['User']['username']
					);*/

					//if( /*false &&*/ $saved ) {
						$this->User->commit();
						$this->Session->setFlash( 'Enregistrement effectué. Veuillez-vous déconnecter et vous reconnecter afin de prendre en compte tous les changements.', 'flash/success' );
						$this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
					/*}
					else {
						$this->User->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}*/
				}
				else {
					$this->User->rollback();
				}
			}
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function edit( $user_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $user_id ), 'error404' );

			$userDb = $this->User->findById( $user_id );
			$this->assert( !empty( $userDb ), 'error404' );

			$this->set( 'zglist', $this->User->Zonegeographique->find( 'list' ) );
			$this->set( 'gp', $this->User->Group->find( 'list' ) );
			$this->set( 'si', $this->User->Serviceinstructeur->find( 'list' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'options', $this->User->allEnumLists() );

			unset( $this->User->validate['passwd'] );

			if( !empty( $this->data ) ) {
				$this->User->begin();
				// Permet de supprimer les zones associées si on ne filtre pas sur les zones
				$filtre_zone_geo = Set::classicExtract( $this->data, 'User.filtre_zone_geo' );
				if( empty( $filtre_zone_geo ) ) {
					$this->data['Zonegeographique']['Zonegeographique'] = array();
				}

				if( $this->User->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					if( $userDb['User']['group_id'] != $this->data['User']['group_id'] ) {
						$this->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Group','foreign_key'=>$this->data['User']['group_id']));
					}

					$this->Dbdroits->MajCruDroits(
						array(
							'model'=>'Utilisateur',
							'foreign_key'=>$this->data['User']['id'],
							'alias'=>$this->data['User']['username']
						),
						array (
							'model'=>'Group',
							'foreign_key'=>$this->data['User']['group_id']
						),
						$this->data['Droits']
					);
					$this->User->commit();
					$this->Session->setFlash( 'Enregistrement effectué. Veuillez-vous déconnecter et vous reconnecter afin de prendre en compte tous les changements.', 'flash/success' );
					$this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
				}
				else {
					$this->User->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->data = $userDb;
				$this->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Utilisateur','foreign_key'=>$user_id));
			}
			$this->set('listeCtrlAction', $this->Menu->menuCtrlActionAffichage());
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $user_id = null ) {
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
			if( $this->User->deleteAll( array( 'User.id' => $user_id ), true ) ) {

				$aro = $this->Acl->Aro->find(
					'first',
					array(
						'conditions'=>array(
							'model'=>'Utilisateur',
							'foreign_key'=> $user_id
						),
						'fields'=>array('id')
					)
				);
				$aro_id = Set::classicExtract( $aro, 'Aro.id' );
				if( !empty( $aro_id ) ) {
					$aro_aco = $this->Acl->Aro->Permission->findByAroId( $aro_id, null, null, -1 );
				}

				$this->Acl->Aro->delete( $aro['Aro']['id'] );
				$this->Acl->Aro->Permission->delete( $aro_aco['Permission']['id'] );

				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
			}
		}

		/**
		 *
		 */

		public function changepass() {
			if (!empty($this->data)) {
				if (($this->User->validatesPassword($this->data)) && ($this->User->validOldPassword($this->data))) {
					$this->User->id = $this->Session->read('Auth.User.id');
					if ($this->User->saveField('password', Security::hash($this->data['User']['confnewpasswd'], null, true))) {
						$this->Session->setFlash('Votre mot de passe a bien été modifié', 'flash/success');
						$this->redirect('/');
					}
					else
						$this->Session->setFlash('Erreur lors de la saisie des mots de passe.', 'flash/error');
				}
				else
					$this->Session->setFlash('Erreur lors de la saisie des mots de passe.', 'flash/error');
			}
			else
				$this->data['User']['id']=$this->Session->read('Auth.User.id');
		}
	}
?>
