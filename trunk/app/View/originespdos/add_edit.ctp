<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'originepdo', "Originespdos::{$this->action}", true )
	)
?>
<?php
	$fields = array(
		'Originepdo.libelle'
	);

	if ( Configure::read( 'Cg.departement' ) == 66 ) {
		$fields = array_merge(
			$fields,
			array( 'Originepdo.originepcg' => array( 'type' => 'radio' ) ),
			array( 'Originepdo.cerparticulier' => array( 'type' => 'radio' ) )
		);
	}
	else {
		$fields = array_merge(
			$fields,
			array( 'Originepdo.originepcg' => array( 'type' => 'hidden', 'value' => 'N' ) )
		);
	}

	echo $default->form(
		$fields,
		array(
			'options' => $options,
			'actions' => array(
				'Originepdo.save',
				'Originepdo.cancel'
			)
		)
	);
?>