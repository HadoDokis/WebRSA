<?php
	/**
	 * Code source de la classe RolesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
    App::uses( 'OccurencesBehavior', 'Model/Behavior' );
	App::uses( 'AppController', 'Controller' );

	/**
	 * La classe RolesController ...
	 *
	 * @package app.Controller
	 */
	class RolesController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Roles';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Default',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default',
			'Default2',
			'Theme',
			'Xform',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Role',
		);

		/**
		 * Utilise les droits d'un autre Controller:action
		 * sur une action en particulier
		 *
		 * @var array
		 */
		public $commeDroit = array(

		);

		/**
		 * Méthodes ne nécessitant aucun droit.
		 *
		 * @var array
		 */
		public $aucunDroit = array(

		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'delete' => 'delete',
			'edit' => 'update',
			'index' => 'read',
			'view' => 'read',
		);

		/**
		 * Listing du contenu de la table
		 */
		public function index() {
			$this->Role->Behaviors->attach( 'Occurences' );

            $querydata = $this->Role->qdOccurencesExists(
                array(
                    'fields' => $this->Role->fields(),
                    'order' => array('Role.name ASC')
                )
            );

            $this->paginate = $querydata;
            $roles = $this->paginate('Role');
			$options = $this->_options();
            $this->set(compact('roles', 'options'));
		}

		/**
		 * Ajout d'une entrée
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Modification d'une entrée
		 *
		 * @param integer $id
		 */
		public function edit( $id = null ) {
            // Retour à la liste en cas d'annulation
            if(isset( $this->request->data['Cancel'])) {
                $this->redirect(array('controller' => 'roles', 'action' => 'index'));
            }

			if(!empty($this->request->data)) {
				$this->Role->begin();
				$this->Role->create( $this->request->data );
				$success = $this->Role->save();
				$role_id = $this->Role->id;

				if ($success) {
					// On prend les anciennes valeurs de RoleUser
					$old = (array)Hash::extract(
						$this->Role->RoleUser->find('all',
							array(
								'fields' => array('user_id'),
								'conditions' => array(
									'role_id' => $role_id
								)
							)
						), '{n}.RoleUser.user_id'
					);

					// On prend les nouvelles
					$new = (array)Hash::get($this->request->data, 'RoleUser.user_id');

					// On défini ce qui doit être ajouté ou supprimé de la base de donnée
					$toWrite = array_diff($new, $old);
					$notDelete = array_intersect($old, $new);

					// On détruit ce qui n'est pas dans la liste
					if (!empty($old)) {
						$conditions = array('role_id' => $role_id);
						if (!empty($notDelete)) {
							$conditions[] = 'user_id NOT IN ('.implode(', ', $notDelete).')';
						}
						$this->Role->RoleUser->deleteAll($conditions);
					}

					// On enregistre les nouvelles valeurs
					if (!empty($toWrite)) {
						$data = array();
						foreach ($toWrite as $user_id) {
							$data[] = compact('role_id', 'user_id');
						}
						$success = $this->Role->RoleUser->saveAll($data);
					}
				}

				$this->_setFlashResult('Save', $success);
				if ($success) {
					Cache::config('one day', array(
						'engine' => 'File',
						'duration' => '+1 day',
						'path' => CACHE,
						'prefix' => 'cake_oneday_'
					));
					Cache::clear(false, 'one day');
					$this->Role->commit();
					$this->redirect( array( 'action' => 'index' ) );
				} else {
					$this->Role->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Role->find(
					'first',
					array(
						'contain' => array('RoleUser'),
						'conditions' => array( 'Role.id' => $id )
					)
				);
				$user_ids = Hash::extract($this->request->data, 'RoleUser.{n}.user_id');
				unset($this->request->data['RoleUser']);
				$this->request->data['RoleUser']['user_id'] = $user_ids;

				$this->assert( !empty( $this->request->data ), 'error404' );
			}
			else{
				$this->request->data['Role']['actif'] = true;
			}

			$users = $this->Role->User->find('all',
				array(
					'fields' => array(
						'User.id',
						'("User"."nom" || \' \' || "User"."prenom") AS "User__nom_prenom"',
						'Group.name',
					),
					'joins' => array(
						$this->Role->User->join('Group')
					),
					'contain' => false,
					'order' => array(
						'Group.name' => 'ASC',
						'User.nom' => 'ASC',
						'User.prenom' => 'ASC',
					)
				)
			);

			$dataUsers = array();
			foreach ($users as $user) {
				$dataUsers[$user['Group']['name']][$user['User']['id']] = $user['User']['nom_prenom'];
			}

			$options = $this->_options();

			$this->set( compact( 'options', 'dataUsers' ) );

			$this->view = 'edit';
		}

		/**
		 * Suppression d'une entrée
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		 * Visualisation de la table
		 *
		 * @param integer $id
		 */
		public function view( $id ) {
			$this->Default->view( $id );
		}

		/**
		 * Options pour la vue
		 *
		 * @return array
		 */
		protected function _options() {
			return array();
		}
	}
?>