<?php
    App::import( 'Model', array( 'Nonorientationpro' ) );

	class Nonorientationpro58 extends Nonorientationpro {

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
					$nonrespectsanctionep93 = array( 'Nonrespectsanctionep93' => $dossierep['Nonrespectsanctionep93'] );
					$nonrespectsanctionep93['Nonrespectsanctionep93']['active'] = 0;
					if( !isset( $dossierep['Decisionnonrespectsanctionep93'][0]['decision'] ) ) {
						$success = false;
					}

					// Copie de la décision
					$nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['decision'];
					$nonrespectsanctionep93['Nonrespectsanctionep93']['montantreduction'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['montantreduction'];
					$nonrespectsanctionep93['Nonrespectsanctionep93']['dureesursis'] = @$dossierep['Decisionnonrespectsanctionep93'][0]['dureesursis'];

					/*if( $nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] == '1reduction' ) { // FIXME: vient de la dernière décision
						$nonrespectsanctionep93['Nonrespectsanctionep93']['montantreduction'] = Configure::read( 'Nonrespectsanctionep93.montantReduction' );
					}
					else if( $nonrespectsanctionep93['Nonrespectsanctionep93']['decision'] == '1sursis' ) {
						$nonrespectsanctionep93['Nonrespectsanctionep93']['dureesursis'] = Configure::read( 'Nonrespectsanctionep93.dureeSursis' );
					}*/

					$this->create( $nonrespectsanctionep93 ); // TODO: un saveAll ?
					$success = $this->save() && $success;
				}
			}

			return $success;
		}
		
	}
?>