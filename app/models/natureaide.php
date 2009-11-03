<?php 
    class Natureaide extends AppModel
    {
        var $name = 'Natureaide';
        var $useTable = 'naturesaides';
//         var $displayField = 'libelle';

        var $belongsTo = array(
            'Apre' => array(
                'classname' => 'Apre',
                'foreignKey' => 'apre_id'
            )
        );

//         var $hasOne = array(
//             'Acqmatprof',
//             'Formqualif',
//             'Actprof',
//             'Acccreaentr',
//             'Amenaglogt',
//             'Permisb',
//             'Locvehicinsert'
//         );
    }

?>