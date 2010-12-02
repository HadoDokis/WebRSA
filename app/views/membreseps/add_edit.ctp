<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'un membre pour une équipe pluridisciplinaire';
	}
	else {
		echo $this->pageTitle = 'Modification d\'un membre pour une équipe pluridisciplinaire';
	}
?>
</h1>

<?php		
	echo $default->form(
		array(
			'Membreep.ep_id',
			'Membreep.fonctionmembreep_id',
			'Membreep.qual',
			'Membreep.nom',
			'Membreep.prenom'
		),
		array(
			'id' => 'MembreepAddEditForm',
			'options' => $options
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'membreseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>