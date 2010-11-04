<?php
	class PartitemFixture extends CakeTestFixture {
		var $name = 'Partitem';

		var $table = 'partitems';

		var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
			'nbpart' => array('type' => 'integer', 'null' => false),
			'name' => array('type' => 'string', 'null' => false),
			'item_id' => array('type' => 'integer', 'null' => false, 'length' => 11),
			'indexes' => array('PRIMARY' => array('unique' => true, 'column' => 'id'))
		);
	
		var $records = array(
			array(
				'id' => 1,
				'nbpart' => 1,
				'name' => 'nom de la partie 1',
				'item_id' => 1
			),
			array(
				'id' => 2,
				'nbpart' => 1,
				'name' => 'nom de la partie 2',
				'item_id' => 2
			),
			array(
				'id' => 3,
				'nbpart' => 2,
				'name' => 'nom de la partie 3',
				'item_id' => 2
			),
			array(
				'id' => 4,
				'nbpart' => 1,
				'name' => 'nom de la partie 4',
				'item_id' => 3
			),
			array(
				'id' => 5,
				'nbpart' => 2,
				'name' => 'nom de la partie 5',
				'item_id' => 3
			),
			array(
				'id' => 6,
				'nbpart' => 3,
				'name' => 'nom de la partie 6',
				'item_id' => 3
			),
		);
	}
?> 
