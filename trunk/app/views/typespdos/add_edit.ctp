<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'typepdo', "Typespdos::{$this->action}", true )
	)
?>

<?php
	$fields = array(
		'Typepdo.libelle'
	);

	if ( Configure::read( 'Cg.departement' ) == 66 ) {
		$fields = array_merge(
			$fields,
			array( 'Typepdo.originepcg' => array( 'type' => 'radio' ) ),
			array( 'Typepdo.cerparticulier' => array( 'type' => 'radio' ) )
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
