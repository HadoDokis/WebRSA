<?php
	class Detailconfort extends AppModel
	{
		public $name = 'Detailconfort';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'confort' => array(
						'type' => 'confort', 'domain' => 'dsp'
					),
				)
			),
// 			'Autovalidate'
		);

		public $belongsTo = array(
			'Dsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'dsp_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);
	}
?>
