<?php
	class Decisionsaisinebilanparcoursep66 extends AppModel
	{
		public $name = 'Decisionsaisinebilanparcoursep66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'etape',
					'decision'
				)
			)
		);

		public $belongsTo = array(
			'Saisinebilanparcoursep66' => array(
				'className' => 'Saisinebilanparcoursep66',
				'foreignKey' => 'saisinebilanparcoursep66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		// TODO: lorsqu'on pourra reporter les dossiers,
		// il faudra soit faire soit un report, soit les validations ci-dessous
		public $validate = array(
			'typeorient_id' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			),
			'structurereferente_id' => array(
				array(
					'rule' => array( 'notEmpty' )
				)
			),
		);
	}
?>
