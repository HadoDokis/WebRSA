<?php
	echo $html->tag(
		'h1',
		$this->pageTitle = __d( 'descriptionpdo', "Descriptionspdos::{$this->action}", true )
	)
?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$sensibilite = Set::classicExtract( $this->data, 'Descriptionpdo.sensibilite' );
	if( empty( $sensibilite ) ) {
		$sensibilite = 'N';
	}

	echo $default->form(
		array(
			'Descriptionpdo.name' => array( 'required' => true  ),
			'Descriptionpdo.modelenotification' => array( 'required' => true  ),
			'Descriptionpdo.sensibilite' => array( 'type' => 'radio', 'value' => $sensibilite, 'required' => true  )
		),
		array(
			'actions' => array(
				'Descriptionpdo.save',
				'Descriptionpdo.cancel'
			),
			'options' => $options
		)
	);
?>
