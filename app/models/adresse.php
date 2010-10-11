<?php
	class Adresse extends AppModel
	{
		var $name = 'Adresse';

		public $virtualFields = array(
			'localite' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."codepos" || \' \' || "%s"."locaadr" )'
			),
		);

		var $validate = array(
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

		var $hasMany = array(
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

		function listeCodesInsee() {
			$queryData = array(
				'fields' => array(
					"DISTINCT {$this->name}.numcomptt",
					"{$this->name}.locaadr",
				),
				'joins' => array(
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Adressefoyer.rgadr = \'01\'',
							'Adressefoyer.adresse_id = Adresse.id'
						)
					)
				),
				'conditions' => array(
					"{$this->name}.locaadr IS NOT NULL",
					"{$this->name}.locaadr <> ''",
					"{$this->name}.numcomptt IS NOT NULL",
					"{$this->name}.numcomptt <> ''"
				),
				'sort' => array(
					"{$this->name}.numcomptt ASC",
					"{$this->name}.locaadr ASC"
				),
				'recursive' => -1
			);
			$tResults = $this->find( 'all', $queryData );

			$results = array();
			foreach( $tResults as $key => $result ) {
				$locaadr = Set::classicExtract( $result, 'Adresse.locaadr' );
				$numcomptt = Set::classicExtract( $result, 'Adresse.numcomptt' );
				$results[$numcomptt] = "$numcomptt $locaadr";
			}

			return $results;
		}
	}
?>
