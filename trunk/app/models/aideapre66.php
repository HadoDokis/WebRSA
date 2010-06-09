<?php
    class Aideapre66 extends AppModel
    {
        public $name = 'Aideapre66';

        var $belongsTo = array(
			'Typeaideapre66',
            'Apre66'
		);

        var $hasOne = array( 'Fraisdeplacement66' );

        var $actsAs = array(
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

        public $hasAndBelongsToMany = array(
            'Pieceaide66' => array(
                'classname'             => 'Pieceaide66',
                'joinTable'             => 'aidesapres66_piecesaides66',
                'foreignKey'            => 'aideapre66_id',
                'associationForeignKey' => 'pieceaide66_id',
                'with'                  => 'Aideapre66Pieceaide66'
            )
        );

        var $validate = array(
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

        function _nbrNormalPieces() {
            $nbNormalPieces = array();

            $typeaideapre66_id = Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' );
            $typeaide = $this->Typeaideapre66->findById( $typeaideapre66_id, null, null, 2 );

            $nbNormalPieces['Typeaideapre66'] = count( Set::extract( $typeaide, '/Pieceaide66/id' ) );
// debug($typeaideapre66_id);
            return $nbNormalPieces;
        }


        /**
        *   Détails des APREs afin de récupérer les pièces liés à cette APRE ainsi que les aides complémentaires avec leurs pièces
        *   @param int $id
        */

        function _details( $aideapre66_id ) {
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
            $piecesParType = $this->Typeaideapre66->Typeaideapre66Pieceaide66->find(
                'list',
                array(
                    'fields' => array( 'id', 'pieceaide66_id' ),
                    'conditions' => array(
                        'Typeaideapre66Pieceaide66.typeaideapre66_id' => $typeaideapre66_id/*,
                        'NOT' => array( 'Typeaideapre66Pieceaide66.pieceaide66_id' => $piecesPresentes )*/
                    )
                )
            );
// debug($piecesPresentes);
            $piecesAbsentes = array_diff( $piecesParType, $piecesPresentes );

            return $details;
        }


        /**
        *
        */

        function afterSave( $created ) {
            $return = parent::afterSave( $created );
            $details = $this->_details( $this->id );
        }

        /**
        *
        */

        /*function beforeSave( $options = array() ) {

            $return = parent::beforeSave( $options );

//             $this->data[$this->alias]['apre_id'] = 14;
// debug($this->data);

            if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'typeaideapre66_id', $this->data[$this->name] ) ) {
                $this->data = Set::insert( $this->data, "{$this->alias}.typeaideapre66_id", suffix( Set::extract( $this->data, "{$this->alias}.typeaideapre66_id" ) ) );
            }

            return $return;
        }*/
    }
?>