<?php
	/**
	 * Fichier source de la classe CohortesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import( 'Sanitize' );
	require_once( APPLIBS.'cmis.php' );

	/**
	 * La classe CohortesController permet de traiter les orientations en cohorte (CG 66 et 93).
	 *
	 * @package app.controllers
	 */
	class CohortesController extends AppController
	{
		public $name = 'Cohortes';

		public $uses = array(
			'Cohorte',
			'Option',
			'Zonegeographique',
			'Personne',
		);

		public $helpers = array( 'Csv', 'Paginator', 'Ajax', 'Default', 'Xpaginator', 'Locale', 'Search' );

		public $components = array(
			'Gedooo.Gedooo',
			'Prg2' => array( 'actions' => 'orientees' ),
			'Cohortes'  => array(
				'nouvelles',
				'enattente'
			),
			'Gestionzonesgeos'
		);

		public $paginate = array( 'limit' => 20 );

		/**
		 *
		 */
		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
			parent::beforeFilter();

			if( in_array( $this->action, array( 'orientees', 'exportcsv' ) ) ) {
				$this->set( 'options', $this->Personne->Orientstruct->enums() );
			}

			$this->set( 'rolepers', $this->Option->rolepers() );

			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa( true ) );

			$etats = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $etats ) );

			$hasDsp = array( 'O' => 'Oui', 'N' => 'Non' );
			$this->set( 'hasDsp', $hasDsp );

			$natpfsSocle = Configure::read( 'Detailcalculdroitrsa.natpf.socle' );
			$this->set( 'natpf', $this->Option->natpf( $natpfsSocle ) );
		}

		/**
		 *
		 */
		public function _setOptions() {
			$typesOrient = $this->Personne->Orientstruct->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient'
					),
					'conditions' => array(
						'Typeorient.actif' => 'O'
					),
					'order' => 'Typeorient.lib_type_orient ASC'
				)
			);
			$this->set( 'typesOrient', $typesOrient );
			$this->set( 'structuresReferentes', $this->Personne->Orientstruct->Structurereferente->list1Options() );
			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'printed', $this->Option->printed() );
			$this->set( 'structuresAutomatiques', $this->Cohorte->structuresAutomatiques() );
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Zonegeographique->Canton->selectList() );
			}

			$this->set(
				'modeles',
				$this->Personne->Orientstruct->Typeorient->find(
					'list',
					array(
						'fields' => array( 'lib_type_orient' ),
						'conditions' => array(
							'Typeorient.parentid IS NULL', 'Typeorient.actif' => 'O' )
					)
				)
			);

			if( in_array( $this->action, array( 'orientees', 'exportcsv', 'statistiques' ) ) ) {
				$this->set( 'options', $this->Personne->Orientstruct->enums() );
			}
			$this->set( 'moticlorsa', $this->Option->moticlorsa() );
		}

		/**
		 *
		 */
		public function nouvelles() {
			$this->Gedooo->check( false, true );
			$this->_index( 'Non orienté' );
		}

		/**
		 *
		 */
		public function orientees() {
			$this->_index( 'Orienté' );
		}

		/**
		 *
		 */
		public function enattente() {
			$this->Gedooo->check( false, true );
			$this->_index( 'En attente' );
		}

		/**
		 *
		 */
		public function preconisationscalculables() {
			$this->Gedooo->check( false, true );
			$this->_index( 'Calculables' );
		}

		/**
		 *
		 */
		public function preconisationsnoncalculables() {
			$this->Gedooo->check( false, true );
			$this->_index( 'Non calculables' );
		}

		/**
		 *
		 * @param string $statutOrientation
		 */
		protected function _index( $statutOrientation = null ) {
			$this->assert( !empty( $statutOrientation ), 'invalidParameter' );

			if( !empty( $this->data ) ) {
				// -----------------------------------------------------------------
				// Formulaire de cohorte
				// -----------------------------------------------------------------
				if( !empty( $this->data['Orientstruct'] ) ) {
					$dossiers_ids = Set::extract(  '/dossier_id', $this->data['Orientstruct'] );
					$this->Cohortes->get( $dossiers_ids );

					// Sauvegarde de l'utilisateur orientant
					foreach( array_keys( $this->data['Orientstruct'] ) as $key ) {
						if( $this->data['Orientstruct'][$key]['statut_orient'] == 'Orienté' ) {
							$this->data['Orientstruct'][$key]['user_id'] = $this->Session->read( 'Auth.User.id' );
							$this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct'][$key]['structurereferente_id'] );
							$this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
						}
						else {
							$this->data['Orientstruct'][$key]['user_id'] = null;
							$this->data['Orientstruct'][$key]['origine'] = null;
							if( $this->data['Orientstruct'][$key]['statut_orient'] == 'Non orienté' ) {
								$this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
							}
						}
					}

					$valid = $this->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only', 'atomic' => false ) );
					$valid = ( count( $this->Personne->Orientstruct->validationErrors ) == 0 );

					if( $valid ) {
						$this->Personne->Foyer->Dossier->begin();
						$saved = $this->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							$this->Personne->Foyer->Dossier->commit();
							$this->Cohortes->release( $dossiers_ids );

							$this->data['Orientstruct'] = array();
						}
						else {
							$this->Personne->Foyer->Dossier->rollback();
							$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
						}
					}
				}

				// Nettoyage, formattage et envoi du filtre à la vue pour en faire des champs cachés du formulaire du bas.
				$tmpFiltre = $this->data;
				unset( $tmpFiltre['Orientstruct'] );
				$filtre = array();
				foreach( $tmpFiltre as $modelName => $modelValues ) {
					if( is_array( $modelValues ) ) {
						foreach( $modelValues as $fieldName => $values ) {
							$filtre["{$modelName}.{$fieldName}"] = $values;
						}
					}
				}
				$this->set( compact( 'filtre' ) );

				// -----------------------------------------------------------------
				// Filtre
				// -----------------------------------------------------------------
				if( isset( $this->data['Filtre'] ) ) {
					$progressivePaginate = !Set::classicExtract( $this->data, 'Filtre.paginationNombreTotal' );

					$filtre = $this->data;
					if( Configure::read( 'Cg.departement' ) == 66 && empty( $filtre['Situationdossierrsa']['etatdosrsa_choice'] ) ) {
						$filtre['Situationdossierrsa']['etatdosrsa'] = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
					}
					unset( $filtre['Filtre']['actif'] );

					$queryData = $this->Cohorte->search(
						$statutOrientation,
						(array)$this->Session->read( 'Auth.Zonegeographique' ),
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$filtre,
						( ( $statutOrientation == 'Orienté' ) ? false : $this->Cohortes->sqLocked() )
					);

					if( $statutOrientation == 'Orienté' ) {
						$queryData['limit'] = 10;
						$this->paginate = array( 'Personne' => $queryData );
						$cohorte = $this->paginate( $this->Personne, array(), array(), $progressivePaginate );

						$this->set( compact( 'cohorte' ) );
					}
					else {
						$queryData['limit'] = 10;

						$this->paginate = array( 'Personne' => $queryData );
						$cohorte = $this->paginate( $this->Personne, array(), array(), $progressivePaginate );

						$dossiers_ids = Set::extract(  '/Dossier/id', $cohorte );
						$this->Cohortes->get( $dossiers_ids );

						$this->set( compact( 'cohorte' ) );
					}
				}
			}
			else {
				// Valeurs par défaut des filtres
				$progressivePaginate = $this->_hasProgressivePagination();
				if( !is_null( $progressivePaginate ) ) {
					$this->data['Filtre']['paginationNombreTotal'] = !$progressivePaginate;
				}
				$filtresdefaut = Configure::read( "Filtresdefaut.{$this->name}_{$this->action}" );
				$this->data = Set::merge( $this->data, $filtresdefaut );
			}

			// Options à passer au formulaire
			$this->set( 'typesOrient', $this->Personne->Orientstruct->Typeorient->listOptionsCohortes93() );
			$this->set( 'structuresReferentes', $this->Personne->Orientstruct->Structurereferente->list1Options() );

			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'printed', $this->Option->printed() );

			$this->set( 'structuresAutomatiques', $this->Cohorte->structuresAutomatiques() );

			// Zones géographiques et cantons
			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			// Préorientations
			$modeles = $this->Personne->Orientstruct->Typeorient->listOptionsPreorientationCohortes93();
			if ( Configure::read( 'Cg.departement' ) == 93 && ( in_array( $this->action, array( 'nouvelles', 'enattente', 'preconisationscalculables' ) ) ) ) {
				$modeles['NOTNULL'] = 'Renseigné';
				$modeles['NULL'] = 'Non renseigné';
			}
			$this->set( 'modeles', $modeles );


			// On n'utilise pas le même layout suivant l'action.
			switch( $statutOrientation ) {
				case 'En attente':
					$this->set( 'pageTitle', 'Demandes en attente de validation d\'orientation' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Non orienté':
					$this->set( 'pageTitle', 'Demandes non orientées' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Calculables':
					$this->set( 'pageTitle', 'Demandes d\'orientation préorientées' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Non calculables':
					$this->set( 'pageTitle', 'Demandes d\'orientation non préorientées' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Orienté':
					$this->set( 'pageTitle', 'Demandes orientées' );
					$this->render( $this->action, null, 'visualisation' );
					break;
			}
		}

		/**
		 *
		 * @param type $personne_id
		 */
		public function cohortegedooo( $personne_id = null ) {
			$queryData = $this->Cohorte->search(
				'Orienté',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' ),
				false
			);

			if( $limit = Configure::read( 'nb_limit_print' ) ) {
				$queryData['limit'] = $limit;
			}

			$queryData['fields'] = array(
				'Orientstruct.id',
				'Pdf.document',
			);

			$queryData['joins'][] = array(
				'table'      => 'pdfs',
				'alias'      => 'Pdf',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Pdf.fk_value = Orientstruct.id',
					'Pdf.modele' => 'Orientstruct',
				)
			);

			if( !empty( $this->params['named']['sort'] ) && !empty( $this->params['named']['direction'] ) ) {
				$queryData['order'] = array( "{$this->params['named']['sort']} {$this->params['named']['direction']}" );
			}

			$queryData['fields'][] = 'Pdf.cmspath';

			$results = $this->Personne->find( 'all', $queryData );

			// Si le contenu du PDF n'est pas dans la table pdfs, aller le chercher sur le serveur CMS
			$nErrors = 0;
			foreach( $results as $i => $result ) {
				if( empty( $result['Pdf']['document'] ) && !empty( $result['Pdf']['cmspath'] ) ) {
					$pdf = Cmis::read( $result['Pdf']['cmspath'], true );
					if( !empty( $pdf['content'] ) ) {
						$results[$i]['Pdf']['document'] = $pdf['content'];
					}
				}
				// Gestion des erreurs: si on n'a toujours pas le document
				if( empty( $results[$i]['Pdf']['document'] ) ) {
					$nErrors++;
					unset( $results[$i] );
				}
			}

			if( $nErrors > 0 ) {
				$this->Session->setFlash( "Erreur lors de l'impression en cohorte: {$nErrors} documents n'ont pas pu être imprimés. Abandon de l'impression de la cohorte. Demandez à votre administrateur d'exécuter cake/console/cake generationpdfs orientsstructs -username <username> (où <username> est l'identifiant de l'utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression)", 'flash/error' );
				$this->redirect( $this->referer() );
			}

			$content = $this->Gedooo->concatPdfs( Set::extract( $results, '/Pdf/document' ), 'orientsstructs' );

			$this->Personne->Foyer->Dossier->begin();
			$success = ( $content !== false ) && $this->Personne->Orientstruct->updateAll(
				array( 'Orientstruct.date_impression' => date( "'Y-m-d'" ) ),
				array(
					'"Orientstruct"."id"' => Set::extract( $results, '/Orientstruct/id' ),
					'"Orientstruct"."date_impression" IS NULL'
				)
			);

			if( $content !== false ) {
				$this->Personne->Foyer->Dossier->commit();
				$this->Gedooo->sendPdfContentToClient( $content, sprintf( "cohorte-orientations-%s.pdf", date( "Ymd-H\hi" ) ) );
				die();
			}
			else {
				$this->Personne->Foyer->Dossier->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'impression en cohorte.', 'flash/error' );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 *
		 */
		public function statistiques() {
			if( !empty( $this->data ) ) {
				$statistiques = $this->Cohorte->statistiques(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->data
				);
			}

			$this->_setOptions();
			$this->set( compact( 'statistiques' ) );
			$this->set( 'pageTitle', 'Statistiques' );
			$this->render( $this->action, null, 'statistiques' );
		}
	}
?>