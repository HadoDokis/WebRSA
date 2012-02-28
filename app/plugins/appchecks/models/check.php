<?php
	/**
	 * PHP 5.3
	 *
	 * @package       app.plugins.checks.models
	 */
	class Check extends AppModel
	{
		/**
		 * @var string
		 */
		public $name = 'Check';

		/**
		 * @var string
		 */
		public $useTable = false;

		/**
		 * Fonction la liste des clés de l'objet Configure.
		 * A utiliser lors du développement.
		 *
		 * @return array
		 */
		public function configureKeys() {
			$Configure = Configure::getInstance();
			$Configure = Set::flatten( $Configure );
			return array_keys( $Configure );
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
						if( is_null( $value ) || !is_array( $value ) || count( $value ) == 0 ) {
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
		 * Retourne la vérification du timeout, avec en message la configuration
		 * utilisée.
		 *
		 * @return array
		 */
		public function timeout() {
			$value = readTimeout();
			$message = null;

			if( Configure::read( 'Session.save' ) == 'php' ) {
				$message = '<code>session.gc_maxlifetime</code> dans le <code>php.ini</code> (valeur actuelle: <em>'.ini_get( 'session.gc_maxlifetime' ).'</em> secondes)';
			}
			else if( Configure::read( 'Session.save' ) == 'cake' ) {
				$message = "<code>Configure::write( 'Session.timeout', '".Configure::read( 'Session.timeout' )."' )</code> dans <code>app/config/core.php</code><br/>";
				$message .= "<code>Configure::write( 'Security.level', '".Configure::read( 'Security.level' )."' )</code> dans <code>app/config/core.php</code>";
			}

			return array(
				'success' => true,
				'value' => sec2hms( $value, true ),
				'message' => $message,
			);
		}
	}
?>