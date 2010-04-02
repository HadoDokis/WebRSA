<?php 
    class Identificationflux extends AppModel
    {
        var $name = 'Identificationflux';
        var $useTable = 'identificationsflux';


        var $hasMany = array(
            'Totalisationacompte' => array(
                'classname' => 'Totalisationacompte',
                'foreignKey' => 'identificationflux_id'
            )
        );

    }

?>