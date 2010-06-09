<?php
	class Decisionreorient extends AppModel
	{
		public $name = 'Decisionreorient';

		public $actsAs = array(
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'decision'
				)
			),
			'Formattable' => array(
				'suffix' => array(
                    'nv_structurereferente_id',
                    'nv_referent_id'
				)
			)
		);

        /**
        *   Vérification pour la décision ou l'avis (valeurs obligatoires en cas d'accord)
        */

        function checkDependantDecision( $check ){
			$values = array_values( $check );
			if( Set::classicExtract( $this->data, "{$this->alias}.decision" ) == 'accord' && empty( $values[0] ) ) {
				return false;
			}

			return true;
        }

// 		public $belongsTo = array(
// 			'Personne',
// 			'Orientstruct',
// 			'Motifdemreorient',
// 			'VxTypeorient' => array(
// 				'className' => 'Typeorient',
// 				'foreignKey' => 'vx_typeorient_id'
// 			)
// 		);

		/*public $hasMany = array(
			'Decisionreorient'
		);*/
	}
?>