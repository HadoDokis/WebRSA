<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) ); ?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		echo $default->form(
			array(
				'Demandereorient.personne_id' => array( 'type' => 'hidden' ),
				'Demandereorient.orientstruct_id' => array( 'type' => 'hidden' ),
				'Demandereorient.motifdemreorient_id',
				'Demandereorient.urgent',
				'Demandereorient.passageep',
				'Demandereorient.vx_typeorient_id' => array( 'type' => 'hidden' ),
				'Demandereorient.vx_structure_id' => array( 'type' => 'hidden' ),
				'Demandereorient.vx_referent_id' => array( 'type' => 'hidden' ),
				'Demandereorient.nv_typeorient_id',
				'Demandereorient.nv_structure_id',
				'Demandereorient.nv_referent_id',
				'Demandereorient.datepremierentretien',
				'Demandereorient.accordconcertation',
				'Demandereorient.dateconcertation',
				'Demandereorient.dateecheance',
				'Demandereorient.motivation',
			),
			array(
				'options' => $options
			)
		);
	?>
</div>