<?php
	class Cohortesvalidationapres66Controller extends AppController
	{
		public $name = 'Cohortesvalidationapres66';

		public $uses = array(
			'Cohortevalidationapre66',
			'Apre',
			'Apre66',
			'Aideapre66',
			'Zonegeographique',
			'Dossier',
			'Option',
			'Canton'
		);

		public $helpers = array( 'Csv', 'Ajax', 'Default2', 'Locale' );

// 		public $components = array( 'Prg' => array( 'actions' => array( 'apresavalider', 'validees' ) ) );
		public $components = array(
			'Prg' => array(
				'actions' => array(
					'apresavalider' => array(
						'filter' => 'Search'
					),
					'validees' => array(
						'filter' => 'Search'
					),
					'notifiees' => array(
						'filter' => 'Search'
					),
					'transfert' => array(
						'filter' => 'Search'
					),
					'traitement' => array(
						'filter' => 'Search'
					)
				)
			)
		);

//         public $paginate = array( 'limit' => 20 );


		/**
		*
		*/
		public function _setOptions() {
			$this->set( 'options',  $this->Apre66->allEnumLists() );

			$this->set( 'qual',  $this->Option->qual() );
			$this->set( 'optionsaideapre66',  $this->Aideapre66->allEnumLists() );
			$this->set( 'referents',  $this->Apre->Referent->find( 'list' ) );
			$this->set( 'themes', $this->Apre66->Aideapre66->Themeapre66->find( 'list' ) );
			$this->set( 'typesaides', $this->Apre66->Aideapre66->Typeaideapre66->listOptions() );
		}



		/**
		*
		*/

		public function apresavalider() {
			$this->_index( 'Validationapre::apresavalider' );
		}

		/**
		*
		*/

		public function validees() {
			$this->_index( 'Validationapre::validees' );
		}

		/**
		*
		*/

		public function notifiees() {
			$this->_index( 'Validationapre::notifiees' );
		}

		/**
		*
		*/

		public function transfert() {
			$this->_index( 'Validationapre::transfert' );
		}

		/**
		*
		*/

		public function traitement() {
			$this->_index( 'Validationapre::traitementcellule' );
		}

		/**
		*
		*/

		protected function _index( $statutValidation = null ) {
			$this->assert( !empty( $statutValidation ), 'invalidParameter' );

			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
// debug( $this->data );
				///Sauvegarde
				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Aideapre66'] ) ) {

					// Ajout des règles de validation
					$this->Apre66->Aideapre66->validationDecisionAllowEmpty( false );

					$valid = $this->Apre66->Aideapre66->saveAll( $this->data['Aideapre66'], array( 'validate' => 'only', 'atomic' => false ) );

					if( $valid ) {
						$this->Aideapre66->begin();
						$saved = $this->Apre66->Aideapre66->saveAll( $this->data['Aideapre66'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							// FIXME ?
							foreach( array_unique( Set::extract( $this->data, 'Aideapre66.{n}.dossier_id' ) ) as $dossier_id ) {
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
							$this->Aideapre66->commit();
							unset( $this->data['Aideapre66'] );
							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
						}
						else {
							$this->Aideapre66->rollback();
						}
					}
				}
				else if( in_array( $this->action, array( 'traitement', 'transfert' ) ) && !empty( $this->data['Apre66'] ) ){

					$valid = $this->Apre66->saveAll( $this->data['Apre66'], array( 'validate' => 'only', 'atomic' => false ) );

					if( $valid ) {
						$this->Apre66->begin();
						$saved = $this->Apre66->saveAll( $this->data['Apre66'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							// FIXME ?
							foreach( array_unique( Set::extract( $this->data, 'Apre66.{n}.dossier_id' ) ) as $dossier_id ) {
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
							$this->Apre66->commit();
							unset( $this->data['Apre66'] );
							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
						}
						else {
							$this->Apre66->rollback();
						}
					}
				}

				///Filtrage

				if( ( $statutValidation == 'Validationapre::apresavalider' ) || ( $statutValidation == 'Validationapre::traitementcellule' ) || ( $statutValidation == 'Validationapre::notifiees' ) || ( $statutValidation == 'Validationapre::transfert' ) || ( $statutValidation == 'Validationapre::validees' ) && !empty( $this->data ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$paginate = $this->Cohortevalidationapre66->search( $statutValidation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$paginate['limit'] = 10;

					$forceVirtualFields = $this->Apre66->forceVirtualFields;
					$this->Apre66->forceVirtualFields = true;
					$this->paginate = $paginate;
					$cohortevalidationapre66 = $this->paginate( 'Apre66' );
					$this->Apre66->forceVirtualFields = $forceVirtualFields;

					//Pour le lien filelink, sauvegarde de l'URL de la recherche lorsqu'on cliquera sur le bouton "Retour" dans la liste des fichiers liés
					$this->Session->write( "Savedfilters.Apres66.filelink",
						Set::merge(
							array(
								'controller' => Inflector::underscore( $this->name ),
								'action' => $this->action
							),
							$this->params['named']
						)
					);

					$this->Dossier->commit();
					foreach( $cohortevalidationapre66 as $key => $value ) {
// debug($value);
						if( empty( $value['Aideapre66']['datemontantaccorde'] ) ) {
							$cohortevalidationapre66[$key]['Aideapre66']['proposition_datemontantaccorde'] = date( 'Y-m-d' );
						}
						else{
							$cohortevalidationapre66[$key]['Aideapre66']['proposition_datemontantaccorde'] = $value['Aideapre66']['datemontantaccorde'];
						}

						$cohortevalidationapre66[$key]['Aideapre66']['datemontantpropose'] = $value['Aideapre66']['datemontantpropose'];

					}
					$this->set( 'cohortevalidationapre66', $cohortevalidationapre66 );

				}

			}

			$this->_setOptions();
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}


			switch( $statutValidation ) {
				case 'Validationapre::apresavalider':
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Validationapre::validees':
					$this->render( $this->action, null, 'visualisation' );
					break;
				case 'Validationapre::notifiees':
					$this->render( $this->action, null, 'notifiees' );
					break;
				case 'Validationapre::transfert':
					$this->render( $this->action, null, 'transfert' );
					break;
				case 'Validationapre::traitementcellule':
					$this->render( $this->action, null, 'traitement' );
					break;
			}
		}

		/**
		 * Export des résultats sous forme de tableau CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Cohortevalidationapre66->search(
				'Validationapre::notifiees',
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' ),
				false
			);
			unset( $querydata['limit'] );
			$apres = $this->Apre66->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'apres' ) );
		}

		/**
		 * Génération de la cohorte des notification d'APRE pour le CG 66.
		 *
		 * @return void
		 */
		public function notificationsCohorte( $statutValidation ) {
			$this->Apre66->begin();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Cohortevalidationapre66->search(
				"Validationapre::{$statutValidation}",
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' ),
				$this->Jetons->ids()
			);
			unset( $querydata['limit'] );
			$apres = $this->Apre66->find( 'all', $querydata );

			$pdfs = array();
			foreach( Set::extract( '/Apre66/id', $apres ) as $apre_id ) {
				$pdfs[] = $this->Apre66->getNotificationAprePdf( $apre_id );
			}
			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'NotificationsApre' );

			if( $pdfs ) {
				$this->Apre66->commit();
				$this->Gedooo->sendPdfContentToClient( $pdfs, sprintf( 'NotificationsApre-%s.pdf', date( 'Y-m-d' ) ) );
			}
			else {
				$this->Apre66->rollback();
				$this->Session->setFlash( 'Impossible de générer les notifications d\'APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>