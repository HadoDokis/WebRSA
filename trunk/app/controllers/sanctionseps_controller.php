<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class SanctionsepsController extends AppController
	{
		public $helpers = array( 'Default2' );
		
		public function beforeFilter() {
			parent::beforeFilter();
			$this->model = 'Sanctionep'.Configure::read( 'Cg.departement' );
		}
		
		public $uses = array( 'Sanctionep58'/*, 'Sanctionep93'*/ );

		/**
		*
		*/

		protected function _selectionPassageSanctionep( $qdName, $origine ) {
			if( !empty( $this->data ) ) {
// debug($this->data);
				$this->{$this->model}->begin();

				$success = $this->{$this->model}->saveCohorte($this->data);

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->{$this->model}->commit();
				}
				else {
					$this->{$this->model}->rollback();
				}
			}

			$queryData = $this->{$this->model}->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->{$this->model}->Dossierep->Personne );
			
			$this->data = null;

			$this->set( compact( 'personnes' ) );
            $this->render( $origine );
		}

		/**
		*
		*/

		public function selectionnoninscrits() {
			$this->_selectionPassageSanctionep( 'qdNonInscrits', 'noninscritpe' );
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageSanctionep( 'qdRadies', 'radiepe' );
		}
	}
?>