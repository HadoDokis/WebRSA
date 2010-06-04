<h1><?php echo $title_for_layout;?></h1>

<?php
	echo $default->form(
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
?>