<?php
	class Aidedirecte extends AppModel
	{
		public $name = 'Aidedirecte';

		public $validate = array(
			'actioninsertion_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Actioninsertion' => array(
				'className' => 'Actioninsertion',
				'foreignKey' => 'actioninsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>