<?php

	class FrenchfloatFixture extends CakeTestFixture {
		var $name = 'Frenchfloat';
		var $table = 'frenchfloats';
		var $fields = array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'frenchfloat' => array('type' => 'float' )
		);
	
		var $records = array(
			array (
				'id' => 1,
				'frenchfloat' => '12'
			)
		);
	}
?> 
