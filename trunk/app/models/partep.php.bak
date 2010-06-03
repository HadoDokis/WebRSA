<?php
    class Partep extends AppModel
    {
        var $name = 'Partep';

//         var $displayField = 'nom_complet';

		var $hasAndBelongsToMany = array(
			'Ep' => array( 'with' => 'EpPartep' ),
            'Rolepartep' => array( 'with' => 'EpPartep' )
		);


// 		var $virtualFields = array(
// 			'nom_complet' => array(
// 				'type'		=> 'string',
// 				'postgres'	=> '( "Partep"."nom" || \' \' || "Partep"."prenom" )'
// 			),
// 		);

		var $actsAs = array(
			'Formattable' => array(
				'phone' => 'tel'
			),
			'Autovalidate',
			'Typeable' => array(
				'tel' => 'phone',
				'email' => 'email'
			)
		);

		var $validate = array(
			'qual' => array(
				array( 'rule' => 'notEmpty' )
			),
			'nom' => array(
				array( 'rule' => 'notEmpty' )
			),
			'prenom' => array(
				array( 'rule' => 'notEmpty' )
			),
			'tel' => array(
				array( 'rule' => 'phoneFr' ),
                //array( 'rule' => array( 'between', 10, 14 ) ),
				array( 'rule' => 'notEmpty' )
			),
			'email' => array(
				array( 'rule' => 'email' ),
				array( 'rule' => 'notEmpty' )
			),
		);
    }
?>