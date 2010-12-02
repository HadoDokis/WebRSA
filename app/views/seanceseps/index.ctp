<h1><?php echo $this->pageTitle = 'Liste des séances d\'EP';?></h1>

<?php
//debug($seanceseps);
	echo $default2->index(
		$seanceseps,
		array(
			'Ep.name',
			'Structurereferente.lib_struc',
			'Seanceep.dateseance',
			'Seanceep.finalisee'
		),
		array(
// 			'actions' => array(
// 				'Seanceep.edit',
// 				'Seanceep.delete',
// 				'Seanceep.choose' => array( 'controller' => 'dossierseps', 'action' => 'choose' ),
// 				'Seanceep.traiterep' => array( 'action' => 'traiterep' ),
// 				'Seanceep.finaliser' => array( 'action' => 'finaliserep' )
// 			),
			'actions' => array(
				'Seanceseps::edit' => array(
					'disabled' => '\'#Seanceep.finalisee#\' != \'\''
				),
				'Seanceseps::delete' => array(
					'disabled' => '\'#Seanceep.finalisee#\' != \'\''
				),
				'Seanceseps::choose' => array(
					'disabled' => '\'#Seanceep.finalisee#\' != \'\'',
					'url' => array( 'controller' => 'dossierseps', 'action' => 'choose', '#Seanceep.id#' )
				),
				'Seanceseps::traiterep' => array(
					'disabled' => '\'#Seanceep.finalisee#\' != \'\' || \'#Seanceep.existe_dossier#\' == false'
				),
				'Seanceseps::finaliserep' => array(
					'disabled' => '\'#Seanceep.finalisee#\' != \'\' || \'#Seanceep.existe_dossier#\' == false'
				),
				'Seanceseps::traitercg' => array(
					'disabled' => '\'#Seanceep.cloture#\' != false || \'#Seanceep.finalisee#\' != \'ep\''
				),
				'Seanceseps::finalisercg' => array(
					'disabled' => '\'#Seanceep.cloture#\' != false || \'#Seanceep.finalisee#\' != \'ep\''
				),
			),
			'add' => array( 'Seanceep.add' )
		)
	);
// debug( $seanceseps );
?>
