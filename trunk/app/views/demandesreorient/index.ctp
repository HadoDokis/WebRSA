
<h1><?php echo $title_for_layout;?></h1>


<?php	echo $default->search(
		array(
			'User.id',
			'User.username',
			'User.password',
			'User.email',
			'User.group_id',
			'User.created',
			'User.modified',
			)
	);

	echo $default->index(
		$users,
		array(
			'User.id',
			'User.username',
			'User.password',
			'User.email',
			'Group.name',
			'User.created',
			'User.modified',
			),
		array(
			'add' => array(
				'User.add' => array( 'admin' => true )
			),
			'actions' => array(
				'User.view',
				'User.edit' => array( 'admin' => true ),
				'User.delete' => array( 'admin' => true )
			)
		)
	);
?>