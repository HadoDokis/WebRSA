<?php
    class PropopdoSituationpdo extends AppModel
    {
        var $name = 'PropopdoSituationpdo';

        var $belongsTo = array(
            'Propopdo',
            'Situationpdo'
        );

        var $actsAs = array (
            'Nullable',
            'ValidateTranslate'
        );

        var $validate = array(
            'propopdo_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
            'situationpdo_id' => array(
                array( 'rule' => 'notEmpty' )
            )
        );
    }
?>