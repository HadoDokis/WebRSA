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
            'Formattable'
        );

        var $displayField = 'numeroapre';

        var $validate = array(
            'typedemandeapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'activitebeneficiaire' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'secteurprofessionnel' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
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
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

        var $hasOne = array(
//             'Aideapre66'
        );

        var $belongsTo = array(
            'Personne',
            'Structurereferente',
            'Referent'
        );


        var $hasAndBelongsToMany = array(
            'Pieceapre' => array(
                 'className'              => 'Pieceapre',
                 'joinTable'              => 'apres_piecesapre',
                 'foreignKey'             => 'apre_id',
                 'associationForeignKey'  => 'pieceapre_id'
            ),
            'Comiteapre' => array(
                'className'              => 'Comiteapre',
                'joinTable'              => 'apres_comitesapres',
                'foreignKey'             => 'apre_id',
                'associationForeignKey'  => 'comiteapre_id',
                'with'                   => 'ApreComiteapre'
            )
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
    }
?>