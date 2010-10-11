<?php
	class Infopoleemploi extends AppModel
	{
		public $name = 'Infopoleemploi';

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

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
						{$table}.personne_id = ".$field."
					ORDER BY {$table}.dateinscription DESC
					LIMIT 1
			";
		}
	}
?>
