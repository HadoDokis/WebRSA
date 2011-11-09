<?php
    App::import( 'Model', array( 'Nonorientationproep' ) );

	class Nonorientationproep58 extends Nonorientationproep {

		public $useTable = 'nonorientationsproseps58';

		public $belongsTo = array(
			'Decisionpropononorientationprocov58' => array(
				'className' => 'Decisionpropononorientationprocov58',
				'foreignKey' => 'decisionpropononorientationprocov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		/**
		*
		*/

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$commissionep = $this->Dossierep->Passagecommissionep->Commissionep->find(
				'first',
				array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array(
						'Ep' => array(
							'Regroupementep'
						)
					)
				)
			);

			$niveauDecisionFinale = $commissionep['Ep']['Regroupementep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
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
						'Dossierep.themeep' => Inflector::tableize( $this->alias )
					),
					'contain' => array(
						'Dossierep' => array(
							'Passagecommissionep' => array(
								'conditions' => array(
									'Passagecommissionep.commissionep_id' => $commissionep_id
								),
								'Decisionnonorientationproep58' => array(
									'conditions' => array(
										'Decisionnonorientationproep58.etape' => $etape
									)
								)
							)
						)
					)
				)
			);

			$success = true;

			if( $niveauDecisionFinale == "decision{$etape}" ) {
				foreach( $dossierseps as $dossierep ) {
					if( !isset( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep58'][0]['decision'] ) || empty( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep58'][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep58'][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep['Nonorientationproep58']['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep58'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep58'][0]['structurereferente_id'],
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $this->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ) + 1,
								'etatorient' => 'decision',
								'user_id' => $dossierep['Nonorientationproep58']['user_id']
							)
						);
						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;
						$success = $this->Orientstruct->generatePdf( $this->Orientstruct->id, $dossierep['Nonorientationproep58']['user_id'] ) && $success;
					}
				}
			}

			return $success;
		}

		/**
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		*/
		public function qdListeDossier( $commissionep_id = null ) {
			$querydata = parent::qdListeDossier( $commissionep_id );

				$joins = array(
					$this->Dossierep->Nonorientationproep58->join( 'Decisionpropononorientationprocov58' ),
					$this->Dossierep->Nonorientationproep58->Decisionpropononorientationprocov58->join( 'Passagecov58' ),
					$this->Dossierep->Nonorientationproep58->Decisionpropononorientationprocov58->Passagecov58->join( 'Cov58' )
				);

				$querydata['joins'] = array_merge( $querydata['joins'], $joins );
// 				$querydata['fields'] = array_merge( $querydata['fields'], array_merge(
// 					$this->Dossierep->Nonorientationproep58->Decisionpropononorientationprocov58->fields(  ),
// 					$this->Dossierep->Nonorientationproep58->Decisionpropononorientationprocov58->Passagecov58->fields(),
// 					$this->Dossierep->Nonorientationproep58->Decisionpropononorientationprocov58->Passagecov58->Cov58->fields()
// 				) );
				$querydata['fields'][] = 'Cov58.datecommission';


			return $querydata;
		}
	}
?>