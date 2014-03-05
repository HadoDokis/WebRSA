<?php
	/**
	 * Code source de la classe Cataloguespdisfps93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Cataloguespdisfps93Controller ...
	 *
	 * @package app.Controller
	 */
	class Cataloguespdisfps93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Cataloguespdisfps93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Search.Filtresdefaut' => array( 'search' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'search' => array( 'filter' => 'Search' ),
				)
			),
			'Search.SearchSavedRequests',
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

		public $modelNames = array( 'Thematiquefp93', 'Categoriefp93', 'Filierefp93', 'Actionfp93', 'Prestatairefp93' );

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Cataloguepdifp93', 'Thematiquefp93' );

		/**
		 * Tableau de suivi du référentiel.
		 */
		public function search() {
			if( Hash::check( $this->request->data, 'Search' ) ) {
				$query = $this->Cataloguepdifp93->search( $this->request->data['Search'] );

				$query['limit'] = 10;

				$this->paginate = array( 'Thematiquefp93' => $query );
				$results = $this->paginate(
					'Thematiquefp93',
					array(),
					$query['fields'],
					!Hash::get( $this->request->data, 'Search.Pagination.nombre_total' )
				);

				$this->SearchSavedRequests->write( // FIXME
					Inflector::underscore( $this->name ),
					$this->action,
					$this->request->params
				);

				$this->set( compact( 'results' ) );
			}

			$options = $this->Cataloguepdifp93->options();

			$this->set( compact( 'options' ) );
		}

		/**
		 * Liste des enregistrements d'un modèle.
		 *
		 * @param string $modelName
		 * @throws Error404Exception
		 */
		public function index( $modelName ) {
			if( !in_array( $modelName, $this->modelNames ) ) {
				throw new Error404Exception();
			}

			$Model = ClassRegistry::init( $modelName );

			// Début factorisation pour formulaire
			$fields = array_keys( $Model->schema() );
			array_remove( $fields, 'id' );
			foreach( $fields as $i => $field ) {
				$fields[$i] = "{$Model->alias}.{$field}";
			}

			$query = array(
				'joins' => array(),
				'order' => array( "{$Model->alias}.{$Model->displayField} ASC" ),
				'limit' => 10
			);

			if( !empty( $Model->belongsTo ) ) {
				foreach( $Model->belongsTo as $alias => $params ) {
					array_remove( $fields, "{$Model->alias}.{$params['foreignKey']}" );

					$OtherModel = $Model->{$alias};
					array_unshift( $fields, "{$alias}.{$OtherModel->displayField}" );
					$query['joins'][] = $Model->join( $alias, array( 'type' => 'INNER' ) );
				}
			}
			// Fin factorisation pour formulaire

			$query['fields'] = $fields;
			$query['fields'][] = "{$Model->alias}.{$Model->primaryKey}";

			$this->paginate = array( $Model->alias => $query );

			$results = $this->paginate( $Model, array(), $fields, false );

			$options = $Model->enums();

			$this->set( compact( 'modelName', 'results', 'fields', 'options' ) );
		}

		public function add( $modelName ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit( $modelName, $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		protected function _add_edit( $modelName, $id = null ) {
			if( !in_array( $modelName, $this->modelNames ) ) {
				throw new Error404Exception();
			}

			$Model = ClassRegistry::init( $modelName );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->SearchSavedRequests->redirect(
					Inflector::underscore( $this->name ),
					'search',
					array( 'controller' => Inflector::underscore( $this->name ), 'action' => 'index', $modelName )
				);
			}

			if( !empty( $this->request->data ) ) {
				$Model->begin();
				$Model->create( $this->request->data );
				if( $Model->save() ) {
					$Model->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					// $this->redirect( array( 'action' => 'index', $modelName ) );
					$this->SearchSavedRequests->redirect( // FIXME
						Inflector::underscore( $this->name ),
						'search',
						array( 'controller' => Inflector::underscore( $this->name ), 'action' => 'index', $modelName )
					);
				}
				else {
					$Model->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $Model->find(
					'first',
					array(
						'conditions' => array( "{$Model->alias}.{$Model->primaryKey}" => $id )
					)
				);
				if( empty( $this->request->data ) ) {
					throw new Error404Exception();
				}
			}

			// Début factorisation pour formulaire
			$fields = array_keys( $Model->schema() );
			array_remove( $fields, 'created' );
			array_remove( $fields, 'modified' );
			foreach( $fields as $i => $field ) {
				$fields[$i] = "{$Model->alias}.{$field}";
			}

			$fields = Hash::normalize( $fields );

			$options = $Model->enums();

			// Début factorisation pour formulaire
			if( !empty( $Model->belongsTo ) ) {
				foreach( $Model->belongsTo as $alias => $params ) {
					$OtherModel = $Model->{$alias};
					$options[$Model->alias][$params['foreignKey']] = $OtherModel->find( 'list' );
				}
			}

			foreach( $options as $modelName => $modelOptions ) {
				foreach( array_keys( $modelOptions ) as $fieldName ) {
					$fields["{$modelName}.{$fieldName}"] = array( 'empty' => true );
				}
			}

			// Fin factorisation pour formulaire

			$this->set( compact( 'options', 'fields', 'modelName' ) );
			$this->render( 'add_edit' );
		}

		public function delete( $modelName, $id ) {
			if( !in_array( $modelName, $this->modelNames ) ) {
				throw new Error404Exception();
			}

			$Model = ClassRegistry::init( $modelName );

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
