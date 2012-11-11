<?php
	/**
	 * Code source de la classe ServicesinstructeursController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ServicesinstructeursController ...
	 *
	 * @package app.Controller
	 */
	class ServicesinstructeursController extends AppController
	{

		public $name = 'Servicesinstructeurs';
		public $uses = array( 'Serviceinstructeur', 'Option' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Servicesinstructeurs:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			parent::beforeFilter();
				$this->set( 'typeserins', $this->Option->typeserins() );
				$this->set( 'typevoie', $this->Option->typevoie() );
		}

		/**
		*
		*/

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}


			$querydata = $this->Serviceinstructeur->prepare( 'list' );
			$servicesinstructeurs = $this->Serviceinstructeur->find( 'all', $querydata );
			$this->set( compact( 'servicesinstructeurs') );
		}

		/**
		*
		*/

		public function add() {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			if( !empty( $this->request->data ) ) {
				$debugLevel = Configure::read( 'debug' );
				Configure::write( 'debug', 0 );
				if( $this->Serviceinstructeur->saveAll( $this->request->data ) ) {
					Configure::write( 'debug', $debugLevel );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
				}
				Configure::write( 'debug', $debugLevel );
			}

			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function edit( $serviceinstructeur_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $serviceinstructeur_id ), 'error404' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			if( !empty( $this->request->data ) ) {
				$debugLevel = Configure::read( 'debug' );
				Configure::write( 'debug', 0 );
				if( $this->Serviceinstructeur->saveAll( $this->request->data ) ) {
					Configure::write( 'debug', $debugLevel );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
				}
				else {
					$sqlErrors = $this->Serviceinstructeur->sqrechercheErrors( $this->request->data['Serviceinstructeur']['sqrecherche'] );
					Configure::write( 'debug', $debugLevel );
					$this->set( 'sqlErrors', $sqlErrors );
				}
			}
			else if( $this->action == 'edit' ) {
				$serviceinstructeur = $this->Serviceinstructeur->find(
					'first',
					array(
						'conditions' => array(
							'Serviceinstructeur.id' => $serviceinstructeur_id,
						)
					)
				);

				$this->request->data = $serviceinstructeur;
			}

			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $serviceinstructeur_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $serviceinstructeur_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$serviceinstructeur = $this->Serviceinstructeur->find(
				'first',
				array( 'conditions' => array( 'Serviceinstructeur.id' => $serviceinstructeur_id )
				)
			);

			// Mauvais paramètre
			if( empty( $serviceinstructeur_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Serviceinstructeur->delete( array( 'Serviceinstructeur.id' => $serviceinstructeur_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ) );
			}
		}
	}

?>
