<?php
    class Ressourcemensuelle extends AppModel
    {
        var $name = 'Ressourcemensuelle';
	var $useTable = 'ressourcesmensuelles';

	var $belongsTo = array(
	    'Ressource' => array(
            'classname'     => 'Ressource',
            'foreignKey'    => 'ressource_id'
		)
		);

    var $hasMany = array(
        'Detailressourcemensuelle' => array(
            'classname'     => 'Detailressourcemensuelle',
            'foreignKey'    => 'ressourcemensuelle_id'
        )
        );

//         function beforeSave() {
// //             parent::beforeSave();
// 
//             debug( $this->data );
// 
//             return true;
//         }
    }
?>
