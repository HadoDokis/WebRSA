<?php
	class HistoriquesepsController extends AppController
	{
		public $name = 'Historiqueseps';
		public $uses = array( 'Dossierep', 'Option' );

		public $helpers = array( 'Default2', 'Xpaginator2' );

		/**
		*
		*/

		protected function _setOptions( $modeleTheme = null, $modeleDecision = null ) {
			$options = $this->Dossierep->Passagecommissionep->enums();
			$options['Dossierep']['themeep'] = $this->Dossierep->themesCg();

			if( !empty( $modeleTheme ) && in_array( 'Enumerable', $this->Dossierep->{$modeleTheme}->Behaviors->attached() ) ) {
				$options = Set::merge(
					$options,
					$this->Dossierep->{$modeleTheme}->enums()
				);
			}

			if( !empty( $modeleDecision ) && in_array( 'Enumerable', $this->Dossierep->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
				$options = Set::merge(
					$options,
					$this->Dossierep->Passagecommissionep->{$modeleDecision}->enums()
				);
			}

			$this->set( compact( 'options' ) );
		}

		/**
		* Affiche la liste des passages en commission d'EP pour une personne donnée.
		* Possibilité de filtrer par thématique.
		*/

		public function index( $personne_id = null ){
			$this->assert( valid_int( $personne_id ), 'error404' );

			$queryData = array(
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id
				),
				'contain' => array(
					'Dossierep',
					'Commissionep' => array(
						'Ep'
					),
				),
				'order' => array(
					'Commissionep.dateseance DESC'
				)
			);

			// Moteur de recherche
			if( !empty( $this->data) ) { // FIXME: méthode search dans le modèle Historiqueep (à créer)
				if( !empty( $this->data['Search']['Dossierep']['themeep'] ) ) {
					$queryData['conditions']['Dossierep.themeep'] = $this->data['Search']['Dossierep']['themeep'];
				}
			}

			$this->paginate = array( 'Passagecommissionep' => $queryData );

			$passages = $this->paginate( $this->Dossierep->Passagecommissionep );

			$this->_setOptions();
			$this->set( compact( 'passages' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		* Visualisation des détails du passage d'un dossier d'EP en commission d'EP
		*/

		public function view_passage( $passagecommssionep_id ) {
			$this->assert( valid_int( $passagecommssionep_id ), 'error404' );

			$passage = $this->Dossierep->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array(
						'Passagecommissionep.id' => $passagecommssionep_id
					),
					'contain' => array(
						'Dossierep',
						'Commissionep' => array(
							'Ep'
						),
					)
				)
			);

			$this->assert( !empty( $passage ), 'error404' );

			// TODO: à factoriser avec view_dossier
			$themeSingulier = Inflector::singularize( $passage['Dossierep']['themeep'] );
			$modeleTheme = Inflector::classify( $themeSingulier );
			$modeleDecision = Inflector::classify( "decision{$themeSingulier}" );

			// Thématique
			if( method_exists( $this->Dossierep->{$modeleTheme}, 'containThematique' ) ) {
				$contain = $this->Dossierep->{$modeleTheme}->containThematique();
			}
			else {
				$contain = false;
			}

			$this->Dossierep->{$modeleTheme}->forceVirtualFields = true;
			$donneesTheme = $this->Dossierep->{$modeleTheme}->find(
				'first',
				array(
					'conditions' => array(
						"{$modeleTheme}.dossierep_id" => $passage['Dossierep']['id']
					),
					'contain' => $contain
				)
			);

			// Décision
			if( method_exists( $this->Dossierep->Passagecommissionep->{$modeleDecision}, 'containDecision' ) ) {
				$contain = $this->Dossierep->Passagecommissionep->{$modeleDecision}->containDecision();
			}
			else {
				$contain = false;
			}

			$this->Dossierep->Passagecommissionep->{$modeleDecision}->forceVirtualFields = true;
			$donneesDecision = $this->Dossierep->Passagecommissionep->{$modeleDecision}->find(
				'all',
				array(
					'conditions' => array(
						"{$modeleDecision}.passagecommissionep_id" => $passage['Passagecommissionep']['id']
					),
					'contain' => $contain
				)
			);

			$passage = Set::merge(
				$passage,
				$donneesTheme,
				array( 'Decision' => Set::classicExtract( $donneesDecision, "{n}" ) )
			);

			// Si on
			if( $this->Dossierep->Passagecommissionep->{$modeleDecision}->Behaviors->attached( 'Suivisanctionep58' ) ) {
				$this->set( 'suivisanction58', $this->Dossierep->Passagecommissionep->{$modeleDecision}->suivisanctions58( $passage ) );
			}

			$this->_setOptions( $modeleTheme, $modeleDecision );
			// Fin factorisation

			$this->set( compact( 'modeleTheme', 'modeleDecision', 'passage' ) );
		}
	}
?>