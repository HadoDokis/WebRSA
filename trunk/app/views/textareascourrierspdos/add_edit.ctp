<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'textareacourrierpdo', "Textareascourrierspdos::{$this->action}", true )
	)
?>
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