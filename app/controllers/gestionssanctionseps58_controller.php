<?php
	/**
	* Gestion des sanctions émises par une EP pour le cG58
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Gestionssanctionseps58Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Csv', 'Ajax', 'Search' );
		public $uses = array( 'Gestionsanctionep58', 'Personne', 'Commissionep', 'Option', 'Dossier', 'Zonegeographique' );
		public $components = array(
			'Prg2' => array( 'actions' => array( 'traitement' => array( 'filter' => 'Search' ), 'visualisation' ) ),
			'Gedooo.Gedooo'
		);

		/**
		 *
		 *
		 */
		protected function _setOptions() {
		
			$etats = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $etats ) );
			
			$options = Set::merge(
				$this->Commissionep->Passagecommissionep->Dossierep->enums(),
				$this->Commissionep->enums(),
				$this->Commissionep->CommissionepMembreep->enums(),
				$this->Commissionep->Passagecommissionep->enums(),
				array( 'Foyer' => array( 'sitfam' => $this->Option->sitfam() ) )
			);


			$options['Ep']['regroupementep_id'] = $this->Commissionep->Ep->Regroupementep->find( 'list' );

			// Ajout des enums pour les thématiques du CG uniquement
			$options['Dossierep']['themeep'] = $this->Gestionsanctionep58->themes();
			foreach( $this->Gestionsanctionep58->themes() as $theme => $intitule ) {
				$theme = Inflector::singularize( $theme );
				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Commissionep->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Commissionep->Passagecommissionep->{$modeleDecision}->enums() );
				}
			}
			
			$this->set( 'listesanctionseps58', $this->Commissionep->Passagecommissionep->Decisionsanctionep58->Listesanctionep58->find( 'list' ) );
			$regularisationlistesanctionseps58 = Set::merge(
				$this->Commissionep->Passagecommissionep->Decisionsanctionep58->enums(),
				$this->Commissionep->Passagecommissionep->Decisionsanctionrendezvousep58->enums()
			);
			$this->set( compact( 'regularisationlistesanctionseps58' ) );
			$this->set( 'typesrdv', $this->Commissionep->Passagecommissionep->Dossierep->Sanctionrendezvousep58->Rendezvous->Typerdv->find( 'list' ) );

			$this->set( compact( 'options' ) );
			$this->set( compact( 'typesorients' ) );
			$this->set( compact( 'structuresreferentes' ) );
			$this->set( compact( 'referents' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );
		}
		
		/**
		*
		*/
		public function traitement() {
			$this->_index( 'Gestion::traitement' );
		}
		
		/**
		*
		*/

		public function visualisation() {
			$this->_index( 'Gestion::visualisation' );
		}
		

		protected function _index( $statutSanctionep = null ) {
			$this->assert( !empty( $statutSanctionep ), 'invalidParameter' );
			
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
			
			
			if( !empty( $this->data ) ) {
			
				foreach( $this->Gestionsanctionep58->themes() as $theme => $intitule ) {
					$modelTheme = Inflector::singularize( $theme );
					$decisionModelTheme = 'Decision'.$modelTheme;

					if( !empty( $this->data[$decisionModelTheme] ) ) {
						$success = true;

						$success = $this->Personne->Dossierep->Passagecommissionep->{$decisionModelTheme}->saveAll( $this->data[$decisionModelTheme], array( 'validate' => 'first', 'atomic' => false ) ) && $success;

						if( $success ) {
							$this->Personne->Dossierep->Passagecommissionep->{$decisionModelTheme}->commit();
							$this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
							unset( $this->data[$decisionModelTheme] );
							if( isset( $this->data['sessionKey'] ) ) {
								$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
							}
						}
						else {
							$this->Personne->Dossierep->Passagecommissionep->{$decisionModelTheme}->rollback();
							$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
						}
					}
				}
				$this->Dossier->begin(); // Pour les jetons
				
				$limit = 10;
				$this->paginate = $this->Gestionsanctionep58->search( $statutSanctionep, $this->data['Search'], $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->Jetons->ids() );
				$this->paginate['limit'] = $limit;
				$gestionsanctionseps58 = $this->paginate( 'Personne' );
				
				foreach( $this->Gestionsanctionep58->themes() as $theme => $intitule ) {
					$modelTheme = Inflector::singularize( $theme );
					$decisionModelTheme = 'Decision'.$modelTheme;
// 					debug( Set::extract( $gestionsanctionseps58, "/{$decisionModelTheme}" ) );
					$this->data[$decisionModelTheme] = Set::classicExtract( $gestionsanctionseps58, "{n}.{$decisionModelTheme}" );
				}
// 				$this->data = $gestionsanctionseps58;

				$this->Dossier->commit();

				$this->set( 'gestionsanctionseps58', $gestionsanctionseps58 );
			}
			else {
				if( $this->action == 'traitement' ) {
					$this->data['Search']['Decision']['sanction'] = 'N';
				}
			}

			
			$this->_setOptions();
			
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			$compteurs = array( 'Ep' => $this->Commissionep->Ep->find( 'count' ) );
			$this->set( compact( 'compteurs' ) );
			
			switch( $statutSanctionep ) {
				case 'Gestion::traitement':
					$this->render( $this->action, null, 'traitement' );
					break;
				case 'Gestion::visualisation':
					$this->render( $this->action, null, 'visualisation' );
					break;
			}
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$queryData = $this->Gestionsanctionep58->search( 'Gestion::visualisation', array_multisize( $this->params['named'] ), $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), null );
			unset( $queryData['limit'] );

			$gestionssanctionseps58 = $this->Personne->find( 'all', $queryData );
			$this->_setOptions();

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'gestionssanctionseps58' ) );

		}
		
		/**
		* Impression du courrier de fin de sanction 1.
		*
		* @param integer $personne_id
		* @return void
		*/
		public function impressionSanction( $niveauSanction, $passagecommissionep_id, $themeep) {
			$pdf = $this->Gestionsanctionep58->getPdfSanction( $niveauSanction, $passagecommissionep_id, $themeep, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'impressionSanction-%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
		
	}
?>