<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une commission d\'EP';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une commission d\'EP';
	}
?>
</h1>

<?php
	echo $form->create( 'Commissionep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

	echo $default->subform(
		array(
			'Commissionep.id' => array('type'=>'hidden'),
			'Commissionep.etatcommissionep' => array('type'=>'hidden'),
			'Commissionep.ep_id' => array( 'type' => 'select' ),
			'Commissionep.name',
			'Commissionep.dateseance' => array( 'dateFormat' => __( 'Locale->dateFormat', true ), 'maxYear' => date('Y')+1, 'minYear' => date('Y')-1,  'timeFormat' => __( 'Locale->timeFormat', true ), 'interval'=>15 ), // TODO: à mettre par défaut dans Default2Helper
			'Commissionep.lieuseance',
			'Commissionep.adresseseance',
			'Commissionep.codepostalseance',
			'Commissionep.villeseance'
		),
		array(
			'options' => $options
		)
	);
	if( Configure::read( 'Cg.departement' ) == 93 ){
		echo $default->subform(
			array(
				'Commissionep.chargesuivi',
				'Commissionep.gestionnairebat',
				'Commissionep.gestionnairebada',
			),
			array(
				'options' => $options
			)
		);
	}

	echo $default->subform(
		array(
			'Commissionep.salle',
			'Commissionep.observations' => array('type'=>'textarea')
		),
		array(
			'options' => $options
		)
	);

	echo $form->end( 'Enregistrer' );

	if( $this->action == 'edit')  {
		echo $default->button(
			'back',
			array(
				'controller' => 'commissionseps',
				'action'     => 'view',
				Set::classicExtract( $this->data, 'Commissionep.id' )
			),
			array(
				'id' => 'Back'
			)
		);
	}
?>