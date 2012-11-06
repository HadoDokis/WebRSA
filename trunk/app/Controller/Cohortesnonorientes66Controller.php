<?php
	/**
	 * Code source de la classe Cohortesnonorientes66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortesnonorientes66Controller permet de traiter les cohortes d'orientation au CG 66 et les
	 * impressions liées.
	 *
	 * @package app.Controller
	 */
	class Cohortesnonorientes66Controller extends AppController
	{
		public $name = 'Cohortesnonorientes66';

		public $uses = array(
			'Cohortenonoriente66',
			'Personne',
			'Zonegeographique',
			'Dossier',
			'Option',
			'Canton'
		);

		public $helpers = array( 'Csv', 'Default2', 'Search', 'Gestionanomaliebdd' );

		public $components = array(
			'Cohortes' => array(
				'isemploi',
				'notisemploi',
			),
			'Gestionzonesgeos',
			'Search.Prg' => array(
				'actions' => array(
					'isemploi' => array( 'filter' => 'Search' ),
					'notisemploi' => array( 'filter' => 'Search' ),
					'notisemploiaimprimer' => array( 'filter' => 'Search' ),
					'notifaenvoyer' => array( 'filter' => 'Search' ),
					'oriente' => array( 'filter' => 'Search' )
				)
			)
		);

		/**
		 * Méthode commune d'envoi des options dans les vues.
		 *
		 * @return void
		 */
		public function _setOptions() {
			$this->set( 'options',  $this->Personne->allEnumLists() );
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );


			$this->set( 'qual',  $this->Option->qual() );

			$etats = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $etats ) );

			$this->set( 'users', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'order' => array( 'User.nom ASC' )
					)
				)
			);

			// Population du select de type d'orientation
			$conditionsTypeorient = array();
			if( $this->action == 'isemploi' ) {
				$typeorient_id = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
				if( is_array( $typeorient_id ) && isset( $typeorient_id[0] ) ){
					$conditionsTypeorient['Typeorient.parentid'] = $typeorient_id;
				}
			}
			else if( $this->action == 'notisemploi' ) {
				$typeorient_id = Configure::read( 'Nonoriente66.notisemploi.typeorientId' );
				if( is_array( $typeorient_id ) && isset( $typeorient_id[0] ) ){
					$conditionsTypeorient['Typeorient.id'] = $typeorient_id;
				}
			}

			$typesOrients = $this->Personne->Orientstruct->Typeorient->listOptions( $conditionsTypeorient );
			$this->set( 'typesOrient', $typesOrients );

			// Population du select des structures référentes
			$this->set( 'structuresReferentes', $this->Personne->Orientstruct->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );

			$Historiqueetatpe = ClassRegistry::init( 'Historiqueetatpe' );
			$this->set( 'historiqueetatpe', $Historiqueetatpe->allEnumLists() );

			$User = ClassRegistry::init( 'User' );
			$this->set( 'gestionnaire', $User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			$optionsOrientstruct = $this->Personne->Orientstruct->allEnumLists();
			$this->set( compact( 'optionsOrientstruct') );

			$this->set( 'options', $this->Personne->Orientstruct->Nonoriente66->allEnumLists() );
		}

		/**
		 * Cohortes d'orientation des allocataires non orientés, inscrits à Pôle Emploi.
		 *
		 * @return void
		 */
		public function isemploi() {
			$this->_index( 'Nonoriente::isemploi' );
		}

		/**
		 * Cohortes d'orientation des allocataires non orientés, non inscrits à Pôle Emploi.
		 *
		 * @return void
		 */
		public function notisemploi() {
			$this->_index( 'Nonoriente::notisemploi' );
		}

		/**
		 * Cohorte d'impression de convocations pour l'orientation des allocataires non inscrits à Pôle Emploi.
		 *
		 * @return void
		 */
		public function notisemploiaimprimer() {
			$this->_index( 'Nonoriente::notisemploiaimprimer' );
		}

		/**
		 * @return void
		 */
		public function notifaenvoyer() {
			$this->_index( 'Nonoriente::notifaenvoyer' );
		}

		/**
		 * @return void
		 */
		public function oriente() {
			$this->_index( 'Nonoriente::oriente' );
		}


		/**
		 * @return void
		 */
		protected function _index( $statutNonoriente = null ) {
			$this->assert( !empty( $statutNonoriente ), 'invalidParameter' );

			// Dans ce contexte-ci, Nonoriente66.reponseallocataire est un champ obligatoire.
			$rule = array(
				'rule' => array( 'notEmpty' ),
				'message' => __( 'Validate::notEmpty' ),
				'allowEmpty' => false
			);
			array_unshift( $this->Personne->Nonoriente66->validate['reponseallocataire'], $rule );

			if( !empty( $this->request->data ) ) {
				// On a renvoyé  le formulaire de la cohorte, tentative de sauvegarde
				if( !empty( $this->request->data['Orientstruct'] ) ) {
					$datas = Set::extract( '/Orientstruct[atraiter=1]', $this->request->data );
					$dossiers_ids = array_unique( Set::extract( '/Orientstruct/dossier_id', $datas ) );
					$this->Cohortes->get( $dossiers_ids );
					$this->Personne->Orientstruct->begin();

					// Tentative de validation
					$validate = true;
					if( in_array( $this->action, array( 'isemploi', 'notisemploi' ) ) ) {
						$validate = $this->Personne->Nonoriente66->saveAll( $this->request->data['Nonoriente66'], array( 'validate' => 'only', 'atomic' => false ) ) && $validate;
					}
					$validate = $this->Personne->Orientstruct->saveAll( $this->request->data['Orientstruct'], array( 'validate' => 'only', 'atomic' => false ) ) && $validate;

					// Tentative de sauvegarde si la validation s'est bien passée
					if( $validate ) {
						$success = true;
						if( in_array( $this->action, array( 'isemploi', 'notisemploi' ) ) ) {
							$success = $this->Personne->Nonoriente66->saveAll( $this->request->data['Nonoriente66'], array( 'validate' => 'first', 'atomic' => false ) ) && $success;
						}
						$success = $this->Personne->Orientstruct->saveAll( $this->request->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) ) && $success;
					}
					else {
						$success = false;
					}

					if( $success ) {
						$this->Personne->Orientstruct->commit();
						$this->Cohortes->release( $dossiers_ids );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						unset( $this->request->data['Orientstruct'], $this->request->data['Nonoriente66'] );

						if( isset( $this->request->data['sessionKey'] ) ) {
							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->request->data['sessionKey']}" );
						}
					}
					else {
						$this->Personne->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}

				// Filtrage
				if( ( $statutNonoriente == 'Nonoriente::isemploi' ) || ( $statutNonoriente == 'Nonoriente::notisemploi' ) || ( $statutNonoriente == 'Nonoriente::notisemploiaimprimer' ) || ( $statutNonoriente == 'Nonoriente::notifaenvoyer' ) || ( $statutNonoriente == 'Nonoriente::oriente' )  && !empty( $this->request->data ) ) {
					$limit = 10;
					if( $statutNonoriente == 'Nonoriente::notisemploiaimprimer' ){
						$limit = 100;
					}
					else if ( $statutNonoriente == 'Nonoriente::notisemploi' ) {
						$TypeorientIdPrepro = Configure::read( 'Nonoriente66.TypeorientIdPrepro' );
						$TypeorientIdSocial = Configure::read( 'Nonoriente66.TypeorientIdSocial' );
						$this->set( 'TypeorientIdPrepro', $TypeorientIdPrepro );
						$this->set( 'TypeorientIdSocial', $TypeorientIdSocial );

						$this->set( 'structuresAutomatiques', $this->Cohortenonoriente66->structuresAutomatiques() );
					}

					$progressivePaginate = !Set::classicExtract( $this->request->data, 'Search.paginationNombreTotal' );

					$paginate = $this->Cohortenonoriente66->search(
						$statutNonoriente,
						(array)$this->Session->read( 'Auth.Zonegeographique' ),
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$this->request->data,
						( in_array( $this->action, array( 'isemploi', 'notisemploi'  ) ) ? $this->Cohortes->sqLocked( 'Dossier' ) : null )
					);
					$paginate['limit'] = $limit;

					$this->paginate = $paginate;
					$cohortesnonorientes66 = $this->paginate( 'Personne', array(), array(), $progressivePaginate );

					if( in_array( $this->action, array( 'isemploi', 'notisemploi'  ) ) ) {
						$this->Cohortes->get( array_unique( Set::extract( '/Dossier/id', $cohortesnonorientes66 ) ) );
					}

					//Pour le lien filelink, sauvegarde de l'URL de la recherche lorsqu'on cliquera sur le bouton "Retour" dans la liste des fichiers liés
					$this->Session->write( "Savedfilters.Nonorientes66.filelink",
						Set::merge(
							array(
								'controller' => Inflector::underscore( $this->name ),
								'action' => $this->action
							),
							$this->request->params['named']
						)
					);

					$this->set( 'cohortesnonorientes66', $cohortesnonorientes66 );

				}

			}

			$this->_setOptions();
			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );


			switch( $statutNonoriente ) {
				case 'Nonoriente::isemploi':
					$this->render( 'isemploi' );
					break;
				case 'Nonoriente::notisemploi':
					$this->render( 'notisemploi' );
					break;
				case 'Nonoriente::notisemploiaimprimer':
					$this->render( 'notisemploiaimprimer' );
					break;
				case 'Nonoriente::notifaenvoyer':
					$this->render( 'notifaenvoyer' );
					break;
				case 'Nonoriente::oriente':
					$this->render( 'oriente' );
					break;
			}
		}


		/**
		* Impression d'un rendez-vous.
		*
		* @param integer $rdv_id
		* @return void
		*/
		public function impression( $id = null ) {
			$pdf = $this->Cohortenonoriente66->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'nonorientation-%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * @return void
		 */
		public function impressions() {
			// La page sur laquelle nous sommes
			$page = Set::classicExtract( $this->request->params, 'named.page' );
			if( ( intval( $page ) != $page ) || $page < 0 ) {
				$page = 1;
			}

			$pdf = $this->Cohortenonoriente66->getDefaultCohortePdf(
				'Nonoriente::notisemploiaimprimer',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$this->Session->read( 'Auth.User.id' ),
				XSet::bump( $this->request->params['named'] ),
				$page
			);

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'nonorientes-%s.pdf', date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * @param integer $id
		 * @return void
		 */
		public function impressionOrientation( $id = null ) {
			$pdf = $this->Personne->Orientstruct->getPdfNonoriente66( $id );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'orientation-%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * @return void
		 */
		public function impressionsOrientation() {
			// La page sur laquelle nous sommes
			$page = Set::classicExtract( $this->request->params, 'named.page' );
			if( ( intval( $page ) != $page ) || $page < 0 ) {
				$page = 1;
			}

			$pdfs = $this->Cohortenonoriente66->getCohortePdfNonoriente66(
				'Nonoriente::notifaenvoyer',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				XSet::bump( $this->request->params['named'], '__' ),
				$page
			);


			if( !empty( $pdfs ) ) {
				$pdf = $this->Gedooo->concatPdfs( $pdfs, 'Nonoriente66' );
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'orientations-%s.pdf', date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Export des résultats sous forme de tableau CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$querydata = $this->Cohortenonoriente66->search(
				'Nonoriente::oriente',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->request->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$nonorientes66 = $this->Personne->find( 'all', $querydata );

			$structures = $this->Cohortenonoriente66->structuresAutomatiques();
			$cantonLie = array();
			foreach( $nonorientes66 as $i => $nonoriente66 ) {
				$typeorient_id = Set::classicExtract( $nonoriente66, 'Typeorient.id' );
				$libelleCanton = Set::classicExtract( $nonoriente66, 'Canton.canton' );
				$cantonLie['Canton']['structureliee'] = !empty( $structures[$libelleCanton][$typeorient_id] ) ? $structures[$libelleCanton][$typeorient_id] : null;
				$nonorientes66[$i]['Canton']['structureliee'] = $cantonLie['Canton']['structureliee'];
			}

			$listestructures = $this->Personne->Orientstruct->Structurereferente->list1Options( array( 'Structurereferente.actif' => 'O' ) );
			$this->set( compact( 'listestructures' ) );

			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'nonorientes66' ) );
		}
	}
?>