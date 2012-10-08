<?php
	class Coderomesecteurdsp66 extends AppModel
	{
		public $name = 'Coderomesecteurdsp66';

		public $displayField = 'intitule';

		public $actsAs = array(
			'Validation.Autovalidate'
		);

		public $hasMany = array(
			'Coderomemetierdsp66' => array(
				'className' => 'Coderomemetierdsp66',
				'foreignKey' => 'coderomesecteurdsp66_id',
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
			'Libsecactderact66SecteurDsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'libsecactderact66_metier_id',
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
			'Libsecactderact66SecteurDspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'libsecactderact66_metier_id',
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
			'Libsecactdomi66SecteurDsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'libsecactdomi66_metier_id',
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
			'Libsecactdomi66SecteurDspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'libsecactdomi66_metier_id',
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
			'Libsecactrech66SecteurDsp' => array(
				'className' => 'Dsp',
				'foreignKey' => 'libsecactrech66_metier_id',
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
			'Libsecactrech66SecteurDspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => 'libsecactrech66_metier_id',
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

		public $virtualFields = array(
			'intitule' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."code" || \'. \' || "%s"."name" )'
			),
		);
	}
?>