<?php
    App::import( 'Model', array( 'Nonorientationproep' ) );

	class Nonorientationproep93 extends Nonorientationproep {

		public $useTable = 'nonorientationsproseps93';

		/**
		 *
		 */

		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Decision'.Inflector::underscore( $this->alias )][$key]['passagecommissionep_id'] = $dossierep['Passagecommissionep'][0]['id'];
				if( $niveauDecision == 'ep' ) {
					if( isset( $datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0] ) ) { // Modification
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['raisonnonpassage'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['commentaire'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['commentaire'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'].'_'.@$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
					}
				}
				elseif( $niveauDecision == 'cg' ) {
					if( !empty( $datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][1] ) ) { // Modification
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['id'];
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decisionpcg'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decisionpcg'];
					}
					else {
						$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decisionpcg'] = 'valide';
					}
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['decision'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['decision'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['raisonnonpassage'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['raisonnonpassage'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['commentaire'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['commentaire'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['structurereferente_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'].'_'.@$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['structurereferente_id'];
					$formData['Decision'.Inflector::underscore( $this->alias )][$key]['typeorient_id'] = @$datas[$key]['Passagecommissionep'][0]['Decision'.Inflector::underscore( $this->alias )][0]['typeorient_id'];
				}
			}

			return $formData;
		}

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
								'Decisionnonorientationproep93' => array(
									'conditions' => array(
										'Decisionnonorientationproep93.etape' => $etape
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
					if( !isset( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep93'][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep93'][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep['Nonorientationproep93']['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep93'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Dossierep']['Passagecommissionep'][0]['Decisionnonorientationproep93'][0]['structurereferente_id'],
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $this->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ) + 1,
								'etatorient' => 'decision',
								'user_id' => $dossierep['Nonorientationproep93']['user_id']
							)
						);

						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;
						$success = $this->Orientstruct->generatePdf( $this->Orientstruct->id, $dossierep['Nonorientationproep93']['user_id'] ) && $success;
					}
				}
			}

			return $success;
		}

	}
?>