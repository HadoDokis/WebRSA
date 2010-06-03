
<h1><?php echo $title_for_layout;?></h1>


<?php	echo $default->view(
		$user,
		array(
			'User.id',
			'User.username',
			'User.password',
			'User.email',
			'Group.name',
			'User.created',
			'User.modified',
			)
	);
?>