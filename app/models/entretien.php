<?php 
    class Entretien extends AppModel
    {
        var $name = 'Entretien';
        var $useTable = 'entretiens';

        var $actsAs = array(
            'Formattable' => array(
                'suffix' => array( 'structurereferente_id', 'referent_id' ),
            ),
            'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'typeentretien'
                )
            )
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            ),
            'Rendezvous' => array(
                'classname' => 'Rendezvous',
                'foreignKey' => 'rendezvous_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            ),
            'Referent' => array(
                'classname' => 'Referent',
                'foreignKey' => 'referent_id'
            ),
            'Typerdv' => array(
                'classname' => 'Typerdv',
                'foreignKey' => 'typerdv_id'
            )
        );


    }

?>