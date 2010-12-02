<h1>
<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une équipe pluridisciplinaire';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une équipe pluridisciplinaire';
	}
?>
</h1>

<?php

	echo $xform->create( null, array( 'id' => 'EpAddEditForm' ) );
	
	if (isset($this->data['Ep']['id']))
		echo $form->input('Ep.id', array('type'=>'hidden'));

	echo $default->subform(
		array(
			'Ep.name' => array('required' => true),
			'Ep.regroupementep_id' => array('required' => true),
		),
		array(
			'options' => $options
		)
	);

	echo $xhtml->tag(
		'fieldset',
		$xhtml->tag(
			'legend',
			'Thématique 93'
		).
		$default->subform(
			array(
				'Ep.saisineepreorientsr93' => array( 'required' => true ),
			),
			array(
				'options' => $options
			)
		)
	);
	
	echo $xhtml->tag(
		'fieldset',
		$xhtml->tag(
			'legend',
			'Thématique 66'
		).
		$default->subform(
			array(
				'Ep.saisineepbilanparcours66' => array( 'required' => true ),
				'Ep.saisineepdpdo66' => array( 'required' => true ),
			),
			array(
				'options' => $options
			)
		),
		array(
			'label'=>'Thématique 66'
		)
	);

	echo $default->subform(
		array(
			'Zonegeographique.Zonegeographique' => array( 'required' => true, 'multiple' => 'checkbox', 'empty' => false, 'domain' => 'ep' )
		),
		array(
			'options' => $options
		)
	);

	echo $xform->end( __( 'Save', true ) );

    echo $default->button(
        'back',
        array(
            'controller' => 'eps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
