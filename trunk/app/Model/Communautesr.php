<?php
	/**
	 * Code source de la classe Communautesr.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe Communautesr ...
	 *
	 * @package app.Model
	 */
	class Communautesr extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Communautesr';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Occurences',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable'
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'communautesr_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
		);

		/**
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'joinTable' => 'communautessrs_structuresreferentes',
				'foreignKey' => 'communautesr_id',
				'associationForeignKey' => 'structurereferente_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'CommunautesrStructurereferente'
			),
		);

		/**
		 * Permet de vérifier que des cases à cocher multiples (ou select
		 * multiples) comprennent bien au moins une valeur.
		 *
		 * @param mixed $check Les données à vérifier
		 * @param string $modelName Le nom du modèle (HABTM) concerné par les
		 *	cases à cocher
		 * @return boolean
		 */
		public function checkMultipleSelect( $check, $modelName ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$path = "{$modelName}.{$modelName}";

			if( Hash::check( $check, $path ) ) {
				$checked = (array)Hash::get( $check, $path );
				$checked = Hash::filter( $checked );

				return !empty( $checked );
			}

			return true;
		}
	}
?>