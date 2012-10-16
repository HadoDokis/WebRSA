<?php
	class Themecov58 extends AppModel
	{
		public $name = 'Themecov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Enumerable' => array(
				'fields' => array(
					'propoorientationcov58',
					'propocontratinsertioncov58',
					'propononorientationprocov58',
					'propoorientsocialecov58',
				)
			)
		);

		public $hasMany = array(
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'themecov58_id',
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

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
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
