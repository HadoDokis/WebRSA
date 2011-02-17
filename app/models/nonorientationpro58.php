<?php
    App::import( 'Model', array( 'Nonorientationpro' ) );

	class Nonorientationpro58 extends Nonorientationpro {

		public $hasMany = array(
			'Decisionnonorientationpro58' => array(
				'className' => 'Decisionnonorientationpro58',
				'foreignKey' => 'nonorientationpro58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
		
	}
?>