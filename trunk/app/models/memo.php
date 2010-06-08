<?php 
    class Memo extends AppModel
    {
        var $name = 'Memo';
        var $useTable = 'memos';


        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            )
        );



        var $validate = array(
            'dddesignation' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                )
            )
        );

    }

?>