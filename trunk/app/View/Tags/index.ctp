<?php
	echo $this->Default3->titleForLayout($infos);
	
	echo $this->element( 'ancien_dossier' );
	
	$perm = $this->Permissions->permList(array( 'add', 'edit', 'delete' ), $dossierMenu);

	echo $this->Default3->actions(
		array(
			"/Tags/add/{$modele}/{$id}" => array(
				'disabled' => !$perm['add']
			),
		)
	);
	
	echo $this->Default3->index(
		$results, 
		array(
			'Categorietag.name',
			'Valeurtag.name',
			'Tag.commentaire',
			'Tag.created',
			'/tags/edit/#Tag.id#' => array( 'disabled' => !$perm['edit'] ),
			'/tags/delete/#Tag.id#' => array( 'disabled' => !$perm['delete'] ),
		), 
		array(
			'options' => $options,
			'paginate' => false
		)
	);	
?>
