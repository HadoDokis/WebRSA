<?php
	class Integrationfichierapre extends AppModel
	{
		public $name = 'Integrationfichierapre';

		public $validate = array(
			'nbr_atraiter' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'nbr_succes' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'nbr_erreurs' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'fichier_in' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
		);
	}
?>