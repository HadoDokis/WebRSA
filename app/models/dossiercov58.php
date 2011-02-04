<?php
	class Dossiercov58 extends AppModel
	{
		public $name = 'Dossiercov58';

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'etapecov'
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
			'Themecov58' => array(
				'className' => 'Themecov58',
				'foreignKey' => 'themecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Cov58' => array(
				'className' => 'Cov58',
				'foreignKey' => 'cov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Propoorientationcov58' => array(
				'className' => 'Propoorientationcov58',
				'foreignKey' => 'dossiercov58_id',
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
		);
		
		public function prepareFormData( $cov58_id, $dossiers ) {
			return $dossiers;
		}
		
	}
?>