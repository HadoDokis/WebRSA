<?php
	/**
	 * Code source de la classe Covs58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Covs58Controller ...
	 *
	 * @package app.Controller
	 */
	class Covs58Controller extends AppController
	{
		public $name = 'Covs58';
		public $uses = array( 'Cov58', 'Option' );
		public $helpers = array( 'Default', 'Default2' );
		public $components = array( 'Search.Prg' => array( 'actions' => array( 'index' ) ), 'Gedooo.Gedooo' );

		public $commeDroit = array(
			'add' => 'Covs58:edit',
			'view' => 'Covs58:index'
		);
		public $etatsActions = array(
			'cree' => array(
				'dossierseps::choose',
				'covs58::edit',
				'covs58::delete'
			),
			'associe' => array(
				'dossierscovs58::choose',
				'covs58::printConvocationBeneficiaire',
				'covs58::printConvocationsBeneficiaires',
				'covs58::printOrdreDuJour',
				'covs58::edit',
				'covs58::delete',
			),
			'traite' => array(
				'covs58::printOrdreDuJour',
				'covs58::impressionpv',
				'covs58::printDecision',
				'covs58::printConvocationBeneficiaire',
				'covs58::printConvocationsBeneficiaires',
			),
			'finalise' => array(
				'covs58::printOrdreDuJour',
				'covs58::impressionpv',
				'covs58::printDecision',
				'covs58::printConvocationBeneficiaire',
				'covs58::printConvocationsBeneficiaires',
			),
			'valide' => array(
				'covs58::ordredujour',
				'covs58::printConvocationBeneficiaire',
				'covs58::printConvocationsBeneficiaires',
				'covs58::printOrdreDuJour',
				'covs58::delete',
			),
			'annule' => array()
		);
		/**
		*
		*/

		public function beforeFilter() {
			return parent::beforeFilter();
		}

		/**
		*
		*/

		protected function _setOptions() {
			$themescovs58 = $this->Cov58->Passagecov58->Dossiercov58->Themecov58->find('list');

			$options = $this->Cov58->enums();
			$options = array_merge( $options, $this->Cov58->Passagecov58->enums() );
			$typevoie = $this->Option->typevoie();
// 			$options = array_merge($options, $this->Cov58->Passagecov58->Dossiercov58->enums());
			$typesorients = $this->Cov58->Passagecov58->Dossiercov58->Propoorientationcov58->Structurereferente->Typeorient->listOptions();
			$structuresreferentes = $this->Cov58->Passagecov58->Dossiercov58->Propoorientationcov58->Structurereferente->list1Options();
			$referents = $this->Cov58->Passagecov58->Dossiercov58->Propoorientationcov58->Structurereferente->Referent->listOptions();
			$sitescovs58 = $this->Cov58->Sitecov58->find( 'list', array( 'fields' => array( 'name' ) ) );

			$referentsorientants = $this->Cov58->Passagecov58->Dossiercov58->Propoorientationcov58->Structurereferente->Referent->find( 'list' );

			$decisionscovs = array( 'accepte' => 'Accepté', 'refus' => 'Refusé', 'ajourne' => 'Ajourné' );
			$this->set(compact('decisionscovs'));
// debug( $this->Cov58->Passagecov58->Dossiercov58->Themecov58->themes() );
			foreach( $this->Cov58->Passagecov58->Dossiercov58->Themecov58->themes() as $theme ) {
// debug($theme);
				$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Cov58->Passagecov58->Dossiercov58->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Cov58->Passagecov58->Dossiercov58->{$model}->enums() );
				}

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Cov58->Passagecov58->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Cov58->Passagecov58->{$modeleDecision}->enums() );
				}
			}
			$this->set( 'duree_engag', $this->Option->duree_engag_cg58() );
			$this->set(compact('options', 'typesorients', 'structuresreferentes', 'referents', 'typevoie', 'sitescovs58', 'referentsorientants' ));

		}

		/**
		*
		*/

		public function index() {
			if( !empty( $this->request->data ) ) {
				$queryData = $this->Cov58->search( $this->request->data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;
				$covs58 = $this->paginate( $this->Cov58 );
				$this->set( 'covs58', $covs58 );
			}
			$this->_setOptions();
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->request->data ) ) {
				$this->Cov58->begin();
				$this->Cov58->create( $this->request->data );
				$success = $this->Cov58->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Cov58->commit();
					$this->redirect( array( 'action' => 'view', $this->Cov58->id ) );
				}
				else {
					$this->Cov58->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Cov58->find(
					'first',
					array(
						'conditions' => array( 'Cov58.id' => $id ),
						'contain' => false
					)
				);
				$this->assert( !empty( $this->request->data ), 'error404' );
				$this->set('cov58_id', $id);
			}

			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		/**
		*
		*/

		public function view( $cov58_id = null ) {
			$cov58 = $this->Cov58->find(
				'first', array(
					'conditions' => array( 'Cov58.id' => $cov58_id ),
					'contain' => array(
						'Sitecov58'
					)
				)
			);
			$this->set('cov58', $cov58);
			$this->_setOptions();

			// Dossiers à passer en séance, par thème traité
// 			$themes = $this->Cov58->Passagecov58->Dossiercov58->Themecov58->find('list');

			$themes = array_keys( $this->Cov58->themesTraites( $cov58_id ) );

			$this->set(compact('themes'));
			$dossiers = array();
			$countDossiers = 0;

			foreach( $themes as $theme ) {
				$class = Inflector::classify( $theme );
				$qdListeDossier = $this->Cov58->Passagecov58->Dossiercov58->{$class}->qdListeDossier();

				if ( isset( $qdListeDossier['fields'] ) ) {
					$qd['fields'] = $qdListeDossier['fields'];
				}
				$qd['conditions'] = array( 'Passagecov58.cov58_id' => $cov58_id, 'Dossiercov58.themecov58' => Inflector::tableize( $class ) );
				$qd['joins'] = $qdListeDossier['joins'];
				$qd['contain'] = false;

				$qd['fields'][] = $this->Cov58->Passagecov58->Dossiercov58->Personne->Foyer->sqVirtualField( 'enerreur' );

				$dossiers[$theme] = $this->Cov58->Passagecov58->Dossiercov58->find(
					'all',
					$qd
				);
// debug($dossiers);
				$countDossiers += count($dossiers[$theme]);
			}

			$dossierscovs58 = $this->Cov58->Passagecov58->find(
				'all',
				array(
					'conditions' => array(
						'Passagecov58.cov58_id' => $cov58_id
					),
					'contain' => array(
						'Dossiercov58' => array(
							'Personne' => array(
								'Foyer' => array(
									'fields' => array(
										$this->Cov58->Passagecov58->Dossiercov58->Personne->Foyer->sqVirtualField( 'enerreur' )
									),
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01'
										),
										'Adresse'
									)
								)
							)
						)
					)
				)
			);

			$this->set( compact( 'dossierscovs58' ) );
			$this->set(compact('dossiers'));

			$this->set(compact('countDossiers'));
		}

		/**
		*
		*/

		public function decisioncov ( $cov58_id ) {
			$cov58 = $this->Cov58->find(
				'first',
				array(
					'conditions' => array(
						'Cov58.id' => $cov58_id,
					)
				)
			);

			$this->assert( !empty( $cov58 ), 'error404' );
			// On s'assure que le commission ne soit pas dans un état final
			$this->assert( !in_array( $cov58['Cov58']['etatcov'], array( 'traite', 'finalise', 'annule', 'reporte' ) ) );

			$dossiers = $this->Cov58->dossiersParListe( $cov58_id );

			if( !empty( $this->request->data ) ) {
				$this->Cov58->begin();
				$success = $this->Cov58->saveDecisions( $cov58_id, $this->request->data );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Cov58->commit();
					$this->redirect( array( 'action' => 'view', $cov58_id ) );
				}
				else {
					$this->Cov58->rollback();
				}
			}

			if( empty( $this->request->data ) ) {
				$this->request->data = $dossiers;
			}

			$this->set( compact( 'cov58', 'dossiers' ) );
			$this->set( 'cov58_id', $cov58_id);
			$this->_setOptions();
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Cov58->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}

		/**
		*
		*/

		public function ordredujour( $cov58_id ) {
			$pdf = $this->Cov58->getPdfOrdreDuJour( $cov58_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'OJ.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'ordre du jour de la COV', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer( null,true ) );
			}
		}

		/**
		*
		*/

		public function impressionpv( $cov58_id ) {
			$pdf = $this->Cov58->getPdfPv( $cov58_id );
			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'pv.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le PV de la COV', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer( null,true ) );
			}
		}

		/**
		*
		*/

		public function impressiondecision( $passagecov58_id ) {

			$passagecov58 = $this->Cov58->Passagecov58->find(
				'first',
				array(
					'conditions' => array(
						'Passagecov58.id' => $passagecov58_id
					),
					'contain' => array(
						'Dossiercov58' => array(
							'Themecov58'
						)
					)
				)
			);
			$modeleTheme = $passagecov58['Dossiercov58']['themecov58'];
			$modeleTheme = Inflector::classify( $modeleTheme );

			$pdf = $this->Cov58->Passagecov58->Dossiercov58->{$modeleTheme}->getPdfDecision( $passagecov58_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'pv.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de décision de la COV', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer( null,true ) );
			}
		}
	}
?>