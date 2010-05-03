<?php
    class Infoagricole extends AppModel
    {
        var $name = 'Infoagricole';
        var $useTable = 'infosagricoles';

        //*********************************************************************

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );

        var $hasMany = array(
            'Aideagricole' => array(
                'classname'     => 'Aideagricole',
                'foreignKey'    => 'infoagricole_id'
            )
        );
        //*********************************************************************

//         var $validate = array(
//             'mtbenagri' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'dtbenagri' => array(
//                 array(
//                     'rule' => 'date',
//                     'message' => 'Veuillez vérifier le format de la date.'
//                 ),
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
// 	    
//             'regfisagri' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             )
//         );

    }
?>