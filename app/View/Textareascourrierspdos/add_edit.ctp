<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'textareacourrierpdo', "Textareascourrierspdos::{$this->action}" )
	)
?>
<?php
	echo $this->Default->form(
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