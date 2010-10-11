<?php
	class ComiteapreParticipantcomite extends AppModel
	{
		public $name = 'ComiteapreParticipantcomite';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'presence' => array(
						'type' => 'presenceca',
						'domain' => 'apre'
					)
				)
			)
		);

		public $validate = array(
            'id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'comiteapre_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'participantcomite_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Comiteapre' => array(
				'className' => 'Comiteapre',
				'foreignKey' => 'comiteapre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Participantcomite' => array(
				'className' => 'Participantcomite',
				'foreignKey' => 'participantcomite_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>