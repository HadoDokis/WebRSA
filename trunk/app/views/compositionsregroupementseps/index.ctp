<h1><?php echo $this->pageTitle = 'Composition de l\'Équipe pluridisciplinaire';?></h1>

<?php

	if ( $compteurs['Regroupementep'] == 0 ) {
		echo "<p class='error'>Merci d'ajouter au moins un regroupement avant d'ajouter une EP.</p>";
	}
	if ( $compteurs['Fonctionmembreep'] == 0 ) {
		echo "<p class='error'>Merci d'ajouter au moins un membre avant d'ajouter une EP.</p>";
	}

	echo $default2->index(
		$regroupementeps,
		array(
			'Regroupementep.name'
		),
		array(
			'actions' => array(
				'Regroupementseps::edit',
				'Regroupementseps::delete'
			),
			'add' => array( 'Regroupementseps.add', 'disabled' => ( $compteurs['Regroupementep'] == 0 || $compteurs['Fonctionmembreep'] == 0 ) ),
			'options' => $options
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'gestionseps',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>