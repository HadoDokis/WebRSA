<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'un regroupement d\'E.P.';
	}
	else {
		echo $this->pageTitle = 'Modification d\'un regroupement d\'E.P.';
	}
?>
</h1>

<?php		
	echo $default->form(
		array(
			'Regroupementep.name'
		),
		array(
			'id' => 'RegroupementepAddEditForm',
			'options' => $options
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'regroupementseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>