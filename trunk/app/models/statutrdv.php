<?php
	class Statutrdv extends AppModel
	{
		public $name = 'Statutrdv';

		public $displayField = 'libelle';

		public $order = 'Statutrdv.id ASC';

		public $validate = array(
			'libelle' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
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
