<?php
	App::import( 'Core', 'File' );

	require_once( LIBS.'model/model.php' );
	require_once( APP.'app_model.php' );

	// cake/console/cake class_diagram && dot -K fdp -T png -o ./class_diagram.png ./class_diagram.dot && gwenview ./class_diagram.png > /dev/null  2>&1

	class ClassDiagramShell extends Shell
	{
		/**
		*
		*/

		protected $_drawBehaviors = false;

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* PostgreSQL valide
		*/

		public function initialize() {
			parent::initialize();

// 			$this->module = $this->_getNamedValue( 'module', 'string' );

			$this->Webrsa = ClassRegistry::init( 'Webrsa' );
		}

		/**
		*
		*/

		protected function _inModule( $modelName, $moduleName ) {
			$models = $this->Webrsa->models( $moduleName );
			return in_array( $modelName, $models );
		}

		/**
		* FIXME: nom
		*/

		protected function _( $modelName ) {
			$restrict = true;
			return (
				!$restrict
// 				|| $this->_inModule( $modelName, 'covs' )
// 				|| $this->_inModule( $modelName, 'eps' )
// 				|| $this->_inModule( $modelName, 'apres' )
// 				|| $this->_inModule( $modelName, 'webrsa' )
// 				|| $this->_inModule( $modelName, 'pcgs' )
				|| $this->_inModule( $modelName, 'caf' )
			);
		}

		/**
		*
		*/

		protected function _behaviorsNames( $behaviors ) {
			if( !$this->_drawBehaviors ) {
				return array();
			}

			return array_keys( Set::normalize( $behaviors ) );
		}

		/**
		*
		*/

		protected function _elementFillString( $elementType, $elementName ) {
			$color = "#FFFFFF";

			if( $elementType == 'behavior' ) {
				$color = "#FFFFFF";
			}
			else if( $elementType == 'model' ) {
				$module = $this->Webrsa->moduleDuModele( $elementName );

				$color = "#FCFBD6";
				if( $module == 'eps' ) {
					$color = '#DCD9FF';
				}
				else if( $module == 'apres' ) {
					$color = '#E1FFD9';
				}
				else if( $module == 'pcgs' ) {
					$color = '#FFD9D9';
				}
				else if( $module == 'covs' ) {
					$color = '#D9FFFD';
				}
				else if( $module == 'caf' ) {
					$color = '#FFF0D9';
				}
			}

			return ", style = \"filled\", fillcolor = \"{$color}\"";
		}

		/**
		*
		*/

		/*protected function _methods( $classMethods, $parentMethods ) {
			$methods = array();
			sort( $classMethods );

			foreach( $classMethods as $tmpMethod ) {
				if( !in_array( $tmpMethod, $parentMethods ) ) {
					$visibility = '+';
					if( preg_match( '/^__/', $tmpMethod ) ) {
						$visibility = '-';
					}
					else if( preg_match( '/^_/', $tmpMethod ) ) {
						$visibility = '#';
					}
					$methods[] = "{$visibility}{$tmpMethod}()";
				}
			}

			if( !empty( $methods ) ) {
				return '|\l'.implode( '\l|', $methods ).'\l';
			}

			return '';
		}*/

		/**
		*
		*/

		protected function _classMethodSignature( &$reflection, $methodName ) {
			$visibility = '+';
			if( preg_match( '/^__/', $methodName ) ) {
				$visibility = '-';
			}
			else if( preg_match( '/^_/', $methodName ) ) {
				$visibility = '#';
			}

			$parameters = array();
			$params = $reflection->getMethod( $methodName )->getParameters();
			if( !empty( $params ) ) {
				foreach( $params as $param ) {
					$parameters[] = "\${$param->name}";
				}
			}

			return "{$visibility}{$methodName}(".implode( ',', $parameters ).")";
		}

		/**
		* FIXME: données + formattage
		*/

		protected function _classMethods( $className ) {
			$methods = array();
			$reflection = new ReflectionClass( $className );
			$tmpMethods = $reflection->getMethods();
			foreach( $tmpMethods as $method ) {
				if( $method->class == $className ) {
					$methods[] = $method->name;
				}
			}

			if( !empty( $methods ) ) {
				sort( $methods );
				foreach( $methods as $i => $method ) {
					$methods[$i] = $this->_classMethodSignature( &$reflection, $method ).'\l';
				}
				return '|'.implode( '|', $methods );
			}

			return '';
		}

		/**
		*
		*/

		public function main() {
			$classes = array();
			$relations = array();
			$parents = array();
			$usedBehaviors = array();

			$behaviorMethods = Set::merge(
				get_class_methods( 'ModelBehavior' )
			);

			$AppModelClass = new AppModel( array( 'name' => 'User', 'table' => 'users', 'ds' => 'default' ) );
			$appModelBehaviors = $this->_behaviorsNames( $AppModelClass->actsAs );
			unset( $AppModelClass );

			$appModelMethods = Set::merge(
				get_class_methods( 'AppModel' ),
				get_class_methods( 'LazyModel' )
			);

			foreach( Configure::listObjects( 'model' ) as $modelName ) {
				if( $this->_( $modelName ) ) {
					App::import( 'Model', $modelName );

					// Methods
					$parentClass = get_parent_class( $modelName );
					if( $parentClass != 'AppModel' ) {
						$parents[] = "\t{$modelName} -> {$parentClass} [arrowhead = \"onormal\"]";
					}

					//$methods = $this->_methods( get_class_methods( $modelName ), $appModelMethods );
					$methods = $this->_classMethods( $modelName );
					$classes[] = "\t{$modelName} [ label = \"{{$modelName}{$methods}}\" ".$this->_elementFillString( 'model', $modelName )." ];";

					if( !in_array( $modelName, array( 'RejetHistorique', 'Visionneuse' ) ) ) {
						// Relations
						$modelClass = ClassRegistry::init( $modelName );

						foreach( $modelClass->__associations as $association ) {
							if( $association != 'hasAndBelongsToMany' ) {
								foreach( $modelClass->{$association} as $assocModel ) {
									if( $this->_( $assocModel['className'] ) ) {
										if( !isset( $relations["{$assocModel['className']} -> {$modelName}"] ) ) {
											$assoc = "{$modelName} -> {$assocModel['className']}";
											$cardinality = '';
											if( $association == 'hasOne' ) {
												$cardinality = "arrowhead = \"odiamond\", headlabel = \"1..1\", taillabel = \"0..1\"";
											}
											else if( $association == 'hasMany' ) {
												$assoc = "{$assocModel['className']} -> {$modelName}";
												$cardinality = "arrowhead = \"odiamond\", headlabel = \"1..1\", taillabel = \"0..1\"";
											}
											else if( $association == 'belongsTo' ) {
												$cardinality = "arrowhead = \"odiamond\", headlabel = \"1..1\", taillabel = \"0..n\"";
											}
											$relations["{$modelName} -> {$assocModel['className']}"] = "\t{$assoc} [ $cardinality ];";
										}
									}
								}
							}
							else { // FIXME: le même que plus haut -> en faire une fonction
								foreach( $modelClass->{$association} as $assocModel ) {
									$assocModel['className'] = $assocModel['with'];
									if( $this->_( $assocModel['className'] ) ) {
										if( !isset( $relations["{$assocModel['className']} -> {$modelName}"] ) ) {
											$assoc = "{$assocModel['className']} -> {$modelName}";
											$cardinality = "arrowhead = \"odiamond\", headlabel = \"1..1\", taillabel = \"0..1\"";

											$relations["{$modelName} -> {$assocModel['className']}"] = "\t{$assoc} [ $cardinality ];";
										}
									}
								}
							}
						}

						// Behaviors
						$behaviors = $this->_behaviorsNames( $modelClass->actsAs );
// 						debug( 	$behaviors );die();
						foreach( $behaviors as $behavior ) {
							if( !in_array( $behavior, $appModelBehaviors ) && $behavior != 'Array' ) {
								$usedBehaviors[] = $behavior;
								$relations["{$behavior}Behavior -> {$modelName}"] = "\t{$behavior}Behavior -> {$modelName} [arrowhead = \"odiamond\", headlabel = \"0..n\", taillabel = \"1..1\"];";
							}
						}
					}
				}
			}

			// Used behavior classes
			foreach( Configure::listObjects( 'behavior' ) as $behaviorName ) {
				App::import( 'Behavior', $behaviorName );
				if( in_array( $behaviorName, $usedBehaviors ) ) {
					$behaviorName = "{$behaviorName}Behavior";
					//$methods = $this->_methods( get_class_methods( $behaviorName ), $behaviorMethods );
					$methods = $this->_classMethods( $behaviorName );
					$classes[$behaviorName] = "\t{$behaviorName} [ label = \"{{$behaviorName}{$methods}}\"".$this->_elementFillString( 'behavior', $behaviorName )." ];";
				}
			}

			$contents = "digraph G {\n \toverlap = false;\n \tconcentrate = \"true\";\n \tsplines = \"polyline\";\n \toutputorder = \"nodesfirst\";\n \tpack = true;\n \tpackmode = \"clust\";\n\tfontname = \"Bitstream Vera Sans\"\n \tfontsize = 8\n \tpack = false\n \tpackMode = clust\n \n \tnode [\n \t\tfontname = \"Bitstream Vera Sans\"\n \t\tfontsize = 8\n \t\tshape = \"record\"\n \t]\n \n \tedge [\n \t\tfontname = \"Bitstream Vera Sans\"\n \t\tfontsize = 8\n \t\tarrowhead = \"none\"\n \t]\n\n";
			$contents .= implode( "\n", $classes )."\n";
			$contents .= implode( "\n", $parents )."\n";
			$contents .= implode( "\n", $relations )."\n";
			$contents .= "\n}";

			file_put_contents( 'class_diagram.dot', $contents );
			$this->_stop( 0 );
		}
	}
?>