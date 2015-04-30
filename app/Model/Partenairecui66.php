<?php
	/**
	 * Fichier source de la classe Partenairecui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Partenairecui66 est la classe contenant les partenaires (entreprises/mairie...) du CUI.
	 *
	 * @package app.Model
	 */
	class Partenairecui66 extends AppModel
	{
		public $name = 'Partenairecui66';
		
		public $recursive = -1;
		
        public $belongsTo = array(
			'Partenairecui' => array(
				'className' => 'Partenairecui',
				'foreignKey' => 'partenairecui_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
        );
		
		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Formattable',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);
	}
?>