<?php
    class Ep extends AppModel
    {
        var $name = 'Ep';

        var $hasAndBelongsToMany = array(
            'Partep' => array( 'with' => 'EpPartep' ),
            'Rolepartep' => array( 'with' => 'EpPartep' )
        );

		var $hasMany = array(
			'Demandereorient',
            'Parcoursdetecte'
		);

		var $actsAs = array(
			'Autovalidate',
            'Enumerable' // FIXME ?
		);



		var $validate = array(
			'name' => array(
				array( 'rule' => 'isUnique' ),
				array( 'rule' => 'notEmpty' )
			),
			'date' => array(
				array( 'rule' => 'notEmpty' ),
				array( 'rule' => 'futureDate' )
			),
			'localisation' => array(
				array( 'rule' => 'notEmpty' )
			),
		);

// 		public $virtualFields = array(
// 			'nbrdemandesreorient' => array(
// 				'type'		=> 'integer',
// 				'postgres'	=> '( SELECT COUNT( "demandesreorient"."id" ) FROM "demandesreorient" WHERE "demandesreorient"."ep_id" = "%s"."id" )'
// 			),
// 			'nbrparcoursdetectes' => array(
// 				'type'		=> 'integer',
// 				'postgres'	=> '( SELECT COUNT( "parcoursdetectes"."id" ) FROM "parcoursdetectes" WHERE "parcoursdetectes"."ep_id" = "%s"."id" )'
// 			),
// 		);
    }
?>