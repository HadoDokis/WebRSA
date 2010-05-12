<?php
	class DefaultHelper extends AppHelper
	{
        public $helpers = array( 'Html', 'Xpaginator', 'Locale', 'Xform', 'Type', 'Permissions' );

		/**
		* FIXME docs
		*/

        public function button( $type, $url, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true ) {
            $enabled = ( isset( $htmlAttributes['enabled'] ) ? $htmlAttributes['enabled'] : true );
            $iconFileSuffix = ( ( $enabled ) ? '' : '_disabled' ); // TODO: les autres aussi

            switch( $type ) {
                case 'add':
                    $icon = 'icons/add'.$iconFileSuffix.'.png';
                    $text = __( 'Add', true );
                    break;
                case 'edit':
                    $icon = 'icons/pencil'.$iconFileSuffix.'.png';
                    $text = __( 'Edit', true );
                    break;
                case 'delete':
                    $icon = 'icons/delete'.$iconFileSuffix.'.png';
                    $text = __( 'Delete', true );
                    break;
                case 'process':
                    $icon = 'icons/lightning'.$iconFileSuffix.'.png';
                    $text = __( 'Process', true );
                    break;
                case 'view':
                    $icon = 'icons/zoom'.$iconFileSuffix.'.png';
                    $text = __( 'View', true );
                    break;
                case 'print':
                    $icon = 'icons/printer'.$iconFileSuffix.'.png';
                    $text = __( 'Imprimer', true );
                    break;
                case 'gedooo':
                    $icon = 'icons/printer'.$iconFileSuffix.'.png';
                    $text = __( 'Imprimer', true );
                    break;
                case 'printcohorte':
                    $icon = 'icons/printer'.$iconFileSuffix.'.png';
                    $text = __( 'Imprimer en cohorte', true );
                    break;
                case 'back':
                    $icon = 'icons/arrow_left'.$iconFileSuffix.'.png';
                    $text = __( 'Retour', true );
                    break;
                case 'equipe':
                    $icon = 'icons/door_out'.$iconFileSuffix.'.png';
                    $text = __( 'Traitement équipe', true );
                    break;
                case 'conseil':
                    $icon = 'icons/door_out'.$iconFileSuffix.'.png';
                    $text = __( 'Traitement conseil', true );
                    break;
                case 'ordre':
                    $icon = 'icons/book_open'.$iconFileSuffix.'.png';
                    $text = __( 'Ordre du jour', true );
                    break;
//                 case 'validate':
//                     $icon = 'icons/tick'.$iconFileSuffix.'.png';
//                     $text = __( '', true );
//                     break;
                case 'valid':
                    $icon = 'icons/tick'.$iconFileSuffix.'.png';
                    $text = __( 'Finaliser', true );
                    break;
                case 'decision':
                    $icon = 'icons/user_comment'.$iconFileSuffix.'.png';
                    $text = __( 'Décisions', true );
                    break;
                case 'liste_demande_reorient':
                    $icon = 'icons/user_comment'.$iconFileSuffix.'.png';
                    $text = __( 'Demandes de réorientation', true );
                    break;
                default:
                    $this->cakeError( 'error500' ); // FIXME -> proprement --> $this->cakeError( 'wrongParameter' )
            }

            $content = $this->Html->image( $icon, array( 'alt' => '' ) ).' '.$text;

            $class = implode(
                ' ',
                array(
                    'widget button',
                    $type,
                    ( $enabled ? 'enabled' : 'disabled' ),
                    ( isset( $htmlAttributes['class'] ) ? $htmlAttributes['class'] : null ),
                )
            );
            $htmlAttributes['class'] = $class;

			//$htmlAttributes = array_filter_keys( $htmlAttributes, array( 'enabled' ), true );
			unset( $htmlAttributes['enabled'] );


            if( $enabled ) {
                return $this->Html->link(
                    $content,
                    $url,
                    $htmlAttributes,
                    $confirmMessage,
                    false
                );

            }
            else {
                return $this->Html->tag( 'span', $content, $htmlAttributes, false, false );
            }
        }

		/**
		* @param string $path ie. User.id, User.0.id
		* @param array $params
		* @return string
		* Valid keys for params:
		*	- domain
		*	- label
		*/

		public function label( $column, $options = array() ) {
			if( !Set::check( $options, 'label' ) ) {
				list( $currentModelName, $currentFieldName ) = model_field( $column );

				if( Set::check( $options, 'domain' ) && !empty( $options['domain'] ) ) {
					$domain = $options['domain'];
				}
				else {
					$domain = Inflector::singularize( Inflector::tableize( $currentModelName ) );
				}

				return __d( $domain, "{$currentModelName}.{$currentFieldName}", true );
			}
			else {
				return $options['label'];
			}
		}

		/**
		* @param array $datas
		* @param string $path ie. User.id
		* @param array $params
		* @return string
		* Valid keys for params:
		*	- model
		*	- type
		*	- domain -> FIXME: unneeded ?
		*	- tag
		*	- options ie. array( 'User' => array( 'status' => array( 1 => 'Enabled', 0 => 'Disabled' ) ) )
		*	- TODO: value et type
		*/

		public function format( $datas, $path, $params = array() ) {
			return $this->Type->format( $datas, $path, $params );
		}

		/**
		* @param string $path ie. User.id
		* @param array $params
		* @return string
		* Valid keys for params:
		*	- options ie. array( 'User' => array( 'status' => array( 1 => 'Enabled', 0 => 'Disabled' ) ) )
		*/

		/*public function field( $path, $params = array() ) {
			list( $modelName, $fieldName ) = model_field( $path );

			// If field is of "type enum", translate it
			if( Set::check( $params, "options.{$modelName}.{$fieldName}" ) ) {
				$params['options'] = Set::classicExtract( $params, "options.{$modelName}.{$fieldName}" );
			}
// debug( $params );
			return $this->Xform->input( $path, $params );
		}*/

		/**
		*
		*/

		public function thead( $columns, $params = array() ) {
			$thead = array();
			$actions = Set::classicExtract( $params, 'actions' );

			foreach( Set::normalize( $columns ) as $column => $options ) {
                if( !Set::check( $options, 'domain' ) && Set::check( $params, 'domain' ) ) {
                    $options['domain'] = $params['domain'];
                }

				$label = $this->label( $column, $options );

				if( Set::check( $this->Xpaginator->params, 'paging' ) ) {
					$thead[] = $this->Xpaginator->sort( $label, $column );
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
					list( $modelName, $action ) = model_field( $action );

                    if( Set::check( $actionParams, 'domain' ) ) {
                        $domain = $actionParams['domain'];
                        unset( $actionParams['domain'] );
                    }
                    else if( Set::check( $params, 'domain' ) ) {
                        $domain = $params['domain'];
                    }
                    else {
                        $domain = Inflector::singularize( Inflector::tableize( $modelName ) );
                    }

					$controller = Inflector::tableize( $modelName );
					$controllerName = Inflector::camelize( $modelName );
					$model = ClassRegistry::init( $modelName );

					// TODO
// 					foreach( array( 'controller', 'action' ) as $t ) {
// 						if( Set::check( $actionParams, $t ) ) {
// 							$$t = Set::classicExtract( $actionParams, $t );
// 						}
// 					}

					if( $action == 'delete' ) {
						$value = $this->button(
							'delete',
                            Set::merge(
                                array(
                                    'controller' => $controller,
                                    'action' => 'delete',
                                    Set::classicExtract( $line, "{$model->name}.{$model->primaryKey}" )
                                ),
                                $actionParams
                            ),
							array(
								'title' => sprintf(
									__d( $domain, "{$controllerName}::{$action}", true ),
									Set::classicExtract( $line, "{$model->name}.{$model->displayField}" )
								),
								'enabled' => $this->Permissions->check( $controller, $action )
							),
							sprintf(
								__d( $domain, "{$controllerName}::{$action}::confirm", true ),
								Set::classicExtract( $line, "{$model->name}.{$model->displayField}" )
							)
						);
					}
					else {
						$value = $this->button(
							$action,
                            Set::merge(
                                array(
                                    'controller' => $controller,
                                    'action' => $action,
                                    Set::classicExtract( $line, "{$model->name}.{$model->primaryKey}" ),
                                ),
                                $actionParams
                            ),
							array(
								'title' => sprintf(
									__d( $domain, "{$controllerName}::{$action}", true ),
									Set::classicExtract( $line, "{$model->name}.{$model->displayField}" )
								),
								'enabled' => $this->Permissions->check( $controller, $action )
							)
						);

					}

					$tds[] = $this->Html->tag( 'td', $value, array( 'class' => 'action' ) );
				}
			}

			return implode( '', $tds );
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
			/// FIXME: supprimer le bouton ajouter de l'index
			/// FIXME: function
			$name = Inflector::camelize( $this->params['controller'] );
			$action = $this->action;
			//$modelName = Inflector::classify( Inflector::tableize( $name ) );
			$modelName = Inflector::classify( $name );
			$modelClass = ClassRegistry::init( Inflector::classify( $modelName ) );
			$cohorte = Set::classicExtract( $cohorteParams, 'cohorte' );

            if( Set::check( $cohorteParams, 'domain' ) ) {
                $domain = $cohorteParams['domain'];
            }
            else {
                $domain = Inflector::singularize( Inflector::tableize( $modelName ) );
            }
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
					$params = $this->Type->prepare( 'output', $path, $params );
					list( $model, $field ) = model_field( $path );
					$validationErrors = ClassRegistry::init( $model )->validationErrors;

					$cohortePath = str_replace( ".", ".$key.", $path );
					$type = Set::classicExtract( $params, 'input' );
					unset( $params['input'] );

					if( !empty( $cohorteOptions ) && !isset( $params['options'] ) ) {
						$params['options'] = $cohorteOptions;
					}

					// FIXME
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

								/// FIXME: avec $this->data
								if( $type == 'checkbox' && Set::check( $params, 'value' ) ) {
									$params['checked'] = ( $params['value'] ? true : false );
								}


								$tdParams = array( 'class' => "input {$type}" );
								if( Set::check( $validationErrors, "{$key}.{$field}" ) ) {
									$tdParams = $this->addClass( $tdParams, 'error' );
								}

								/// Error handling FIXME $modelClass
								$error = '';
								if( Set::check( $modelClass->validationErrors, "{$key}.{$field}" ) ) {
									$error = Set::classicExtract( $modelClass->validationErrors, "{$key}.{$field}" );
									if( !empty( $error ) ) {
										$tdParams['class'] = "{$tdParams['class']} error";
										$error = $this->Html->tag( 'div', $error, array( 'class' => 'error-message' ) );
									}
								}

								$line[] = $this->Html->tag( 'td', $hiddenFields.$this->Type->input( $cohortePath, $params ).$error, $tdParams );
								break;
							default:
								$line[] = $this->Html->tag( 'td', $hiddenFields.$this->Type->format( $data, $path, $params ) );
						}
					}
					else {
						$td = $this->Type->format( $data, $path, Set::merge( $params, array( 'tag' => 'td' ) ) );
						$line[] = preg_replace( '/<\/td>$/', "$hiddenFields</td>", $td );
					}
					$iteration++;
				}

				$line = implode( '', $line ).$this->actions( $data, $cohorteParams );
				if( Set::check( $cohorteParams, 'tooltip' ) ) {
					$tooltip = Set::extract( $cohorteParams, 'tooltip' );
					$tooltip = $this->view( $data, $tooltip, array( 'widget' => 'table', 'class' => 'innerTable', 'id' => "innerTable{$key}" ) );
					$line .= $this->Html->tag( 'td', $tooltip, array( 'class' => 'innerTableCell noprint' ) );
				}

				$trOptions = ( ( ( $key + 1 ) % 2 ) ?  $oddOptions : $evenOptions );
				/// FIXME: prefixer l'id du conteneur si présent + si l'id est à false -> pas d'id, sinon calcul auto
				$trOptions['id'] = $containerId.'Row'.( $key + 1 );
				$trs[] = $this->Html->tag( 'tr', $line, $trOptions );
			}

			$return = '';

			/// Liste d'actions communes à la table
			if( Set::check( $cohorteParams, 'add' ) ) { // FIXME: ensemble d'actions
                $actions = Set::normalize( Set::classicExtract( $cohorteParams, 'add' ) );

                // FIXME + faire un fonction, voir actions dans la table
                $actionModelField = array_keys( $actions );
                $actionModelField = $actionModelField[0];

				list( $actionModel, $actionAction ) = model_field( $actionModelField );

                $url = Set::merge(
                    array(
                        'controller' => Inflector::tableize( $actionModel ),
                        'action' => $actionAction
                    ),
                    $actions[$actionModelField]
                );

				$return .= $this->Html->tag(
					'p',
					$this->button(
						$actionAction,
						$url,
						array(
							'title' => __d( $domain, "{$actionModel}::{$actionAction}", true ),
							'enabled' => $this->Permissions->check( $url['controller'], $url['action'] )
						)
					),
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
			if( Set::check( $cohorteParams, 'tooltip' ) ) { /// FIXME: th
				$tableOptions['class'] = 'tooltips';
			}

			/// FIXME
			$paginateModel = $modelClass->name;
			if( Set::check( $cohorteParams, 'paginate' ) ) {
				$paginateModel = Set::classicExtract( $cohorteParams, 'paginate' );
			}

			$thead = $this->thead( $cells, $cohorteParams );
			if( Set::check( $cohorteParams, 'groupColumns' ) ) {
				$groupColumns = Set::classicExtract( $cohorteParams, 'groupColumns' );
				$thead = $this->groupColumns( $thead, $groupColumns );
			}

			$pagination = $this->Xpaginator->paginationBlock( $paginateModel, Set::merge( $this->params['pass'], $this->params['named'] ) );
			$return .= $pagination.$this->Html->tag(
				'table',
				$thead.
				$this->Html->tag( 'tbody', implode( '', $trs ) ),
				$tableOptions
			).$pagination;

			if( $cohorte == true ) {
				$return = $this->Xform->create( null, array( 'url' => Set::merge( array( 'controller' => $this->params['controller'], 'action' => $this->params['action'] ), $this->params['pass'], $this->params['named'] ) ) ).$return;
			}

			/// Hidden -> FIXME $this->data
			if( ( $cohorte == true ) && Set::check( $cohorteParams, 'search' ) ) {
				foreach( Set::extract( $cohorteParams, 'search' ) as $searchModelField ) {
					$key = "Search.$searchModelField";
					$return .= $this->Xform->input( $key, array( 'type' => 'hidden' ) );
				}
			}
			/// FIXME: ids
			if( $cohorte == true ) {
				if( Set::check( $this->data, 'Search' ) ) { /// FIXME: + page / sort / ...
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
				$return = $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false ).$return;
			}

			return $return;
		}

		/**
		* FIXME: corriger affichage des montants lorsque le champ est DECIMAL(10,2)
        *           TEXTAREA au lieu de champ text : 'type' => 'text' corrige le problème
		*/

		public function subform( $fields, $formParams = array() ) {
			$default = array();

			$fields = Set::normalize( $fields );
			foreach( $fields as $fieldName => $options ) {
				$fields[$fieldName] = Set::merge( $default, $options );
			}

			$return = '';

			foreach( $fields as $path => $params ) {
				list( $fieldModelName, $fieldModelfield ) = model_field( $path );
				$modelClass = ClassRegistry::init( $fieldModelName );

				$required = ( count( Set::extract( $modelClass->validate, "/{$fieldModelfield}[rule=notEmpty]" ) ) > 0 );
				$params = Set::merge( array( 'required' => $required ), $params );

				/*if( !Set::check( $params, 'options' ) && Set::check( $formParams, "options.{$fieldModelName}.{$fieldModelfield}" ) ) {
					$params['options'] = Set::classicExtract( $formParams, "options.{$fieldModelName}.{$fieldModelfield}" );
					if( !Set::check( $params, 'empty' ) ) {
						$params['empty'] = true;
					}
				}

                if( !Set::check( $params, 'domain' ) || empty( $params['domain'] ) ) {
                    $params['domain'] = Inflector::singularize( Inflector::tableize( $fieldModelName ) );
                }*/

				if( !Set::check( $params, 'options' ) ) {
					$options = Set::extract( $formParams, "options.{$fieldModelName}.{$fieldModelfield}" );
					if( !empty( $options ) ) {
						$params['options'] = $options;
					}
				}

                if( Set::check( $formParams, 'domain' ) ) {
                    $params['domain'] = $formParams['domain'];
                }

				$return .= $this->Type->input( $path, $params );
			}

			return $return;
		}

		/**
		*
		*/

		public function form( $fields, $formParams = array() ) {
			$name = Inflector::camelize( $this->params['controller'] );
			$action = $this->action;
			/// FIXME: vérifier, c'est tjs le classify du nom de la table
			$modelName = Inflector::classify( $this->params['controller'] );
			$modelClass = ClassRegistry::init( Inflector::classify( $modelName ) );
			$domain = Inflector::singularize( Inflector::tableize( $modelClass->name ) );

			$primaryKey = Set::classicExtract( $this->data, "{$modelName}.{$modelClass->primaryKey}" );

			$return = '';
			$return .= $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => $domain ) ) );

			if( !empty( $primaryKey ) ) {
				$return .= $this->Xform->input( "{$modelName}.{$modelClass->primaryKey}" );
			}

			$return .= $this->subform( $fields, $formParams );

			/// Form buttons -> FIXME: en faire une fonction
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

			return $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false ).$return;
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
			$data = array_keys( $this->data );
