<?php
	class Situationpdo extends AppModel
	{
		public $name = 'Situationpdo';

		public $displayField = 'libelle';

		public $validate = array(
			'libelle' => array(
				array( 'rule' => 'notEmpty' )
			)
		);

		public $actsAs = array(
			'ValidateTranslate'
		);

		public $hasAndBelongsToMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'joinTable' => 'propospdos_situationspdos',
				'foreignKey' => 'situationpdo_id',
				'associationForeignKey' => 'propopdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PropopdoSituationpdo'
			),
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'joinTable' => 'personnespcgs66_situationspdos',
				'foreignKey' => 'situationpdo_id',
				'associationForeignKey' => 'personnepcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Personnepcg66Situationpdo'
			),// Test liaison avec modèletypecourrierpcg66
// 			'Modeletypecourrierpcg66' => array(
// 				'className' => 'Modeletypecourrierpcg66',
// 				'joinTable' => 'modelestypescourrierspcgs66_situationspdos',
// 				'foreignKey' => 'situationpdo_id',
// 				'associationForeignKey' => 'modeletypecourrierpcg66_id',
// 				'unique' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'finderQuery' => '',
// 				'deleteQuery' => '',
// 				'insertQuery' => '',
// 				'with' => 'Modeletypecourrierpcg66Situationpdo'
// 			)
		);

		/**
		*	Récupération de la liste des situations liées à la personne
		*/

		public function listeMotifsPersonne( $personnepcg66_id ) {
			$listeSituation = $this->find(
				'list',
				array(
					'conditions' => array(
						'Situationpdo.id IN (
							'.$this->Personnepcg66Situationpdo->sq(
								array(
									'alias' => 'personnespcgs66_situationspdos',
									'fields' => array( 'personnespcgs66_situationspdos.situationpdo_id' ),
									'conditions' => array(
										'personnespcgs66_situationspdos.personnepcg66_id' => $personnepcg66_id
									),
									'contain' => false
								)
							).' )'
						),
						'recursive' => -1
					)
				);
			return $listeSituation;
		}
	}
?>