<?php	
	/**
	 * Code source de la classe Detailcalculdroitrsa.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Detailcalculdroitrsa ...
	 *
	 * @package app.Model
	 */
	class Detailcalculdroitrsa extends AppModel
	{
		public $name = 'Detailcalculdroitrsa';

		public $validate = array(
			'detaildroitrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'detaildroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		
		/**
		 * Retourne le dernier détail du droit rsa d'un dossier RSA
		 *
		 * @param string $detaildroitrsaIdField
		 * @return string
		 */
		public function sqDernier( $detaildroitrsaIdField = 'Detaildroitrsa.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'detailscalculsdroitsrsa.id'
					),
					'alias' => 'detailscalculsdroitsrsa',
					'conditions' => array(
						"detailscalculsdroitsrsa.detaildroitrsa_id = {$detaildroitrsaIdField}"
					),
					'order' => array( 'detailscalculsdroitsrsa.ddnatdro DESC' ),
					'limit' => 1
				)
			);
		}
	}
?>