<?php
    class Demandereorient extends AppModel
    {
        var $name = 'Demandereorient';

        var $order = array( 'Demandereorient.created ASC' );

		var $belongsTo = array(
			'Ep' => array(
				'type' => 'LEFT OUTER',
			),
			'Motifdemreorient',
			'Reforigine' => array(
				'className' => 'Referent',
				'foreignKey' => 'reforigine_id'
			),
			'Orientstruct' => array(
				'type' => 'LEFT OUTER',
			),
			/*'Refaccueil' => array(
				'className' => 'Referent'
			),*/
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id'
			),
		);

		var $hasOne = array(
			'Precoreorientreferent' => array(
				'className' => 'Precoreorient',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Precoreorientreferent.rolereorient' => 'referent'
				),
				'dependent' => true
			),
			'Precoreorientequipe' => array(
				'className' => 'Precoreorient',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Precoreorientequipe.rolereorient' => 'equipe'
				),
				'dependent' => true
			),
			'Precoreorientconseil' => array(
				'className' => 'Precoreorient',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Precoreorientconseil.rolereorient' => 'conseil'
				),
				'dependent' => true
			),
		);

		var $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'accordbenef' => array(
						'values' => array( 0, 1 )
					),
					'urgent' => array(
						'values' => array( 0, 1 )
					),
					/*'accordrefaccueil' => array(
						'values' => array( 0, 1 )
					),
					'decisionep' => array(
						'values' => array( 0, 1 )
					),
					'decisioncg' => array(
						'values' => array( 0, 1 )
					),*/
				)
			)
		);

		var $validate = array(
			'reforigine_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'motifdemreorient_id' => array(
				array( 'rule' => 'notEmpty' )
			),
// 			'refaccueil_id' => array(
// 				array( 'rule' => 'notEmpty' )
// 			),
		);

/*		function beforeSave( $options = array() ) {
		}*/

		public $virtualFields = array(
			'statut' => array(
				'type'		=> 'string',
				'postgres'	=> '( SELECT COUNT( "precosreorients"."id" ) FROM "precosreorients" WHERE "precosreorients"."demandereorient_id" = "%s"."id" )'
			),
		);

        /**
        * FIXME: un behavior
        */

        function beforeValidate( $options ) {
            $this->data = Set::flatten( $this->data );

            foreach( $this->data as $path => $value ) {
                if( $path == "{$this->alias}.ep_id" && $value == 0 ) {
                    $this->data[$path] = null;
                }
            }

            $this->data = Xset::bump( $this->data );
            return true;
        }
    }
?>