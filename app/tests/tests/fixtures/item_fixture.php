<?php
	class ItemFixture extends CakeTestFixture {
		var $name = 'Item';

		var $table = 'items';

		var $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
			'firstname' => array('type' => 'string', 'null' => false),
			'lastname' => array('type' => 'string', 'null' => false),
			'name_a' => array('type' => 'string', 'null' => false),
			'name_b' => array('type' => 'string', 'null' => true, 'default' => NULL),
			'version_a' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'version_n' => array('type' => 'integer', 'null' => true),
			'description_a' => array('type' => 'text', 'null' => false, 'length' => 1073741824),
			'description_b' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
			'modifiable_a' => array('type' => 'boolean', 'null' => false, 'default' => 'true'),
			'modifiable_b' => array('type' => 'boolean', 'null' => true),
			'date_a' => array('type' => 'date', 'null' => false, 'default' => '1970-01-01'),
			'date_b' => array('type' => 'date', 'null' => true),
			'tel' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
			'fax' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
			'category_id' => array('type' => 'integer', 'null' => true),
			'foo' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1),
			'bar' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1),
			'montant' => array('type' => 'float', 'null' => true),
			'indexes' => array('PRIMARY' => array('unique' => true, 'column' => 'id'))
		);
	
		var $records = array(
			array(
				'id' => 1,
				'firstname' => 'Firstname n°1',
				'lastname' => 'Lastname n°1',
				'name_a' => 'name_a',
				'name_b' => 'name_b',
				'version_a' => 1,
				'version_n' => 1,
				'description_a' => 'description_a',
				'description_b' => 'description_b',
				'modifiable_a' => true,
				'modifiable_b' => true,
				'date_a' => '2010-03-17',
				'date_b' => '2010-03-17',
				'tel' => '0101010101',
				'fax' => '0101010101',
				'category_id' => '12',
				'foo' => 'f',
				'bar' => null,
				'montant' => '666.66'
			),
			array(
				'id' => 2,
				'firstname' => 'Firstname n°2',
				'lastname' => 'Lastname n°2',
				'name_a' => 'name_c',
				'name_b' => 'name_d',
				'version_a' => 2,
				'version_n' => 2,
				'description_a' => 'description_c',
				'description_b' => 'description_d',
				'modifiable_a' => true,
				'modifiable_b' => false,
				'date_a' => '2010-03-23',
				'date_b' => '2010-03-23',
				'tel' => '0202020202',
				'fax' => '0202020202',
				'category_id' => '45',
				'foo' => 'o',
				'bar' => null,
				'montant' => '123'
			),
			array(
				'id' => 3,
				'firstname' => 'Firstname n°3',
				'lastname' => 'Lastname n°3',
				'name_a' => 'name_e',
				'name_b' => 'name_f',
				'version_a' => 3,
				'version_n' => 3,
				'description_a' => 'description_e',
				'description_b' => 'description_f',
				'modifiable_a' => false,
				'modifiable_b' => true,
				'date_a' => '2010-03-12',
				'date_b' => '2010-03-12',
				'tel' => '0303030303',
				'fax' => '0303030303',
				'category_id' => '736',
				'foo' => 'o',
				'bar' => null,
				'montant' => '867.3'
			)
		);
	}
?> 