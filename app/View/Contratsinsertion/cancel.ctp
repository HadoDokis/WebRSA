<?php
	$modelClassName = 'Contratinsertion';
	$domain = "contratinsertion";

	$this->pageTitle = __d( $domain, "Contratsinsertion::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<?php
		echo $this->Xform->create();


		echo $this->Default->subform(
			array(
				"{$modelClassName}.id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.personne_id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.structurereferente_id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.positioncer" => array( 'type' => 'hidden', 'value' => 'annule' ),
				"{$modelClassName}.motifannulation" => array( 'type' => 'textarea' ),
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
</div>
<div class="clearer"><hr /></div>