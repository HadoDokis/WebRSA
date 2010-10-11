<?php
	class TestsController extends AppController
	{
		public $uses = array();

        public $aucunDroit = array( 'index' );

		public function index() {
			$themes = array(
				'Epinay s/ Seine' => 20,
				'Pierrefitte' => 12,
				'Villetaneuse' => 15,
				'Saint Denis' => 10,
				'Ile-St-Denis' => 15,
				'Saint-Ouen' => 8
			);
			$this->set( compact( 'themes' ) );
		}
	}
?>