<h1><?php echo $this->pageTitle = 'Gestion pour passage en EP par objet et type de RDV';?></h1>

<?php

		echo $default2->index(
			$statutsrdvs_typesrdv,
			array(
				'Typerdv.libelle',
				'Statutrdv.libelle',
				'StatutrdvTyperdv.nbabsenceavantpassageep',
				'StatutrdvTyperdv.motifpassageep'
			),
			array(
				'actions' => array(
					'StatutsrdvsTypesrdv::edit',
					'StatutsrdvsTypesrdv::delete'
				),
				'add' => array( 'StatutrdvTyperdv.add' ),
				'options' => $options
			)
		);

	echo $default->button(
		'back',
		array(
			'controller' => 'gestionsrdvs',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);

?>
