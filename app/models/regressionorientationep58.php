<?php
    App::import( 'Model', array( 'Regressionorientationep' ) );

	class Regressionorientationep58 extends Regressionorientationep
	{
		public $name = 'Regressionorientationep58';

		public $hasMany = array(
			'Decisionregressionorientationep58' => array(
				'className' => 'Decisionregressionorientationep58',
				'foreignKey' => 'regressionorientationep58_id',
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