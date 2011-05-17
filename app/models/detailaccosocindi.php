<?php
	class Detailaccosocindi extends AppModel
	{
		public $name = 'Detailaccosocindi';

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
					'nataccosocindi' => array(
						'type' => 'nataccosocindi', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
			'Autovalidate'
		);
	}
?>
