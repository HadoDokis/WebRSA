<?php
	class Detaildiflog extends AppModel
	{
		public $name = 'Detaildiflog';

		public $belongsTo = array(
			'Dsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'dsp_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'diflog' => array(
						'type' => 'diflog', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
// 			'Autovalidate'
		);
	}
?>
