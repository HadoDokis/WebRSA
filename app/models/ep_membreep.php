<?php
	class EpMembreep extends AppModel
	{
		public $name = 'EpMembreep';
		
		public $actsAs = array( 'Autovalidate' );

		public $belongsTo = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'membreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
