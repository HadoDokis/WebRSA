<?php
	class Detaildifdisp extends AppModel
	{
		public $name = 'Detaildifdisp';

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
					'difdisp' => array(
						'type' => 'difdisp', 'domain' => 'dsp'
					),
				)
			),
		);
	}
?>