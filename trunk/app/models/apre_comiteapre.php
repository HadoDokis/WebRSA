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

//                 $model->validate[$field][] = array(
//                     'rule'       => array( 'inList', $options ),
//                     'message'    => sprintf(
//                         __( $this->settings['validationRule'], true ),
//                         implode( $this->settings['validationRuleSeparator'], $options )
//                     ),
//                     'allowEmpty' => $this->settings['validationRuleAllowEmpty']
//                 );

        var $enumFields = array(
            'decisioncomite' => array( 'type' => 'decisioncomite', 'domain' => 'apre' ),
        );
    }
?>