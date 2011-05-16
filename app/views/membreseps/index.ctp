<h1><?php echo $this->pageTitle = 'Liste des membres pour les équipes pluridisciplinaires';?></h1>

<?php

	if ( $compteurs['Fonctionmembreep'] == 0 ) {
		echo "<p class='error'>Merci d'ajouter au moins une fonction pour les membres avant d'ajouter un membre.</p>";
	}

	echo $default2->index(
		$membreseps,
		array(
			'Membreep.nomcomplet'=>array('type'=>'text'),
			'Fonctionmembreep.name',
			'Membreep.tel',
			'Membreep.adresse'=>array('type'=>'text'),
			'Membreep.mail',
			'Membreep.nomcompletsuppleant'=>array('type'=>'text')	
		),
		array(
			'actions' => array(
				'Membreseps::edit',
				'Membreseps::delete'
			),
			'add' => array( 'Membreep.add', 'disabled' => ( $compteurs['Fonctionmembreep'] == 0 ) ),
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
