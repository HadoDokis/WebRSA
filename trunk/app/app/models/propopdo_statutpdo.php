<?php
    class PropopdoStatutpdo extends AppModel
    {
        var $name = 'PropopdoStatutpdo';

        var $belongsTo = array(
            'Propopdo',
            'Statutpdo'
        );

        var $actsAs = array (
            'Nullable',
            'ValidateTranslate'
        );

        var $validate = array(
            'propopdo_id' => array(
                array( 'rule' => 'notEmpty' )
            ),
            'statutpdo_id' => array(
                array( 'rule' => 'notEmpty' )
            )
        );
    }
?>