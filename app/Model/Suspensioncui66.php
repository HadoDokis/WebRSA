<?php
	/**
	 * Code source de la classe Suspensioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Suspensioncui66 ...
	 *
	 * @package app.Model
	 */
	class Suspensioncui66 extends AppModel
	{
		public $name = 'Suspensioncui66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'decisioncui'
				)
			),
			'Formattable'
		);

		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "Cui.personne_id" ),
				'joins' => array(
					$this->join( 'Cui', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result['Cui']['personne_id'];
			}
			else {
				return null;
			}
		}
	}
?>