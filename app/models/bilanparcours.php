<?php 
    class Bilanparcours extends AppModel
    {
        var $name = 'Bilanparcours';
        var $useTable = 'bilanparcours';

        var $actsAs = array(
            'Formattable' => array(
                'suffix' => array(
                    'referent_id'
                )
            ),
            'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
//                     'type_proposition',
//                     'type_choixparcours',
//                     'type_reorientation',
//                     'type_orient',
//                     'type_aviscommission',
                    'accordprojet',
                    'maintienorientsansep',
                    'choixparcours',
                    'maintienorientsansep',
                    'changementrefsansep',
                    'maintienorientparcours',
                    'changementrefparcours',
                    'reorientation',
                    'examenaudition',
//                     'aviseplocale',
                    'maintienorientavisep',
                    'changementrefeplocale',
                    'reorientationeplocale',
                    'typeeplocale',
                    'decisioncommission',
                    'decisioncoordonnateur',
                    'decisioncga'
                )
            )
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            ),
            'Referent' => array(
                'classname' => 'Referent',
                'foreignKey' => 'referent_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            )
        );


/*
        var $validate = array(

        );*/

    }

?>