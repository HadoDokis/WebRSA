<?php
	class Departement extends AppModel
	{
		public $name = 'Departement';

		public $validate = array(
			'numdep' => array(
				'notempty' => array(
					'rule' => array('notempty')
				),
			),
			'name' => array(
				'notempty' => array(
					'rule' => array('notempty')
				),
			),
		);
	}
?>