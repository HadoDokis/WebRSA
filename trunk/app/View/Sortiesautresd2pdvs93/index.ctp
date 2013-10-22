<?php
	echo $this->Default3->titleForLayout();

	echo $this->Default3->actions(
		array(
			"/Sortiesautresd2pdvs93/add" => array(
				'disabled' => !$this->Permissions->check( 'Sortiesautresd2pdvs93', 'add' )
			),
		)
	);

	echo $this->Default3->index(
		$sortiesautresd2pdvs93,
		array(
			'Sortieautred2pdv93.name',
			'/Sortiesautresd2pdvs93/edit/#Sortieautred2pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Sortiesautresd2pdvs93', 'edit' )
			),
			'/Sortiesautresd2pdvs93/delete/#Sortieautred2pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Sortiesautresd2pdvs93', 'delete' ),
				'confirm' => true
			),
		)
	);

	echo $this->Default3->actions(
		array(
			"/Parametrages/modulefse93" => array(
				'domain' => 'sortiesautresd2pdvs93',
				'class' => 'back',
				'disabled' => !$this->Permissions->check( 'parametrages', 'modulefse93' )
			),
		)
	);
?>