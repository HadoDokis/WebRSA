<?php
    App::import( 'Model', array( 'Regressionorientationep', 'Gedooo' ) );

	class Regressionorientationep93 extends Regressionorientationep
	{
		public $name = 'Regressionorientationep93';

		public $useTable = 'regressionsorientationseps93';

		/**
		* Finalisation de la décision pour le cg93
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$success = true;

			$commissionep = $this->Dossierep->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					),
					'contain' => false
				)
			);
			list( $dateseance, $heureseance ) = explode ( ' ', $commissionep['Commissionep']['dateseance'] );

			$dossierseps = $this->Dossierep->find(
				'all',
				array(
					'conditions' => array(
						'commissionep_id' => $commissionep_id,
						'themeep' => 'regressionsorientationseps93'
					),
					'contain' => array(
						'Regressionorientationep93' => array(
							'Decisionregressionorientationep93'
						)
					)
				)
			);
			foreach( $dossierseps as $dossierep ) {
				$orientstruct = array(
					'Orientstruct' => array(
						'personne_id' => $dossierep['Dossierep']['personne_id'],
						'typeorient_id' => $dossierep['Regressionorientationep93']['Decisionregressionorientationep93'][0]['typeorient_id'],
						'structurereferente_id' => $dossierep['Regressionorientationep93']['Decisionregressionorientationep93'][0]['structurereferente_id'],
						'date_propo' => $dossierep['Regressionorientationep93']['datedemande'],
						'date_valid' => $dateseance,
						'statut_orient' => 'Orienté',
						'referent_id' => $dossierep['Regressionorientationep93']['Decisionregressionorientationep93'][0]['referent_id'],
						'etatorient' => 'decision',
						'rgorient' => $this->Structurereferente->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ),
						'user_id' => $dossierep['Regressionorientationep93']['user_id']
					)
				);

				$success = $this->Structurereferente->Orientstruct->save( $orientstruct ) && $success;
				$success = $this->Structurereferente->Orientstruct->generatePdf( $this->Structurereferente->Orientstruct->id, $dossierep['Regressionorientationep93']['user_id'] ) && $success;
			}

			return $success;
		}

	}

?>