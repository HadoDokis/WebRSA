<?php
	/**
	 * Code source de la classe Cataloguesromesv3Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Cataloguesromesv3Controller ...
	 *
	 * @package app.Controller
	 */
	class Cataloguesromesv3Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Cataloguesromesv3';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Search.Filtresdefaut' => array( 'famillesromesv3', 'domainesromesv3', 'metiersromesv3', 'appellationsromesv3' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'famillesromesv3' => array( 'filter' => 'Search' ),
					'domainesromesv3' => array( 'filter' => 'Search' ),
					'metiersromesv3' => array( 'filter' => 'Search' ),
					'appellationsromesv3' => array( 'filter' => 'Search' ),
				)
			)
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			'Search.SearchForm',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Catalogueromev3', 'Familleromev3', 'Domaineromev3', 'Metierromev3', 'Appellationromev3' );

		public $commeDroit = array(
			'famillesromesv3' => 'Cataloguesromesv3:index',
			'domainesromesv3' => 'Cataloguesromesv3:index',
			'metiersromesv3' => 'Cataloguesromesv3:index',
			'appellationsromesv3' => 'Cataloguesromesv3:index',
			'add' => 'Cataloguesromesv3:edit'
		);

		/**
		 * Liste des codes familles ROME V3.
		 */
		public function famillesromesv3() {
			$this->index( 'Familleromev3' );
		}

		/**
		 * Liste des codes domaines ROME V3.
		 */
		public function domainesromesv3() {
			$this->index( 'Domaineromev3' );
		}

		/**
		 * Liste des codes metiers ROME V3.
		 */
		public function metiersromesv3() {
			$this->index( 'Metierromev3' );
		}

		/**
		 * Liste des appellations ROME V3.
		 */
		public function appellationsromesv3() {
			$this->index( 'Appellationromev3' );
		}

		/**
		 * Pagination sur les <éléments> de la table.
		 */
		public function index( $modelName ) {
			if( !in_array( $modelName, $this->Catalogueromev3->modelesParametrages ) ) {
				throw new Error404Exception();
			}

			$tableName = Inflector::tableize( $modelName );

			$search = (array)Hash::get( $this->request->data, 'Search' );

			$query = $this->{$modelName}->search( $search );
			$this->paginate = array(
				$modelName => $query + array(
					'limit' => 10
				)
			);

			$fields = $this->{$modelName}->searchResultFields;

			$results = $this->paginate(
				$modelName,
				array(),
				$fields,
				false // FIXME
				//!Hash::get( $this->request->data, 'Search.Pagination.nombre_total' )
			);

			// A-t'on des enregistrements liés ?
			$this->{$modelName}->Behaviors->attach( 'Occurences' );
			$occurences = $this->{$modelName}->occurencesExists();
			foreach( $results as $i => $result ) {
				$primaryKey = Hash::get( $result, "{$modelName}.{$this->{$modelName}->primaryKey}" );
				$results[$i][$modelName]['occurences'] = ( Hash::get( $occurences, $primaryKey ) ? '1' : '0' );
			}

			$options = $this->{$modelName}->options();

			$this->set( compact( 'results', 'options', 'fields', 'modelName', 'tableName' ) );
			$this->render( 'index' );
		}

		/**
		 * Formulaire d'ajout
		 *
		 * @param string $modelName
		 */
		public function add( $modelName ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification
		 *
		 * @param string $modelName
		 * @param integer $id
		 */
		public function edit( $modelName, $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire d'ajout / de modification
		 *
		 * @param string $modelName
		 * @param integer $id
		 * @throws Error404Exception
		 */
		protected function _add_edit( $modelName, $id = null ) {
			if( !in_array( $modelName, $this->Catalogueromev3->modelesParametrages ) ) {
				throw new Error404Exception();
			}

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$referer = Hash::get( $this->request->data, "{$modelName}.referer" );
				$this->redirect( $referer );
			}

			$Model = ClassRegistry::init( $modelName );

			if( !empty( $this->request->data ) ) {
				$Model->begin();
				if( $Model->saveParametrage( $this->request->data ) ) {
					$Model->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );

					$referer = Hash::get( $this->request->data, "{$modelName}.referer" );
					$this->redirect( $referer );
				}
				else {
					$Model->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $Model->getParametrageFormData( $id );

				if( empty( $this->request->data ) ) {
					throw new Error404Exception();
				}
			}

			// Sauvegarder dans le formulaire de l'adresse de laquelle on vient
			if( !Hash::get( $this->request->data, "{$modelName}.referer" ) ) {
				$referer = $this->referer( null, true );
				$here = $this->request->here( false );

				if( in_array( $referer, array( '/', $here ), true ) ) {
					$url = array( 'controller' => $this->request->params['controller'], 'action' => 'index', $modelName );
					$referer = Router::normalize( Router::url( $url, false ) );
				}

				$this->request->data = Hash::merge(
					(array)$this->request->data,
					array( $modelName => array( 'referer' => $referer ) )
				);
			}

			$fields = $Model->getParametrageFields();
			$fields["{$modelName}.referer"] = array( 'type' => 'hidden' );
			$options = $Model->getParametrageOptions();
			$dependantFields = $Model->getParametrageDependantFields();

			$this->set( compact( 'options', 'fields', 'modelName', 'dependantFields' ) );
			$this->render( 'add_edit' );
		}

		/**
		 * Tentative de suppression d'un enregistrement
		 *
		 * @param string $modelName
		 * @param integer $id
		 * @throws Error404Exception
		 * @throws Error500Exception
		 */
		public function delete( $modelName, $id ) {
			if( !in_array( $modelName, $this->Catalogueromev3->modelesParametrages ) ) {
				throw new Error404Exception();
			}

			$Model = ClassRegistry::init( $modelName );

			$Model->Behaviors->attach( 'Occurences' );
			$occurences = $Model->occurencesExists();
			if( Hash::get( $occurences, $id ) ) {
				throw new Error500Exception( null );
			}

			$Model->begin();
			if( $Model->delete( $id ) ) {
				$Model->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$Model->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}
	}
?>
