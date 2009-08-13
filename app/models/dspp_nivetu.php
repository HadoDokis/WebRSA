<?php
    class DsppNivetu extends AppModel
    {
        var $name = 'DsppNivetu';

        var $usetable = 'dspps_nivetus';

        var $validate = array(
            'id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'nivetu_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
            'dspp_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

    }
?>