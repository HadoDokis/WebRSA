<?php
	class DspRev extends AppModel
	{
		public $name = 'DspRev';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe'
				)
			)
		);

		public $hasMany = array(
			'DetaildifsocRev' => array(
				'className' => 'DetaildifsocRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailaccosocfamRev' => array(
				'className' => 'DetailaccosocfamRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailaccosocindiRev' => array(
				'className' => 'DetailaccosocindiRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetaildifdispRev' => array(
				'className' => 'DetaildifdispRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailnatmobRev' => array(
				'className' => 'DetailnatmobRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetaildiflogRev' => array(
				'className' => 'DetaildiflogRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailmoytransRev' => array(
				'className' => 'DetailmoytransRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetaildifsocproRev' => array(
				'className' => 'DetaildifsocproRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailprojproRev' => array(
				'className' => 'DetailprojproRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailfreinformRev' => array(
				'className' => 'DetailfreinformRev',
				'foreignKey' => 'dsp_rev_id',
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
			'DetailconfortRev' => array(
				'className' => 'DetailconfortRev',
				'foreignKey' => 'dsp_rev_id',
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
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Dsp\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
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
