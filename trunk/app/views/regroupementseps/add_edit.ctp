<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

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
	$EpDepartement = Configure::read( 'Cg.departement' );
	if( empty( $EpDepartement ) || !in_array( $EpDepartement, array( 58, 66, 93 ) ) ) {
		echo $xhtml->tag( 'p', 'Veuillez contacter votre adminitrateur afin qu\'il ajoute le paramètre de configuration Cg.departement dans le fichier webrsa.inc', array( 'class' => 'error' ) );
	}

	echo $xform->create( null );
	if( $this->action == 'edit' ) {
		echo $xform->input( 'Regroupementep.id', array( 'type' => 'hidden' ) );
	}
	echo $xform->input( 'Regroupementep.name', array( 'domain' => 'regroupementep' ) );

	// Le CG 93 ne souhaite pas voir ces choix: pour eux, tout se décide
	// au niveau cg, et toutes les eps traitent potentiellement de tous
	// les thèmes
	if( Configure::read( 'Cg.departement' ) == 93 ) {
		echo $default->subform(
			array(
				'Regroupementep.reorientationep93' => array( 'type' => 'hidden', 'value' => 'decisioncg' ),
				'Regroupementep.nonrespectsanctionep93' => array( 'type' => 'hidden', 'value' => 'decisioncg' ),
				'Regroupementep.radiepoleemploiep93' => array( 'type' => 'hidden', 'value' => 'decisioncg' ),
				'Regroupementep.nonorientationproep93' => array( 'type' => 'hidden', 'value' => 'decisioncg' ),
			)
		);
	}
	// On laisse la possibilité de choisir comme avant pour le CG 58
	elseif( Configure::read( 'Cg.departement' ) == 58 ) {
		echo $default->subform(
			array(
				'Regroupementep.nonorientationproep58' => array( 'type' => 'hidden', 'value' => 'decisionep' ),
				'Regroupementep.regressionorientationep58' => array( 'type' => 'hidden', 'value' => 'decisionep' ),
				'Regroupementep.sanctionep58' => array( 'type' => 'hidden', 'value' => 'decisionep' ),
				'Regroupementep.sanctionrendezvousep58' => array( 'type' => 'hidden', 'value' => 'decisionep' ),
			)
		);
	}
	// Le choix est également possible pour le CG 66
	elseif( Configure::read( 'Cg.departement' ) == 66 ) {
		echo $xhtml->tag(
			'fieldset',
			$xhtml->tag(
				'legend',
				'Thématiques 66'
			).
			$default->subform(
				array(
					'Regroupementep.saisinebilanparcoursep66' => array( 'required' => true ),
					'Regroupementep.saisinepdoep66' => array( 'required' => true ),
					'Regroupementep.defautinsertionep66' => array( 'required' => true ),
				),
				array(
					'options' => $options
				)
			),
			array(
				'label'=>'Thématiques 66'
			)
		);
	}

	echo $xform->end( __( 'Save', true ) );

// 	echo $default->form(
// 		array(
// 			'Regroupementep.name'
// 		),
// 		array(
// 			'id' => 'RegroupementepAddEditForm',
// 			'options' => $options
// 		)
// 	);

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