<?php	
	/**
	 * Code source de la classe RecoursController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe RecoursController ...
	 *
	 * @package app.Controller
	 */
	class RecoursController extends AppController
	{

		public $name = 'Recours';
		public $uses = array( 'Infofinanciere', 'Option', 'Avispcgdroitrsa' );

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'commission', $this->Option->commission() );
			$this->set( 'decisionrecours', $this->Option->decisionrecours() );
			$this->set( 'motifrecours', $this->Option->motifrecours() );
		}

		/**
		 *
		 */
		public function gracieux( $dossier_id = null ) {

			$this->assert( valid_int( $dossier_id ), 'invalidParameter' );

			$gracieux = $this->Avispcgdroitrsa->find(
					'first', array(
				'conditions' => array(
					'Avispcgdroitrsa.dossier_id' => $dossier_id
				),
				'recursive' => -1
					)
			);

			$qd_avispcg = array(
				'conditions' => array(
					'Avispcgdroitrsa.dossier_id' => $dossier_id
				)
			);
			$avispcg = $this->Avispcgdroitrsa->find( 'first', $qd_avispcg );


			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'urlmenu', '/indus/index/'.$dossier_id );
			$this->set( 'avispcg', $avispcg );
			$this->set( 'gracieux', $gracieux );
		}

		/**
		 *
		 */
		public function contentieux( $dossier_id = null ) {
			$this->assert( valid_int( $dossier_id ), 'invalidParameter' );

			$contentieux = $this->Avispcgdroitrsa->find(
					'first', array(
				'conditions' => array(
					'Avispcgdroitrsa.dossier_id' => $dossier_id
				),
				'recursive' => -1
					)
			);

			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'urlmenu', '/indus/index/'.$dossier_id );
			$this->set( 'contentieux', $contentieux );
		}

	}
?>