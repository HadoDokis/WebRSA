<?php
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
			$this->set( 'natpfcre', $this->Option->natpfcre(  ) );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function index() {
			if( !empty( $this->data ) ) {
				$params = $this->Totalisationacompte->search( $this->data );
				$totsacoms = $this->Totalisationacompte->find( 'all', $params );
				$this->set('totsacoms', $totsacoms );
			}
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function exportcsv() {
			$params = $this->Totalisationacompte->search( array_multisize( $this->params['named'] ) );
			$totsacoms = $this->Totalisationacompte->find( 'all', $params );

			$identsflux = $this->Identificationflux->find( 'all' );
			$this->set( 'identsflux', $identsflux );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'totsacoms' ) );
		}
	}

?>