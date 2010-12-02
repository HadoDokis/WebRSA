<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'descriptionpdo', "Descriptionspdos::{$this->action}", true )
	)
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$sensibilite = Set::classicExtract( $this->data, 'Descriptionpdo.sensibilite' );
	if( empty( $sensibilite ) ) {
		$sensibilite = 'N';
	}

	echo $default->form(
		array(
			'Descriptionpdo.name',
			'Descriptionpdo.modelenotification',
			'Descriptionpdo.sensibilite' => array( 'type' => 'radio', ),
			'Descriptionpdo.dateactive' => array( 'type' => 'select' ),
			'Descriptionpdo.declencheep' => array( 'type' => 'radio' )
		),
		array(
			'actions' => array(
				'descriptionspdos::save',
				'descriptionspdos::cancel'
			),
			'options' => $options
		)
	);
?>
