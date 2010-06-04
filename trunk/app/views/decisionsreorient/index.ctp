<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->search(
		array(
			'Decisionreorient.demandereorient_seanceep_id',
			'Decisionreorient.etape',
			'Decisionreorient.decision',
			'Decisionreorient.commentaire',
			'Decisionreorient.nv_typeorient_id',
			'Decisionreorient.nv_structurereferente_id',
			'Decisionreorient.nv_referent_id',
			'Decisionreorient.created',
			'Decisionreorient.modified',
		)
	);

	echo $default->index(
		$demandesreorient,
		array(
			'Decisionreorient.id',
			'Decisionreorient.demandereorient_seanceep_id',
			'Decisionreorient.etape',
			'Decisionreorient.decision',
			'Decisionreorient.commentaire',
			'Decisionreorient.nv_typeorient_id',
			'Decisionreorient.nv_structurereferente_id',
			'Decisionreorient.nv_referent_id',
			'Decisionreorient.created',
			'Decisionreorient.modified',
		),
		array(
			'add' => array(
				'Demandereorient.add'
			),
			'actions' => array(
				'Demandereorient.view',
				'Demandereorient.edit',
				'Demandereorient.delete'
			)
		)
	);
?>