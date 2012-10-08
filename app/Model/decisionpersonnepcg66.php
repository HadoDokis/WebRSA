<?php
	class Decisionpersonnepcg66 extends AppModel
	{
		public $name = 'Decisionpersonnepcg66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Personnepcg66Situationpdo' => array(
				'className' => 'Personnepcg66Situationpdo',
				'foreignKey' => 'personnepcg66_situationpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasAndBelongsToMany = array(
			'Decisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'joinTable' => 'decisionsdossierspcgs66_decisionspersonnespcgs66',
				'foreignKey' => 'decisionpersonnepcg66_id',
				'associationForeignKey' => 'decisiondossierpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Decisiondossierpcg66Decisionpersonnepcg66'
			)
		);
		/**
		*	Récupération de la liste des situations liées à la personne
		*/

		public function listeDecisionsParPersonnepcg66( $personnepcg66_id ) {

			$personnepcg66situationpdo = $this->Personnepcg66Situationpdo->find(
				'all',
				array(
					'conditions' => array(
						'Personnepcg66Situationpdo.personnepcg66_id' => $personnepcg66_id
					),
					'contain' => false
				)
			);
			$personnepcg66situationpdo_id = array();
			foreach( $personnepcg66situationpdo as $i => $value ){
				$personnepcg66situationpdo_id[] = $value['Personnepcg66Situationpdo']['id'];
			}

			$listeDecisions = $this->find(
				'all',
				array(
					'conditions' => array(
						'Decisionpersonnepcg66.personnepcg66_situationpdo_id IN (
                            '.$this->Personnepcg66Situationpdo->sq(
                                array(
									'alias' => 'personnespcgs66_situationspdos',
                                    'fields' => array( 'personnespcgs66_situationspdos.id' ),
                                    'conditions' => array(
										'personnespcgs66_situationspdos.id' => $personnepcg66situationpdo_id
									),
									'contain' => false
								)
							).' )',
						),
						'contain' => array(
							'Personnepcg66Situationpdo' => array(
								'Situationpdo',
								'Personnepcg66'
							),
							'Decisionpdo'
						)
					)
				);

			return $listeDecisions;
		}
	}
?>