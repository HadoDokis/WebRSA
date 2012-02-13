<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'textareacourrierpdo', "Textareascourrierspdos::{$this->action}", true )
	)
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	echo $default->form(
		array(
			'Textareacourrierpdo.courrierpdo_id' => array( 'type' => 'select', 'options' => $options ),
			'Textareacourrierpdo.nomchampodt',
			'Textareacourrierpdo.name' => array( 'type' => 'text' ),
			'Textareacourrierpdo.ordre'
		),
		array(
			'actions' => array(
				'textareascourrierspdos::save',
				'textareascourrierspdos::cancel'
			)
		)
	);
?>