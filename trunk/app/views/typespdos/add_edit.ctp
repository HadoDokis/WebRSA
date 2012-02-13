<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typepdo', "Typespdos::{$this->action}", true )
	)
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$fields = array(
		'Typepdo.libelle'
	);

	if ( Configure::read( 'Cg.departement' ) == 66 ) {
		$fields = array_merge(
			$fields,
			array( 'Typepdo.originepcg' => array( 'type' => 'radio' ) )
		);
	}
	else {
		$fields = array_merge(
			$fields,
			array( 'Typepdo.originepcg' => array( 'type' => 'hidden', 'value' => 'N' ) )
		);
	}

	echo $default->form(
		$fields,
		array(
			'options' => $options,
			'actions' => array(
				'Typepdo.save',
				'Typepdo.cancel'
			)
		)
	);
?>
