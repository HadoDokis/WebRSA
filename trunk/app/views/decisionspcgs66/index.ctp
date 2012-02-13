<h1><?php echo $this->pageTitle = 'Décisions';?></h1>

<?php
	echo $default2->index(
		$decisionspcgs66,
		array(
			'Decisionpcg66.name',
			'Decisionpcg66.nbmoisecheance',
			'Decisionpcg66.courriernotif'
		),
		array(
			'actions' => array(
				'Decisionspcgs66::edit',
				'Decisionspcgs66::delete'
			),
			'add' => array( 'Decisionpcg66.add' )
		)
	);

	echo $default->button(
		'back',
		array(
			'controller' => 'pdos',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>