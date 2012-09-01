<h1>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'un lien entre statut et type de RDV';
	}
	else {
		echo $this->pageTitle = 'Modification d\'un lien entre statut et type de RDV';
	}
?>
</h1>

<?php
	echo $xform->create( null, array( 'id' => 'StatutrdvTyperdvAddEditForm' ) );

	if (isset($this->data['StatutrdvTyperdv']['id']))
		echo $form->input('StatutrdvTyperdv.id', array('type'=>'hidden'));

	echo $default->subform(
		array(
			'StatutrdvTyperdv.typerdv_id' => array('required' => true, 'type' => 'select', 'options' => $typesrdv),
			'StatutrdvTyperdv.statutrdv_id' => array('required' => true, 'type' => 'select', 'options' => $statutsrdvs),
			'StatutrdvTyperdv.nbabsenceavantpassageep' => array('required' => true, 'type' => 'text'),
			'StatutrdvTyperdv.motifpassageep' => array('type' => 'text')
		),
		array(
			'options' => $options
		)
	);



	echo $xform->end( __( 'Save', true ) );

    echo $default->button(
		'back',
        array(
        	'controller' => 'statutsrdvs_typesrdv',
        	'action'     => 'index'
        ),
        array(
        	'id' => 'Back'
        )
	);
?>