<?php	
	/**
	 * Code source de la classe Suspensiondroit.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Suspensiondroit ...
	 *
	 * @package app.Model
	 */
	class Suspensiondroit extends AppModel
	{
		public $name = 'Suspensiondroit';

		public $belongsTo = array(
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'situationdossierrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Retourne une sous-requête permettant d'avoir la dernière entrée de la table (avec un tri
		 * descendant sur ddsusdrorsa), pour une entrée de situationsdossiersrsa.
		 * Fonctionne si une entrée existe pour Situationdossierrsa ou pas.
		 *
		 * @param string $situationdossierrsaId
		 * @return string
		 */
		public function sqDerniere( $situationdossierrsaId = 'Situationdossierrsa.id' ) {
			return $this->sq(
				array(
					'alias' => 'suspensionsdroits',
					'fields' => array( "suspensionsdroits.id" ),
					'contain' => false,
					'conditions' => array(
						"suspensionsdroits.situationdossierrsa_id = {$situationdossierrsaId}"
					),
					'order' => array( "suspensionsdroits.ddsusdrorsa DESC" ),
					'limit' => 1
				)
			);
		}
	}
?>
