<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Zones géographiques';?>

	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Zonegeographique', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden', 'value' => '' ) );
		}
		else {
			echo $form->create( 'Zonegeographique', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
		}
	?>

	<fieldset>
		<?php echo $form->input( 'Zonegeographique.libelle', array( 'label' => required( __( 'libelle', true ) ), 'type' => 'text' ) );?>
		<?php echo $form->input( 'Zonegeographique.codeinsee', array( 'label' => required( __( 'codeinsee', true ) ), 'type' => 'text', 'maxLength' => 5 ) );?>
	</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>

<div class="clearer"><hr /></div>