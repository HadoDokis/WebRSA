<?php
	class Entretien extends AppModel
	{
		public $name = 'Entretien';

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			),
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'typeentretien'
				)
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
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
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typerdv' => array(
				'className' => 'Typerdv',
				'foreignKey' => 'typerdv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'rendezvous_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>
