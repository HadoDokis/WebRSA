<?php
	$this->pageTitle = 'Question PDO';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<h1><?php echo $this->pageTitle;?></h1>

<fieldset>
<?php
	echo $form->create( 'Questionpcg66', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

	echo $default2->subform(
		array(
			'Questionpcg66.id' => array( 'type'=>'hidden' ),
			'Questionpcg66.defautinsertion' => array( 'required' => true, 'type' => 'select', 'empty' => true ),
			'Questionpcg66.compofoyerpcg66_id' => array( 'required' => true, 'type' => 'select', 'empty' => true, 'options' => $options['Compofoyerpcg66'] ),
			'Questionpcg66.recidive' => array( 'required' => true, 'type' => 'radio', 'empty' => true ),
			'Questionpcg66.phase' => array( 'required' => true, 'type' => 'select', 'empty' => true ),
			'Questionpcg66.decisionpcg66_id' => array( 'required' => true, 'type' => 'select', 'empty' => true, 'options' => $options['Decisionpcg66'] )
		),
		array(
			'options' => $options
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