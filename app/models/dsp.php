<?php
	// FIXME: possible de faire plus "proprement" qu'avec des define ?
	define( 'ANNOBTNIVDIPMAX_MIN_YEAR', ( date( 'Y' ) - 100 ) );
	define( 'ANNOBTNIVDIPMAX_MAX_YEAR', date( 'Y' ) );
	define( 'ANNOBTNIVDIPMAX_MESSAGE', 'Veuillez entrer une année comprise entre '.ANNOBTNIVDIPMAX_MIN_YEAR.' et '.ANNOBTNIVDIPMAX_MAX_YEAR.' .' );

	class Dsp extends AppModel
	{
		public $name = 'Dsp';

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Detaildifsoc' => array(
				'className' => 'Detaildifsoc',
				'foreignKey' => 'dsp_id',
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
			'Detailaccosocfam' => array(
				'className' => 'Detailaccosocfam',
				'foreignKey' => 'dsp_id',
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
			'Detailaccosocindi' => array(
				'className' => 'Detailaccosocindi',
				'foreignKey' => 'dsp_id',
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
			'Detaildifdisp' => array(
				'className' => 'Detaildifdisp',
				'foreignKey' => 'dsp_id',
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
			'Detailnatmob' => array(
				'className' => 'Detailnatmob',
				'foreignKey' => 'dsp_id',
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
			'Detaildiflog' => array(
				'className' => 'Detaildiflog',
				'foreignKey' => 'dsp_id',
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
			'Detailmoytrans' => array(
				'className' => 'Detailmoytrans',
				'foreignKey' => 'dsp_id',
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
			'Detaildifsocpro' => array(
				'className' => 'Detaildifsocpro',
				'foreignKey' => 'dsp_id',
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
			'Detailprojpro' => array(
				'className' => 'Detailprojpro',
				'foreignKey' => 'dsp_id',
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
			'Detailfreinform' => array(
				'className' => 'Detailfreinform',
				'foreignKey' => 'dsp_id',
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
			'Detailconfort' => array(
				'className' => 'Detailconfort',
				'foreignKey' => 'dsp_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)/*,
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
			)*/
		);

		public $validate = array(
			'annobtnivdipmax' => array(
				'rule' => array( 'inclusiveRange', ANNOBTNIVDIPMAX_MIN_YEAR, ANNOBTNIVDIPMAX_MAX_YEAR ),
				'message' => ANNOBTNIVDIPMAX_MESSAGE,
				'allowEmpty' => true
			),
			'personne_id' => array( // FIXME: Autovalidate ne le fait pas ? -> contratsinsertion/edit/10630
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'sitpersdemrsa' => array(
						'values' => array( '0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109' )
					),
					'nivetu' => array(
						array( '1201', '1202', '1203', '1204', '1205', '1206', '1207' )
					),
					'nivdipmaxobt' => array(
						'values' => array( '2601', '2602', '2603', '2604', '2605', '2606' )
					),
					'hispro' => array(
						'values' => array( '1901', '1902', '1903', '1904' )
					),
					'cessderact' => array(
						'values' => array( '2701', '2702' )
					),
					'duractdomi' => array(
						'values' => array( '2104', '2105', '2106', '2107' )
					),
					'inscdememploi' => array(
						'values' => array( '4301', '4302', '4303', '4304'  )
					),
					'accoemploi' => array(
						'values' => array( '1801', '1802', '1803'  )
					),
					'natlog' => array(
						'values' => array( '0901', '0902', '0903', '0904', '0905', '0906', '0907', '0908', '0909', '0910', '0911', '0912', '0913' )
					),
					'demarlog' => array(
						'values' => array( '1101', '1102', '1103' )
					),
					'topisogroouenf' => array( 'type' => 'booleannumber', 'domain' => 'default', ),
					'topdrorsarmiant' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcouvsoc' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topqualipro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcompeextrapro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topengdemarechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topdomideract' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topisogrorechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topprojpro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcreareprientre' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topmoyloco' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'toppermicondub' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topautrpermicondu' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'accosocfam' => array( 'type' => 'nov', 'domain' => 'default' ),
					'accosocindi' => array( 'type' => 'nov', 'domain' => 'default' ),
					'soutdemarsoc' => array( 'type' => 'nov', 'domain' => 'default' ),
					'concoformqualiemploi' => array( 'type' => 'nos', 'domain' => 'default' ),
					'drorsarmianta2' => array( 'type' => 'nos', 'domain' => 'default' ),
					'statutoccupation' => array( 'values' => array('proprietaire', 'locataire') ),
					'suivimedical'
				)
			),
			'Revision' => array('auto' => false)
		);

		/*
		* FIXME: le Set::remove est plus propre, non ?
		*/

		public function filterOptions( $cg, $options ) {
			if( $cg == 'cg58' ) {
				$valuesDeleted = array( '0502', '0503', '0504' );
				foreach( $valuesDeleted as $valueDeleted ){
					unset( $options['Detaildifdisp']['difdisp'][$valueDeleted] );
				}
				return $options;
			}

			// Detaildifdisp.difdisp
			$values = array( '0507', '0508', '0509', '0510', '0511', '0512', '0513', '0514' );
			foreach( $values as $value ) {
				//$options = Set::remove( $options['Detaildifdisp']['difdisp'], $value );
				unset( $options['Detaildifdisp']['difdisp'][$value] );
			}

			return $options;
		}
	}
?>
