<?php
	class Piececomptable66 extends AppModel
	{
		public $name = 'Piececomptable66';

		public $order = 'Piececomptable66.name ASC';

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'joinTable' => 'aidesapres66_piecescomptables66',
				'foreignKey' => 'piececomptable66_id',
				'associationForeignKey' => 'aideapre66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Aideapre66Piececomptable66'
			),
			'Typeaideapre66' => array(
				'className' => 'Typeaideapre66',
				'joinTable' => 'piecescomptables66_typesaidesapres66',
				'foreignKey' => 'piececomptable66_id',
				'associationForeignKey' => 'typeaideapre66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Piececomptable66Typeaideapre66'
			)
		);

		/**
		 * Retourne une sous-requête permettant d'obtenir la liste des pièces liées
		 * à une aide APRE du 66. Les éléments de la liste sont triés et préfixés par une
		 * chaîne de caractères.
		 *
		 * @param string $aideapre66Id
		 * @param string $prefix
		 * @return string
		 */
		public function vfListePieces( $aideapre66Id = 'Aideapre66.id', $prefix = '\\n\r-' ) {
			$alias = Inflector::tableize( $this->alias );

			$sq = $this->sq(
				array(
					'alias' => $alias,
					'fields' => array(
						"'{$prefix}' || \"{$alias}\".\"name\" AS \"{$alias}__name\""
					),
					'contain' => false,
					'joins' => array(
						array_words_replace(
							$this->join( 'Aideapre66Piececomptable66', array( 'type' => 'INNER' ) ),
							array(
								'Aideapre66Piececomptable66' => 'aidesapres66_piecescomptables66',
								'Piececomptable66' => 'piecescomptables66'
							)
						),
					),
					'conditions' => array(
						"aidesapres66_piecescomptables66.aideapre66_id = {$aideapre66Id}"
					),
					'order' => array(
						"{$alias}.name ASC"
					)
				)
			);

			return "ARRAY_TO_STRING( ARRAY( {$sq} ), '' )";
		}
	}
?>
