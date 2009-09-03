<?php
	class IndicateursmensuelsController extends AppController
	{
		var $name = 'Indicateursmensuels';

		function index() {
			$annee = Set::extract( $this->data, 'Indicateurmensuel.annee' );
			if( !empty( $annee ) ) {
				$indicateurs = $this->Indicateurmensuel->liste( $annee );
				$this->set( compact( 'indicateurs' ) );
			}
		}
	}
?>