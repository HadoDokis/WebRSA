<?php
	class Piecemodeletypecourrierpcg66 extends AppModel
	{
		public $name = 'Piecemodeletypecourrierpcg66';

		public $order = 'Piecemodeletypecourrierpcg66.name ASC';

		public $validate = array(
			'name' => array(
				array(
					'rule' => array(
						'checkUnique',
						array( 'name', 'modeletypecourrierpcg66_id' )
					),
					'message' => 'Cet intitulé de pièce est déjà utilisé avec ce modèle de courrier.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'modeletypecourrierpcg66_id' => array(
				array(
					'rule' => array(
						'checkUnique',
						array( 'name', 'modeletypecourrierpcg66_id' )
					),
					'message' => 'Ce modèle de courrier est déjà utilisé avec cet intitulé de pièce.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Modeletypecourrierpcg66' => array(
				'className' => 'Modeletypecourrierpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Modeletraitementpcg66' => array(
				'className' => 'Modeletraitementpcg66',
				'joinTable' => 'mtpcgs66_pmtcpcgs66',
				'foreignKey' => 'piecemodeletypecourrierpcg66_id',
				'associationForeignKey' => 'modeletraitementpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Mtpcg66Pmtcpcg66'
			)
		);    
		
		/**
		 * Retourne une sous-requête permettant d'obtenir la liste des pièces liées
		 * au modèle de courrier des traitements PCGs 66.
		 * Les éléments de la liste sont triés et préfixés par une
		 * chaîne de caractères.
		 *
		 * @param string $aideapre66Id
		 * @param string $prefix
		 * @return string
		 */
		public function vfListeMotifs( $modeletraitementpcg66_id = 'Modeletraitementpcg66.id', $prefix = '\\n\r-' ) {
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
							$this->join( 'Mtpcg66Pmtcpcg66', array( 'type' => 'INNER' ) ),
							array(
								'Mtpcg66Pmtcpcg66' => 'mtpcgs66_pmtcpcgs66',
								'Piecemodeletypecourrierpcg66' => 'piecesmodelestypescourrierspcgs66'
							)
						),
					),
					'conditions' => array(
						"mtpcgs66_pmtcpcgs66.modeletraitementpcg66_id = {$modeletraitementpcg66_id}"
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
