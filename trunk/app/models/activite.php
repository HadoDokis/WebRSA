<?php
	class Activite extends AppModel
	{
		public $name = 'Activite';

		protected $_modules = array( 'caf' );

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Retourne une sous-requète permettant d'obtenir l'identifiant de la dernière action pour une
		 * personne donnée.
		 *
		 * @param string $personneId
		 * @return string
		 */
		public function sqDerniere( $personneId = 'Personne.id' ) {
			return $this->sq(
				array(
					'alias' => 'activites',
					'fields' => array(
						'activites.id'
					),
					'conditions' => array(
						"activites.personne_id = {$personneId}"
					),
					'order' => array( 'activites.ddact DESC' ),
					'limit' => 1
				)
			);
		}
	}
?>