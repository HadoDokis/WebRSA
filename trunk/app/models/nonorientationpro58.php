<?php
    App::import( 'Model', array( 'Nonorientationpro' ) );

	class Nonorientationpro58 extends Nonorientationpro {

		public $hasMany = array(
			'Decisionnonorientationpro58' => array(
				'className' => 'Decisionnonorientationpro58',
				'foreignKey' => 'nonorientationpro58_id',
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

		public function finaliser( $seanceep_id, $etape ) {
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
						'Decisionnonorientationpro58' => array(
							'conditions' => array(
								'Decisionnonorientationpro58.etape' => $etape
							)
						),
						'Dossierep'
					)
				)
			);
			
			$success = true;
			
			if( $niveauDecisionFinale == $etape ) {
				foreach( $dossierseps as $dossierep ) {
					if( !isset( $dossierep['Decisionnonorientationpro58'][0]['decision'] ) ) {
						$success = false;
					}
					elseif ( $dossierep['Decisionnonorientationpro58'][0]['decision'] == 'reorientation' ) {
						list($date_propo, $heure_propo) = explode( ' ', $dossierep['Nonorientationpro58']['created'] );
						list($date_valid, $heure_valid) = explode( ' ', $seanceep['Seanceep']['dateseance'] );
						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $dossierep['Dossierep']['personne_id'],
								'typeorient_id' => @$dossierep['Decisionnonorientationpro58'][0]['typeorient_id'],
								'structurereferente_id' => @$dossierep['Decisionnonorientationpro58'][0]['structurereferente_id'],
								'date_propo' => $date_propo,
								'date_valid' => $date_valid,
								'statut_orient' => 'Orienté',
								'rgorient' => $this->Orientstruct->rgorientMax( $dossierep['Dossierep']['personne_id'] ) + 1,
								'etatorient' => 'decision',
								'user_id' => $dossierep['Nonorientationpro58']['user_id']
							)
						);
						
						$this->Orientstruct->create( $orientstruct );
						$success = $this->Orientstruct->save() && $success;
						$success = $this->Orientstruct->generatePdf( $this->Orientstruct->id, $dossierep['Nonorientationpro58']['user_id'] ) && $success;
			}
				}
			}
			
			return $success;
		}
		
	}
?>