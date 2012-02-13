<?php
	class Typocontrat extends AppModel
	{
		public $name = 'Typocontrat';

		public $displayField = 'lib_typo';

		public $order = 'Typocontrat.id ASC';

		public $validate = array(
			'lib_typo' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			)
		);

		public $hasMany = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'typocontrat_id',
				'dependent' => false,
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