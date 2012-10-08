<?php
	/**
	 * INFO: cette thématique n'est actuellement pas utilisée lorsqu'on crée un regroupementep, même si
	 * la thématique figure dans l'enum type_themeep.
	 */
	require_once( ABSTRACTMODELS.'Nonorientationproep.php' );

	class Nonorientationproep66 extends Nonorientationproep
	{
		public $belongsTo = array(
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
								'Decisionnonorientationproep66' => array(
									'conditions' => array(
										'Decisionnonorientationproep66.etape' => $etape
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
					if( !isset( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep66'][0]['decision'] ) || empty( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep66'][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep66'][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep['Nonorientationproep66']['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep66'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep66'][0]['structurereferente_id'],
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $this->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ) + 1,
								'etatorient' => 'decision',
								'user_id' => $dossierep['Nonorientationproep66']['user_id']
							)
						);
						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;
						$success = $this->Orientstruct->generatePdf( $this->Orientstruct->id, $dossierep['Nonorientationproep66']['user_id'] ) && $success;
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
					$this->Dossierep->Nonorientationproep66->join( 'Decisionpropononorientationprocov58' ),
					$this->Dossierep->Nonorientationproep66->Decisionpropononorientationprocov58->join( 'Passagecov58' ),
					$this->Dossierep->Nonorientationproep66->Decisionpropononorientationprocov58->Passagecov58->join( 'Cov58' )
				);

				$querydata['joins'] = array_merge( $querydata['joins'], $joins );



			return $querydata;
		}
	}
?>