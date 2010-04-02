<?php
    class Partenaire extends AppModel
    {
        var $name = 'Partenaire';
//         var $useTable = 'partenaires';

        var $displayField = 'libstruc';

        var $actsAs = array(
            'ValidateTranslate',
        );

        var $hasAndBelongsToMany = array(
            'Actioncandidat' => array( 'with' => 'ActioncandidatPartenaire' )
        );

        var $hasMany = array(
            'Contactpartenaire'
        );
    }
?>