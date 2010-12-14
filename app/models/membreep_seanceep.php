<?php
	class MembreepSeanceep extends AppModel
	{
		public $name = 'MembreepSeanceep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'reponse',
					'presence',
					'suppleant' => array( 'domain' => 'default', 'type' => 'booleannumber' )
				)
			)
		);		
		
		
		public $belongsTo = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'membreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),

			'Seanceep' => array(
				'className' => 'Seanceep',
				'foreignKey' => 'seanceep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);		
	}	
?>