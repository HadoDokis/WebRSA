<?php
	/**
	 * Code source de la classe StatutsrdvsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppController', 'Controller' );

	/**
	 * La classe StatutsrdvsController ...
	 *
	 * @package app.Controller
	 */
	class StatutsrdvsController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Statutsrdvs';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(

		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			'Xform',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Rendezvous',
			'Option',
			'Statutrdv',
		);

		/**
		 * Utilise les droits d'un autre Controller:action
		 * sur une action en particulier
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'add' => 'Statutsrdvs:edit',
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
		);

		protected function _setOptions() {
			$provoquepassagecommission = array( '0' => 'Non', '1' => 'Oui' );
			$permetpassageepl = array( '0' => 'Non', '1' => 'Oui' );
			$this->set( compact( 'provoquepassagecommission', 'permetpassageepl' ) );
		}

		/**
		 * Liste des statuts de RDV
		 */
		public function index() {
			$statutsrdvs = $this->Statutrdv->find(
				'all',
				array(
					'recursive' => -1,
					'order' => 'Statutrdv.libelle ASC'
				)
			);

			$this->_setOptions();
			$this->set( 'statutsrdvs', $statutsrdvs );
		}

		public function add() {
			if( !empty( $this->request->data ) ) {
				$this->Statutrdv->begin();
				if( $this->Statutrdv->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->Statutrdv->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'statutsrdvs', 'action' => 'index' ) );
				}
				else {
					$this->Statutrdv->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function edit( $statutrdv_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $statutrdv_id ), 'invalidParameter' );

			$statutrdv = $this->Statutrdv->find(
				'first',
				array(
					'conditions' => array(
						'Statutrdv.id' => $statutrdv_id
					),
					'recursive' => -1
				)
			);

			// Si action n'existe pas -> 404
			if( empty( $statutrdv ) ) {
				$this->cakeError( 'error404' );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->Statutrdv->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'statutsrdvs', 'action' => 'index', $statutrdv['Statutrdv']['id']) );
				}
			}
			else {
				$this->request->data = $statutrdv;
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function delete( $statutrdv_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $statutrdv_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$statutrdv = $this->Statutrdv->find(
				'first',
				array( 'conditions' => array( 'Statutrdv.id' => $statutrdv_id )
				)
			);

			// Mauvais paramètre
			if( empty( $statutrdv ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Statutrdv->deleteAll( array( 'Statutrdv.id' => $statutrdv_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'statutsrdvs', 'action' => 'index' ) );
			}
		}
	}
?>