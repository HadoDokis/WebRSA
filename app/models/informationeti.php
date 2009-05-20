<?php
    class Informationeti extends AppModel
    {
        var $name = 'Informationeti';
        var $useTable = 'informationseti';

        //*********************************************************************

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );

       //*********************************************************************

        var $validate = array(
            'mtbenagri' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtbenagri' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
	    
            'regfisagri' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

    }
?>