<?php
    class ApreComiteapre extends AppModel
    {
        var $name = 'ApreComiteapre';
        var $actsAs = array( 'Enumerable' );


        var $validate = array(
            'decisioncomite' => array(
                array(
                    'rule'      => array( 'inList', array( 'AJ', 'ACC', 'REF' ) ),
                    'message'   => 'Veuillez choisir une valeur.',
                    'allowEmpty' => false
                )
            ),
            'montantattribue' => array(
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                ),
            ),
        );

        var $enumFields = array(
            'decisioncomite' => array( 'type' => 'decisioncomite', 'domain' => 'apre' ),
        );
    }
?>