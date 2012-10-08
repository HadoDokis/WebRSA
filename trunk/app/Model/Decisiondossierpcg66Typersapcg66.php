<?php
	class Decisiondossierpcg66Typersapcg66 extends AppModel
	{
		public $name = 'Decisiondossierpcg66Typersapcg66';

		public $belongsTo = array(
			'Decisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'foreignKey' => 'decisiondossierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typersapcg66' => array(
				'className' => 'Typersapcg66',
				'foreignKey' => 'typersapcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>