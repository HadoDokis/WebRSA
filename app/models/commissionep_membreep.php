<?php
	class CommissionepMembreep extends AppModel
	{
		public $name = 'CommissionepMembreep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'reponse',
					'presence',
					'suppleant' => array( 'domain' => 'default', 'type' => 'booleannumber' )
				)
			),
			'Formattable'
		);

		public $belongsTo = array(
			'Membreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'membreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Commissionep' => array(
				'className' => 'Commissionep',
				'foreignKey' => 'commissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Remplacantmembreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'reponsesuppleant_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Remplacanteffectifmembreep' => array(
				'className' => 'Membreep',
				'foreignKey' => 'presencesuppleant_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		/**
		 * Fonction qui retourne vrai si dans les données envoyées au moins 2 membres sont
		 * remplacés par la même personne. Retourne faux dans le cas contraire.
		 */
		public function checkDoublon( $datas ) {
			$doublon = false;
			$liste = array();
			foreach( $datas as $data ) {
				if ( isset( $data['suppleant_id'] ) && !empty( $data['suppleant_id'] ) ) {
					if ( in_array( $data['suppleant_id'], $liste ) ) {
						$doublon = true;
					}
					else {
						$liste[] = $data['suppleant_id'];
					}
				}
			}
			return $doublon;
		}

	}
?>