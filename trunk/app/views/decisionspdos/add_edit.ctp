<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Décisions PDOs';?>

	<h1><?php echo $this->pageTitle;?></h1>

	<?php 
		if( $this->action == 'add' ) {
			echo $form->create( 'Decisionpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo $form->input( 'Decisionpdo.id', array( 'type' => 'hidden', 'value' => '' ) );
		}
		else {
			echo $form->create( 'Decisionpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo $form->input( 'Decisionpdo.id', array( 'type' => 'hidden' ) );
		}
	?>

	<fieldset>
		<?php
			echo $default->subform(
				array(
					'Decisionpdo.libelle',
					'Decisionpdo.clos' => array( 'type' => 'radio' )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>

	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

	<?php echo $form->end();?>

<div class="clearer"><hr /></div>