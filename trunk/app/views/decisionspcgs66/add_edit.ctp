<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Décisions PDOs';?>

	<h1><?php echo $this->pageTitle;?></h1>

	<fieldset>
	<?php
		echo $form->create( 'Decisionpcg66', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

		echo $default2->subform(
			array(
				'Decisionpcg66.id' => array( 'type'=>'hidden' ),
				'Decisionpcg66.name' => array( 'required' => true ),
				'Decisionpcg66.nbmoisecheance' => array( 'required' => true ),
				'Decisionpcg66.courriernotif'
			)
		);
	?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	</fieldset>