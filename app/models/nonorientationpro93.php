<?php
    App::import( 'Model', array( 'Nonorientationpro' ) );

	class Nonorientationpro93 extends Nonorientationpro {

		public $useTable = 'nonorientationspros93';

		public $hasMany = array(
			'Decisionnonorientationpro93' => array(
				'className' => 'Decisionnonorientationpro93',
				'foreignKey' => 'nonorientationpro93_id',
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

		public function finaliser( $seanceep_id, $etape, $user_id ) {
			$seanceep = $this->Dossierep->Seanceep->find(
				'first',
				array(
					'conditions' => array( 'Seanceep.id' => $seanceep_id ),
					'contain' => array( 'Ep' )
				)
			);

			$niveauDecisionFinale = $seanceep['Ep'][Inflector::underscore( $this->alias )];

			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id,
						'Dossierep.themeep' => Inflector::tableize( $this->alias )
					),
					'contain' => array(
						'Decisionnonorientationpro93' => array(
							'conditions' => array(
								'Decisionnonorientationpro93.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);

			$success = true;

			if( $niveauDecisionFinale == $etape ) {
				foreach( $dossierseps as $dossierep ) {
					if( !isset( $dossierep['Decisionnonorientationpro93'][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep['Decisionnonorientationpro93'][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep['Nonorientationpro93']['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $seanceep['Seanceep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep['Decisionnonorientationpro93'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Decisionnonorientationpro93'][0]['structurereferente_id'],
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $this->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ) + 1,
								'etatorient' => 'decision',
								'user_id' => $dossierep['Nonorientationpro93']['user_id']
							)
						);

						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;
						$success = $this->Orientstruct->generatePdf( $this->Orientstruct->id, $dossierep['Nonorientationpro93']['user_id'] ) && $success;
			}
				}
			}

			return $success;
		}

	}
?>