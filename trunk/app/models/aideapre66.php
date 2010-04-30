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
//             'autorisationvers' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
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