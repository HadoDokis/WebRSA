<?php
	class Fraisdeplacement66 extends AppModel
	{
		public $name = 'Fraisdeplacement66';

		public $actsAs = array(
			'Autovalidate',
			'Formattable',
			'Frenchfloat' => array(
				'fields' => array(
					'nbkmvoiture',
					'nbtrajetvoiture',
					'nbtrajettranspub',
					'prixbillettranspub',
					'nbnuithebergt',
					'nbrepas',
					'totalvehicule',
					'totalhebergt',
					'totaltranspub',
					'totalrepas'
				)
			),
		);

		public $validate = array(
			'aideapre66_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
				array(
					'rule' => 'notEmpty'
				)
			),
			'destination' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'aideapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>