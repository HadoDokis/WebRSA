<?php
	/**
	 * Code source de la classe UsersController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe UsersController permet la gestion des utilisateurs.
	 *
	 * @package app.controllers
	 */
	class UsersController extends AppController
	{
		public $name = 'Users';

		public $uses = array( 'User', 'Option' );

		public $helpers = array( 'Xform', 'Default2' );

		public $components = array( 'Menu', 'Dbdroits', 'Search.Prg' => array( 'actions' => array( 'index' ) ) );

		public $aucunDroit = array( 'login', 'logout' );

		public $commeDroit = array(
			'add' => 'Users:edit'
		);

		/**
		 *
		 */
		public function beforeFilter() {
			ini_set( 'max_execution_time', 0 );
			ini_set( 'memory_limit', '1024M' );
			$return = parent::beforeFilter();
			return $return;
		}

		/**
		 *
		 */
		protected function _setOptions() {
			$options = array(
				'Groups' => $this->User->Group->find( 'list' ),
				'Serviceinstructeur' => $this->User->Serviceinstructeur->listOptions(),
				'structuresreferentes' => $this->User->Structurereferente->find( 'list' ),
				'referents' => $this->User->Referent->find(
					'list',
					array(
						'fields' => array(
							'Referent.id',
							'Referent.nom_complet',
							'Structurereferente.lib_struc'
						),
						'recursive' => -1,
						'joins' => array(
							$this->User->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) )
						),
						'order' => array(
							'Structurereferente.lib_struc ASC',
							'Referent.nom_complet ASC',
						)
					)
				),
			);
			$this->set( compact( 'options' ) );
		}

		/**
		 * Chargement et mise en cache (session) des permissions de l'utilisateur
		 * INFO:
		 * 	- n'est réellement exécuté que la première fois
		 * 	- http://dsi.vozibrale.com/articles/view/all-cakephp-acl-permissions-for-your-views
		 * 	- http://www.neilcrookes.com/2009/02/26/get-all-acl-permissions/
		 */
		protected function _loadPermissions() {
			// FIXME:à bouger dans un composant ?
			if( $this->Session->check( 'Auth.User' ) && !$this->Session->check( 'Auth.Permissions' ) ) {
				$aro = $this->Acl->Aro->find(
						'first', array(
					'conditions' => array(
						'Aro.model' => 'Utilisateur',
						'Aro.foreign_key' => $this->Session->read( 'Auth.User.id' )
					)
						)
				);

				// Recherche des droits pour les sous-groupes
				$parent_id = Set::extract( $aro, 'Aro.parent_id' );
				$parentAros = array( );
				while( !empty( $parent_id ) && ( $parent_id != 0 ) ) {
					$parentAro = $this->Acl->Aro->find(
							'first', array(
						'conditions' => array(
							'Aro.id' => $parent_id
						)
							)
					);
					$parentAros[] = $parentAro;
					$parent_id = Set::extract( $parentAro, 'Aro.parent_id' );
				}

				$permissions = array( );
				if( !empty( $parentAros ) && !empty( $parentAros['Aro'] ) && !empty( $parentAros['Aco'] ) ) {
					$permissions = Set::combine( $parentAros, '/Aco/alias', '/Aco/Permission/_create' );
				}
				if( !empty( $aro ) ) {
					$qd_permissions = array(
						'contain' => array(
							'Aco'
						),
						'fields' => array(
							'Aco.alias',
							'Permission._create'
						),
						'conditions' => array(
							'Permission.aro_id' => array( $aro['Aro']['id'], $aro['Aro']['parent_id'] )
						)
					);
					$data = $this->Acl->Aro->Permission->find( 'all', $qd_permissions );
					$permissions = Set::merge( $permissions, Set::combine( $data, '{n}.Aco.alias', '{n}.Permission._create' ) );

					foreach( $permissions as $key => $permission ) {
						$permissions[$key] = ( $permission != -1 );
					}
					$this->Session->write( 'Auth.Permissions', $permissions );
				}
			}
		}

		/**
		 * Chargement et mise en cache (session) des zones géographiques associées à l'utilisateur
		 * INFO: n'est réellement exécuté que la première fois
		 */
		protected function _loadZonesgeographiques() {
			if( $this->Session->check( 'Auth.User' ) && $this->Session->read( 'Auth.User.filtre_zone_geo' ) && !$this->Session->check( 'Auth.Zonegeographique' ) ) {
				$qd_users_zonegeographiques = array(
					'fields' => array(
						'Zonegeographique.id',
						'Zonegeographique.codeinsee'
					),
					'contain' => array(
						'Zonegeographique'
					),
					'conditions' => array(
						'UserZonegeographique.user_id' => $this->Session->read( 'Auth.User.id' )
					)
				);
				$results = $this->User->UserZonegeographique->find( 'all', $qd_users_zonegeographiques );

				if( count( $results ) > 0 ) {
					$zones = array( );
					foreach( $results as $result ) {
						$zones[$result['Zonegeographique']['id']] = $result['Zonegeographique']['codeinsee'];
					}
					$this->Session->write( 'Auth.Zonegeographique', $zones ); // FIXME: vide -> rééxécute ?
				}
			}
		}

		/**
		 * Chargement du service instructeur de l'utilisateur connecté, lancement
		 * d'une erreur 500 si aucun service instructeur n'est associé à l'utilisateur
		 *
		 * @return void
		 */
		protected function _loadServiceInstructeur() {
			if( !$this->Session->check( 'Auth.Serviceinstructeur' ) ) {
				$qd_service = array(
					'conditions' => array(
						'Serviceinstructeur.id' => $this->Session->read( 'Auth.User.serviceinstructeur_id' )
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$service = $this->User->Serviceinstructeur->find( 'first', $qd_service );
				$this->assert( !empty( $service ), 'error500' );
				$this->Session->write( 'Auth.Serviceinstructeur', $service['Serviceinstructeur'] );
			}
		}

		/**
		 * Chargement du groupe de l'utilisateur connecté, lancement
		 * d'une erreur 500 si aucun groupe n'est associé à l'utilisateur
		 *
		 * @return void
		 */
		protected function _loadGroup() {
			if( !$this->Session->check( 'Auth.Group' ) ) {
				$qd_group = array(
					'conditions' => array(
						'Group.id' => $this->Session->read( 'Auth.User.group_id' )
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$group = $this->User->Group->find( 'first', $qd_group );
				$this->assert( !empty( $group ), 'error500' );
				$this->Session->write( 'Auth.Group', $group['Group'] );
			}
		}

		/**
		 * Permet la connexion via le composant Auth en fonction de la version de CakePHP
		 *
		 * @return type
		 */
		private function _cakeLogin() {
			if( CAKE_BRANCH == '1.2' ) {
				return $this->Auth->user();
			}
			else {
				return $this->Auth->login();
			}
		}

		/**
		 *
		 */
		public function login() {
			if( $this->_cakeLogin() ) {
				/* Lecture de l'utilisateur authentifié */
				if( CAKE_BRANCH == '1.2' ) {
					$authUser = $this->Auth->user();
				}
				else { //Si CakePHP est en version >= 2.0 on interroge la base de données plutôt que le composant Auth
					$authUser = $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $this->Session->read( 'Auth.User.id' ) ), 'recursive' => -1 ) );
				}
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
						$qd_otherConnection = array(
							'conditions' => array(
								'Connection.user_id' => $authUser['User']['id']
							)
						);
						$otherConnection = $this->User->Connection->find( 'first', $qd_otherConnection );

						$this->Session->delete( 'Auth' );
						$this->Session->setFlash(
								sprintf(
										'Utilisateur déjà connecté jusqu\'au %s (nous sommes actuellement le %s)', strftime( '%d/%m/%Y à %H:%M:%S', ( strtotime( $otherConnection['Connection']['modified'] ) + readTimeout() ) ), strftime( '%d/%m/%Y, il est %H:%M:%S' )
								), 'flash/error'
						);

						$this->redirect( $this->Auth->logout() );
					}
				}
				// Fin utilisateurs concurrents

				/* lecture du service de l'utilisateur authentifié */
				if( CAKE_BRANCH == '1.2' ) {
					$this->User->Service->recursive = -1;
				}
				$group = $this->User->Group->find(
						'first', array(
					'conditions' => array(
						'Group.id' => $authUser['User']['group_id']
					),
					'contain' => false
						)
				);
				$authUser['User']['aroAlias'] = $authUser['User']['username'];
				/* lecture de la collectivite de l'utilisateur authentifié */
				$this->Session->write( 'Auth', $authUser );

				// chargements des informations complémentaires
				$this->_loadPermissions();
				$this->_loadZonesgeographiques();
				$this->_loadGroup();
				$this->_loadServiceInstructeur();

				// Supprimer la vue cachée du menu
				$this->_deleteCachedElements();

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
					$this->_deleteCachedElements();

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
		 * Suppression des éléments cachés de l'utilisateur
		 *
		 * @return boolean
		 */
		protected function _deleteCachedElements() {
			if( CAKE_BRANCH == '1.2' ) {
				App::import( 'Core', 'File' );
			}
			else {
				App::uses('Folder', 'Utility');
				App::uses('File', 'Utility');
			}

			$Folder =  new Folder();
			$dir = TMP.'cache'.DS.'views';
			$Folder->cd( $dir );

			$regexp = 'element_'.$this->Session->read( 'Auth.User.username' ).'_.*';
			$results = $Folder->find( $regexp );

			$success = true;
			if( !empty( $results ) ) {
				foreach( $results as $result ) {
					$File =  new File( $dir.DS.$result, false );
					$success = $File->delete() && $success;
				}
			}

			return $success;
		}

		/**
		 * Suppression des répertoires temporaires de l'utilisateur.
		 *
		 * @return void
		 */
		protected function _deleteTemporaryFiles() {
			if( CAKE_BRANCH == '1.2' ) {
				App::import( 'Core', 'File' );
			}
			else {
				App::uses('Folder', 'Utility');
				App::uses('File', 'Utility');
			}

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
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			if( !empty( $this->request->data ) ) {
				$queryData = $this->User->search( $this->request->data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;
				$users = $this->paginate( 'User' );
				$this->set( 'users', $users );
			}
			$this->_setOptions();
			$this->render( 'index' );
		}

		/**
		 * Envoi des options à la vue pour un add ou un edit
		 *
		 * @return void
		 */
		protected function _setOptionsAddEdit() {
			$this->set( 'zglist', $this->User->Zonegeographique->find( 'list' ) );
			$this->set( 'gp', $this->User->Group->find( 'list' ) );
			$this->set( 'si', $this->User->Serviceinstructeur->find( 'list' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'options', $this->User->allEnumLists() );
			$this->set( 'structuresreferentes', $this->User->Structurereferente->find( 'list' ) );
			$this->set( 'referents', $this->User->Referent->find(
					'list',
					array(
						'fields' => array(
							'Referent.id',
							'Referent.nom_complet',
							'Structurereferente.lib_struc'
						),
						'recursive' => -1,
						'joins' => array(
							$this->User->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) )
						),
						'order' => array(
							'Structurereferente.lib_struc ASC',
							'Referent.nom_complet ASC',
						)
					)
				)
			);
		}

		/**
		 *
		 */
		// FIXME: à l'ajout, on n'obtient pas toutes les acl de son groupe
		public function add() {
			if( !empty( $this->request->data ) ) {
				$this->User->begin();
				if( $this->User->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					// Définition des nouvelles permissions

					$this->request->data['Droits'] = $this->Dbdroits->litCruDroits( array( 'model' => 'Group', 'foreign_key' => $this->request->data['User']['group_id'] ) );
					$this->Dbdroits->MajCruDroits(
						array(
							'model' => 'Utilisateur',
							'foreign_key' => $this->User->id,
							'alias' => $this->request->data['User']['username']
						),
						array(
							'model' => 'Group',
							'foreign_key' => $this->request->data['User']['group_id']
						),
						$this->request->data['Droits']
					);
					$this->User->commit();
					$this->Session->setFlash( 'Enregistrement effectué. Veuillez-vous déconnecter et vous reconnecter afin de prendre en compte tous les changements.', 'flash/success' );
					$this->redirect( array( 'controller' => 'users', 'action' => 'index' ) );
				}
				else {
					$this->User->rollback();
				}
			}

			$this->_setOptionsAddEdit();
			$this->render( 'add_edit' );
		}

		/**
		 *
		 */
		public function edit( $user_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $user_id ), 'error404' );

			$qd_userDb = array(
				'conditions' => array(
					'User.id' => $user_id
				)
			);
			$userDb = $this->User->find( 'first', $qd_userDb );


			$this->assert( !empty( $userDb ), 'error404' );

			unset( $this->User->validate['passwd'] );

			if( !empty( $this->request->data ) ) {
				$this->User->begin();
				// Permet de supprimer les zones associées si on ne filtre pas sur les zones
				$filtre_zone_geo = Set::classicExtract( $this->request->data, 'User.filtre_zone_geo' );
				if( empty( $filtre_zone_geo ) ) {
					$this->request->data['Zonegeographique']['Zonegeographique'] = array( );
				}

				if( $this->User->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					if( $userDb['User']['group_id'] != $this->request->data['User']['group_id'] ) {
						$this->request->data['Droits'] = $this->Dbdroits->litCruDroits( array( 'model' => 'Group', 'foreign_key' => $this->request->data['User']['group_id'] ) );
					}

					$this->Dbdroits->MajCruDroits(
						array(
							'model' => 'Utilisateur',
							'foreign_key' => $this->request->data['User']['id'],
							'alias' => $this->request->data['User']['username']
						),
						array(
							'model' => 'Group',
							'foreign_key' => $this->request->data['User']['group_id']
						),
						$this->request->data['Droits']
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
				$this->request->data = $userDb;
				$this->request->data['Droits'] = $this->Dbdroits->litCruDroits( array( 'model' => 'Utilisateur', 'foreign_key' => $user_id ) );
			}

			$this->_setOptionsAddEdit();
			$this->set( 'listeCtrlAction', $this->Menu->menuCtrlActionAffichage() );
			$this->render( 'add_edit' );
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
					'first', array( 'conditions' => array( 'User.id' => $user_id )
					)
			);

			// Mauvais paramètre
			if( empty( $user_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->User->deleteAll( array( 'User.id' => $user_id ), true ) ) {

				$aro = $this->Acl->Aro->find(
						'first', array(
					'conditions' => array(
						'model' => 'Utilisateur',
						'foreign_key' => $user_id
					),
					'fields' => array( 'id' )
						)
				);
				$aro_id = Set::classicExtract( $aro, 'Aro.id' );
				if( !empty( $aro_id ) ) {
					$qd_aro_aco = array(
						'conditions' => array(
							'Permission.aro_id' => $aro_id
						),
						'fields' => null,
						'order' => null,
						'recursive' => -1
					);
					$aro_aco = $this->Acl->Aro->Permission->find( 'first', $qd_aro_aco );
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
			if( !empty( $this->request->data ) ) {
				if( ($this->User->validatesPassword( $this->request->data )) && ($this->User->validOldPassword( $this->request->data )) ) {
					$this->User->id = $this->Session->read( 'Auth.User.id' );
					if( $this->User->saveField( 'password', Security::hash( $this->request->data['User']['confnewpasswd'], null, true ) ) ) {
						$this->Session->setFlash( 'Votre mot de passe a bien été modifié', 'flash/success' );
						$this->redirect( '/' );
					}
					else
						$this->Session->setFlash( 'Erreur lors de la saisie des mots de passe.', 'flash/error' );
				}
				else
					$this->Session->setFlash( 'Erreur lors de la saisie des mots de passe.', 'flash/error' );
			}
			else
				$this->request->data['User']['id'] = $this->Session->read( 'Auth.User.id' );
		}

	}
?>