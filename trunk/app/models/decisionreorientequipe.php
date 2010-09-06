<?php
	class Decisionreorientequipe extends AppModel
	{
		public $name = 'Decisionreorientequipe';
		public $useTable = 'decisionsreorient';

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

		/*public $belongsTo = array(
			'NvTypeorientequipe' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'nv_typeorient_id'
			),
			'NvStructurereferenteequipe' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'nv_structurereferente_id'
			),
			'NvReferentequipe' => array(
				'className' => 'Referent',
				'foreignKey' => 'nv_referent_id'
			),
		);*/

		/*public $hasMany = array(
			'Decisionreorient'
		);*/
	}
?>