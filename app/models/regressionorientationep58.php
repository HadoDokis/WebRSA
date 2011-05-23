<?php
    App::import( 'Model', array( 'Regressionorientationep', 'Gedooo' ) );

	class Regressionorientationep58 extends Regressionorientationep
	{
		public $name = 'Regressionorientationep58';

		public $useTable = 'regressionsorientationseps58';

		/**
		* Finalisation de la décision pour le cg58
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$success = true;

			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
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
						'Dossierep.id IN ( '.
							$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array(
										'passagescommissionseps.dossierep_id'
									),
									'alias' => 'passagescommissionseps',
									'conditions' => array(
										'passagescommissionseps.commissionep_id' => $commissionep_id
									)
								)
							)
						.' )',
						'themeep' => 'regressionsorientationseps58'
					),
					'contain' => array(
						'Regressionorientationep58',
						'Passagecommissionep' => array(
							'conditions' => array(
								'Passagecommissionep.commissionep_id' => $commissionep_id
							),
							'Decisionregressionorientationep58'
						)
					)
				)
			);

			foreach( $dossierseps as $dossierep ) {
				if ( $dossierep['Passagecommissionep'][0]['Decisionregressionorientationep58'][0]['decision'] == 'accepte' ) {
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep['Dossierep']['personne_id'],
							'typeorient_id' => $dossierep['Passagecommissionep'][0]['Decisionregressionorientationep58'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Passagecommissionep'][0]['Decisionregressionorientationep58'][0]['structurereferente_id'],
							'date_propo' => $dossierep['Regressionorientationep58']['datedemande'],
							'date_valid' => $dateseance,
							'statut_orient' => 'Orienté',
							'referent_id' => $dossierep['Passagecommissionep'][0]['Decisionregressionorientationep58'][0]['referent_id'],
							'etatorient' => 'decision',
							'rgorient' => $this->Structurereferente->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ),
							'user_id' => $dossierep['Regressionorientationep58']['user_id']
						)
					);
					$this->Structurereferente->Orientstruct->create( $orientstruct );
					$success = $this->Structurereferente->Orientstruct->save() && $success;
					$success = $this->Structurereferente->Orientstruct->generatePdf( $this->Structurereferente->Orientstruct->id, $dossierep['Regressionorientationep58']['user_id'] ) && $success;
				}
			}
			return $success;
		}

	}

?>