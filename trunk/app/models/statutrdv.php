<?php
	class Statutrdv extends AppModel
	{
		public $name = 'Statutrdv';

		public $displayField = 'libelle';

		public $order = 'Statutrdv.id ASC';

		public $validate = array(
			'libelle' => array(
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

		public $hasMany = array(
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'statutrdv_id',
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
			'Typerdv' => array(
				'className' => 'Typerdv',
				'joinTable' => 'statutsrdvs_typesrdv',
				'foreignKey' => 'statutrdv_id',
				'associationForeignKey' => 'typerdv_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'StatutrdvTyperdv'
			)
		);

		/**
		 * Retourne un booléen suivant si le statut du rdv passé en paramètre
		 * peut ou non provoquer un passage en EP
		 */
		public function provoquePassageEp( $statutrdv_id ) {
			$statutrdv = $this->find(
				'first',
				array(
					'conditions' => array(
						'Statutrdv.id' => $statutrdv_id
					),
					'contain' => false
				)
			);

			return $statutrdv['Statutrdv']['provoquepassageep'];
		}
	}
?>
