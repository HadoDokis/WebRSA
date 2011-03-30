<?php
	class Covs58Controller extends AppController
	{
		public $name = 'Covs58';

		public $helpers = array( 'Default', 'Default2' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

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
			$themescovs58 = $this->Cov58->Dossiercov58->Themecov58->find('list');

			$options = $this->Cov58->enums();
			$options = array_merge($options, $this->Cov58->Dossiercov58->enums());
			$typesorients = $this->Cov58->Dossiercov58->Propoorientationcov58->Structurereferente->Typeorient->listOptions();
			$structuresreferentes = $this->Cov58->Dossiercov58->Propoorientationcov58->Structurereferente->list1Options();

			$this->set(compact('options', 'typesorients', 'structuresreferentes'));

			$decisionscovs = array( 'accepte' => 'Accepté', 'refus' => 'Refusé', 'ajourne' => 'Ajourné' );
			$this->set(compact('decisionscovs'));
		}

		/**
		*
		*/

		public function index() {
			if( !empty( $this->data ) ) {
				$queryData = $this->Cov58->search( $this->data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;
				$covs58 = $this->paginate( $this->Cov58 );
				$this->set( 'covs58', $covs58 );
// 				$this->set( 'etape', $etape );
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
			if( !empty( $this->data ) ) {
				$this->Cov58->begin();
				$this->Cov58->create( $this->data );
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
				$this->data = $this->Cov58->find(
					'first',
					array(
						'conditions' => array( 'Cov58.id' => $id ),
						'contain' => false
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
				$this->set('cov58_id', $id);
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function view( $cov58_id = null ) {
			$cov58 = $this->Cov58->find(
				'first', array(
					'conditions' => array( 'Cov58.id' => $cov58_id ),
					'contain' => false
				)
			);
// 			debug( $commissionep );
			$this->set('cov58', $cov58);
			$this->_setOptions();

			// Dossiers à passer en séance, par thème traité
			$themes = $this->Cov58->Dossiercov58->Themecov58->find('list');
			$this->set(compact('themes'));
			$dossiers = array();
			$countDossiers = 0;
			foreach( $themes as $key => $theme ) {
				$class = Inflector::classify( $theme );
				$dossiers[$class] = $this->Cov58->Dossiercov58->find(
					'all',
					array(
						'conditions' => array(
							'Dossiercov58.cov58_id' => $cov58_id,
							'Dossiercov58.themecov58_id' => $key
						),
						'contain' => array(
							$class,
							'Personne' => array(
								'Foyer' => array(
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01'
										),
										'Adresse'
									)
								)
							)
						),
					)
				);
				$countDossiers += count($dossiers[$class]);
			}
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

			if( !empty( $this->data ) ) {
// debug( $this->data );
				$this->Cov58->begin();
				$success = $this->Cov58->saveDecisions( $cov58_id, $this->data );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$nbDossierTraitement = $this->Cov58->Dossiercov58->find(
						'count',
						array(
							'conditions' => array(
								'Dossiercov58.cov58_id' => $cov58_id,
								'Dossiercov58.etapecov' => 'traitement'
							)
						)
					);
					if ($nbDossierTraitement == 0) {
						$cov58['Cov58']['etatcov'] = 'finalise';
						$this->Cov58->save($cov58);
					}
					else {
						$cov58['Cov58']['etatcov'] = 'traitement';
						$this->Cov58->save($cov58);
					}
					$this->Cov58->commit();
					$this->redirect( array( 'action' => 'view', $cov58_id ) );
				}
				else {
					$this->Cov58->rollback();
				}
			}

			$dossiers = $this->Cov58->dossiersParListe( $cov58_id );

			if( empty( $this->data ) ) {
				$this->data = $dossiers;
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

	}
?>
