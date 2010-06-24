<?php
    class Rendezvous extends AppModel
    {
        var $name = 'Rendezvous';
        var $useTable = 'rendezvous';
        var $displayField = 'libelle';

        var $actsAs = array(
            'Formattable' => array(
                'suffix' => array( 'referent_id', 'permanence_id' )
            )
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            ),
            'Referent',
			'Statutrdv',
			'Permanence'
        );

        var $hasOne = array(
            'Typerdv' => array(
                'foreignKey' => false,
                'conditions' => array( 'Typerdv.id = Rendezvous.typerdv_id' )
            )
        );

        var $validate = array(
            'structurereferente_id' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'typerdv_id' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'daterdv' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                )
            ),
            'heurerdv' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'statutrdv_id' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

        function dossierId( $rdv_id ){
            $this->unbindModelAll();
             $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Rendezvous.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        ),
                        'Typerdv' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Typerdv.id = Rendezvous.typerdv_id' )
                        )
                    )
                )
            );
            $rdv = $this->findById( $rdv_id, null, null, 0 );
// debug( $rdv );
            if( !empty( $rdv ) ) {
                return $rdv['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }


    }

?>