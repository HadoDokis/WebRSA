<?php

	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of WebrsaAbstractMoteursComponent
	 *
	 * @author atma
	 */
	abstract class WebrsaAbstractMoteursComponent extends Component {
		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array();


		/**
		 * Retourne le chemin de base de la clé de configuration.
		 *
		 * @param array $params
		 * @return string
		 */
		protected function _baseConfigureKey( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			return "{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}";
		}

		/**
		 * Retourne les options à envoyer dans la vue pour les champs du moteur
		 * de recherche et les traductions de valeurs de certains champs.
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			return Hash::merge(
				$this->Allocataires->options(),
				$Controller->{$params['modelName']}->enums()
			);
		}

		/**
		 * Permet de filtrer les options envoyées à la vue au moyen de la clé
		 * 'accepted' dans le fichier de configuration.
		 *
		 * @param array $params
		 * @param array $options
		 * @return array
		 */
		protected function _getFilteredOptions( array $params, array $options ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$accepted = (array)Configure::read( $this->_baseConfigureKey( $params ).'.accepted' );

			foreach( $accepted as $path => $acceptedValues ) {
				foreach( (array)Hash::get( $options, $path ) as $value => $label ) {
					if( in_array( $value, $acceptedValues ) === false ) {
						$options = Hash::remove( $options, "{$path}.{$value}" );
					}
				}
			}

			return $options;
		}

		/**
		 * Permet de créer un fichier csv avec le contenu de la recherche
		 *
		 * @param array $params
		 */
		public function exportcsv( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$Controller->loadModel( $params['modelRechercheName'] );

			$query = $this->_getBaseQuery( $this->_baseConfigureKey( $params ), $params );

			$search = Hash::get( Hash::expand( $Controller->request->params['named'], '__' ), $params['searchKey'] );
			$query = $Controller->{$params['modelRechercheName']}->searchConditions( $query, $search );
			$query = $this->Allocataires->completeSearchQuery( $query );
			unset( $query['limit'] );

			$order = trim( Hash::get( $Controller->request->params, 'named.sort' ).' '.Hash::get( $Controller->request->params, 'named.direction' ) );
			if( !empty( $order ) ) {
				$query['order'] = $order;
			}
			else {
				$query = $this->_getQueryOrder($query, $params);
			}

			$Controller->{$params['modelName']}->forceVirtualFields = true;
			$results = $Controller->{$params['modelName']}->find( 'all', $query );

			$options = $this->options( $params );
			$options = $this->_getFilteredOptions( $params, $options );

			$Controller->set( compact( 'results', 'options' ) );
			$Controller->layout = '';
		}

		/**
		 * Retourne un array avec clés de paramètres suivantes complétées en
		 * fonction du contrôleur:
		 *	- modelName: le nom du modèle sur lequel se fera la pagination
		 *	- modelRechercheName: le nom du modèle de moteur de recherche
		 *	- searchKey: le préfixe des filtres renvoyés par le moteur de recherche
		 *	- searchKeyPrefix: le préfixe des champs configurés
		 *	- configurableQueryFieldsKey: les clés de configuration contenant les
		 *    champs à sélectionner dans la base de données.
		 *  - auto: la recherche doit-elle être lancée (avec les valeurs par défaut
		 *    des filtres de recherche) automatiquement au premier accès à la page,
		 *    lors de l'appel à une méthode search() ou cohorte().
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _params( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelName' => $Controller->modelClass,
				'modelRechercheName' => 'WebrsaRecherche'.$Controller->modelClass,
				'searchKey' => 'Search',
				'searchKeyPrefix' => 'ConfigurableQuery',
				'configurableQueryFieldsKey' => "{$Controller->name}.{$Controller->request->params['action']}",
				'auto' => false,
				'filtresdefautClass' => 'Search.Filtresdefaut'
			);

			return $params;
		}

		/**
		 * Récupère la query de modelRechercheName->searchQuery et la met en cache
		 *
		 * @param mixed $keys
		 * @param array $params
		 * @return array
		 */
		protected function _getBaseQuery( $keys, array $params = array() ) {
			$keys = (array)$keys;
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$cacheKey = $Controller->{$params['modelName']}->useDbConfig.'_'.$Controller->name.'_'.$Controller->action.'_'.$Controller->{$params['modelName']}->alias.'_searchQuery';
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Controller->{$params['modelRechercheName']}->searchQuery();
				$query = ConfigurableQueryFields::getFieldsByKeys( $keys, $query );

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Permet de surcharger, nettoyer, ... les valeurs des filtres renvoyées
		 * par le moteur de recherche.
		 *
		 * Voir les clés 'skip', 'restrict' et 'force' dans le fichier de configuration.
		 *
		 * @param array $search
		 * @param array $params
		 * @return array
		 */
		protected function _overrideFilterValues( array $search, array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			// 1°) Si certains champs sont skipped, on ne doit pas les retrouver dans les valeurs du filtre
			$skip = (array)Configure::read( $this->_baseConfigureKey( $params ).'.skip' );
			foreach( $skip as $path ) {
				$search = Hash::remove( $search, $path );
			}

			// 2°) Restriction de valeurs
			$restrict = (array)Configure::read( $this->_baseConfigureKey( $params ).'.restrict' );
			foreach( $restrict as $path => $accepted ) {
				$value = Hash::get( $search, $path );

				if( $value === null || ( !is_array( $value ) && !in_array( $value, (array)$accepted ) ) ) {
					$value = $accepted;
				}
				else if( is_array( $value ) ) {
					$intersect = array_intersect( $value, (array)$accepted );
					if( empty( $intersect ) ) {
						$value = (array)$accepted;
					}
					else {
						$value = $intersect;
					}
				}

				$search = Hash::insert( $search, $path, $value );
			}

			// 3°) Forçage de valeurs
			$force = (array)Configure::read( $this->_baseConfigureKey( $params ).'.force' );
			foreach( $force as $path => $value ) {
				$search = Hash::insert( $search, $path, $value );
			}

			return $search;
		}

		/**
		 * Retourne le querydata complété par les conditions du moteur de recherche,
		 * ainsi que des conditions liées à l'utilisateur connecté.
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		protected function _getQueryConditions( array $query, array $params = array()  ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$search = empty( $params['searchKey'] ) ? (array)$Controller->request->data : (array)Hash::get( $Controller->request->data, $params['searchKey'] );
			$search = $this->_overrideFilterValues( $search, $params );

			$query = $Controller->{$params['modelRechercheName']}->searchConditions( $query, $search );
			$query = $this->Allocataires->completeSearchQuery( $query, $params );

			return $query;

		}

		/**
		 * Ajoute des order by en fonction du paramétrage.
		 * Dans le cas d'un exportcsv, on ne modifie pas l'ordre affiché dans le
		 * moteur de recherche.
		 *
		 * @fixme: simplifier, test unitaire, permettre d'enlever le tri
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		protected function _getQueryOrder( $query = array(), array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$myPathParams = $this->_baseConfigureKey( $params );
			$myParams = (array)Configure::read( $myPathParams );

			// 1. Si le tri est configuré pour mon action
			if( Hash::check( $myParams, 'order' ) ) {
				$query['order'] = Hash::get( $myParams, 'order' );
			}
			// 2. Si le tri dépend du paramètre prevAction éventuellement présent dans l'URL
			else if( Hash::check( $Controller->request->params, 'named.prevAction') ) {
				$action = Hash::get( $Controller->request->params, 'named.prevAction');

				$myPathParams = "{$params['searchKeyPrefix']}{$Controller->name}.{$action}";
				$myParams = (array)Configure::read( $myPathParams );

				if( Hash::check( $myParams, 'order' ) ) {
					$query['order'] = Hash::get( $myParams, 'order' );
				}
			}
			// 3 Si le tri est configuré dans la page ayant redirigé vers nous
			else {
				// INFO: on pourrait utiliser named.prevAction / celui-ci ne sert donc plus à rien
				$referer = Router::parse( $Controller->request->referer( true ) );
				if( is_array( $referer ) && !empty( $referer ) ) {
					$myPathParams = $params['searchKeyPrefix'].Inflector::camelize($referer['controller']).'.'.$referer['action'];
					$myParams = (array)Configure::read( $myPathParams );
					if( Hash::check( $myParams, 'order' ) ) {
						$query['order'][] = Hash::get( $myParams, 'order' );
					}
				}
			}

			return $query;
		}

		/**
		 * Récupère la query complète pour la recherche en fonction des clefs du params
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _getQuery( array $params = array() ) {
			$keys = array(
				"{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}.fields",
				"{$params['searchKeyPrefix']}{$params['configurableQueryFieldsKey']}.innerTable"
			);
			$query = $this->_getBaseQuery( $keys, $params );

			$query = $this->_getQueryConditions( $query, $params );

			$query = $this->_getQueryOrder( $query, $params );

			return $query;
		}

		/**
		 * Retourne le component (Search.)Filtresdefaut, au besoin en le
		 * chargeant à la volée dans le contrôleur.
		 *
		 * @param array $params
		 */
		protected function _filtresdefaut( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			if( $Controller->Components->attached( 'Filtresdefaut' ) === false ) {
				$Controller->Filtresdefaut = $Controller->Components->load( $params['filtresdefautClass'] );
				$Controller->Filtresdefaut->initialize($Controller);
			}

			return $Controller->Filtresdefaut;
		}

		/**
		 * Préremplit les filtres du formulaire de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _prepareFilter( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$filtresdefaut = Configure::read( $this->_filtresdefaut( $params )->configureKey() );
			$Controller->request->data = Hash::merge( $Controller->request->data, array( $params['searchKey'] => $filtresdefaut ) );

			return $filtresdefaut;
		}

		/**
		 * Doit-on forcer la recherche au premier accès à la page ?
		 *
		 * @param array $params
		 * @return boolean
		 */
		protected function _needsAutoSearch( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			return empty( $Controller->request->data ) && $params['auto'] === true;
		}

		/**
		 * Lance automatiquement la recherche au premier accès à la page avec
		 * le contenu des filtres par défaut.
		 *
		 * Complète la clé 'named' des params de la request pour que le formulaire
		 * de filtres soit caché.
		 *
		 * @param array $params
		 */
		protected function _autoSearch( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$this->_filtresdefaut( $params )->merge();

			if( !empty( $params['searchKey'] ) ) {
				$Controller->request->data = array(
					$params['searchKey'] => $Controller->request->data
				);
			}

			if( empty( $Controller->request->params['named'] ) ) {
				$Controller->request->params['named'] = array( 'active' => true );
			}
		}
	}
