<?php
	$this->pageTitle = 'Paramètres financiers pour la gestion de l\'APRE';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag( 'h1', $this->pageTitle );

	echo $xform->create( 'Parametrefinancier' );
	if( isset( $this->data['Parametrefinancier']['id'] ) ) {
		echo '<div>'.$xform->input( 'Parametrefinancier.id', array( 'type' => 'hidden' ) ).'</div>';
	}
	echo $xform->input( 'Parametrefinancier.entitefi', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.engagement', array(  'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.tiers', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.codecdr', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.libellecdr', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.natureanalytique', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.lib_natureanalytique', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.programme', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.lib_programme', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.apreforfait', array(  'required' => true, 'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.aprecomplem', array(  'domain' => 'apre' ) );
	echo $xform->input( 'Parametrefinancier.natureimput', array(  'required' => true, 'domain' => 'apre' ) );

	echo '<div class="submit">';
		echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
	echo '</div>';

	echo $xform->end();
?>