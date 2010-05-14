<?php
    class ActioncandidatPersonne extends AppModel
    {
        var $name = 'ActioncandidatPersonne';
//         var $useTable = 'actionscandidats_personnes';


        var $belongsTo = array(
            'Personne',
            'Referent',
            'Actioncandidat'
        );

        var $actsAs = array (
            'Nullable',
            'ValidateTranslate',
            'Enumerable' => array(
                'fields' => array(
                    'enattente' => array(
                        'values' => array( 'O', 'N' )
                    ),
                    'bilanvenu' => array(
                        'values' => array( 'VEN', 'NVE' ),
                        'domain' => 'actioncandidat_personne'
                    ),
                    'bilanretenu' => array(
                        'values' => array( 'RET', 'NRE' ),
                        'domain' => 'actioncandidat_personne'
                    ),
                    'bilanrecu' => array(
                        'values' => array( 'O', 'N' ),
                        'domain' => 'actioncandidat_personne'
                    )
                )
            ),
            'Formattable'
        );


        var $validate = array(
            'personne_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
            'referent_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
            'actioncandidat_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
        );

    }
?>