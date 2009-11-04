<?php
	class Canton extends AppModel
	{
		var $name = 'Canton';
		var $displayField = 'canton';

		/**
		*	FIXME: docs
		*/

	/*typevoie			VARCHAR(4),
	nomvoie				VARCHAR(25),
	locaadr				VARCHAR(26),
	codepos				VARCHAR(5),
	numcomptt			VARCHAR(5),
	canton				VARCHAR(30)*/

		var $validate = array(
            'canton' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'locaadr' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'codepos' => array(
                array(
                    'rule' => array( 'between', 5, 5 ),
                    'message' => 'Le code postal se compose de 5 caractères',
					'allowEmpty' => true
                )
            ),
            'numcomptt' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => array( 'between', 5, 5 ),
                    'message' => 'Le code INSEE se compose de 5 caractères'
                )
            ),
		);

		/**
		*	FIXME: docs
		*/

		function selectList() {
			$queryData = array(
				'fields' => array( 'DISTINCT Canton.canton' ),
				'conditions' => array( 'Canton.canton IS NOT NULL', 'Canton.canton <> \'\'' ),
				'recursive' => -1,
				'order' => array( 'Canton.canton ASC' )
			);

			$results = parent::find( 'all', $queryData );

            if( !empty( $results ) ) {
                $cantons = Set::extract( $results, '/Canton/canton' );
				return array_combine( $cantons, $cantons );
            }
            else {
                return $results;
            }
		}

		/**
		*	FIXME: docs
		*/

		function queryConditions( $canton ) {
			$cantons = $this->find(
				'all',
				array(
					'conditions' => array(
					'Canton.canton' => $canton
					)
				)
			);
			$_conditions = array();
			foreach( $cantons as $canton ) {
				$_condition = array();
				if( !empty( $canton['Canton']['numcomptt'] ) ) {
					$_condition['Adresse.numcomptt'] = $canton['Canton']['numcomptt'];
				}
				if( !empty( $canton['Canton']['codepos'] ) ) {
					$_condition['Adresse.codepos'] = $canton['Canton']['codepos'];
				}
				if( !empty( $canton['Canton']['locaadr'] ) ) {
					$_condition['Adresse.locaadr ILIKE'] = $canton['Canton']['locaadr'];
				}
				if( !empty( $canton['Canton']['typevoie'] ) ) {
					$_condition['Adresse.typevoie ILIKE'] = $canton['Canton']['typevoie'];
				}
				if( !empty( $canton['Canton']['nomvoie'] ) ) {
					$_condition['Adresse.nomvoie ILIKE'] = $canton['Canton']['nomvoie'];
				}
				$_conditions[] = $_condition;
			}
			return array( 'or' => $_conditions );
		}

		/**
		*	FIXME: docs
		*/

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

			foreach( array( 'nomvoie', 'locaadr', 'canton' ) as $field ) {
				if( !empty( $this->data[$this->name][$field] ) ) {
					$this->data[$this->name][$field] = strtoupper( replace_accents( $this->data[$this->name][$field] ) );
				}
			}

            return $return;
        }
	}
?>