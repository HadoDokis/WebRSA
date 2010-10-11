<?php
	class Aideapre66 extends AppModel
	{
		public $name = 'Aideapre66';

		public $actsAs = array(
			'Autovalidate',
			'Formattable' => array(
				'amount' => array( 'montantaide' ),
				'suffix' => array( 'typeaideapre66_id' ),
			),
			'Enumerable' => array(
				'fields' => array(
					'virement' => array( 'type' => 'virement', 'domain' => 'aideapre66' ),
					'versement' => array( 'type' => 'versement', 'domain' => 'aideapre66' ),
					'autorisationvers' => array( 'type' => 'no', 'domain' => 'aideapre66' ),
					'decisionapre' => array( 'type' => 'decisionapre', 'domain' => 'aideapre66' ),
				)
			)
		);

		public $belongsTo = array(
			'Apre66' => array(
				'className' => 'Apre66',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Themeapre66' => array(
				'className' => 'Themeapre66',
				'foreignKey' => 'themeapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeaideapre66' => array(
				'className' => 'Typeaideapre66',
				'foreignKey' => 'typeaideapre66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Fraisdeplacement66' => array(
				'className' => 'Fraisdeplacement66',
				'foreignKey' => 'aideapre66_id',
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


		public $hasAndBelongsToMany = array(
			'Pieceaide66' => array(
				'className' => 'Pieceaide66',
				'joinTable' => 'aidesapres66_piecesaides66',
				'foreignKey' => 'aideapre66_id',
				'associationForeignKey' => 'pieceaide66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Aideapre66Pieceaide66'
			),
			'Piececomptable66' => array(
				'className' => 'Piececomptable66',
				'joinTable' => 'aidesapres66_piecescomptables66',
				'foreignKey' => 'aideapre66_id',
				'associationForeignKey' => 'piececomptable66_id',
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
			)
		);

		public $validate = array(
			'themeapre66_id' => array(
				array(
					'rule' => 'notEmpty'
				)
			),
			'montantaide' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				),
				array(
					'rule' => 'plafondMontantAideapre',
					'message' => 'Plafond dépassé'
				)
			),
			'montantpropose' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				),
				array(
					'rule' => 'plafondMontantAideapre',
					'message' => 'Plafond dépassé'
				)
			),
			'montantaccorde' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				),
				array(
					'rule' => 'plafondMontantAideapre',
					'message' => 'Plafond dépassé'
				)
			),
			'virement' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'versement' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'creancier' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'motivdem' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)/*,
			'decisionapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)*/
		);

		/**
		* Vérification du montant demandé pour une aide APRE
		* Ce montant doit être inférieur au plafond de cette aide
		*
		* FIXME: signature + retour
		*
		* @param string $montantaide Value to check
		* @param integer $plafond Valeur à ne pas dépasser
		*
		* @return boolean Success
		* @access public
		*/
		public function plafondMontantAideapre( $check ) {
			$return = true;
			$typeaideapre66_id = Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' );
			$typeaideapre66 = $this->Typeaideapre66->findById( $typeaideapre66_id, null, null, -1 );
			$plafond = Set::classicExtract( $typeaideapre66, 'Typeaideapre66.plafond' );

			foreach( $check as $field => $value ) {
				$return = ( $value <= $plafond ) && $return;
			}
			return $return;
		}


		/**
		*   Récupération du nombre de pièces liées aux types d'aides d'une APRE
		*/

		protected function _nbrNormalPieces() {
			$nbNormalPieces = array();

			$typeaideapre66_id = Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' );
			$typeaide = $this->Typeaideapre66->findById( $typeaideapre66_id, null, null, 2 );

			$nbNormalPieces['Typeaideapre66'] = count( Set::extract( $typeaide, '/Pieceaide66/id' ) );
			return $nbNormalPieces;
		}


		/**
		*   Détails des APREs afin de récupérer les pièces liés à cette APRE ainsi que les aides complémentaires avec leurs pièces
		*   @param int $id
		*/

		public function _details( $aideapre66_id ) {
			$nbNormalPieces = $this->_nbrNormalPieces();
			$details['Piecepresente'] = array();
			$details['Piecemanquante'] = array();


			// Nombre de pièces trouvées par-rapport au nombre de pièces prévues - Apre
			$details['Piecepresente']['Typeaideapre66'] = $this->Aideapre66Pieceaide66->find( 'count', array( 'conditions' => array( 'aideapre66_id' => $aideapre66_id ) ) );

			$details['Piecemanquante']['Typeaideapre66'] = abs( $details['Piecepresente']['Typeaideapre66'] - $nbNormalPieces['Typeaideapre66'] );

			$piecesPresentes = array();
			// Quelles sont les pièces manquantes
			$piecesPresentes = Set::extract( $this->Aideapre66Pieceaide66->find( 'all', array( 'conditions' => array( 'aideapre66_id' => $aideapre66_id ) ) ), '/Aideapre66Pieceaide66/pieceaide66_id' );

			$typeaideapre66_id = Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' );
			$piecesParType = $this->Typeaideapre66->Pieceaide66Typeaideapre66->find(
				'list',
				array(
					'fields' => array( 'id', 'pieceaide66_id' ),
					'conditions' => array(
						'Pieceaide66Typeaideapre66.typeaideapre66_id' => $typeaideapre66_id/*,
						'NOT' => array( 'Pieceaide66Typeaideapre66.pieceaide66_id' => $piecesPresentes )*/
					)
				)
			);
			$piecesAbsentes = array_diff( $piecesParType, $piecesPresentes );

			return $details;
		}


		/**
		*
		*/

		public function afterSave( $created ) {
			$return = parent::afterSave( $created );
			$details = $this->_details( $this->id );
		}
	}
?>
