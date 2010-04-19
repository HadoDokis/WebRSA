<?php
    class Apre66 extends AppModel
    {
        var $name = 'Apre66';
        var $useTable = 'apres';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
                    'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
                    'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
                    'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
                    'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
                    'ajoutcomiteexamen' => array( 'type' => 'no', 'domain' => 'apre' ),
                    'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' ),
                    'eligibiliteapre' => array( 'type' => 'eligibiliteapre', 'domain' => 'apre' ),
                    'presence' => array( 'type' => 'presence', 'domain' => 'apre' ),
                    'justificatif' => array( 'type' => 'justificatif', 'domain' => 'apre' )
                )
            ),
            'Frenchfloat' => array(
                'fields' => array(
                    'montantaverser',
                    'montantattribue',
                    'montantdejaverse'
                )
            ),
			'Formattable' => array(
				'suffix' => array( 'referent_id' ),
			)
        );

        var $displayField = 'numeroapre';

        var $validate = array(
            'typedemandeapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
            ),
            'activitebeneficiaire' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
            ),
            'secteurprofessionnel' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
            ),
            'montantaverser' => array(
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.'
                ),
            ),
            'montantattribue' => array(
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.'
                ),
            ),
            'structurereferente_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
            )
        );

        var $hasOne = array(
            'Aideapre66'/*,
            'Themeapre66'*/
        );

        var $belongsTo = array(
            'Personne',
            'Structurereferente',
            'Referent'
        );


        var $hasAndBelongsToMany = array(
//             'Pieceaide66' => array(
//                  'className'              => 'Pieceaide66',
//                  'joinTable'              => 'apres_piecesaides66',
//                  'foreignKey'             => 'apre_id',
//                  'associationForeignKey'  => 'pieceapre_id'
//             )
        );

        function dossierId( $apre_id ){
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( "Personne.id = {$this->alias}.personne_id" )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $apre = $this->findById( $apre_id, null, null, 0 );

            if( !empty( $apre ) ) {
                return $apre['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }


        /**
        *
        */

        /*function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
debug( $return );
            if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'referent_id', $this->data[$this->name] ) ) {
                $this->data = Set::insert( $this->data, "{$this->alias}.referent_id", suffix( Set::extract( $this->data, "{$this->alias}.referent_id" ) ) );
            }

            return $return;
        }*/
    }
?>