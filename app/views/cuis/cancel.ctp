<?php
	$modelClassName = 'Cui';
	$domain = "cui";

	$this->pageTitle = __d( $domain, "Cuis::{$this->action}", true );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<?php
		echo $xform->create();


		echo $default->subform(
			array(
				"{$modelClassName}.id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.personne_id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.positioncui66" => array( 'type' => 'hidden', 'value' => 'annule' ),
                "{$modelClassName}.decisioncui" => array( 'type' => 'hidden', 'value' => 'C' ),
				"{$modelClassName}.motifannulation" => array( 'type' => 'textarea' )
			),
			array(
				'domain' => $domain
			)
		);

		echo '<div class="submit">';
		echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		echo '</div>';
		echo $xform->end();
	?>
</div>
<div class="clearer"><hr /></div>