<h1><?php echo $this->pageTitle = 'Liste des équipes pluridisciplinaires';?></h1>

<?php

	if ( $compteurs['Regroupementep'] == 0 ) {
		echo "<p class='error'>Merci d'ajouter au moins un regroupement avant d'ajouter une EP.</p>";
	}

	echo $default2->index(
		$eps,
		array(
			'Ep.identifiant',
			'Regroupementep.name',
			'Ep.name'
		),
		array(
			'actions' => array(
				'Eps::edit',
				'Eps::delete'
			),
			'add' => array( 'Ep.add', 'disabled' => ( $compteurs['Regroupementep'] == 0 ) ),
			'options' => $options
		)
	);

// 	echo $default->button(
// 		'back',
// 		array(
// 			'controller' => 'gestionseps',
// 			'action'     => 'index'
// 		),
// 		array(
// 			'id' => 'Back'
// 		)
// 	);
?>
