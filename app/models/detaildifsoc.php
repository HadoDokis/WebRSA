<?php
	class Detaildifsoc extends AppModel
	{
		public $name = 'Detaildifsoc';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'difsoc' => array(
						'type' => 'difsoc', 'domain' => 'dsp'
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