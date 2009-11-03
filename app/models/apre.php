<?php 
    class Apre extends AppModel
    {
        var $name = 'Apre';
        var $useTable = 'apres';
        var $actsAs = array( 'Enumerable' );

        var $validate = array(
            'typedemandeapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'activitebeneficiaire' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );

        var $enumFields = array(
            'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
            'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
            'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
            'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
            /*'drorsarmianta2' => array( 'type' => 'nos', 'domain' => 'default' ),
            'topcouvsoc',
            'accosocfam' => array( 'type' => 'nov', 'domain' => 'default' ),
            'accosocindi' => array( 'type' => 'nov', 'domain' => 'default' ),
            'soutdemarsoc' => array( 'type' => 'nov', 'domain' => 'default' ),
            'nivetu',
            'nivdipmaxobt',
            'topqualipro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topcompeextrapro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topengdemarechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'hispro',
            'cessderact',
            'topdomideract' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'duractdomi',
            'inscdememploi',
            'topisogrorechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'accoemploi',
            'topprojpro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topcreareprientre' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'concoformqualiemploi' => array( 'type' => 'nos', 'domain' => 'default' ),
            'topmoyloco' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'toppermicondub' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topautrpermicondu' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'natlog',
            'demarlog'*/
        );


        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            )
        );

        var $hasMany = array(
            'Referentapre' => array(
                'classname' => 'Referentapre',
                'foreignKey' => 'apre_id',
            ),
            'Natureaide' => array(
                'classname' => 'Natureaide',
                'foreignKey' => 'apre_id',
            ),
            'Montantconsomme' => array(
                'classname' => 'Montantconsomme',
                'foreignKey' => 'apre_id',
            )
        );

        function nbEnfants( $foyer_id ){
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'belongsTo' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Apre.personne_id' )
                        )
                    )
                )
            );

            $this->Personne->Foyer->unbindModelAll();
            $this->Personne->Foyer->bindModel(
                array(
                    'hasMany' => array(
                        'Personne' => array(
                            'classname'     => 'Personne',
                            'foreignKey'    => 'foyer_id'
                        )
                    )
                )
            );
            $foyer = $this->Personne->Foyer->find( 'first', array( 'conditions' => array( 'Foyer.id' => $foyer_id ), 'recursive' => 1 ) );

            ///Nombre d'enfants dans le foyer
            $nbEnfants = $this->Personne->Prestation->find(
                'count',
                array(
                    'conditions' => array(
                        'Personne.id' => Set::classicExtract( $foyer, 'Personne.{n}.id' ),
                        'Prestation.rolepers' => 'ENF'
                    )
                )
            );
           return $nbEnfants;
        }

        function dossierId( $apre_id ){
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Apre.personne_id' )
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