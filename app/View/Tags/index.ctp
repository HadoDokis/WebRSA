<?php
	echo $this->Default3->titleForLayout($infos);
	
	echo $this->element( 'ancien_dossier' );
	
	$perm = $this->Permissions->permList(array( 'add', 'edit', 'delete', 'cancel' ), $dossierMenu);

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
			'Tag.etat',
			'Tag.commentaire',
			'Tag.limite',
			'Tag.created',
			'/tags/edit/#Tag.id#' => array( 'disabled' => !$perm['edit'] ),
			'/tags/cancel/#Tag.id#' => array( 'disabled' => "((".(!$perm['cancel'] ? 'TRUE' : 'FALSE').") OR ('#Tag.etat#' === 'annule'))" ),
			'/tags/delete/#Tag.id#' => array( 'disabled' => !$perm['delete'] ),
		), 
		array(
			'options' => $options,
			'paginate' => false
		)
	);	
?>
