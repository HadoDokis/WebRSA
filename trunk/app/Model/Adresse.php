<?php
	/**
	 * Code source de la classe Adresse.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Adresse ...
	 *
	 * @package app.Model
	 */
	class Adresse extends AppModel
	{
		public $name = 'Adresse';

		public $virtualFields = array(
			'localite' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."codepos" || \' \' || "%s"."locaadr" )'
			)
		);

		public $validate = array(
			'typevoie' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nomvoie' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			// FIXME: validation format code
			'numcomptt' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => array( 'between', 5, 5 ),
					'message' => 'Le code INSEE se compose de 5 caractères'
				)
			),
			'codepos' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'locaadr' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'pays' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
		);

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		public $hasMany = array(
			'Adressefoyer' => array(
				'className' => 'Adressefoyer',
				'foreignKey' => 'adresse_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		public function listeCodesInsee() {
			$querydata = array(
				'fields' => array(
					"DISTINCT {$this->name}.numcomptt",
					"{$this->name}.locaadr",
				),
				'joins' => array(
					$this->join( 'Adressefoyer', array( 'type' => 'INNER', 'conditions' => array( 'Adressefoyer.rgadr' => '01' ) ) )
				),
				'conditions' => array(
					"{$this->name}.locaadr IS NOT NULL",
					"{$this->name}.locaadr <> ''",
					"{$this->name}.numcomptt IS NOT NULL",
					"{$this->name}.numcomptt <> ''"
				),
				'order' => array(
					"{$this->name}.numcomptt ASC",
					"{$this->name}.locaadr ASC"
				),
				'recursive' => -1
			);

			$results = $this->find( 'all', $querydata );

			return Hash::combine( $results, '{n}.Adresse.numcomptt', array( '%s %s', '{n}.Adresse.numcomptt', '{n}.Adresse.locaadr' ) );
		}
	}
?>