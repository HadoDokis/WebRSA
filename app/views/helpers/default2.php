<?php
	/**
	* @url http://fr2.php.net/manual/fr/function.array-merge.php#95294
	*/

	function array_extend( $a, $b ) {
		foreach($b as $k=>$v) {
			if( is_array($v) ) {
				if( !isset($a[$k]) ) {
					$a[$k] = $v;
				} else {
					$a[$k] = array_extend($a[$k], $v);
				}
			} else {
				$a[$k] = $v;
			}
		}
		return $a;
	}

	class Default2Helper extends AppHelper
	{
		public $helpers = array( 'Html', 'Xpaginator2', 'Locale', 'Xform', 'Type2' );

		/**
		* TODO docs
		*/

		public function button( $type, $url, $htmlAttributes = array(), $confirmMessage = false ) {
			$enabled = ( isset( $htmlAttributes['enabled'] ) ? $htmlAttributes['enabled'] : true );
			$iconFileSuffix = ( ( $enabled ) ? '' : '_disabled' ); // TODO: les autres aussi

			$htmlAttributes = array_filter_keys( $htmlAttributes, array( 'enabled' ), true );

			// TODO: une fonction ?
			$urlParams = Router::parse( str_replace( $this->base, '', Router::url( $url ) ) );
			$controllerName = Inflector::camelize( $urlParams['controller'] );

			$content = __( "Button::{$urlParams['action']}", true );

			$class = implode(
				' ',
				array(
					'button',
					$type,
					( $enabled ? 'enabled' : 'disabled' ),
					( isset( $htmlAttributes['class'] ) ? $htmlAttributes['class'] : null ),
				)
			);
			$htmlAttributes['class'] = $class;
			$htmlAttributes['escape'] = false;

			if( $enabled ) {
				return $this->Html->link(
					$content,
					$url,
					$htmlAttributes,
					$confirmMessage
				);
			}
			else {
				return $this->Html->tag( 'span', $content, $htmlAttributes, false, false );
			}
		}

		/**
		* @param string $path ie. User.id, User.0.id or Users::view
		* @param array $params
		* @return string
		* Valid keys for params:
		*	- domain
		*	- label
		*/

		public function label( $column, $options = array() ) {
			if( isset( $options['label'] ) ) {
				return $options['label'];
			}

			$domain = null;
			if( isset( $options['domain'] ) ) {
				$domain = $options['domain'];
			}

			// Posts::view
			if( strstr( $column, '::' ) !== false ) {
				list( $controller, $action ) = explode( '::', $column );

				if( empty( $options['domain'] ) ) {
					$domain = Inflector::singularize( Inflector::tableize( $controller ) );
				}

				return __d( $domain, $column, true );
			}

			// Post.id
			list( $currentModelName, $currentFieldName ) = Xinflector::modelField( $column );
			if( empty( $options['domain'] ) ) {
				$domain = Inflector::singularize( Inflector::tableize( $currentModelName ) );
			}

			return __d( $domain, "{$currentModelName}.{$currentFieldName}", true );
		}

		/**
		* @param array $datas
		* @param string $path ie. User.id
		* @param array $params
		* @return string
		* Valid keys for params:
		*	- model
		*	- type
		*	- domain -> TODO: unneeded ?
		*	- tag
		*	- options ie. array( 'User' => array( 'status' => array( 1 => 'Enabled', 0 => 'Disabled' ) ) )
		*	- TODO: value et type
		*/

		public function format( $datas, $path, $params = array() ) {
			return $this->Type2->format( $datas, $path, $params );
		}

		/**
		*
		*/

		public function thead( $columns, $params = array() ) {
			$thead = array();
			$actions = Set::classicExtract( $params, 'actions' );

			foreach( Set::normalize( $columns ) as $column => $options ) {
				$label = $this->label( $column, $options );

				if( Set::check( $this->Xpaginator2->params, 'paging' ) ) {
					$thead[] = $this->Xpaginator2->sort( $label, $column );
				}
				else {
					$thead[] = $label;
				}
			}

			$thead = $this->Html->tableHeaders( $thead );

			if( is_array( $actions ) && !empty( $actions ) ) {
				$thead = str_replace(
					'</tr>',
					'<th colspan="'.count( $actions ).'" class="action">Actions</th></tr>',
					$thead
				);
			}

			if( Set::check( $params, 'tooltip' ) ) {
				$thead = preg_replace( '/<\/tr>$/', "<th class=\"innerTableHeader noprint\">Informations complementaires</th></tr>", $thead );
			}

			return $this->Html->tag( 'thead', $thead );
		}

		/**
		*
		*/

		public function actions( $line, $params ) {
			$actions = Set::normalize( Set::classicExtract( $params, 'actions' ) );
			$tds = array();

			if( is_array( $actions ) && !empty( $actions ) ) {
				foreach( $actions as $action => $actionParams ) {
					if( $this->_translateVisible( $line, $actionParams ) ) {
						list( $controller, $action ) = explode( '::', $action );
						$controllerUrl = Inflector::underscore( $controller );
						$modelName = Inflector::classify( $controllerUrl );
						$domain = Inflector::singularize( Inflector::tableize( $modelName ) );

						$primaryKey = $this->Type2->primaryKey( $modelName );
						$displayField = $this->Type2->displayField( $modelName );

						$primaryKeyValue = Set::classicExtract( $line, "{$modelName}.{$primaryKey}" );
						$displayFieldValue = Set::classicExtract( $line, "{$modelName}.{$displayField}" );

						$enabled = !$this->Type2->translateDisabled( $line, $actionParams );
						// TODO
						unset( $actionParams['disabled'] );
						unset( $actionParams['condition'] );

						// FIXME: à mettre dans le DefaultHelper 1.3
						$url = (array)Set::classicExtract( $actionParams, 'url' );
						$url = array_extend(
							array(
								'controller' => $controllerUrl,
								'action' => $action,
								$primaryKeyValue
							),
							$url
						);
						// TODO: c'est moche ?
						foreach( $url as $key => $value ) {
							$url[$key] = dataTranslate( $line, $value );
						}

						if( $action == 'delete' ) {

							$value = $this->button(
								'delete',
								$url,
								array(
									'enabled' => $enabled,
									'title' => sprintf(
										__d( $domain, "{$controller}::{$action}", true ),
										$displayFieldValue
									)
								),
								sprintf(
									__d( $domain, "{$controller}::{$action}::confirm", true ),
									$displayFieldValue
								)
							);
						}
						else {
							$value = $this->button(
								$action,
								$url,
								array(
									'enabled' => $enabled,
									'title' => sprintf(
										__d( $domain, "{$controller}::{$action}", true ),
										$displayFieldValue
									)
								)
							);
						}

						$tds[] = $this->Html->tag( 'td', $value, array( 'class' => 'action' ) );
					}
				}
			}

			return implode( '', $tds );
		}

		/**
		*
		*/

		protected function _translateVisible( $data, $params ) {
			if( !isset( $params['condition'] ) ) {
				return true;
			}
			return $this->Type2->evaluate( $data, $params['condition'] );
		}

		/**
		* @param array $datas
		* @param array $cells ie. array( 'User.status' => array( 'domain' => 'Cohorte' ), 'User.userae' )
		* @param array $params
		* @return string
		* Valid keys for params:
		*	- domain
		*	- cohorte -> true/false
		*	- hidden
		*	- options ie. array( 'User' => array( 'status' => array( 1 => 'Enabled', 0 => 'Disabled' ) ) )
		* 	- tooltip
		*	- valuePath en paramètre de chacun des input
		*	- groupColumns
		*/

		public function index( $datas, $cells, $cohorteParams = array() ) {
			/// TODO: supprimer le bouton ajouter de l'index
			/// TODO: function
			$name = Inflector::camelize( $this->params['controller'] );
			$action = $this->action;
			// FIXME: est-ce plus correct + MAJ 1.3.x_default_helper
			//$modelName = Inflector::classify( Inflector::tableize( $name ) );
			$modelName = Inflector::classify( Inflector::underscore( $name ) );
			$cohorte = Set::classicExtract( $cohorteParams, 'cohorte' );
			$domain = Inflector::singularize( Inflector::tableize( $modelName ) );
			///

			$cells = Set::normalize( $cells );
			$cohorteOptions = Set::classicExtract( $cohorteParams, "options" );
			$cohorteHidden = Set::classicExtract( $cohorteParams, "hidden" );

			if( Set::check( $cohorteParams, "id" ) ) {
				$containerId = $value = Set::classicExtract( $cohorteParams, "id" );
				if( !$value ) {
					unset( $cohorteParams['id'] );
				}
			}
			else {
				$containerId = $cohorteParams['id'] = Inflector::camelize( "{$name}_{$action}" );
			}

			$oddOptions = array( 'class' => 'odd');
			$evenOptions = array( 'class' => 'even');

			$trs = array();
			foreach( $datas as $key => $data ) {
				$iteration = 0;
				$line = array();
				foreach( $cells as $path => $params ) {
					if( $this->_translateVisible( $data, $params ) ) {
						$params = $this->Type2->prepare( 'output', $path, $params );
						list( $model, $field ) = Xinflector::modelField( $path );
						$validationErrors = $this->validationErrors[$modelName];

						$cohortePath = str_replace( ".", ".$key.", $path );
						$type = Set::classicExtract( $params, 'input' );
						unset( $params['input'] );

						if( !empty( $cohorteOptions ) && !isset( $params['options'] ) ) {
							$params['options'] = $cohorteOptions;
						}

						// TODO
						if( !Set::check( $this->data, $cohortePath ) ) {
							$params['value'] = Set::classicExtract( $data, $path );
						}

						$hiddenFields = '';
						if( ( $cohorte == true ) && ( $iteration == 0 ) && !empty( $cohorteHidden ) ) {
							foreach( Set::normalize( $cohorteHidden ) as $hiddenPath => $hiddenParams ) {
								$hiddenParams = Set::merge( $hiddenParams, array( 'type' => 'hidden' ) );
								if( !Set::check( $this->data, $cohortePath ) ) {
									if( !Set::check( $hiddenParams, 'value' ) ) {
										if( Set::check( $hiddenParams, 'valuePath' ) ) {
											$hiddenParams['value'] = Set::classicExtract( $data, $hiddenParams['valuePath'] );
											unset( $hiddenParams['valuePath'] );
										}
										else {
											$hiddenParams['value'] = Set::classicExtract( $data, $hiddenPath );
										}
									}
								}

								$hiddenFields .= $this->Xform->input( str_replace( ".", ".$key.", $hiddenPath ), $hiddenParams );
							}
						}

						if( !empty( $type ) ) {
							switch( $type ) {
								case 'radio':
								case 'checkbox':
								case 'select':
								case 'text':
									$params['type'] = $type;
									$params['label'] = false;
									$params['legend'] = false;
									$params['div'] = false;

									if( !in_array( $type, array( 'select', 'radio' ) ) ) {
										unset( $params['options'] );
									}
									else if( Set::check( $cohorteParams, "options.{$model}.{$field}" ) ) {
										$params['options'] = Set::classicExtract( $cohorteParams, "options.{$model}.{$field}" );
									}

									if( !isset( $params['multiple'] ) && !in_array( $type, array( 'radio' ) ) ) {
										unset( $params['legend'] );
									}

									if( in_array( $type, array( 'radio' ) ) ) {
										unset( $params['label'] );
									}

									if( !Set::check( $this->data, $path ) ) {
										if( Set::check( $params, 'valuePath' ) ) {
											$value = Set::classicExtract( $data, $params['valuePath'] );
											unset( $params['valuePath'] );
										}
									}

									/// TODO: avec $this->data
									if( $type == 'checkbox' && Set::check( $params, 'value' ) ) {
										$params['checked'] = ( $params['value'] ? true : false );
									}


									$tdParams = array( 'class' => "input {$type}" );
									if( Set::check( $validationErrors, "{$key}.{$field}" ) ) {
										$tdParams = $this->addClass( $tdParams, 'error' );
									}

									/// Error handling -> INFO: 1.3 -> le fait tout seul
		// 								$error = '';
		// 								if( Set::check( $this->validationErrors, "{$modelName}.{$key}.{$field}" ) ) {
		// 									$error = Set::classicExtract( $this->validationErrors, "{$modelName}.{$key}.{$field}" );
		// 									if( !empty( $error ) ) {
		// 										$tdParams['class'] = "{$tdParams['class']} error";
		// 										$error = $this->Html->tag( 'div', $error, array( 'class' => 'error-message' ) );
		// 									}
		// 								}

									$params['disabled'] = $this->Type2->translateDisabled( $data, $params );
									$line[] = $this->Html->tag( 'td', $hiddenFields.$this->Type2->input( $cohortePath, $params )/*.$error*/, $tdParams );
									break;
								default:
									$params['disabled'] = $this->Type2->translateDisabled( $data, $params );
									$line[] = $this->Html->tag( 'td', $hiddenFields.$this->Type2->format( $data, $path, $params ) );
							}
						}
						else {
							$td = $this->Type2->format( $data, $path, Set::merge( $params, array( 'tag' => 'td' ) ) );
							$line[] = preg_replace( '/<\/td>$/', "$hiddenFields</td>", $td );
						}
						$iteration++;
					}
				}

				$line = implode( '', $line ).$this->actions( $data, $cohorteParams );
				if( Set::check( $cohorteParams, 'tooltip' ) ) {
					$tooltip = Set::extract( $cohorteParams, 'tooltip' );
					$tooltip = $this->view( $data, $tooltip, array( 'widget' => 'table', 'class' => 'innerTable', 'id' => "innerTable{$key}" ) );
					$line .= $this->Html->tag( 'td', $tooltip, array( 'class' => 'innerTableCell noprint' ) );
				}

				$trOptions = ( ( ( $key + 1 ) % 2 ) ?  $oddOptions : $evenOptions );
				/// TODO: prefixer l'id du conteneur si présent + si l'id est à false -> pas d'id, sinon calcul auto
				$trOptions['id'] = $containerId.'Row'.( $key + 1 );
				$trs[] = $this->Html->tag( 'tr', $line, $trOptions );
			}

			$return = '';

			/// Liste d'actions communes à la table
			if( Set::check( $cohorteParams, 'add' ) ) { // TODO: ensemble d'actions
				$actions = Set::normalize( Set::classicExtract( $cohorteParams, 'add' ) );

				if( $actions == true ) {
					$controllerName = Inflector::camelize( $this->params['controller'] );
					/*$controllerUrl = Inflector::tableize( $modelName );
					$controllerName = Inflector::camelize( $controllerUrl );
					debug( $controllerName );
					debug( $controllerUrl );*/
					/// INFO: modification faite par gaëtan pour personaliser l'url
					$url = array();
					foreach( $actions as $text => $actionParams ) {
						$url = $actionParams;
					}
					if (empty($url))
						$url = array( 'controller' => $this->params['controller'], 'action' => 'add' );
					$actions = array(
						"{$controllerName}::add" => array( 'url' => $url )
					);
				}

				$lis = array();
				foreach( $actions as $text => $actionParams ) {
						$lis[] = $this->Html->tag(
						'li',
						$this->button(
							$actionParams['url']['action'],
							$actionParams['url'],
							array( 'title' => __d( $domain, $text, true ) )
						),
						array( 'class' => $actionParams['url']['action'] )
					);
				}
				$return .= $this->Html->tag(
					'ul',
					implode( "\n", $lis ),
					array( 'class' => 'actions' )
				);
			}

			if( empty( $trs ) ) {
				return $return.$this->Html->tag(
					'p',
					__d( $domain, "{$modelName}::index::empty", true ),
					array( 'class' => 'notice' )
				);
			}

			$tableOptions = array();
			if( Set::check( $cohorteParams, 'tooltip' ) ) { /// TODO: th
				$tableOptions['class'] = 'tooltips';
			}

			/// TODO
			$paginateModel = $modelName;
			if( Set::check( $cohorteParams, 'paginate' ) ) {
				$paginateModel = Set::classicExtract( $cohorteParams, 'paginate' );
			}

			$thead = $this->thead( $cells, $cohorteParams );
			if( Set::check( $cohorteParams, 'groupColumns' ) ) {
				$groupColumns = Set::classicExtract( $cohorteParams, 'groupColumns' );
				$thead = $this->groupColumns( $thead, $groupColumns );
			}

			$pagination = $this->Xpaginator2->paginationBlock( $paginateModel, Set::merge( $this->params['pass'], $this->params['named'] ) );
			$return .= $pagination.$this->Html->tag(
				'table',
				$thead.
				$this->Html->tag( 'tbody', implode( '', $trs ) ),
				$tableOptions
			).$pagination;

			if( $cohorte == true ) {
				$return = $this->Xform->create( null, array( 'url' => Set::merge( array( 'controller' => $this->params['controller'], 'action' => $this->params['action'] ), $this->params['pass'], $this->params['named'] ) ) ).$return;
			}

			/// Hidden -> TODO $this->data
			if( ( $cohorte == true ) && Set::check( $cohorteParams, 'search' ) ) {
				foreach( Set::extract( $cohorteParams, 'search' ) as $searchModelField ) {
					$key = "Search.$searchModelField";
					$return .= $this->Xform->input( $key, array( 'type' => 'hidden' ) );
				}
			}
			/// TODO: ids
			if( $cohorte == true ) {
				if( Set::check( $this->data, 'Search' ) ) { /// TODO: + page / sort / ...
					$search = Set::extract( $this->data, 'Search' );
					if( !empty( $search ) ) {
						$search = Set::flatten( array( 'Search' => $search ) );
						foreach( $search as $path => $value ) {
							$return .= $this->Xform->input( $path, array( 'type' => 'hidden' ) );
						}
					}
				}

				// Pagination
	// 				foreach( array( 'page', 'sort', 'direction' ) as $paginationKey ) {
	// 					if( Set::check( $this->params, "named.{$paginationKey}" ) ) {
	// 						$return .= $this->Xform->input( $paginationKey, array( 'type' => 'hidden', 'value' => Set::classicExtract( $this->params, "named.{$paginationKey}" ) ) );
	// 					}
	// 				}

				$return .= $this->Xform->submit( __( 'Validate', true ), array( 'name' => 'cohorte' ) );
				$return .= $this->Xform->end();
				$return = $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) ).$return;
			}

			return $return;
		}

		/**
		*
		*/

		public function subform( $fields, $formParams = array() ) {
			$default = array();

			$fields = Set::normalize( $fields );
			foreach( $fields as $fieldName => $options ) {
				$fields[$fieldName] = Set::merge( $default, $options );
			}

			$return = '';

			foreach( $fields as $path => $params ) {
				list( $fieldModelName, $fieldModelfield ) = Xinflector::modelField( $path );
				if( !Set::check( $params, 'options' ) ) {
					$options = Set::extract( $formParams, "options.{$fieldModelName}.{$fieldModelfield}" );
					if( !empty( $options ) ) {
						$params['options'] = $options;
					}
				}

				$return .= $this->Type2->input( $path, $params );
			}

			return $return;
		}

		/**
		*
		*/

		public function form( $fields, $formParams = array() ) {
			$name = Inflector::camelize( $this->params['controller'] );
			$action = $this->action;
			/// TODO: vérifier, c'est tjs le classify du nom de la table
			$modelName = Inflector::classify( $this->params['controller'] );
			$domain = Inflector::singularize( Inflector::tableize( $modelName ) );

			$primaryKey = $this->Type2->primaryKey( $modelName );
			$primaryKeyValue = Set::classicExtract( $this->data, "{$modelName}.{$primaryKey}" );

			$return = '';
			$return .= $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => $domain ) ) );

			if( !empty( $primaryKeyValue ) ) {
				$return .= $this->Xform->input( "{$modelName}.{$primaryKey}" );
			}

			$return .= $this->subform( $fields, $formParams );

			/// Form buttons -> TODO: en faire une fonction
			$submit = array( 'Save' => 'submit' );
			if( Set::check( $formParams, 'submit' ) ) {
				$submit = Set::classicExtract( $formParams, 'submit' );
				if( is_string( $submit ) ) {
					$submit = array( $submit => 'submit' );
				}
			}

			$buttons = array();
			$default = array( 'type' => 'submit' );
			foreach( $submit as $value => $options ) {
				if( is_string( $options ) ) {
					$options = array( 'type' => $options );
				}
				$options = Set::merge( $default, $options );
				$options['class'] = "input {$options['type']}";
				$buttons[] = $this->Xform->button( __( $value, true ), $options );
			}

			$return .= $this->Html->tag( 'div', implode( ' ', $buttons ), array( 'class' => 'submit' ) );
			$return .= $this->Xform->end();

			return $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) ).$return;
		}

		/**
		*
		*/

		public function search( array $fields, array $params = array() ) {
			$domain = strtolower( Inflector::classify( $this->params['controller'] ) );

			$params['inputDefaults'] = Set::merge(
				array(
					'required' => false,
					'domain' => $domain,
					// TODO: le faire pour les bons input
	// 					'empty' => true,
	// 					'dateFormat' => __( 'Locale->dateFormat', true ),
				),
				Set::extract(
					$params,
					'inputDefaults'
				)
			);

			$paramsOptions = Set::extract( $params, 'options' );
			unset( $params['options'] );

			// Was search data sent ?
			$data = ( !empty( $this->data ) ? array_keys( $this->data ) : array() );
	// 			$data = preg_replace( '/^Search_/', 'Search.', $data );
			$data = Xset::bump( Set::normalize( $data ) );
			// FIXME: ajouter le bouton pour le déplier
			/*if( Set::check( $data, 'Search' ) ) {
				$params = $this->addClass( $params, 'folded' );
			}*/

			$return = $this->Xform->create( null, $params );

			foreach( Set::normalize( $fields ) as $fieldName => $options ) {
				list( $fieldModelName, $fieldModelfield ) = Xinflector::modelField( $fieldName );

				/// TODO: function ?
				if( Set::check( $paramsOptions, "{$fieldModelName}.{$fieldModelfield}" ) && empty( $options['options'] ) ) {
					$options['options'] = Set::classicExtract( $paramsOptions, "{$fieldModelName}.{$fieldModelfield}" );
				}

				// Ajouter les options 'dateFormat' => 'Locale->dateFormat' si besoin quand c'est le bon type
				//$return .= $this->Xform->input( "Search_$fieldName", $options );

				list( $options['model'], $options['field'] ) = Xinflector::modelField( $fieldName );
				$options = $this->Type2->prepare( 'input', $fieldName, $options );
				$return .= $this->Type2->input( "Search.$fieldName", $options );
			}

			$return .= $this->Xform->input( "Search.active", array( 'value' => true, 'type' => 'hidden' ) );
			$return .= $this->Xform->submit( __( 'Search', true ) );
			$return .= $this->Xform->end();

			return $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) ).$return;
		}

		/**
		* @param array $item
		* @param array $columns ie. array( 'User.status', 'User.userae' )
		* @param array $options
		* @return string
		* Valid keys for params:
		*	- widget -> dl, table
		*	- options ie. array( 'User' => array( 'status' => array( 1 => 'Enabled', 0 => 'Disabled' ) ) )
		*   - domain
		* TODO: $this->defaultModel() à la place de $this->params, 'controller'
		*/

		public function view( $item, $columns, $options = array() ) { // TODO: rename options en viewParams
			$widget = Set::classicExtract( $options, 'widget' );
			$widget = ( empty( $widget ) ? 'table' : $widget );
			unset( $options['widget'] );

			$name = Inflector::camelize( Set::classicExtract( $this->params, 'controller' ) ); // TODO -> params + params -> table/list
			$modelName = Inflector::classify( $name );

			$rows = array();
			$lineNr = 1;
			foreach( Set::normalize( $columns ) as $column => $columnOptions ) {
				$columnOptions = $this->Type2->prepare( 'output', $column, $columnOptions );
				list( $columnModel, $columnField ) = Xinflector::modelField( $column );
				$columnDomain = Inflector::singularize( Inflector::tableize( $columnModel ) );
				/// dans une fonction ?

				if( !Set::check( $columnOptions, 'domain' ) ) {
					if( Set::check( $options, 'domain' ) ) {
						$columnOptions['domain'] = $options['domain'];
					}
					else {
						$columnOptions['domain'] = $columnDomain;
					}
				}

				$formatOptions = $labelOptions = $columnOptions = $this->addClass( $columnOptions, ( ( $lineNr % 2 ) ?  'odd' : 'even' ) );

				/// TODO
				unset(
					$columnOptions['domain'],
					$columnOptions['type'],
					$columnOptions['null'],
					$columnOptions['default'],
					$columnOptions['country'],
					$columnOptions['length'],
					$columnOptions['virtual'],
					$columnOptions['key'],
					$columnOptions['options'],
					$columnOptions['dateFormat'],
					$columnOptions['maxlength'],
					$columnOptions['suffix'],
					$columnOptions['currency']
				);

				$line = $this->Html->tag(
					( ( $widget == 'table' ) ? 'th' : 'dt' ),
					$this->label( $column, $labelOptions ),
					$columnOptions
				);

				$params = array( 'tag' => ( ( $widget == 'table' ) ? 'td' : 'dd' ) );
				foreach( array( 'options', 'type', 'class', 'domain' ) as $optionsKey ) {
					if( isset( $columnOptions[$optionsKey] ) ) {
						$params[$optionsKey] = $columnOptions[$optionsKey];
					}
				}

				if( $widget == 'dl' ) {
					$params['class'] = $columnOptions['class'];
				}

				if( Set::check( $options, 'options' ) && !Set::check( $params, 'options' ) ) {
					$params['options'] = $options['options'];
				}

				$params = Set::merge( $params, $formatOptions );
				unset( $params['null'], $params['country'], $params['length'] );

				$line .= $this->Type2->format( $item, $column, $params );

				if( $widget == 'table' ) {
					$rows[] = $this->Html->tag( 'tr', $line, array( 'class' => $params['class'] ) );
				}
				else {
					$rows[] = $line;
				}

				$lineNr++;
			}

			$defaultOptions = array(
	// 				'id' => "{$modelName}View",
				'class' => 'view',
			);

			$options = Set::merge( $defaultOptions, $options );
			unset( $options['options'] );

			if( $widget == 'table' ) {
				$return = $this->Html->tag(
					'table',
					$this->Html->tag(
						'tbody',
						implode( '', $rows )
					),
					$options
				);
			}
			else {
				$return = $this->Html->tag(
					'dl',
					implode( '', $rows ),
					$options
				);
			}

			return $return;
		}

		/**
		* TODO: faire h( la traduction )
		*/

		public function groupColumns( $thead, $group ) {
			preg_match_all( '/(<th(?!\w).*<\/th>)/U', $thead, $matches, PREG_PATTERN_ORDER );
			$ths = $matches[0];
			$firstline = array();
			$secondline = array();

			$group = Set::normalize( $group );
			$groupedColumns = Set::flatten( $group );

			foreach( $ths as $position => $th ) {
				if( in_array( $position, $groupedColumns ) ) {
					$key = array_search( $position, $groupedColumns );
					if( preg_match( '/^(.*)\.0$/', $key, $matches ) ) { // premier
						$firstline[] = '<th colspan="'.count( $group[$matches[1]] ).'">'.$matches[1].'</th>';
					}

					$secondline[] = $th;
				}
				else {
					$firstline[] = preg_replace( '/(<th(?!\w))/U', '<th rowspan="2"', $th );
				}
			}

			return "<thead><tr>".implode( $firstline )."</tr><tr>".implode( $secondline )."</tr></thead>";
		}

		/**
		* TODO: permissions
		*/

		public function menu( $items ) {
			$return = '';
			foreach( $items as $key => $item ) {
				if( is_array( $item ) && isset( $item['controller'] ) && isset( $item['action'] ) ) {
					$return .= $this->Html->tag(
						'li',
						$this->Html->link( $key, $item )
					);
				}
				else if( is_array( $item ) ) {
					$return .= $this->Html->tag(
						'li',
						$this->Html->link( $key, '#' ).$this->menu( $item )
					);
				}
				else {
					trigger_error( "got {$item} insteat of array", E_USER_ERROR );
				}
			}

			if( !empty( $return ) ) {
				$return = "<ul>{$return}</ul>";
			}

			return $return;
		}
	}
?>
