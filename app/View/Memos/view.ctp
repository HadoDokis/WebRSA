<?php
	echo $this->Default3->titleForLayout( $memo );

	echo $this->Default3->view(
		$memo,
		array(
			'Personne.nom_complet',
			'Dossier.matricule',
			'Memo.name',
			'Memo.created',
			'Memo.modified',
		)/*,
		array(
			'options' => $options
		)*/
	);

	echo $this->DefaultDefault->actions(
		$this->Default3->DefaultAction->back()
	);
?>