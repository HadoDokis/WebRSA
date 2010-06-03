<?php
    class PropopdoStatutdecisionpdo extends AppModel
    {
        var $name = 'PropopdoStatutdecisionpdo';

        var $belongsTo = array(
            'Propopdo',
            'Statutdecisionpdo'
        );

        var $actsAs = array (
            'Nullable',
            'ValidateTranslate'
        );

        var $validate = array(
            'propopdo_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
            'statutdecisionpdo_id' => array(
                array( 'rule' => 'notEmpty' )
            )
        );
    }
?>