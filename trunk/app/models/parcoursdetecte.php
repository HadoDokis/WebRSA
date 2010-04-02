<?php
	class Parcoursdetecte extends AppModel
	{
		var $name = 'Parcoursdetecte';

        var $order = array( 'Parcoursdetecte.id ASC' );

		var $belongsTo = array(
			'Orientstruct',
			'Ep' => array(
				'type' => 'LEFT OUTER',
			)
		);

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'signale' => array(
                        'values' => array( 0, 1 )
                    )
                )
            )
        );

		var $hasOne = array(
			'Decisionparcoursequipe' => array(
				'className' => 'Decisionparcours',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Decisionparcoursequipe.roleparcours' => 'equipe'
				)
			),
			'Decisionparcoursconseil' => array(
				'className' => 'Decisionparcours',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Decisionparcoursconseil.roleparcours' => 'conseil'
				)
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