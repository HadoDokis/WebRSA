<?php
	class Pdf extends AppModel
	{
		public $name = 'Pdf';

		public $validate = array(
			'modele' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'modeledoc' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'fk_value' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);
	}
?>