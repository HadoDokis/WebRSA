<?php
	echo $this->Default3->titleForLayout();

	echo $this->Default3->actions(
		array(
			"/Sortiesemploisd2pdvs93/add" => array(
				'disabled' => !$this->Permissions->check( 'Sortiesemploisd2pdvs93', 'add' )
			),
		)
	);

	echo $this->Default3->index(
		$sortiesemploisd2pdvs93,
		array(
			'Sortieemploid2pdv93.name',
			'/Sortiesemploisd2pdvs93/edit/#Sortieemploid2pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Sortiesemploisd2pdvs93', 'edit' )
			),
			'/Sortiesemploisd2pdvs93/delete/#Sortieemploid2pdv93.id#' => array(
				'disabled' => !$this->Permissions->check( 'Sortiesemploisd2pdvs93', 'delete' ),
				'confirm' => true
			),
		)
	);

	echo $this->Default3->actions(
		array(
			"/Parametrages/modulefse93" => array(
				'domain' => 'sortiesemploisd2pdvs93',
				'class' => 'back',
				'disabled' => !$this->Permissions->check( 'parametrages', 'modulefse93' )
			),
		)
	);
?>