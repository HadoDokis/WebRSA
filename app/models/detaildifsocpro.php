<?php
	class Detaildifsocpro extends AppModel
	{
		public $name = 'Detaildifsocpro';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'difsocpro' => array(
						'type' => 'difsocpro', 'domain' => 'dsp'
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
