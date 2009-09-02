<?php
	class IndicateursmensuelsController extends AppController
	{
		var $name = 'Indicateursmensuels';

		function index() {
			$indicateurs = $this->Indicateurmensuel->liste( 2009 );
			$this->set( compact( 'indicateurs' ) );
		}
	}
?>