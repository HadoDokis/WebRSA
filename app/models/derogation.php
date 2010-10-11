<?php
	class Derogation extends AppModel
	{
		public $name = 'Derogation';

		public $validate = array(
			'avispcgpersonne_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Avispcgpersonne' => array(
				'className' => 'Avispcgpersonne',
				'foreignKey' => 'avispcgpersonne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/

		public function dossierId( $derogation_id ) {
			$query = array(
				'fields' => array(
					'"Foyer"."dossier_id"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'avispcgpersonnes',
						'alias'      => 'Avispcgpersonne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Derogation.avispcgpersonne_id = Avispcgpersonne.id',
							'Derogation.id' => $derogation_id
						)
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Avispcgpersonne.personne_id = Personne.id' )
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					)
				)
			);

			$result = $this->find( 'first', $query );

			if( !empty( $result ) ) {
				return $result['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
	}
?>