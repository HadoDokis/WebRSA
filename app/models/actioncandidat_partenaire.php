<?php
    class ActioncandidatPartenaire extends AppModel
    {
        var $name = 'ActioncandidatPartenaire';
//         var $useTable = 'actionscandidats_partenaires';


        var $displayField = 'libstruc';

        var $belongsTo = array(
            'Actioncandidat',
            'Partenaire'
        );

        var $actsAs = array (
            'Nullable',
            'ValidateTranslate'
        );

        var $validate = array(
            'actioncandidat_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
            'partenaire_id' => array(
                array( 'rule' => 'notEmpty' )
            )
        );

    }
?>