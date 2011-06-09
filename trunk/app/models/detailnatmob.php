<?php
	class Detailnatmob extends AppModel
	{
		public $name = 'Detailnatmob';

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
					'natmob' => array(
						'type' => 'natmob', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
// 			'Autovalidate'
		);
	}
?>
