<?php
	/**
	 * TODO: à mettre dans le modèle ?
	 * Classe de vérification de paramétrages, d'extensions, de modèles odt, ...
	 *
	 * Les fonctions au singulier renvoient une seule vérification, les fonctions
	 * au pluriel renvoient plusieurs vérifications.
	 *
	 * Une vérification est un booléen ou un array:
	 *	'clé' => array(
	 *		'value' => mixed,
	 *		'success' => boolean,
	 *		'message' => string,
	 *	)
	 *
     * PHP 5.3
     *
	 * @package       app.controllers.components
	 */
	class ChecksComponent extends Component
	{
		/**
		 * Controller using this component.
		 *
		 * @var _Controller
		 */
		protected $_Controller = null;

		/**
		 * Called before the Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 * @access public
		 */
		public function initialize( &$controller ) {
			$this->_Controller = $controller;
		}

		/**
		 * Vérifie la disponibilité de modules Apache
		 *
		 * @param array $modules Les modules à vérifier.
		 * @param string $message Le gabarit du message à utiliser en cas de non disponibilité.
		 * @return array
		 */
		public function apacheModules( array $modules, $message = "Le module Apache %s n'est pas disponible." ) {
			$loaded = apache_get_modules();

			$checks = array();
			foreach( $modules as $module ) {
				$success = in_array( $module, $loaded );

				$checks[$module] = array(
					'success' => $success,
					'message' => ( $success ? null : sprintf( $message, $module ) )
				);
			}

			return $checks;
		}

		/**
		 * Vérifie la disponibilité dextensions PHP
		 *
		 * @param array $extensions Les extensions à vérifier.
		 * @param string $message Le gabarit du message à utiliser en cas de non disponibilité.
		 * @return array
		 */
		public function phpExtensions( array $extensions, $message = "L'extension PHP %s n'est pas disponible." ) {
			$checks = array();
			foreach( $extensions as $extension ) {
				$success = extension_loaded( $extension );

				$checks[$extension] = array(
					'success' => $success,
					'message' => ( $success ? null : sprintf( $message, $extension ) )
				);
			}

			return $checks;
		}

		/**
		 * Vérifie la configuration de variables dans le php.ini
		 *
		 * @param array $extensions Les variables à vérifier.
		 * @param string $message Le gabarit du message à utiliser en cas de non disponibilité.
		 * @return array
		 */
		public function phpInis( array $inis, $message = "Le paramétrage de %s doit être fait dans le fichier php.ini" ) {
			$checks = array();
			foreach( $inis as $ini ) {
				$value = ini_get( $ini );
				$checks[$ini] = array(
					'value' => $value,
					'success' => !empty( $value ),
					'message' => ( !empty( $value ) ? null : sprintf( $message, $ini ) )
				);
			}

			return $checks;
		}

		/**
		 * Vérifie la présence de binaires sur le système.
		 *
		 * @param array $binaries Les binaires à vérifier.
		 * @param string $message Le gabarit du message à utiliser en cas d'absence.
		 * @return array
		 */
		public function binaries( array $binaries, $message = "Le binaire %s n'est pas accessible sur la système." ) {
			$checks = array();
			foreach( $binaries as $binary ) {
				$which = exec( "which {$binary}" );
				$success = !empty( $which );

				$checks[$binary] = array(
					'success' => $success,
					'message' => ( $success ? null : sprintf( $message, $binary ) )
				);
			}

			return $checks;
		}

		/**
		 *
		 * @param array $files
		 * @return array
		 */
		public function files( array $files, $base = null ) {
			if( !is_null( $base ) ) {
				$base = '/^'.preg_quote( $base, '/' ).'/'; // FIXME
			}
			$checks = array();
			foreach( $files as $file ) {
				if( is_null( $base ) ) {
					$key = $file;
				}
				else {
					$key = preg_replace( $base, '', $file );
				}
				$checks[$key] = $this->filePermission( $file, 'r' );
			}

			return $checks;
		}

		/**
		 * TODO: is_executable
		 *
		 * @param string $directory
		 * @param string $permission
		 * @return array
		 */
		public function directoryPermission( $directory, $permission = 'r' ) {
			if( !in_array( $permission, array( 'r', 'w' ) ) ) {
				trigger_error( sprintf( __( 'Paramètre non permis dans %s::%s: %s. Paramètres permis: \'r\' ou \'w\'', true ), __CLASS__, __FUNCTION__, $permission ), E_USER_WARNING );
			}

			$success = true;
			$message = null;
			if( !is_dir( $directory ) ) {
				$success = false;
				$message = "Le dossier {$directory} n'existe pas.";
			}
			else if( !is_readable( $directory ) ) {
				$success = false;
				$message = "Le dossier {$directory} n'est pas lisible.";
			}
			else if( $permission == 'w' && !is_writable( $directory ) ) {
				$success = false;
				$message = "Le dossier {$directory} n'est pas inscriptible.";
			}

			return array(
				'success' => $success,
				'message' => $message
			);
		}

		/**
		 *
		 * @param array $directories
		 * @return array
		 */
		public function directories( array $directories, $base = null ) {
			if( !is_null( $base ) ) {
				$base = '/^'.preg_quote( $base, '/' ).'/'; // FIXME
			}
			$checks = array();
			foreach( Set::normalize( $directories ) as $directory => $mode ) {
				if( is_null( $base ) ) {
					$key = $directory;
				}
				else {
					$key = preg_replace( $base, '', $directory );
				}
				$checks[$key] = $this->directoryPermission( $directory, $mode );
			}

			return $checks;
		}

		/**
		 * TODO: is_executable
		 *
		 * @param string $file
		 * @param string $permission
		 * @return array
		 */
		public function filePermission( $file, $permission = 'r' ) {
			if( !in_array( $permission, array( 'r', 'w' ) ) ) {
				trigger_error( sprintf( __( 'Paramètre non permis dans %s::%s: %s. Paramètres permis: \'r\' ou \'w\'', true ), __CLASS__, __FUNCTION__, $permission ), E_USER_WARNING );
			}

			$success = true;
			$message = null;
			if( !file_exists( $file ) ) {
				$success = false;
				$message = "Le fichier {$file} n'existe pas.";
			}
			else if( !is_readable( $file ) ) {
				$success = false;
				$message = "Le fichier {$file} n'est pas lisible.";
			}
			else if( $permission == 'w' && !is_writable( $file ) ) {
				$success = false;
				$message = "Le fichier {$file} n'est pas inscriptible.";
			}

			return array(
				'success' => $success,
				'message' => $message
			);
		}

		/**
		 *
		 * @param array $modeles
		 * @param string $prefixPath Le répertoire de base des modèles.
		 * @return array
		 */
		public function modelesOdt( array $modeles, $prefixPath ) {
			$return = array();
			foreach( $modeles as $modele ) {
				$return[$modele] = $this->filePermission( $prefixPath.$modele, 'r' );
			}
			ksort( $return );

			return $return;
		}

		/**
		 *
		 * @param array $paths
		 * @return array
		 */
		public function configure( array $paths ) {
			$return = array();

			foreach( Set::normalize( $paths ) as $path => $type ) {
				$value = Configure::read( $path );
				$message = null;

				switch( $type ) {
					case 'array':
						if( is_null( $value ) || !is_array( $value ) ) {
							$message = $type;
						}
						break;
					case 'boolean':
						if( is_null( $value ) || !is_bool( $value ) ) {
							$message = $type;
						}
						break;
					case 'integer':
						if( is_null( $value ) || !is_integer( $value ) ) {
							$message = $type;
						}
						break;
					case 'numeric':
						if( is_null( $value ) || !is_numeric( $value ) ) {
							$message = $type;
						}
						break;
					case 'string':
						if( is_null( $value ) || !is_string( $value ) ) {
							$message = $type;
						}
						break;
					default:
						if( is_null( $value ) || !is_string( $value ) ) {
							$message = 'undefined';
						}
				}

				$return[$path] = array(
					'success' => is_null( $message ),
					'value' => $value,
					'message' => $message
				);
			}
			ksort( $return );

			return $return;
		}

		/**
		 *
		 * @param string $software
		 * @param string $actual
		 * @param string $low
		 * @param string $high
		 * @return array
		 */
		public function version( $software, $actual, $low, $high = null ) {
			$version_difference = version_difference( $actual, $low, $high );

			$message = null;
			if( !$version_difference ) {
				if( is_null( $high ) ) {
					$message = "La version de {$software} doit être au moins {$low}";
				}
				else {
					$message = "La version de {$software} doit être au moins {$low}, mais plus petite que {$high}";
				}
			}

			return array(
				'value' => $actual,
				'success' => $version_difference,
				'message' => $message,
			);
		}

		/**
		 * Called before Controller::redirect().  Allows you to replace the url that will
		 * be redirected to with a new url. The return of this method can either be an array or a string.
		 *
		 * If the return is an array and contains a 'url' key.  You may also supply the following:
		 *
		 * - `status` The status code for the redirect
		 * - `exit` Whether or not the redirect should exit.
		 *
		 * If your response is a string or an array that does not contain a 'url' key it will
		 * be used as the new url to redirect to.
		 *
		 * @param Controller $controller Controller with components to beforeRedirect
		 * @param string|array $url Either the string or url array that is being redirected to.
		 * @param integer $status The status code of the redirect
		 * @param boolean $exit Will the script exit.
		 * @return array|null Either an array or null.
		 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
		 */
		public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			return array( 'url' => $url, 'status' => $status, 'exit' => $exit );
		}
	}
?>