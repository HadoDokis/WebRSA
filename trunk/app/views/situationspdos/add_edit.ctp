<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}", true )
	)
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$fields = array(
		'Situationpdo.libelle' => array( 'type' => 'text' )
	);

// 	if ( Configure::read( 'Cg.departement' ) == 66 ) {
// 		$fields['Situationpdo.nc'] = array( 'type' => 'checkbox' );
// 		$fields['Situationpdo.nr'] = array( 'type' => 'checkbox' );
// 	}

	echo $default->form(
		$fields,
		array(
			'actions' => array(
				'Situationpdo.save',
				'Situationpdo.cancel'
			)
		)
	);
?>
