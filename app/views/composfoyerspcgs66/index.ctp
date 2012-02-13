<h1><?php echo $this->pageTitle = 'Compositions des foyers';?></h1>

<?php
	echo $default2->index(
		$composfoyerspcgs66,
		array(
			'Compofoyerpcg66.name'
		),
		array(
			'actions' => array(
				'Composfoyerspcgs66::edit',
				'Composfoyerspcgs66::delete'
			),
			'add' => array( 'Compofoyerpcg66.add' )
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