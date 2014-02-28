<?php
	/**
	 * Code source de la classe ProgressivePaginatorComponent.
	 *
	 * PHP 5.3
	 *
	 * CakePHP 2.2.2
	 *
	 * @package Search
	 * @subpackage Controller.Component
	 */
	App::uses( 'PaginatorComponent', 'Controller/Component' );

	/**
	 * La classe Search.ProgressivePaginatorComponent permet d'optimiser la pagination en ne comptant pas le
	 * nombre total de résultats, mais en regardant si on a au moins un élément sur la page suivante.
	 *
	 * @package Search
	 * @subpackage Controller.Component
	 */
	class ProgressivePaginatorComponent extends PaginatorComponent
	{

		/**
		 * Surcharge de la méthode validateSort pour que les champs virtuels soient pris en compte pour le tri
		 * TODO: à reporter dans le paginator "normal"
		 *
		 * @see CakePHP 2.2.2
		 *
		 * @param Model $object
		 * @param array $options
		 * @param array $whitelist
		 * @return array
		 */
		public function validateSort( $object, $options, $whitelist = array( ) ) {
			if( isset( $options['sort'] ) ) {
				$direction = null;
				if( isset( $options['direction'] ) ) {
					$direction = strtolower( $options['direction'] );
				}
				if( $direction != 'asc' && $direction != 'desc' ) {
					$direction = 'asc';
				}
				$options['order'] = array( $options['sort'] => $direction );
			}

			if( !empty( $whitelist ) && isset( $options['order'] ) && is_array( $options['order'] ) ) {
				$field = key( $options['order'] );
				if( !in_array( $field, $whitelist ) ) {
					$options['order'] = null;
				}
			}

			if( !empty( $options['order'] ) && is_array( $options['order'] ) ) {
				$order = array( );
				foreach( $options['order'] as $key => $value ) {
					$field = $key;
					$alias = $object->alias;
					if( strpos( $key, '.' ) !== false ) {
						list($alias, $field) = explode( '.', $key );
					}

					if( $object->hasField( $field ) ) {
						$order[$alias.'.'.$field] = $value;
					}
					elseif( $object->hasField( $key, true ) ) {
						$order[$field] = $value;
					}
					elseif( isset( $object->{$alias} ) && $object->{$alias}->hasField( $field, true ) ) {
						$order[$alias.'.'.$field] = $value;
					}
					// Début modification
					else {
						$order[$key] = $value;
					}
					// Fin modification
				}
				$options['order'] = $order;
			}

			return $options;
		}

		/**
		 * Surcharge de la méthode paginate pour obtenir la pagination progressive
		 *
		 * @see CakePHP 2.2.2
		 *
		 * @param array|string|Model $object
		 * @param array $scope
		 * @param array $whitelist
		 * @return array
		 * @throws MissingModelException
		 */
		public function paginate( $object = null, $scope = array( ), $whitelist = array( ) ) {
			if( is_array( $object ) ) {
				$whitelist = $scope;
				$scope = $object;
				$object = null;
			}

			$object = $this->_getObject( $object );

			if( !is_object( $object ) ) {
				throw new MissingModelException( $object );
			}

			$options = $this->mergeOptions( $object->alias );
			$options = $this->validateSort( $object, $options, $whitelist );
			$options = $this->checkLimit( $options );

			$conditions = $fields = $order = $limit = $page = $recursive = null;

			if( !isset( $options['conditions'] ) ) {
				$options['conditions'] = array( );
			}

			$type = 'all';

			if( isset( $options[0] ) ) {
				$type = $options[0];
				unset( $options[0] );
			}

			extract( $options );

			if( is_array( $scope ) && !empty( $scope ) ) {
				$conditions = array_merge( $conditions, $scope );
			}
			elseif( is_string( $scope ) ) {
				$conditions = array( $conditions, $scope );
			}
			if( $recursive === null ) {
				$recursive = $object->recursive;
			}

			$extra = array_diff_key( $options, compact(
							'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
					) );
			if( $type !== 'all' ) {
				$extra['type'] = $type;
			}

			if( intval( $page ) < 1 ) {
				$page = 1;
			}
			$page = $options['page'] = (int)$page;

			if( $object->hasMethod( 'paginate' ) ) {
				$results = $object->paginate(
						$conditions, $fields, $order, $limit, $page, $recursive, $extra
				);
			}
			else {
				$parameters = compact( 'conditions', 'fields', 'order', 'limit', 'page' );
				if( $recursive != $object->recursive ) {
					$parameters['recursive'] = $recursive;
				}
				// Début modification
				// $results = $object->find( $type, array_merge( $parameters, $extra ) );
				$querydata = array_merge( $parameters, $extra );
				$querydata['offset'] = ( max( 0, $page - 1 ) * $querydata['limit'] );
				$querydata['limit'] = ( $querydata['limit'] + 1 );
				$querydata['page'] = 1; // INFO: CakePHP 2.0
				$results = $object->find( $type, $querydata );

				$count = count( $results ) + ( ( $page - 1 ) * $limit );
				if( isset( $results[$querydata['limit'] - 1] ) ) {
					unset( $results[$querydata['limit'] - 1] );
				}
				// Fin modification
			}
			$defaults = $this->getDefaults( $object->alias );
			unset( $defaults[0] );

			// Début modification
//			if( $object->hasMethod( 'paginateCount' ) ) {
//				$count = $object->paginateCount( $conditions, $recursive, $extra );
//			}
//			else {
//				$parameters = compact( 'conditions' );
//				if( $recursive != $object->recursive ) {
//					$parameters['recursive'] = $recursive;
//				}
//				$count = $object->find( 'count', array_merge( $parameters, $extra ) );
//			}
			// Fin modification
			$pageCount = intval( ceil( $count / $limit ) );
			$page = max( min( $page, $pageCount ), 1 );

			$paging = array(
				'page' => $page,
				'current' => count( $results ),
				'count' => $count,
				'prevPage' => ($page > 1),
				'nextPage' => ($count > ($page * $limit)),
				'pageCount' => $pageCount,
				'order' => $order,
				'limit' => $limit,
				'options' => Hash::diff( $options, $defaults ),
				'paramType' => $options['paramType']
			);
			if( !isset( $this->Controller->request['paging'] ) ) {
				$this->Controller->request['paging'] = array( );
			}
			$this->Controller->request['paging'] = array_merge(
					(array)$this->Controller->request['paging'],
					array( $object->alias => $paging )
			);

			if(
					!in_array( 'Paginator', $this->Controller->helpers ) &&
					!array_key_exists( 'Paginator', $this->Controller->helpers )
			) {
				$this->Controller->helpers[] = 'Paginator';
			}
			return $results;
		}
	}
?>