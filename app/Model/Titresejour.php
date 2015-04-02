<?php	
	/**
	 * Code source de la classe Titresejour.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Titresejour ...
	 *
	 * @package app.Model
	 */
	class Titresejour extends AppModel
	{
		public $name = 'Titresejour';

		protected $_modules = array( 'caf' );

		public $validate = array(
			'personne_id' => array('numeric')
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
		 * Permet de récupérer le dernier titre de séjour d'une personne, en
		 * fonction de la date de début de titre de séjour.
		 *
		 * @param string $personneIdFied
		 * @return string
		 */
		public function sqDernier( $personneIdFied = 'Personne.id' ) {
			$query = array(
				'alias' => 'titressejour',
				'fields' => array( 'titressejour.id' ),
				'conditions' => array(
					"titressejour.personne_id = {$personneIdFied}"
				),
				'contain' => false,
				'order' => array( 'titressejour.ddtitsej DESC' ),
				'limit' => 1
			);

			return $this->sq( $query );
		}
	}
?>