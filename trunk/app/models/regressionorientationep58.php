<?php
    App::import( 'Model', array( 'Regressionorientationep', 'Gedooo' ) );

	class Regressionorientationep58 extends Regressionorientationep
	{
		public $name = 'Regressionorientationep58';

		public $useTable = 'regressionsorientationseps58';

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

		public function finaliser( $seanceep_id, $etape, $user_id ) {
			$success = true;

			$seanceep = $this->Dossierep->Seanceep->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id
					),
					'contain' => false
				)
			);
			list( $dateseance, $heureseance ) = explode ( ' ', $seanceep['Seanceep']['dateseance'] );

			$dossierseps = $this->Dossierep->find(
				'all',
				array(
					'conditions' => array(
						'seanceep_id' => $seanceep_id,
						'themeep' => 'regressionsorientationseps58'
					),
					'contain' => array(
						'Regressionorientationep58' => array(
							'Decisionregressionorientationep58'
						)
					)
				)
			);
			foreach( $dossierseps as $dossierep ) {
				$orientstruct = array(
					'Orientstruct' => array(
						'personne_id' => $dossierep['Dossierep']['personne_id'],
						'typeorient_id' => $dossierep['Regressionorientationep58']['Decisionregressionorientationep58'][0]['typeorient_id'],
						'structurereferente_id' => $dossierep['Regressionorientationep58']['Decisionregressionorientationep58'][0]['structurereferente_id'],
						'date_propo' => $dossierep['Regressionorientationep58']['datedemande'],
						'date_valid' => $dateseance,
						'statut_orient' => 'Orienté',
						'referent_id' => $dossierep['Regressionorientationep58']['Decisionregressionorientationep58'][0]['referent_id'],
						'etatorient' => 'decision',
						'rgorient' => $this->Structurereferente->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ),
						'user_id' => $dossierep['Regressionorientationep58']['user_id']
					)
				);

				$success = $this->Structurereferente->Orientstruct->save( $orientstruct ) && $success;
				$success = $this->Structurereferente->Orientstruct->generatePdf( $this->Structurereferente->Orientstruct->id, $dossierep['Regressionorientationep58']['user_id'] ) && $success;
			}

			return $success;
		}

	}

?>