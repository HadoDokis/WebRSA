<?php
    class ApreComiteapre extends AppModel
    {
        var $name = 'ApreComiteapre';
        var $actsAs = array( 'Enumerable' );

        var $enumFields = array(
            'decisioncomite' => array( 'type' => 'decisioncomite', 'domain' => 'apre' ),
        );
    }
?>