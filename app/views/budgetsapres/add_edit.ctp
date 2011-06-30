<?php
	$this->pageTitle = 'Budget APRE';
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $xhtml->tag( 'h1', $this->pageTitle );

	echo $xform->create( 'Budgetapre', array( 'url' => Router::url( null, true ) ) );

	if( isset( $this->data['Budgetapre']['id'] ) ) {
		echo $xhtml->tag( 'div', $xform->input( 'Budgetapre.id' ) );
	}
	echo $xform->input( 'Budgetapre.exercicebudgetai', array( 'domain' => 'apre', 'options' => array_range( date( 'Y' ) + 1, date( 'Y' ) - 10 ), 'empty' => true ) );
	echo $xform->input( 'Budgetapre.montantattretat', array( 'domain' => 'apre', 'maxlength' => 10  ) );
	echo $xform->input( 'Budgetapre.ddexecutionbudge', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
	echo $xform->input( 'Budgetapre.dfexecutionbudge', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );

	echo $xform->submit( 'Enregistrer' );
	echo $xform->end();
?>
<?php
	echo $default->button(
		'back',
		array(
			'controller' => 'budgetsapres',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>