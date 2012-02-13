<?php
	class Detailfreinform extends AppModel
	{
		public $name = 'Detailfreinform';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'freinform' => array(
						'type' => 'freinform', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false)
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