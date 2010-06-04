<?php
	class DefaultComponent extends Component
	{
		//called before Controller::beforeFilter()
		function initialize( &$controller, $settings = array() ) {
			// saving the controller reference for later use
			$this->controller =& $controller;
			// FIXME: settings, ...
		}

		/// FIXME
		//called after Controller::beforeRender()
		function beforeRender(&$controller) {
			if( isset( $this->controller->{$this->controller->modelClass} ) ) {
				$model = $this->controller->{$this->controller->modelClass};
				$domain = Inflector::singularize( Inflector::tableize( $model->name ) );

				switch( $controller->action ) {
					case 'edit':
					case 'view': // FIXME: View::get ?
					case 'delete':
						$varName = $domain;
						$controller->pageTitle = sprintf(
							__d( $domain, "{$controller->name}::{$controller->action}", true ),
							Set::classicExtract( Xset::classicExtract( $controller->viewVars, $varName ), "{$model->name}.{$model->displayField}" )
						);
						break;
					case 'add':
					case 'index':
					default:
							$controller->pageTitle = sprintf(
								__d( $domain, "{$controller->name}::{$controller->action}", true )
							);
						break;
				}
			}
		}

		/**
		*
		*/

		public function search( $operations, $queryData = array() ) {
			$search = Set::extract( $this->controller->data, 'Search' );
			if( !empty( $search ) ) {
				$search = Xset::filterDeep( Set::flatten( $search ) );

				if( !empty( $search ) ) {
					$search = Set::remove( $search, 'active' );
					$conditions = $this->conditions( $search, $operations );
					$queryData = Set::merge( $queryData, array( $this->controller->modelClass => array( 'conditions' => $conditions ) ) );
				}

				$this->index( $queryData );
			}
		}

		/**
		* TODO
		*
		* @access public
		*/

		public function index( $queryData = array() ) {
			// FIXME
			$this->controller->paginate = array(
				$this->controller->modelClass => array(
					'limit' => 5,
					'recursive' => 0
				)
			);

			$this->controller->paginate = Xset::merge( $this->controller->paginate, $queryData );
			$items = $this->controller->paginate( $this->controller->modelClass );

			$varname = Inflector::tableize( $this->controller->modelClass );
			$this->controller->set( $varname, $items );
//             $this->controller->render( $this->controller->action, null, 'index' );
		}

		/**
		* TODO
		*
		* @access public
		*/

		public function view( $id = null ) {
			$item = $this->controller->{$this->controller->modelClass}->findById( $id, null, null, 1 );
			$this->controller->assert( !empty( $item ), 'invalidParameter' );

			//debug( Inflector::underscore( Inflector::singularize( $this->controller->name ) ) ); // FIXME TODO
			$varname = strtolower( Inflector::singularize( $this->controller->name ) );
			$this->controller->set( $varname, $item );
		}

		/**
		* FIXME docs
		*
		* @access public
		*/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

		/**
		* FIXME docs
		*
		* @access public
		*/

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

		/**
		* FIXME docs
		*
		* @access private
		*/

		public function _add_edit( $id = null ) {
			if( Set::check( $this->controller->params, 'form.cancel' ) ) {
				$this->controller->Session->setFlash( __( 'Save->cancel', true ), 'flash/information' );
				$this->controller->redirect( array( 'action' => 'index' ) ); // FIXME: paramétrer
			}

			if( $this->controller->action == 'edit' ) {
				$item = $this->controller->{$this->controller->modelClass}->findById( $id, null, null, 1 );
				$this->controller->assert( !empty( $item ), 'invalidParameter' );

				$varname = strtolower( Inflector::singularize( $this->controller->name ) ); // FIXME: voir view
				$this->controller->set( $varname, $item );
			}

			if( !empty( $this->controller->data ) ) {
				if( Set::classicExtract( $this->controller->params, "{$this->controller->action}.operation" ) == 'saveAll' ) {
					if( $this->controller->{$this->controller->modelClass}->saveAll( $this->controller->data ) ) {
						$this->controller->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
						$this->controller->redirect( array( 'action' => 'index' ) );
					}
				}
				else {
					$this->controller->{$this->controller->modelClass}->create( $this->controller->data );
					if( $this->controller->{$this->controller->modelClass}->save() ) {
						$this->controller->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
						$this->controller->redirect( array( 'action' => 'index' ) );
					}
				}
			}
			else if( $this->controller->action == 'edit' ) {
				$this->controller->data = $item;

				// Assign checkboxes - FIXME
				if( !empty( $this->controller->{$this->controller->modelClass}->hasAndBelongsToMany ) ) {
					$HABTMModelNames = array_keys( $this->controller->{$this->controller->modelClass}->hasAndBelongsToMany );
					foreach( $HABTMModelNames as $HABTMModelName )
						$this->controller->data = Xset::insert( $this->controller->data, "{$HABTMModelName}.{$HABTMModelName}", Set::extract( $this->controller->data, "/{$HABTMModelName}/id" ) );
				}
			}

            $this->controller->render( $this->controller->action, null, 'add_edit' );
		}

		/**
		* FIXME docs
		*
		* @access public
		*/

		public function delete( $id = null ) {
			$item = $this->controller->{$this->controller->modelClass}->findById( $id, null, null, -1 );
			$this->controller->assert( !empty( $item ), 'invalidParameter' );

			if( $this->controller->{$this->controller->modelClass}->delete( $id ) ) {
				$this->controller->Session->setFlash( __( 'Delete->success', true ), 'flash/success' );
			}
			else {
				$this->controller->Session->setFlash( __( 'Delete->error', true ), 'flash/error' );
			}

			$this->controller->redirect( $this->controller->referer() );
		}

		/**
		* Super postConditions ?
		* FIXME: dans le DefaultComponent
		*/

		public function conditions( array $data, array $operations ) {
			$conditions = array();

			/// Reformat values
			$data = Xset::bump( Set::normalize( $data ) );
			if( !empty( $data ) ) {
				foreach( $data as $model => $params ) {
					$model = ClassRegistry::init( $model );
					foreach( $params as $field => $value ) {
						/// FORMAT VALUE -> date
						if( is_array( $value ) && ( array_keys( $value ) == array( 'day', 'month', 'year' ) ) ) {
							$value = "{$value['year']}-{$value['month']}-{$value['day']}";
						}
						/// FORMAT VALUE -> phone, montant, ... FIXME: à ne faire qu'une seule fois pas modèle
						else {
							$model->create( array( $model->alias => array( $field => $value ) ) );
							$model->Behaviors->trigger( $model, 'beforeValidate', array( 'callbacks' => true ) );
							$value = Set::classicExtract( $model->data, "{$model->name}.{$field}" );
						}

                        if( $model->getColumnType( $field ) == 'datetime' ) { /// FIXME: si c'est un vrai datetime
                            $data[] = "{$model->alias}.{$field} BETWEEN '{$value}' AND '".date( 'Y-m-d', strtotime( $value ) + ( 24 * 60 * 60 ) )."'";
                            $data = Set::remove( $data, 'Ep.date' );
                        }
                        else {
                            $data[$model->alias][$field] = $value;
                        }
					}
				}
			}

			/// Special operations
			$operations = Xset::flatten( Set::normalize( $operations ) );
			if( !empty( $operations ) ) {
                $conn = ConnectionManager::getInstance();
				foreach( $operations as $path => $operation ) {
					switch( strtoupper( $operation ) ) {
						case 'BETWEEN':
							if( Set::check( $data, "{$path}_from" ) && Set::check( $data, "{$path}_to" ) ) {
								$from = Set::classicExtract( $data, "{$path}_from" );
								$to = Set::classicExtract( $data, "{$path}_to" );

								foreach( array( 'from', 'to' ) as $var ) {
									if( is_array( ${$var} ) ) {
										${$var} = "{${$var}['year']}-{${$var}['month']}-{${$var}['day']}";
									}
								}

								$conditions[] = "{$path} BETWEEN '{$from}' AND '{$to}'";
								$data = Set::remove( $data, "{$path}_from" );
								$data = Set::remove( $data, "{$path}_to" );
							}
							break;
						case 'LIKE':
						case 'ILIKE':
							if( Set::check( $data, $path ) ) {
								$value = Set::classicExtract( $data, $path );
								list( $model, $field ) = model_field( $path );

								$model = ClassRegistry::init( $model );
								$conn = ConnectionManager::getInstance();
								$driver = $conn->config->{$model->useDbConfig}['driver'];

								$conditions["{$path} ".( $driver == 'postgres' ? 'ILIKE' : 'LIKE' )] = "%$value%";
								$data = Set::remove( $data, $path );
							}
							break;
						default:
							if( Set::check( $data, $path ) ) {
								$value = Set::classicExtract( $data, $path );
								$conditions["{$path} {$operation}"] = $value;
								$data = Set::remove( $data, $path );
							}
							break;
					}

				}
			}

			/// data that were not in special formatting. TODO: option 1/0, voir postConditions
			$data = Xset::filterDeep( $data );
			if( !empty( $data ) ) {
				$data = Xset::flatten( $data );
				foreach( $data as $path => $value ) {
					$conditions[$path] = $value;
				}
			}

			return $conditions;
		}

        /**
        * The beforeRedirect method is invoked when the controller's redirect method
        * is called but before any further action. If this method returns false the
        * controller will not continue on to redirect the request.
        * The $url, $status and $exit variables have same meaning as for the controller's method.
        */
        function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
            parent::beforeRedirect( $controller, $url, $status , $exit );
        }
	}
?>