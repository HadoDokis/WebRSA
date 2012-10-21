<?php
	/**
	 * Code source de la classe Gestionssanctionseps58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Gestionssanctionseps58Controller permet de gérer les sanctions émises par une EP pour le cG58.
	 *
	 * @package app.Controller
	 */
	class Gestionssanctionseps58Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Csv', 'Ajax', 'Search' );

		public $uses = array( 'Gestionsanctionep58', 'Personne', 'Commissionep', 'Option', 'Dossier', 'Zonegeographique' );

		public $components = array(
			'Gedooo.Gedooo',
			'Gestionzonesgeos',
			'Cohortes' => array(
				'traitement'
			),
			'Search.Prg' => array( 'actions' => array( 'traitement' => array( 'filter' => 'Search' ), 'visualisation' ) ),
		);

		/**
		 * Méthode commune d'envoi des options dans les vues.
		 *
		 * @return void
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
		 * Formulaire de traitement des sanctions.
		 *
		 * @return void
		 */
		public function traitement() {
			$this->_index( 'Gestion::traitement' );
		}

		/**
		 * Visualisation des sanctions.
		 *
		 * @return void
		 */
		public function visualisation() {
			$this->_index( 'Gestion::visualisation' );
		}

		/**
		 * Traitement ou visualisation des sanctions.
		 *
		 * @param string $statutSanctionep
		 */
		protected function _index( $statutSanctionep = null ) {
			$this->assert( !empty( $statutSanctionep ), 'invalidParameter' );

			if( !empty( $this->request->data ) ) {
				$data = $this->request->data;
				unset( $data['Search'], $data['sessionKey'] );

				if( count( $data ) > 0 ) {
					$this->Cohortes->get( Set::extract( '/Foyer/dossier_id', $this->request->data ) );

					$success = true;
					$this->Personne->begin();

					foreach( $this->Gestionsanctionep58->themes() as $theme => $intitule ) {
						$modelTheme = Inflector::singularize( $theme );
						$decisionModelTheme = 'Decision'.$modelTheme;

						if( !empty( $this->request->data[$decisionModelTheme] ) ) {
							$success = $this->Personne->Dossierep->Passagecommissionep->{$decisionModelTheme}->saveAll( $this->request->data[$decisionModelTheme], array( 'validate' => 'first', 'atomic' => false ) ) && $success;
						}
					}

					if( $success ) {
						$this->Personne->commit();
						$this->Cohortes->release( Set::extract( '/Foyer/dossier_id', $this->request->data ) );

						$this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
						unset( $this->request->data[$decisionModelTheme] );
						if( isset( $this->request->data['sessionKey'] ) ) {
							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->request->data['sessionKey']}" );
						}
					}
					else {
						$this->Personne->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
					}
				}

				$limit = 10;
				$paginate = $this->Gestionsanctionep58->search(
					$statutSanctionep,
					$this->request->data['Search'],
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					( ( $statutSanctionep == 'Gestion::traitement' ) ? $this->Cohortes->sqLocked( 'Dossier' ) : null )
				);
				$paginate['limit'] = $limit;

				$this->paginate = $paginate;
				$gestionsanctionseps58 = $this->paginate( 'Personne' );

				if( $statutSanctionep == 'Gestion::traitement' ) {
					$this->Cohortes->get( Set::extract( '/Foyer/dossier_id', $gestionsanctionseps58 ) );
				}

				foreach( $this->Gestionsanctionep58->themes() as $theme => $intitule ) {
					$modelTheme = Inflector::singularize( $theme );
					$decisionModelTheme = 'Decision'.$modelTheme;
					$this->request->data[$decisionModelTheme] = Set::classicExtract( $gestionsanctionseps58, "{n}.{$decisionModelTheme}" );
				}

				$this->set( 'gestionsanctionseps58', $gestionsanctionseps58 );
			}
			else {
				if( $this->action == 'traitement' ) {
					$this->request->data['Search']['Decision']['sanction'] = 'N';
				}
			}


			$this->_setOptions();
			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$compteurs = array( 'Ep' => $this->Commissionep->Ep->find( 'count' ) );
			$this->set( compact( 'compteurs' ) );

			switch( $statutSanctionep ) {
				case 'Gestion::traitement':
					$this->render( 'traitement' );
					break;
				case 'Gestion::visualisation':
					$this->render( 'visualisation' );
					break;
			}
		}

		/**
		 * Export du tableau en CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$queryData = $this->Gestionsanctionep58->search(
				'Gestion::visualisation',
				Xset::bump( $this->request->params['named'], '__' ),
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				null
			);
			unset( $queryData['limit'] );

			$gestionssanctionseps58 = $this->Personne->find( 'all', $queryData );
			$this->_setOptions();

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'gestionssanctionseps58' ) );

		}


		/**
			**Fonction d'impression pour le cas des sanctions 1 du CG58
			* @param type $contratinsertion_id
			*
			*/
		public function impressionSanction1 ( $niveauSanction, $passagecommissionep_id, $themeep ) {
			$this->_impressionSanction( '1', $passagecommissionep_id, $themeep );
		}


		public function impressionSanction2 ( $niveauSanction, $passagecommissionep_id, $themeep ) {
			$this->_impressionSanction( '2', $passagecommissionep_id, $themeep );
		}


		/**
		* Impression du courrier de fin de sanction 1.
		*
		* @param integer $personne_id
		* @return void
		*/
		public function _impressionSanction( $niveauSanction, $passagecommissionep_id, $themeep) {
			$pdf = $this->Gestionsanctionep58->getPdfSanction( $niveauSanction, $passagecommissionep_id, $themeep, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'impressionSanction-%s.pdf', date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}


		/**
 		 * Fonction d'impression en cohorte pour le cas des sanctions 1 du CG58
 		 */
		public function impressionsSanctions1() {
			$this->_impressionsSanctions( '1' );
		}

		/**
		 * Fonction d'impression en cohorte pour le cas des sanctions 2 du CG58
		 */
		public function impressionsSanctions2() {
			$this->_impressionsSanctions( '2' );
		}

		/**
		 * @param integer $id L'id de
		 */
		public function _impressionsSanctions( $niveauSanction = null ) {
			// La page sur laquelle nous sommes
			$page = Set::classicExtract( $this->request->params, 'named.page' );
			if( ( intval( $page ) != $page ) || $page < 0 ) {
				$page = 1;
			}

			$pdfs = $this->Gestionsanctionep58->getCohortePdfSanction(
				$niveauSanction,
				'Gestion::visualisation',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				XSet::bump( $this->request->params['named'], '__' ),
				$page,
				$this->Session->read( 'Auth.User.id' )
			);


			if( !empty( $pdfs ) ) {
				$pdf = $this->Gedooo->concatPdfs( $pdfs, 'Gestionsanctionep58' );
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'gestionssanctions-%s.pdf', date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

	}
?>