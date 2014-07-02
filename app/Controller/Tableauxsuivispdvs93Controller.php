<?php
	/**
	 * Code source de la classe Tableauxsuivispdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Tableauxsuivispdvs93Controller ...
	 *
	 * @package app.Controller
	 */
	class Tableauxsuivispdvs93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Tableauxsuivispdvs93';

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'delete' => 'delete',
			'historiser' => 'create',
			'index' => 'read',
			'tableau1b3' => 'read',
			'tableau1b4' => 'read',
			'tableau1b4new' => 'read',
			'tableau1b5' => 'read',
			'tableau1b5new' => 'read',
			'tableau1b6' => 'read',
			'tableaud1' => 'read',
			'tableaud2' => 'read',
			'view' => 'read',
		);

		/**
		 * Pour les tests des nouveaux tableaux, on donne les mêmes droits que pour les anciens
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'tableau1b4new' => 'Tableauxsuivispdvs93:tableau1b4',
			'tableau1b5new' => 'Tableauxsuivispdvs93:tableau1b5'
		);

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Search.SearchPrg' => array(
				'actions' => array(
					'index',
					'tableaud1',
					'tableaud2',
					'tableau1b3',
					'tableau1b4',
					'tableau1b4new',
					'tableau1b5',
					'tableau1b5new',
					'tableau1b6',
				)
			),
			'Workflowscers93'
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			'Tableaud2'
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Tableausuivipdv93', 'Cohortetransfertpdv93' );

		/**
		 *
		 */
		public function tableaud1() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$results = $this->Tableausuivipdv93->tableaud1( $search );
				$this->set( compact( 'results' ) );
			}

			$this->set( 'categories', $this->Tableausuivipdv93->tableaud1Categories() );
			$this->set( 'columns', $this->Tableausuivipdv93->columns_d1 );
		}

		/**
		 *
		 */
		public function tableaud2() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$results = $this->Tableausuivipdv93->tableaud2( $search );
				$this->set( compact( 'results' ) );
			}

			$this->set( 'categories', $this->Tableausuivipdv93->tableaud2Categories() );
		}

		/**
		 * @param integer $user_structurereferente_id
		 */
		protected function _setOptions( $user_structurereferente_id ) {
			// TODO: dans le beforeFilter ?
			$years = array_reverse( range( 2009, date( 'Y' ) ) );
			$structurereferente_id = $this->Tableausuivipdv93->listePdvs();
			if( $this->action == 'index' ) {
				$structurereferente_id = Hash::merge( array( 'NULL' => 'Conseil général' ), $structurereferente_id );
			}

			$options = array(
				'Search' => array(
					'annee' => array_combine( $years, $years ),
					'structurereferente_id' => $structurereferente_id,
					'user_id' => $this->Tableausuivipdv93->listePhotographes(),
					'tableau' => $this->Tableausuivipdv93->tableaux,
					'typethematiquefp93_id' => ClassRegistry::init( 'Thematiquefp93' )->enum( 'type' )
				),
				'problematiques' => $this->Tableausuivipdv93->problematiques(),
				'acteurs' => $this->Tableausuivipdv93->acteurs(),
				'Tableausuivipdv93' => array( 'name' => $this->Tableausuivipdv93->tableaux )
			);

			$userIsCg = empty( $user_structurereferente_id );
			$this->set( compact( 'options', 'userIsCg' ) );
		}

		/**
		 * @param array $search
		 * @return array
		 */
		protected function _applyStructurereferente( array $search ) {
			$user_structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );
			$this->_setOptions( $user_structurereferente_id );

			if( !empty( $search ) ) {
				if( !empty( $user_structurereferente_id ) ) {
					$search = Hash::insert( $search, 'Search.structurereferente_id', $user_structurereferente_id );
				}
			}

			return $search;
		}

		/**
		 * Moteur de recherche pour le tableau 1 B3: Problématiques des bénéficiaires de l'opération
		 */
		public function tableau1b3() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b3( $search ) );
			}
		}

		/**
		 * Moteur de recherche pour le tableau 1 B4: Prescriptions vers les acteurs
		 * sociaux, culturels et de sante
		 *
		 * @deprecated
		 */
		public function tableau1b4() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b4( $search ) );
			}
		}

		/**
		 * Moteur de recherche pour le tableau 1 B4: Prescriptions vers les acteurs
		 * sociaux, culturels et de sante.
		 */
		public function tableau1b4new() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b4new( $search ) );
			}
		}

		/**
		 * Moteur de recherche pour le tableau 1 B5
		 *
		 * @deprecated
		 */
		public function tableau1b5() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b5( $search ) );
			}
		}

		/**
		 * Moteur de recherche pour le tableau 1 B5
		 */
		public function tableau1b5new() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b5new( $search ) );
			}
		}

		/**
		 * Moteur de recherche pour le tableau 1 B6
		 */
		public function tableau1b6() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b6( $search ) );
			}
		}

		/**
		 *
		 * @param string $action
		 * @param integer $id
		 * @throws NotFoundException
		 */
		public function exportcsvcorpus( $action, $id ) {
			if( !in_array( $action, array( 'tableaud1', 'tableaud2' ) ) ) {
				throw new NotFoundException();
			}

			$tableausuivipdv93 = $this->Tableausuivipdv93->find(
				'first',
				array(
					'conditions' => array(
						'Tableausuivipdv93.id' => $id,
						'Tableausuivipdv93.name' => $action,
					),
					'contain' => array(
						'Pdv'
					),
				)
			);

			if( empty( $tableausuivipdv93 ) ) {
				throw new NotFoundException();
			}

			if( $action === 'tableaud1' ) {
				$querydata = $this->Tableausuivipdv93->qdExportcsvCorpusD1( $id );
			}
			else {
				$querydata = $this->Tableausuivipdv93->qdExportcsvCorpusD2( $id );
			}

			$results = $this->Tableausuivipdv93->find( 'all', $querydata );

			$options = Hash::merge(
				$this->Tableausuivipdv93->Populationd1d2pdv93->Questionnaired1pdv93->enums(),
				$this->Tableausuivipdv93->Populationd1d2pdv93->Questionnaired2pdv93->enums(),
				$this->Tableausuivipdv93->Populationd1d2pdv93->Questionnaired1pdv93->Situationallocataire->enums()
			);
			// INFO: pour que l'intitulé des 1207 ne soit pas "Non scolarisé" mais "Niveau VI (6e à 4e ou formation préprofessionnelle de 1 an et non scolarisé)"
			$options['Questionnaired1pdv93']['nivetu'][1207] = $options['Questionnaired1pdv93']['nivetu'][1206];

			$csvfile = $this->_csvFileName( $this->action, $tableausuivipdv93 );

			$this->set( compact( 'results', 'options', 'csvfile' ) );
			$this->layout = null;
		}

		/**
		 * Retourne le nom de fichier utilisé pour un export CSV.
		 *
		 * @param string $type
		 * @param array $tableausuivipdv93
		 * @return string
		 */
		protected function _csvFileName( $type, $tableausuivipdv93 ) {
			$lieu = ( empty( $tableausuivipdv93['Pdv']['lib_struc'] ) ? 'CG' : $tableausuivipdv93['Pdv']['lib_struc'] );
			$lieu = preg_replace( '/[^a-z0-9\-_]+/i', '_', $lieu );
			$lieu = trim( $lieu, '_' );

			return sprintf(
				"%s-%s-%s-%d-%s",
				$type,
				$tableausuivipdv93['Tableausuivipdv93']['name'],
				$lieu,
				$tableausuivipdv93['Tableausuivipdv93']['annee'],
				date( 'Ymd-His' )
			).'.csv';
		}

		/**
		 * Export des données d'un tableau D1 ou D2 au format CSV.
		 *
		 * @param string $action
		 * @param integer $id
		 * @throws NotFoundException
		 */
		public function exportcsvdonnees( $action, $id ) {
			if( !in_array( $action, array( 'tableaud1', 'tableaud2' ) ) ) {
				throw new NotFoundException();
			}

			$tableausuivipdv93 = $this->Tableausuivipdv93->find(
				'first',
				array(
					'conditions' => array(
						'Tableausuivipdv93.id' => $id,
						'Tableausuivipdv93.name' => $action,
					),
					'contain' => array(
						'Pdv'
					),
				)
			);

			if( empty( $tableausuivipdv93 ) ) {
				throw new NotFoundException();
			}

			$results = unserialize( $tableausuivipdv93['Tableausuivipdv93']['results'] );

			if( $action === 'tableaud1' ) {
				$categories = $this->Tableausuivipdv93->tableaud1Categories();
				$this->set( 'columns', $this->Tableausuivipdv93->columns_d1 );
			}
			else {
				$categories = $this->Tableausuivipdv93->tableaud2Categories();
			}

			$csvfile = $this->_csvFileName( $this->action, $tableausuivipdv93 );

			$this->set( compact( 'results', 'action', 'categories', 'tableausuivipdv93', 'csvfile' ) );

			$this->layout = null; // FIXME
			$this->render( "exportcsvdonnees_{$action}" );
		}

		/**
		 * Historisation d'un tableau de résultat.
		 *
		 * @param string $action
		 */
		public function historiser( $action ) {
			$search = $this->_applyStructurereferente( Hash::expand( $this->request->params['named'] ) );

			$this->Tableausuivipdv93->begin();
			$success = $this->Tableausuivipdv93->historiser( $action, $search, $this->Session->read( 'Auth.User.id' ) );

			if( $success ) {
				$this->Tableausuivipdv93->commit();
				$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
				$this->setAction( 'view', $this->Tableausuivipdv93->id );
			}
			else {
				$this->Tableausuivipdv93->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				$this->redirect( $this->request->referer() );
			}
		}

		/**
		 * Accès à la liste des résultats historisés.
		 *
		 * @param string $action
		 */
		public function index( $action = null ) {
			$search = $this->_applyStructurereferente( $this->request->data );
			$vfNomcomplet = $this->Tableausuivipdv93->Photographe->sqVirtualfield( 'nom_complet', false );

			if( !empty( $search ) ) {
				$querydata = array(
					'fields' => array(
						'Tableausuivipdv93.id',
						'Tableausuivipdv93.annee',
						'Pdv.lib_struc',
						'Tableausuivipdv93.name',
						'Tableausuivipdv93.version',
						"( CASE WHEN \"Photographe\".\"id\" IS NOT NULL THEN {$vfNomcomplet} ELSE 'Photographie automatique' END ) AS \"Photographe__nom_complet\"",
						'Tableausuivipdv93.created',
						'Tableausuivipdv93.modified',
					),
					'contain' => array(
						'Pdv',
						'Photographe'
					),
					'order' => array(
						'Tableausuivipdv93.annee DESC',
						'Pdv.lib_struc ASC',
						'Tableausuivipdv93.name ASC',
						'Tableausuivipdv93.modified DESC'
					),
					'limit' => 10
				);

				// TODO: une méthode search dans le modèle
				// TODO: en paramètre de la recherche + version
				if( !empty( $action ) ) {
					$querydata['conditions']['Tableausuivipdv93.name'] = $action;
				}
				if( !empty( $search['Search']['annee'] ) ) {
					$querydata['conditions']['Tableausuivipdv93.annee'] = $search['Search']['annee'];
				}
				if( !empty( $search['Search']['structurereferente_id'] ) ) {
					if( $search['Search']['structurereferente_id'] == 'NULL' ) {
						$querydata['conditions'][] = 'Tableausuivipdv93.structurereferente_id IS NULL';
					}
					else {
						$querydata['conditions']['Tableausuivipdv93.structurereferente_id'] = $search['Search']['structurereferente_id'];
					}
				}
				if( !empty( $search['Search']['user_id'] ) ) {
					if( $search['Search']['user_id'] == 'NULL' ) {
						$querydata['conditions'][] = 'Tableausuivipdv93.user_id IS NULL';
					}
					else {
						$querydata['conditions']['Tableausuivipdv93.user_id'] = $search['Search']['user_id'];
					}
				}
				if( !empty( $search['Search']['tableau'] ) ) {
					$querydata['conditions']['Tableausuivipdv93.name'] = $search['Search']['tableau'];
				}

				$this->paginate = array( 'Tableausuivipdv93' => $querydata );
				$tableauxsuivispdvs93 = $this->paginate( 'Tableausuivipdv93', array(), array(), false );
				$this->set( compact( 'tableauxsuivispdvs93' ) );
			}
		}

		/**
		 * Accès à une version historisée d'un tableau.
		 *
		 * FIXME: vérifier les accès
		 * TODO: enlever le lien historiser et ajouter les détails de la capture
		 *
		 * @param string $action
		 */
		public function view( $id ) {
			$tableausuivipdv93 = $this->Tableausuivipdv93->find(
				'first',
				array(
					'conditions' => array(
						'Tableausuivipdv93.id' => $id
					)
				)
			);

			if( empty( $tableausuivipdv93 ) ) {
				throw new NotFoundException();
			}

			if( in_array( $tableausuivipdv93['Tableausuivipdv93']['name'], array( 'tableaud1', 'tableaud2' ) ) ) {
				$method = $tableausuivipdv93['Tableausuivipdv93']['name'].'Categories';
				$this->set( 'categories', $this->Tableausuivipdv93->{$method}() );
				if( $tableausuivipdv93['Tableausuivipdv93']['name'] == 'tableaud1' ) {
					$this->set( 'columns', $this->Tableausuivipdv93->columns_d1 );
				}
			}

			$this->request->data = $this->_applyStructurereferente( unserialize( $tableausuivipdv93['Tableausuivipdv93']['search'] ) );
			$results = unserialize( $tableausuivipdv93['Tableausuivipdv93']['results'] );
			$this->set( compact( 'results', 'tableausuivipdv93', 'id' ) );
			$this->render( $tableausuivipdv93['Tableausuivipdv93']['name'] );
		}

		/**
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->Tableausuivipdv93->begin();

			if( $this->Tableausuivipdv93->delete( $id ) ) {
				$this->Tableausuivipdv93->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Tableausuivipdv93->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( $this->request->referer() );
		}
	}
?>
