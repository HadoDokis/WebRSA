<?php
	class Paiementfoyer extends AppModel
	{
		public $name = 'Paiementfoyer';

		public $validate = array(
			'foyer_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'foyer_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Retourne la sous-requête permettant d'obtenir l'id de paiementsfoyers
		 * correspondant à un allocataire donné (suivant que cette personne est
		 * demandeur ou conjoint).
		 *
		 * @param string $modelFieldPersonneId
		 * @return string
		 */
		public function sqPaiementfoyerIdPourAllocataire( $modelFieldPersonneId = 'Personne.id' ) {
			return "SELECT MAX(paiementsfoyers.id)
					FROM paiementsfoyers
					WHERE paiementsfoyers.topribconj = (
						CASE WHEN (
							SELECT prestations.rolepers
								FROM prestations
								WHERE prestations.personne_id = {$modelFieldPersonneId}
									AND prestations.natprest = 'RSA'
									AND prestations.rolepers IN ( 'DEM', 'CJT' )
						) = 'CJT' THEN true ELSE false END
					)
					GROUP BY paiementsfoyers.foyer_id";
		}
	}
?>