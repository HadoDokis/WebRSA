<?php
	echo $this->Default3->titleForLayout();

	echo $this->Default3->actions(
		array(
			"/Thematiquesrdvs/add" => array(
				'disabled' => !$this->Permissions->check( 'Thematiquesrdvs', 'add' )
			),
		)
	);

	echo $this->Default3->index(
		$thematiquesrdvs,
		array(
			'Thematiquerdv.id',
			'/Thematiquesrdvs/edit/#Thematiquerdv.id#' => array(
				'disabled' => !$this->Permissions->check( 'Thematiquesrdvs', 'delete' )
			),
			'/Thematiquesrdvs/delete/#Thematiquerdv.id#' => array(
				'disabled' => !$this->Permissions->check( 'Thematiquesrdvs', 'delete' )
			),
		)
	);
?>