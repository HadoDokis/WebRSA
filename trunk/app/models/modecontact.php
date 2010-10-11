<?php
	class Modecontact extends AppModel
	{
		public $name = 'Modecontact';

		public $validate = array(
			// Role personne
			'numtel' => array(
				array(
					'rule' => array( 'between', 10, 14 ),
					'message' => 'Le numéro de téléphone est composé de 10 chiffres',
					'allowEmpty' => true
				)
			),
			'numposte' => array(
				array(
					'allowEmpty' => true
				),

				array(
					'rule' => array( 'between', 4, 4 ),
					'message' => 'Le numéro de poste est composé de 4 chiffres'
				)
			)
		);

		public $belongsTo = array(
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'foyer_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/

		public function dossierId( $modecontact_id ) {
			$modecontact = $this->findById( $modecontact_id, null, null, 0 );
			if( !empty( $modecontact ) ) {
				return $modecontact['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		*
		*/

		public function sqDerniere($field) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false );
			return "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.foyer_id = ".$field."
					ORDER BY {$table}.foyer_id DESC
					LIMIT 1
			";
		}
	}
?>
