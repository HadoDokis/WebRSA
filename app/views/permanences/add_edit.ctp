<?php
	$this->pageTitle = 'Permanences';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Permanence', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	}
	else {
		echo $form->create( 'Permanence', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Permanence.id', array( 'label' => false, 'type' => 'hidden' ) );
	}
?>

	<fieldset>
		<?php
			echo $form->input( 'Permanence.libpermanence', array( 'label' => required( __( 'Libellé de la permanence', true ) ), 'type' => 'text' ) );
			echo $form->input( 'Permanence.structurereferente_id', array( 'label' => required( __( 'Type de structure liée à la permanence', true ) ), 'type' => 'select', 'options' => $sr, 'empty' => true ) );
			echo $form->input( 'Permanence.numtel', array( 'label' => required( __( 'N° téléphone de la permanence', true ) ), 'type' => 'text', 'maxlength' => 15 ) );
			echo $form->input( 'Permanence.numvoie', array( 'label' =>  __d( 'adresse', 'Adresse.numvoie', true ), 'type' => 'text', 'maxlength' => 15 ) );
			echo $form->input( 'Permanence.typevoie', array( 'label' => required( __d( 'adresse', 'Adresse.typevoie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );
			echo $form->input( 'Permanence.nomvoie', array( 'label' => required(  __d( 'adresse', 'Adresse.nomvoie', true ) ), 'type' => 'text', 'maxlength' => 50 ) );
			echo $form->input( 'Permanence.compladr', array( 'label' =>  __d( 'adresse', 'Adresse.compladr', true ), 'type' => 'text', 'maxlength' => 50 ) );
			echo $form->input( 'Permanence.codepos', array( 'label' => required( __d( 'adresse', 'Adresse.codepos', true ) ), 'type' => 'text', 'maxlength' => 5 ) );
			echo $form->input( 'Permanence.ville', array( 'label' => required( __( 'ville', true ) ), 'type' => 'text' ) );
			echo $form->input( 'Permanence.actif', array( 'label' => required( __( 'actif', true ) ), 'type' => 'radio', 'options' => $options['actif'] ) );
		?>
	</fieldset>

	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<?php echo $form->end();?>