<?php
	class ActioncandidatMotifsortie extends AppModel
	{
		public $name = 'ActioncandidatMotifsortie';
        
        public $actsAs = array(
            'Validation.Autovalidate',
            'Formattable'
        );

		public $validate = array(
			'actioncandidat_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'motifsortie_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			)
		);

		public $belongsTo = array(
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Motifsortie' => array(
				'className' => 'Motifsortie',
				'foreignKey' => 'motifsortie_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>