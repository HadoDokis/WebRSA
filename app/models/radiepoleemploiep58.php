<?php
	App::import( 'Model', 'Radiepoleemploiep' );
	
	class Radiepoleemploiep58 extends Radiepoleemploiep
	{
		public $name = 'Radiepoleemploiep58';
		
		public $decisionName = 'Decisionradiepoleemploiep58';

		public $hasMany = array(
			'Decisionradiepoleemploiep58' => array(
				'className' => 'Decisionradiepoleemploiep58',
				'foreignKey' => 'radiepoleemploiep58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);
	}
?>