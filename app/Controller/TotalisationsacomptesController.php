<?php	
	/**
	 * Code source de la classe TotalisationsacomptesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe TotalisationsacomptesController ...
	 *
	 * @package app.Controller
	 */
	class TotalisationsacomptesController extends AppController
	{

		public $name = 'Totalisationsacomptes';
		public $uses = array( 'Totalisationacompte', 'Identificationflux', 'Option', 'Infofinanciere' );
		public $helpers = array( 'Locale', 'Csv' );

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function beforeFilter() {
			parent::beforeFilter();
			// Type_totalisation
			$this->set( 'type_totalisation', $this->Option->type_totalisation() );
			$this->set( 'natpfcre', ClassRegistry::init('Infofinanciere')->enum('natpfcre'));
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function index() {
			if( !empty( $this->request->data ) ) {
				$params = $this->Totalisationacompte->search( $this->request->data );
				$totsacoms = $this->Totalisationacompte->find( 'all', $params );
				$this->set('totsacoms', $totsacoms );
			}
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function exportcsv() {
			$params = $this->Totalisationacompte->search( Hash::expand( $this->request->params['named'], '__' ) );
			$totsacoms = $this->Totalisationacompte->find( 'all', $params );

			$identsflux = $this->Identificationflux->find( 'all' );
			$this->set( 'identsflux', $identsflux );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'totsacoms' ) );
		}
	}

?>