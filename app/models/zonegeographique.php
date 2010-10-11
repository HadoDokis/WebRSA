<?php
	class Zonegeographique extends AppModel
	{
		public $name = 'Zonegeographique';

		public $displayField = 'libelle';

		public $order = array( 'libelle ASC' );

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà présente'
				)
			),
			'codeinsee' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà présente'
				)
			)
		);

		public $hasMany = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'zonegeographique_id',
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


		public $hasAndBelongsToMany = array(
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'joinTable' => 'structuresreferentes_zonesgeographiques',
				'foreignKey' => 'zonegeographique_id',
				'associationForeignKey' => 'structurereferente_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'StructurereferenteZonegeographique'
			),
			'User' => array(
				'className' => 'User',
				'joinTable' => 'users_zonesgeographiques',
				'foreignKey' => 'zonegeographique_id',
				'associationForeignKey' => 'user_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'UserZonegeographique'
			),
			'Regroupementzonegeo' => array(
				'className' => 'Regroupementzonegeo',
				'joinTable' => 'regroupementszonesgeo_zonesgeographiques',
				'foreignKey' => 'zonegeographique_id',
				'associationForeignKey' => 'regroupementzonegeo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'RegroupementzonegeoZonegeographique'
			)
		);


		/**
		*
		*/

		public function listeCodesInseeLocalites( $codesFiltres = array(), $filtre_zone_geo = true ){
			$conditions = array();

			if( $filtre_zone_geo == true ) {
				if( !empty( $codesFiltres ) ) {
					$conditions['Zonegeographique.codeinsee'] = $codesFiltres;
				}
				else {
					$conditions['Zonegeographique.codeinsee'] = null;
				}
			}

			$codes = $this->find(
				'all',
				array(
					'fields' => array( 'DISTINCT Zonegeographique.codeinsee', 'Zonegeographique.libelle' ),
					'conditions' => $conditions,
					'recursive' => -1,
					'order' => 'Zonegeographique.codeinsee'
				)
			);

			if( !empty( $codes ) ) {
				$ids = Set::extract( $codes, '/Zonegeographique/codeinsee' );
				$values = Set::format( $codes, '{0} {1}', array( '{n}.Zonegeographique.codeinsee', '{n}.Zonegeographique.libelle' ) );
				return array_combine( $ids, $values );
			}
			else {
				return $codes;
			}
		}
	}
?>