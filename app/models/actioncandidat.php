<?php
    class Actioncandidat extends AppModel
    {
        var $name = 'Actioncandidat';
//         var $useTable = 'actionscandidats';

        var $displayField = 'intitule';

        var $actsAs = array(
            'ValidateTranslate',
        );

        var $hasAndBelongsToMany = array(
            'Partenaire' => array( 'with' => 'ActioncandidatPartenaire' ),
            'Personne' => array( 'with' => 'ActioncandidatPersonne' )
        );
    }
?>