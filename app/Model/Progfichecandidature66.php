<?php	
	/**
	 * Code source de la classe Progfichecandidature66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Progfichecandidature66 ...
	 *
	 * @package app.Model
	 */
	class Progfichecandidature66 extends AppModel
	{
		public $name = 'Progfichecandidature66';

		public $recursive = -1;

		public $actsAs = array(
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate'
		);

		public $hasAndBelongsToMany = array(
			'ActioncandidatPersonne' => array(
				'className' => 'ActioncandidatPersonne',
				'joinTable' => 'candidatures_progs66',
				'foreignKey' => 'progfichecandidature66_id',
				'associationForeignKey' => 'actioncandidat_personne_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CandidatureProg66'
			)
		);
	}
?>