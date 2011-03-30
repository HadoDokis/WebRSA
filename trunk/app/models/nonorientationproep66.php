<?php
    App::import( 'Model', array( 'Nonorientationproep' ) );

	class Nonorientationproep66 extends Nonorientationproep {

		public $useTable = 'nonorientationsproseps66';

		public $hasMany = array(
			'Decisionnonorientationproep66' => array(
				'className' => 'Decisionnonorientationproep66',
				'foreignKey' => 'nonorientationproep66_id',
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