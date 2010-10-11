<?php
	class ActioncandidatPartenaire extends AppModel
	{
		public $name = 'ActioncandidatPartenaire';

		public $displayField = 'libstruc';

		public $actsAs = array (
			'Nullable',
			'ValidateTranslate'
		);

		public $validate = array(
			'actioncandidat_id' => array(
				array(
					'rule' => array('numeric'),
				),
				array(
					'rule' => array('notEmpty'),
				),
			),
			'partenaire_id' => array(
				array(
					'rule' => array('numeric'),
				),
				array(
					'rule' => array('notEmpty'),
				),
			),
		);

		public $belongsTo = array(
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
