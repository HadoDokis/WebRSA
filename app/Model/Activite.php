<?php
	/**
	 * Code source de la classe Activite.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Activite ...
	 *
	 * @package app.Model
	 */
	class Activite extends AppModel
	{
		public $name = 'Activite';

		protected $_modules = array( 'caf' );

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Option' );

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'paysact' => array(
				'inList' => array(
					'rule' => array('inList', array('FRA', 'LUX', 'CEE', 'ACE', 'CNV', 'AUT'))
				)
			)
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

		/**
		 * Surcharge de la méthode enums pour obtenir les valeurs possibles
		 * du champ act.
		 *
		 * @return array
		 */
		public function enums() {
			$enums = parent::enums();

			$enums[$this->alias]['act'] = $this->Option->act();

			return $enums;
		}
	}
?>