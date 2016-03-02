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
			'tableau1b5' => 'read',
			'tableau1b6' => 'read',
			'tableaud1' => 'read',
			'tableaud2' => 'read',
			'view' => 'read',
		);

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'InsertionsAllocataires',
			'Search.SearchPrg' => array(
				'actions' => array(
					'index',
					'tableaud1',
					'tableaud2',
					'tableau1b3',
					'tableau1b4',
					'tableau1b5',
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
					'referent_id' => $this->Tableausuivipdv93->listeReferentsPdvs( $user_structurereferente_id ),
					'user_id' => $this->Tableausuivipdv93->listePhotographes(),
					'tableau' => $this->Tableausuivipdv93->tableaux,
					'typethematiquefp93_id' => ClassRegistry::init( 'Thematiquefp93' )->enum( 'type' )
				),
				'problematiques' => $this->Tableausuivipdv93->problematiques(),
				'acteurs' => $this->Tableausuivipdv93->acteurs(),
				'Tableausuivipdv93' => array( 'name' => $this->Tableausuivipdv93->tableaux )
			);

			$userIsCg = empty( $user_structurereferente_id );
			$userIsCi = $this->Session->read( 'Auth.User.type' ) === 'externe_ci';
			$this->set( compact( 'options', 'userIsCg', 'userIsCi' ) );
		}

		/**
		 * Retourne un array contenant les clés structurereferente_id et referent_id
		 * pas à NULL  lorsque l'on doit ajouter des conditions aux requêtes
		 * en fonction de l'utilisateur connecté (CPDV / secrétaire ou chargé
		 * d'insertion).
		 *
		 * @return array
		 */
		protected function _getConditionsUtilisateur() {
			$conditions = array(
				'structurereferente_id' => null,
				'referent_id' => null
			);

			// Si l'utilisateur connecté est limité à un PDV
			$user_structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );
			if( !empty( $user_structurereferente_id ) ) {
				$conditions['structurereferente_id'] = $user_structurereferente_id;
			}

			// Si l'utilisateur connecté est un référent, on limite encore plus
			$user_referent_id = null;
			if( $this->Session->read( 'Auth.User.type' ) === 'externe_ci' ) {
				$user_referent_id = $this->Session->read( 'Auth.User.referent_id' );
				if( !empty( $user_referent_id ) ) {
					$conditions['referent_id'] = $user_referent_id;
				}
			}

			return $conditions;
		}

		/**
		 * Méthode utilitaire permettant d'ajouter des filtres automatiquement
		 * concernant la structure référente (CPDV, secrétaire) ou le référent
		 * connecté (chargé d'insertion).
		 * De plus, les options seront envoyées à la vue, suivant le type
		 * d'utilisateur connecté.
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _applyStructurereferente( array $search ) {
			$conditions = $this->_getConditionsUtilisateur();

			if( !empty( $search ) ) {
				if( !empty( $conditions['structurereferente_id'] ) ) {
					$search = Hash::insert( $search, 'Search.structurereferente_id', $conditions['structurereferente_id'] );
				}
				if( !empty( $conditions['referent_id'] ) ) {
					$search = Hash::insert( $search, 'Search.referent_id', $conditions['referent_id'] );
				}
			}

			$this->_setOptions( $conditions['structurereferente_id'] );

			return $search;
		}

		/**
		 * Complète le querydata en s'assurant de bien limiter l'utilisateur à ce
		 * à quoi il a droit.
		 *
		 * @param array $query
		 * @param string $modelName
		 * @return array
		 */
		protected function _completeQueryUtilisateur( array $query, $modelName ) {
			$conditions = $this->_getConditionsUtilisateur();

			foreach( array( 'structurereferente_id', 'referent_id' ) as $fieldName ) {
				if( !empty( $conditions[$fieldName] ) ) {
					$query['conditions']["{$modelName}.{$fieldName}"] = $conditions[$fieldName];
				}
			}

			return $query;
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
		 * sociaux, culturels et de sante.
		 */
		public function tableau1b4() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b4( $search ) );
			}
		}

		/**
		 * Moteur de recherche pour le tableau 1 B5
		 */
		public function tableau1b5() {
			$search = $this->_applyStructurereferente( $this->request->data );

			if( !empty( $search ) ) {
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b5( $search ) );
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
			if( !in_array( $action, array_keys( $this->Tableausuivipdv93->tableaux ) ) ) {
				throw new NotFoundException();
			}

			$query = array(
				'conditions' => array(
					'Tableausuivipdv93.id' => $id,
					'Tableausuivipdv93.name' => $action,
				),
				'contain' => array(
					'Pdv',
					'Referent' => array(
						'fields' => array(
							$this->Tableausuivipdv93->Referent->sqVirtualField( 'nom_complet' )
						)
					)
				),
			);

			$query = $this->_completeQueryUtilisateur( $query, 'Tableausuivipdv93' );

			$tableausuivipdv93 = $this->Tableausuivipdv93->find( 'first', $query );

			if( empty( $tableausuivipdv93 ) ) {
				throw new NotFoundException();
			}

			// Récupération des données du corpus
			$query = array(
				'conditions' => array(
					'Corpuspdv93.tableausuivipdv93_id' => $id
				),
				'contain' => false
			);

			$corpuspdv93 = $this->Tableausuivipdv93->Corpuspdv93->find( 'first', $query );

			// Nouvelle façon de faire, avec la table corpuspdvs93
			if( !empty( $corpuspdv93 ) ) {
				// TODO: le faire dans le modèle beforeSave / afterFind ?
				$fields = json_decode( $corpuspdv93['Corpuspdv93']['fields'], true );
				$results = json_decode( $corpuspdv93['Corpuspdv93']['results'], true );
				$options = json_decode( $corpuspdv93['Corpuspdv93']['options'], true );
			}
			// Ancienne façon de faire, tant que l'on n'a pas tout mis à jour
			else {
				if( $action === 'tableaud1' ) {
					$query = $this->Tableausuivipdv93->qdExportcsvCorpusd1( $id );
				}
				else if( $action === 'tableaud2' ) {
					$query = $this->Tableausuivipdv93->qdExportcsvCorpusd2( $id );
				}
				else if( $action === 'tableau1b3' ) {
					$query = $this->Tableausuivipdv93->qdExportcsvCorpus1b3( $id );
				}
				else if( $action === 'tableau1b4' ) {
					$query = $this->Tableausuivipdv93->qdExportcsvCorpus1b4( $id );
				}
				else if( $action === 'tableau1b5' ) {
					$query = $this->Tableausuivipdv93->qdExportcsvCorpus1b5( $id );
				}
				else if( $action === 'tableau1b6' ) {
					$query = $this->Tableausuivipdv93->qdExportcsvCorpus1b6( $id );
				}

				if( !in_array( $action, array( 'tableaud1', 'tableaud2' ) )  ) {
					$query = ConfigurableQueryFields::getFieldsByKeys( "{$this->name}.{$action}.{$this->request->action}", $query );
				}

				$this->Tableausuivipdv93->forceVirtualFields = true;
				$results = $this->Tableausuivipdv93->find( 'all', $query );

				$options = $this->Tableausuivipdv93->getOptions( $action );
			}

			$csvfile = $this->_csvFileName( $this->action, $tableausuivipdv93 );

			$this->set( compact( 'results', 'options', 'csvfile', 'action' ) );
			$this->layout = null;

			if( in_array( $action, array( 'tableaud1', 'tableaud2' ) )  ) {
				$this->view = 'exportcsvcorpus_d1d2';
			}
			else {
				$this->view = 'exportcsvcorpus';
			}
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

			$referent = Hash::get( $tableausuivipdv93, 'Referent.nom_complet' );
			$referent = preg_replace( '/[^a-z0-9\-_]+/i', '_', $referent );
			$referent = trim( $referent, '_' );

			return implode(
				'-',
				Hash::filter(
					array(
						$type,
						$tableausuivipdv93['Tableausuivipdv93']['name'],
						$lieu,
						$referent,
						$tableausuivipdv93['Tableausuivipdv93']['annee'],
						date( 'Ymd-His' )
					)
				)
			).'.csv';
		}

		/**
		 * Export des données d'un tableau D1 ou D2 au format CSV.
		 *
		 * @fixme 1B4, 1B5
		 *
		 * @param string $action
		 * @param integer $id
		 * @throws NotFoundException
		 */
		public function exportcsvdonnees( $action, $id ) {
			if( !in_array( $action, array_keys( $this->Tableausuivipdv93->tableaux ) ) ) {
				throw new NotFoundException();
			}

			$query = array(
				'conditions' => array(
					'Tableausuivipdv93.id' => $id,
					'Tableausuivipdv93.name' => $action,
				),
				'contain' => array(
					'Pdv',
					'Referent' => array(
						'fields' => array(
							$this->Tableausuivipdv93->Referent->sqVirtualField( 'nom_complet' )
						)
					)
				),
			);

			$query = $this->_completeQueryUtilisateur( $query, 'Tableausuivipdv93' );

			$tableausuivipdv93 = $this->Tableausuivipdv93->find( 'first', $query );

			if( empty( $tableausuivipdv93 ) ) {
				throw new NotFoundException();
			}

			$results = unserialize( $tableausuivipdv93['Tableausuivipdv93']['results'] );

			if( $action === 'tableaud1' ) {
				$categories = $this->Tableausuivipdv93->tableaud1Categories();
				$this->set( 'columns', $this->Tableausuivipdv93->columns_d1 );
			}
			else if( $action === 'tableaud2' ) {
				$categories = $this->Tableausuivipdv93->tableaud2Categories();
			}
			else if( $action === 'tableau1b3' ) {
				$categories = $this->Tableausuivipdv93->problematiques();
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
						//'Referent.nom_complet',
						$this->Tableausuivipdv93->Referent->sqVirtualField( 'nom_complet' ),
						'Tableausuivipdv93.name',
						'Tableausuivipdv93.version',
						"( CASE WHEN \"Photographe\".\"id\" IS NOT NULL THEN {$vfNomcomplet} ELSE 'Photographie automatique' END ) AS \"Photographe__nom_complet\"",
						'Tableausuivipdv93.created',
						'Tableausuivipdv93.modified',
					),
					'contain' => array(
						'Pdv',
						'Referent',
						'Photographe'
					),
					'order' => array(
						'Tableausuivipdv93.annee DESC',
						'Pdv.lib_struc ASC',
						'Referent.nom_complet ASC',
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
				if( !empty( $search['Search']['referent_id'] ) ) {
					if( $search['Search']['referent_id'] == 'NULL' ) {
						$querydata['conditions'][] = 'Tableausuivipdv93.referent_id IS NULL';
					}
					else {
						$querydata['conditions']['Tableausuivipdv93.referent_id'] = suffix( $search['Search']['referent_id'] );
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
		 * TODO: enlever le lien historiser et ajouter les détails de la capture
		 *
		 * @param string $action
		 *
		 * @throws NotFoundException
		 */
		public function view( $id ) {
			$query = array(
				'conditions' => array(
					'Tableausuivipdv93.id' => $id
				)
			);

			$query = $this->_completeQueryUtilisateur( $query, 'Tableausuivipdv93' );

			$tableausuivipdv93 = $this->Tableausuivipdv93->find( 'first', $query );

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

			// On préfixe l'id du référent avec l'id de sa structure si ce n'est pas déjà fait
			$referent_id = Hash::get( $this->request->data, 'Search.referent_id' );
			if( !empty( $referent_id ) && strpos( $referent_id, '_' ) === false ) {
				$structurereferente_id = Hash::get( $this->request->data, 'Search.structurereferente_id' );
				$this->request->data = Hash::insert( $this->request->data, 'Search.referent_id', "{$structurereferente_id}_{$referent_id}" );
			}

			$results = unserialize( $tableausuivipdv93['Tableausuivipdv93']['results'] );
			$this->set( compact( 'results', 'tableausuivipdv93', 'id' ) );

			// Pour les tableaux 1B4 et 1B5, il existe plusieurs versions
			$name = $tableausuivipdv93['Tableausuivipdv93']['name'];
			$version = $tableausuivipdv93['Tableausuivipdv93']['version'];
			// Par défaut, le nom de la vue est le nom du tableau
			$viewName = $name;

			if( in_array( $name, array( 'tableau1b4', 'tableau1b5' ) ) ) {
				// Entre la version 2.5.1 et la version 2.7.0
				if( version_compare( $version, '2.7', '<') ) {
					$viewName = $name.'_2.5.1';
				}
				// Pour la tableau 1B5, entre la version 2.7.0 et la version 2.7.06
				else if( $name === 'tableau1b5' && version_compare( $version, '2.7.06', '<') ) {
					$viewName = $name.'_2.7.0';
				}
			}

			$this->render( $viewName );
		}

		/**
		 * @param integer $id
		 *
		 * @throws NotFoundException
		 */
		public function delete( $id ) {
			$query = array(
				'fields' => array(
					'Tableausuivipdv93.id'
				),
				'conditions' => array(
					'Tableausuivipdv93.id' => $id
				)
			);

			$query = $this->_completeQueryUtilisateur( $query, 'Tableausuivipdv93' );

			$record = $this->Tableausuivipdv93->find( 'first', $query );

			if( empty( $record ) ) {
				throw new NotFoundException();
			}

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
