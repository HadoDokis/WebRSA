<?php
	class Mtpcg66Pmtcpcg66 extends AppModel
	{
		public $name = 'Mtpcg66Pmtcpcg66';

		public $belongsTo = array(
			'Mtpcg66' => array(
				'className' => 'Mtpcg66',
				'foreignKey' => 'mtpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Pmtcpcg66' => array(
				'className' => 'Pmtcpcg66',
				'foreignKey' => 'pmtcpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>