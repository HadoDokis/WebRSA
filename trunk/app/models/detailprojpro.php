<?php
	class Detailprojpro extends AppModel
	{
		public $name = 'Detailprojpro';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'projpro' => array(
						'type' => 'projpro', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
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
