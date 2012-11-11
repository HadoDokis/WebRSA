<?php	
	/**
	 * Code source de la classe EvenementsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe EvenementsController ...
	 *
	 * @package app.Controller
	 */
	class EvenementsController extends AppController
	{

		public $name = 'Evenements';
		public $uses = array( 'Option', 'Foyer', 'Evenement' );
		public $helpers = array( 'Locale', 'Xform' );

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'fg', $this->Option->fg() );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function index( $foyer_id = null ){
			$this->assert( valid_int( $foyer_id ), 'invalidParameter' );

			$evenements = $this->Evenement->find(
				'all',
				array(
					'conditions' => array(
						'Evenement.foyer_id' => $foyer_id
					)
				)
			);
			$this->set( 'evenements', $evenements );
			$this->set( 'foyer_id', $foyer_id );
		}
	}
?>