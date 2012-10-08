<?php
	class ComitesapresController extends AppController
	{

		public $name = 'Comitesapres';
		public $uses = array( 'Apre', 'Option', 'Personne', 'Comiteapre', 'Dossier'/* , 'ComiteapreParticipantcomite' */, 'Participantcomite', 'Apre', 'Referent' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
		public $components = array( 'Prg' => array( 'actions' => array( 'index', 'liste' ) ) );
		public $commeDroit = array(
			'view' => 'Comitesapres:index',
			'add' => 'Comitesapres:edit'
		);

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		protected function _setOptions() {
			$this->set( 'referent', $this->Referent->find( 'list' ) );
			$options = $this->Comiteapre->ApreComiteapre->allEnumLists();
			$options = Set::merge( $options, $this->Comiteapre->ComiteapreParticipantcomite->allEnumLists() );
			$this->set( 'options', $options );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		public function index() {
			$this->_index( 'Comiteapre::index' );
		}

		//---------------------------------------------------------------------

		public function liste() {
			$this->_index( 'Comiteapre::liste' );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		protected function _index( $display = null ) {
			$this->Comiteapre->Apre->deepAfterFind = false;
			if( !empty( $this->data ) ) {
				$this->Dossier->begin(); // Pour les jetons
				$comitesapres = $this->Comiteapre->search( $display, $this->data );
				$comitesapres['limit'] = 10;
				$comitesapres['recursive'] = 1;
				$this->paginate = $comitesapres;
				$comitesapres = $this->paginate( $this->Comiteapre );
				$this->Dossier->commit();
				$this->_setOptions();
				$this->set( 'comitesapres', $comitesapres );
			}

			switch( $display ) {
				case 'Comiteapre::index':
					$this->set( 'pageTitle', 'Recherche de comités' );
					$this->render( $this->action, null, 'index' );
					break;
				case 'Comiteapre::liste':
					$this->set( 'pageTitle', 'Liste des comités' );
					$this->render( $this->action, null, 'liste' );
					break;
			}
		}

		/**		 * *************************************************************************************
		 *   Affichage du Comité après sa création permettant ajout des APREs et des Participants
		 * ** ************************************************************************************ */
		public function view( $comiteapre_id = null ) {
			$this->Comiteapre->Apre->deepAfterFind = false;

			$containApre = array( );
			foreach( $this->Apre->aidesApre as $modelAideAlias ) {
				$modelPieceAlias = 'Piece'.Inflector::underscore( $modelAideAlias );
				$containApre[$modelAideAlias] = array( $modelPieceAlias );
			}

			$contain = array(
				'Apre' => array_merge(
						$containApre, array(
					'Personne' => array(
						'Foyer' => array(
							'Adressefoyer' => array(
								'Adresse'
							)
						)
					)
						)
				),
				'Participantcomite'
			);

			$comiteapre = $this->Comiteapre->find(
					'first', array(
				'conditions' => array( 'Comiteapre.id' => $comiteapre_id ),
				'contain' => $contain
					)
			);
			$this->assert( !empty( $comiteapre ), 'invalidParameter' );

			$this->set( 'comiteapre', $comiteapre );
			$this->_setOptions();
			$participants = $this->Participantcomite->find( 'list' );
			$this->set( 'participants', $participants );
			$this->set( 'listeAidesApre', $this->Apre->aidesApre );
		}

		/**		 * *********************************************************************************************
		 *   Affichage du rapport suite au Comité ( présence / absence des participants + décision APREs)
		 * ** ********************************************************************************************* */
		public function rapport( $comiteapre_id = null ) {
			$this->assert( valid_int( $comiteapre_id ), 'invalidParameter' );

			$this->Comiteapre->Apre->deepAfterFind = false;
			$comiteapre = $this->Comiteapre->find(
					'first', array(
				'conditions' => array( 'Comiteapre.id' => $comiteapre_id ),
				'contain' => array(
					'Apre' => array(
						'Personne' => array(
							'Foyer' => array(
								'Adressefoyer' => array(
									'Adresse'
								)
							)
						)
					),
					'Participantcomite'
				)
					)
			);

			$this->set( 'comiteapre', $comiteapre );
			$this->_setOptions();
			$participants = $this->Participantcomite->find( 'list' );
			$this->set( 'participants', $participants );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		protected function _add_edit( $id = null ) {
			$this->Comiteapre->begin();

			$isRapport = Set::classicExtract( $this->params, 'named.rapport' );

			/// Récupération des id afférents
			if( $this->action == 'add' ) {
				$this->assert( empty( $id ), 'invalidParameter' );
			}
			else if( $this->action == 'edit' ) {
				$comiteapre_id = $id;
				$qd_comiteapre = array(
					'conditions' => array(
						'Comiteapre.id' => $comiteapre_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => 1
				);
				$comiteapre = $this->Comiteapre->find( 'first', $qd_comiteapre );
				$this->assert( !empty( $comiteapre ), 'invalidParameter' );
			}

			if( !empty( $this->data ) ) {
				if( $this->Comiteapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Comiteapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );

					if( $saved ) {
						$this->Comiteapre->commit(); // FIXME
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );

						if( !$isRapport ) {
							$this->redirect( array( 'controller' => 'comitesapres', 'action' => 'view', $this->Comiteapre->id ) );
						}
						else if( $isRapport ) {
							$this->redirect( array( 'controller' => 'comitesapres', 'action' => 'rapport', $this->Comiteapre->id ) );
						}
					}
					else {
						$this->Comiteapre->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->data = $comiteapre;
				}
			}
			$this->Comiteapre->commit();
			$this->_setOptions();
			$this->render( $this->action, null, 'add_edit' );
		}

		public function exportcsv() {
			$querydata = $this->Comiteapre->search( 'Comiteapre::index', Xset::bump( $this->params['named'], '__' ) );
			unset( $querydata['limit'] );
			$comitesapres = $this->Comiteapre->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'comitesapres' ) );
		}

	}
?>