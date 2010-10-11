<?php
	class Avispcgpersonne extends AppModel
	{
		public $name = 'Avispcgpersonne';

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
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

		public $hasMany = array(
			'Derogation' => array(
				'className' => 'Derogation',
				'foreignKey' => 'avispcgpersonne_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Liberalite' => array(
				'className' => 'Liberalite',
				'foreignKey' => 'avispcgpersonne_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		/**
		*
		*/

		public function idFromDossierId( $dossier_id ){
			$options = array(
				'fields' => array(
					'Personne.id'
				),
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'Prestation.rolepers = \'DEM\'',
						)
					),
				),
				'conditions' => array(
					'Foyer.dossier_id' => $dossier_id
				),
				'recursive' => -1
			);
			$personne = $this->Personne->find( 'first', $options );
			if( empty( $personne ) ) {
				return null;
			}

			$avispcgpersonne = $this->findByPersonneId( $personne['Personne']['id'], null, null, -1 );
			if( empty( $avispcgpersonne ) ) {
				return null;
			}

			return Set::extract( $avispcgpersonne, 'Avispcgpersonne.id' );
		}
	}
?>
