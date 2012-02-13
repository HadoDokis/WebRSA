<?php
	class Detailaccosocfam extends AppModel
	{
		public $name = 'Detailaccosocfam';

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
					'nataccosocfam' => array(
						'type' => 'nataccosocfam', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false)
		);
	}
?>