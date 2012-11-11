<?php
	/**
	 * Code source de la classe SituationsdossiersrsaController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SituationsdossiersrsaController ...
	 *
	 * @package app.Controller
	 */
	class SituationsdossiersrsaController extends AppController
	{

		public $name = 'Situationsdossiersrsa';
		public $uses = array( 'Situationdossierrsa',  'Option' , 'Dossier', 'Suspensiondroit',  'Suspensionversement');

		public $commeDroit = array(
			'view' => 'Situationsdossiersrsa:index'
		);

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'moticlorsa', $this->Option->moticlorsa() );
			$this->set( 'motisusdrorsa', $this->Option->motisusdrorsa() );
			$this->set( 'motisusversrsa', $this->Option->motisusversrsa() );
		}

		public function index( $dossier_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$situationdossierrsa = $this->Situationdossierrsa->find(
				'first',
				array(
					'conditions' => array(
						'Situationdossierrsa.dossier_id' => $dossier_id
					),
					'contain' => array(
						'Dossier',
						'Suspensiondroit',
						'Suspensionversement'
					)
				)
			) ;
			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'situationdossierrsa', $situationdossierrsa );
		}

		public function view( $situationdossierrsa_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $situationdossierrsa_id ), 'error404' );

			$situationdossierrsa = $this->Situationdossierrsa->find(
				'first',
				array(
					'conditions' => array(
						'Situationdossierrsa.id' => $situationdossierrsa_id
					),
				'recursive' => -1
				)
			);
			$this->assert( !empty( $situationdossierrsa ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $situationdossierrsa['Situationdossierrsa']['dossier_id'] );
			$this->set( 'situationdossierrsa', $situationdossierrsa );
		}
	}

?>