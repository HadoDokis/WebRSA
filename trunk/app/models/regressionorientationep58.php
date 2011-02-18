<?php
    App::import( 'Model', array( 'Regressionorientationep', 'Gedooo' ) );

	class Regressionorientationep58 extends Regressionorientationep
	{
		public $name = 'Regressionorientationep58';

		public $hasMany = array(
			'Decisionregressionorientationep58' => array(
				'className' => 'Decisionregressionorientationep58',
				'foreignKey' => 'regressionorientationep58_id',
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
		* Finalisation de la décision pour le cg58
		*/
		
		public function finaliser( $seanceep_id, $etape ) {
			$success = true;
			
			$seanceep = $this->Dossierep->Seanceep->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id
					),
					'contain' => array(
						'Dossierep' => array(
							'Regressionorientationep58' => array(
								'Decisionregressionorientationep58'
							)
						)
					)
				)
			);
			list( $dateseance, $heureseance ) = explode ( ' ', $seanceep['Seanceep']['dateseance'] );
			foreach( $seanceep['Dossierep'] as $dossierep ) {
				$orientstruct = array(
					'Orientstruct' => array(
						'personne_id' => $dossierep['personne_id'],
						'typeorient_id' => $dossierep['Regressionorientationep58']['Decisionregressionorientationep58'][0]['typeorient_id'],
						'structurereferente_id' => $dossierep['Regressionorientationep58']['Decisionregressionorientationep58'][0]['structurereferente_id'],
						'date_propo' => $dossierep['Regressionorientationep58']['datedemande'],
						'date_valid' => $dateseance,
						'statut_orient' => 'Orienté',
						'referent_id' => $dossierep['Regressionorientationep58']['Decisionregressionorientationep58'][0]['referent_id'],
						'etatorient' => 'decision',
						'rgorient' => $this->Structurereferente->Orientstruct->rgorientMax( $dossierep['personne_id'] )
					)
				);
				
				$success = $this->Structurereferente->Orientstruct->save( $orientstruct ) && $success;
				$success = $this->Gedooo->mkOrientstructPdf( $this->Structurereferente->Orientstruct->getLastInsertId() ) && $success;
			}
			
			return $success;
		}
		
	}

?>