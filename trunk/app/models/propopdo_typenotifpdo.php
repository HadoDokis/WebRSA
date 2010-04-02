<?php
    class PropopdoTypenotifpdo extends AppModel
    {
        var $name = 'PropopdoTypenotifpdo';

        var $usetable = 'propospdos_typesnotifspdos';

        var $validate = array(
            'id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'typenotifpdo_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'propopdo_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'datenotifpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

    }
?>