<?php
	/**
	 * Code source de la classe Pieceactprof.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Pieceactprof ...
	 *
	 * @package app.Model
	 */
	class Pieceactprof extends AppModel
	{
		public $name = 'Pieceactprof';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = 1;

		public $displayField = 'libelle';

		public $order = array( 'Pieceactprof.libelle ASC' );

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
			),
		);

		public $hasAndBelongsToMany = array(
			'Actprof' => array(
				'className' => 'Actprof',
				'joinTable' => 'actsprofs_piecesactsprofs',
				'foreignKey' => 'pieceactprof_id',
				'associationForeignKey' => 'actprof_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActprofPieceactprof'
			)
		);
	}
?>
