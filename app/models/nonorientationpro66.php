<?php
    App::import( 'Model', array( 'Nonorientationpro' ) );

	class Nonorientationpro66 extends Nonorientationpro {

		public $useTable = 'nonorientationspros66';

		public $hasMany = array(
			'Decisionnonorientationpro66' => array(
				'className' => 'Decisionnonorientationpro66',
				'foreignKey' => 'nonorientationpro66_id',
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