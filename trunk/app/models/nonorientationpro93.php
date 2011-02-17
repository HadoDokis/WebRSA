<?php
    App::import( 'Model', array( 'Nonorientationpro' ) );

	class Nonorientationpro93 extends Nonorientationpro {

		public $hasMany = array(
			'Decisionnonorientationpro93' => array(
				'className' => 'Decisionnonorientationpro93',
				'foreignKey' => 'nonorientationpro93_id',
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