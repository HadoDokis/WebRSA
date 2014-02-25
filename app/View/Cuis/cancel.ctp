<?php
	$modelClassName = 'Cui';
	$domain = "cui";

	$this->pageTitle = __d( $domain, "Cuis::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>
<h1><?php echo $this->pageTitle;?></h1>
<?php
	echo $this->Xform->create();


	echo $this->Default->subform(
		array(
			"{$modelClassName}.id" => array( 'type' => 'hidden' ),
			"{$modelClassName}.personne_id" => array( 'type' => 'hidden' ),
			"{$modelClassName}.positioncui66" => array( 'type' => 'hidden', 'value' => 'annule' ),
			"{$modelClassName}.decisioncui" => array( 'type' => 'hidden', 'value' => 'annule' ),
			"{$modelClassName}.motifannulation" => array( 'type' => 'textarea' )
		),
		array(
			'domain' => $domain
		)
	);

	echo '<div class="submit">';
	echo $this->Xform->submit( 'Enregistrer', array( 'div' => false ) );
	echo $this->Xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
	echo '</div>';
	echo $this->Xform->end();
?>