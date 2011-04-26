<?php
	class CommissionepMembreep extends AppModel
	{
		public $name = 'CommissionepMembreep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'reponse',
					'presence',
					'suppleant' => array( 'domain' => 'default', 'type' => 'booleannumber' )
				)
			),
			'Formattable'
		);


		public $belongsTo = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'membreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),

			'Commissionep' => array(
				'className' => 'Commissionep',
				'foreignKey' => 'commissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>