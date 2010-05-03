<?php
    class Fraisdeplacement66 extends AppModel
    {
        public $name = 'Fraisdeplacement66';

        var $belongsTo = array(
            'Aideapre66'
        );

        var $actsAs = array(
            'Autovalidate',
            'Formattable',
            'Frenchfloat' => array(
                'fields' => array(
                    'nbkmvoiture',
                    'nbtrajetvoiture',
                    'nbtrajettranspub',
                    'prixbillettranspub',
                    'nbnuithebergt',
                    'nbrepas'
                )
            ),
        );

        var $validate = array(
            'aideapre66_id' => array(
                array(
                    'rule' => 'notEmpty'
                )
            )
        );

    }
?>