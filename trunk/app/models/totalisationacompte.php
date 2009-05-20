<?php 
    class totalisationacompte extends AppModel
    {
        var $name = 'Totalisationacompte';
        var $useTable = 'totalisationsacomptes';


        var $belongsTo = array(
            'Identificationflux' => array(
                'classname' => 'Identificationflux',
                'foreignKey' => 'identificationflux_id'
            )
        );

        var $validate = array(
            'type_totalisation' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotsoclrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotsoclmajorsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotlocalrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'mttotrsa' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );
    }

?>