<?php
	/**
	 * Code source de la classe ModescontactController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ModescontactController ...
	 * (CG 66 et 93).
	 *
	 * @package app.Controller
	 */
	class ModescontactController extends AppController
	{
		public $name = 'Modescontact';

		public $uses = array( 'Modecontact',  'Option' , 'Foyer');

		public $helpers = array( 'Xform','Default2',  'Default', 'Theme' );

		public $components = array( 'Jetons2' );

		public $commeDroit = array(
			'view' => 'Modescontact:index',
			'add' => 'Modescontact:edit'
		);

		protected function _setOptions() {
			$options = array();
			$options['Modecontact']['nattel'] = $this->Option->nattel();
			$options['Modecontact']['matetel'] = $this->Option->matetel();
			$options['Modecontact']['autorutitel'] = $this->Option->autorutitel();
			$options['Modecontact']['autorutiadrelec'] = $this->Option->autorutiadrelec();
			$this->set( compact( 'options' ) );
		}

		public function index( $foyer_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			// Recherche des personnes du foyer
			$modescontact = $this->Modecontact->find(
				'all',
				array(
					'conditions' => array( 'Modecontact.foyer_id' => $foyer_id ),
					'contain' => false
				)
			);

			// Assignations à la vue
			$this->set( 'foyer_id', $foyer_id );
			$this->set( 'modescontact', $modescontact );
			$this->_setOptions();
		}

		public function add( $foyer_id = null ){
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			$dossier_id = $this->Foyer->dossierId( $foyer_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			if( !empty( $this->request->data ) ) {
				$this->Modecontact->create( $this->request->data );
				if( $this->Modecontact->validates() ) {
					$this->Modecontact->begin();

					if( $this->Modecontact->save( $this->request->data ) ) {
						$this->Modecontact->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement réussi', 'flash/success' );
						$this->redirect( array( 'controller' => 'modescontact', 'action' => 'index', $foyer_id ) );
					}
					else {
						$this->Modecontact->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}

			$this->set( 'foyer_id', $foyer_id );
			$this->request->data['Modecontact']['foyer_id'] = $foyer_id;

			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function edit( $id = null ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$dossier_id = $this->Modecontact->dossierId( $id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Essai de sauvegarde
			if( !empty( $this->request->data ) ) {
				$this->Modecontact->set( $this->request->data );
				if( $this->Modecontact->validates() ) {
					$this->Modecontact->begin();

					if( $this->Modecontact->save( $this->request->data ) ) {
						$this->Modecontact->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array(  'controller' => 'modescontact','action' => 'index', $this->request->data['Modecontact']['foyer_id'] ) );
					}
					else {
						$this->Modecontact->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			// Afficage des données
			else {
				$modecontact = $this->Modecontact->find(
					'first',
					array(
						'conditions' => array( 'Modecontact.id' => $id ),
						'contain' => false
					)
				);
				$this->assert( !empty( $modecontact ), 'invalidParameter' );

				// Assignation au formulaire
				$this->request->data = $modecontact;
			}

			$this->Modecontact->commit();
			$this->_setOptions();
			$this->render( 'add_edit' );

		}

		public function view( $modecontact_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $modecontact_id ), 'error404' );

			$modecontact = $this->Modecontact->find(
				'first',
				array(
					'conditions' => array(
						'Modecontact.id' => $modecontact_id
					),
					'recursive' => -1
				)

			);

			$this->assert( !empty( $modecontact ), 'error404' );

			// Assignations à la vue
			$this->set( 'foyer_id', $modecontact['Modecontact']['foyer_id'] );
			$this->set( 'modecontact', $modecontact );
			$this->_setOptions();

		}
	}
?>
