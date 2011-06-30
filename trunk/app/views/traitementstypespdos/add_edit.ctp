<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'traitementtypepdo', "Traitementstypespdos::{$this->action}", true )
	)
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	echo $default->form(
		array(
			'Traitementtypepdo.name' => array( 'type' => 'text', 'required' => true )
		),
		array(
			'actions' => array(
				'Traitementtypepdo.save',
				'Traitementtypepdo.cancel'
			)
		)
	);
?>
