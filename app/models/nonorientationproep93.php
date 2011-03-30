<?php
    App::import( 'Model', array( 'Nonorientationproep' ) );

	class Nonorientationproep93 extends Nonorientationproep {

		public $useTable = 'nonorientationsproseps93';

		public $hasMany = array(
			'Decisionnonorientationproep93' => array(
				'className' => 'Decisionnonorientationproep93',
				'foreignKey' => 'nonorientationproep93_id',
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

		public function finaliser( $commissionep_id, $etape, $user_id ) {
			$commissionep = $this->Dossierep->Commissionep->find(
				'first',
				array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array( 'Ep' )
				)
			);

			$niveauDecisionFinale = $commissionep['Ep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.commissionep_id' => $commissionep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias )
					),
					'contain' => array(
						'Decisionnonorientationproep93' => array(
							'conditions' => array(
								'Decisionnonorientationproep93.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;

			if( $niveauDecisionFinale == $etape ) {
				foreach( $dossierseps as $dossierep ) {
					if( !isset( $dossierep['Decisionnonorientationproep93'][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep['Decisionnonorientationproep93'][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep['Nonorientationproep93']['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep['Decisionnonorientationproep93'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Decisionnonorientationproep93'][0]['structurereferente_id'],
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