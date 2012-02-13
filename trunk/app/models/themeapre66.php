<?php
	class Themeapre66 extends AppModel
	{
		public $name = 'Themeapre66';

		public $order = 'Themeapre66.name ASC';

		public $validate = array(
			'name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
		);

		public $hasMany = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'themeapre66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Typeaideapre66' => array(
				'className' => 'Typeaideapre66',
				'foreignKey' => 'themeapre66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
	}
?>