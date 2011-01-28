<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'un motif de demande de réorientation';
	}
	else {
		echo $this->pageTitle = 'Modification d\'un motif de demande de réorientation';
	}
?>
</h1>

<?php
	echo $default->form(
		array(
			'Motifreorient.name'
		),
		array(
			'id' => 'MotifreorientAddEditForm'
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'motifsreorients',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>