<?php
	class Regroupementep extends AppModel
	{
		public $name = 'Regroupementep';

		public $order = array( 'Regroupementep.name ASC' );

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					// Thèmes 66
					'saisinebilanparcoursep66',
					'saisinepdoep66',
					'defautinsertionep66',
					// Thèmes 93
					'nonrespectsanctionep93',
					'reorientationep93',
					'nonorientationproep93',
// 					'regressionorientationep93',
					'signalementep93',
					// Thèmes 58
					'nonorientationproep58',
					'regressionorientationep58',
					'sanctionep58',
					'sanctionrendezvousep58',
				)
			),
			'Formattable'
		);

		public $hasMany = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'regroupementep_id',
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

		// INFO: le behavior Autovalidate ne trouve pas les contraintes UNIQUE (17/02/2011)
		public $validate = array(
			'name' => array(
				array(
					'rule' => array( 'isUnique' ),
				)
			)
		);

		/**
		* Retourne la liste des thèmes traités par le regroupement
		*/

		public function themes() {
			$enums = $this->enums();
			foreach( array_keys( $enums[$this->alias] ) as $key ) {
				if( substr( $key, -2 ) != Configure::read( 'Cg.departement' ) ) {
					unset( $enums[$this->alias][$key] );
				}
			}
			return array_keys( $enums[$this->alias] );
		}
	}
?>