// 			$data = preg_replace( '/^Search_/', 'Search.', $data );
			$data = Xset::bump( Set::normalize( $data ) );
			if( Set::check( $data, 'Search' ) ) {
				$params = $this->addClass( $params, 'folded' );
			}


			$return = $this->Xform->create( null, $params );

			foreach( Set::normalize( $fields ) as $fieldName => $options ) {
				list( $fieldModelName, $fieldModelfield ) = model_field( $fieldName );

				/// TODO: function ?
				if( Set::check( $paramsOptions, "{$fieldModelName}.{$fieldModelfield}" ) && empty( $options['options'] ) ) {
					$options['options'] = Set::classicExtract( $paramsOptions, "{$fieldModelName}.{$fieldModelfield}" );
				}

				// Ajouter les options 'dateFormat' => 'Locale->dateFormat' si besoin quand c'est le bon type
				//$return .= $this->Xform->input( "Search_$fieldName", $options );

				list( $options['model'], $options['field'] ) = model_field( $fieldName );
				$options = $this->Type->prepare( 'input', $fieldName, $options );
				$return .= $this->Type->input( "Search.$fieldName", $options );
			}

			$return .= $this->Xform->input( "Search.active", array( 'value' => true, 'type' => 'hidden' ) );
			$return .= $this->Xform->submit( __( 'Search', true ) );
			$return .= $this->Xform->end();

			return $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false ).$return;
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
        * FIXME: ajouter domain_suffix. (voir avec ActioncandidatPersonne)
		*/

		public function view( $item, $columns, $options = array() ) { // FIXME: rename options en viewParams
			$widget = Set::classicExtract( $options, 'widget' );
			$widget = ( empty( $widget ) ? 'dl' : $widget );
			unset( $options['widget'] );

			$name = Inflector::camelize( Set::classicExtract( $this->params, 'controller' ) ); // FIXME -> params + params -> table/list
			$modelName = Inflector::classify( $name );

			$rows = array();
			$lineNr = 1;
			foreach( Set::normalize( $columns ) as $column => $columnOptions ) {
				$columnOptions = $this->Type->prepare( 'output', $column, $columnOptions );
				list( $columnModel, $columnField ) = model_field( $column );
				$columnDomain = Inflector::singularize( Inflector::tableize( $columnModel ) );
				/// dans une fonction ?

                /// FIXME -> domain
                if( Set::check( $options, 'domain' ) ) {
                    $columnOptions['domain'] = $options['domain'];
                }
                else if( !Set::check( $columnOptions, 'domain' ) ) {
                    $columnOptions['domain'] = $columnDomain;
                }

				$formatOptions = $labelOptions = $columnOptions = $this->addClass( $columnOptions, ( ( $lineNr % 2 ) ?  'odd' : 'even' ) );

				$params = array( 'tag' => ( ( $widget == 'table' ) ? 'td' : 'dd' ) );
				foreach( array( 'options', 'type', 'class', 'domain' ) as $optionsKey ) {
					if( isset( $columnOptions[$optionsKey] ) ) {
						$params[$optionsKey] = $columnOptions[$optionsKey];
					}
				}

                /// FIXME
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

				if( $widget == 'dl' ) {
					$params['class'] = $columnOptions['class'];
				}

                if( Set::check( $options, 'options' ) && !Set::check( $params, 'options' ) ) {
                    $params['options'] = $options['options'];
                }

				$params = Set::merge( $params, $formatOptions );
				unset( $params['null'], $params['country'], $params['length'] );

// 				$line .= $this->Type->format( $item, $column, $params );
				$tmpLine = $this->Type->format( $item, $column, $params );

				// Empty ?
				if( preg_match( "/class=\".*(?<!\w)empty(?!\w).*\"/", $tmpLine ) ) {
					$columnOptions = $this->addClass( $columnOptions, 'empty' );
				}

				$line = $this->Html->tag(
					( ( $widget == 'table' ) ? 'th' : 'dt' ),
					$this->label( $column, $labelOptions ),
					$columnOptions
				);

				$line .= $tmpLine;

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
					/// throw error
				}
			}

			if( !empty( $return ) ) {
				$return = "<ul>{$return}</ul>";
			}

			return $return;
		}
	}
?>