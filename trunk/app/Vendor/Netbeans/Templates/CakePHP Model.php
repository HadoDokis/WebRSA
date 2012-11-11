<#include "freemarker_functions.ftl">
<?php
	/**
	 * Fichier source du modèle ${class_name(name)}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
	 * @package app.Model
	 * @license ${license}
	 */

	/**
	 * Classe ${class_name(name)}.
	 *
	 * @package app.Model
	 */
	class ${class_name(name)} extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = '${class_name(name)}';

		/**
		 * Associations "Has one".
		 *
		 * @var array
		 */
		public $hasOne = array(
			/*'' => array(
				'className' => '',
				'foreignKey' => '${foreign_key(name)}',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),*/
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			/*'' => array(
				'className' => '',
				'foreignKey' => null,
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),*/
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			/*'' => array(
				'className' => '',
				'foreignKey' => '${name?replace("([a-z0-9])([A-Z])", "$1_$2", "r")?lower_case}_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),*/
		);

		/**
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			/*'' => array(
				'className' => '${name}',
				'joinTable' => '',
				'foreignKey' => '${name?replace("([a-z0-9])([A-Z])", "$1_$2", "r")?lower_case}_id',
				'associationForeignKey' => '',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => null
			),*/
		);
	}
?>