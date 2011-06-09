<?php
	class Detailmoytrans extends AppModel
	{
		public $name = 'Detailmoytrans';

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
					'moytrans' => array(
						'type' => 'moytrans', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
// 			'Autovalidate'
		);
	}
?>
