<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Contratsinsertion');

	class TestContratsinsertionController extends ContratsinsertionController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Contratsinsertion';

		public function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}

		public function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}

		public function _stop($status = 0) {
			$this->stopped = $status;
		}

		public function assert( $condition, $error = 'error500', $parameters = array() ) {
			$this->condition = $condition;
			$this->error = $error;
			$this->parameters = $parameters;
		}

		public function add() {
			$this->action = 'add';
			parent::add();
		}

		public function edit() {
			$this->action = 'edit';
			parent::edit();
		}

		public function _add_edit() {
			$this->action = '_add_edit';
			parent::_add_edit();
		}
 
		public function delete() {
			$this->action = 'delete';
			parent::delete($id);
		}

		// Attention on surcharge la visibilite du parent
		function _setOptions() {
			return parent::_setOptions();
		}

		// Attention on surcharge la visibilite du parent
		function _libelleTypeorientNiv0($typeorient_id) {
			return parent::_libelleTypeorientNiv0($typeorient_id);
		}

		// Attention on surcharge la visibilite du parent
		function _referentStruct() {
			return parent::_referentStruct();
		}

		// Attention on surcharge la visibilite du parent
		function _getDsp($personne_id) {
			return parent::_getDsp($personne_id);
		}
	}

	class ContratsinsertionControllerTest extends CakeAppControllerTestCase {
/*
		protected function _setOptions() {

		}

		protected function _libelleTypeorientNiv0(  ) {
			$typeorient_id
		}

		protected function _referentStruct(  ) {

		}

		public function ajaxref( $referent_id = null ) { // FIXME

		}

		public function ajaxstruct( $structurereferente_id = null ) { // FIXME

		}

		public function index( $personne_id = null ){

		}

		public function view( $contratinsertion_id = null ) {

		}

		public function add() {

		}

		public function edit() {

		}

		protected function _getDsp(  ) {
			$personne_id
		}

		protected function _add_edit( $id = null ) {

		}

		public function valider( $contratinsertion_id = null ) {

		}

		public function delete( $id ) {

		}

		public function notificationsop( $id = null ) {

		}
*/
	}

?>
