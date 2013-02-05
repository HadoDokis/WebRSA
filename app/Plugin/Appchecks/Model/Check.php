<?php
	/**
	 * Code source de la classe Check.
	 *
	 * PHP 5.3
	 *
	 * @package Appchecks
	 * @subpackage Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	define( 'APPCHECKS_PLUGIN_DIR', dirname( __FILE__ ).DS.'..'.DS );
	require_once( APPCHECKS_PLUGIN_DIR.'Lib'.DS.'basics.php' );
	require_once( APPCHECKS_PLUGIN_DIR.'Lib'.DS.'xvalidation.php' );
	App::uses( 'Validation', 'Utility' );
	App::uses( 'CakeEmail', 'Network/Email' );

	/**
	 * La classe Check fournit des méthodes de vérification de l'environnement
	 * applicatif.
	 *
	 * PHP 5.3
	 *
	 * @package Appchecks
	 * @subpackage Model
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
			$loaded = apache_modules();

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
		 * Vérifie la disponibilité d'extensions PHP.
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
		 * @param array $inis Les variables à vérifier, avec règles de validation éventuelles.
		 * @param string $message Le gabarit du message à utiliser en cas de non disponibilité.
		 * @return array
		 */
		public function phpInis( array $inis, $message = "Le paramétrage de %s doit être fait dans le fichier php.ini" ) {
			$checks = array();
			foreach( Set::normalize( $inis ) as $ini => $rules ) {
				$message = null;
				$value = ini_get( $ini );

				if( !empty( $rules ) ) {
					foreach( $rules as $rule => $ruleParams ) {
						if( is_null( $message ) ) {
							$message = $this->_validate( $value, $rule, $ruleParams );
						}
					}
				}
				else {
					$message = ( !empty( $value ) ? null : sprintf( $message, $ini ) );
				}

				$checks[$ini] = array(
					'value' => $value,
					'success' => is_null( $message ),
					'message' => $message
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
		 * Vérifie si des fichiers sont présents et accessibles en lecture.
		 *
		 * @param array $files Les chemins vers les fichiers à vérifier
		 * @param string $base Le répertoire de base de l'application (en général ROOT.DS)
		 * @return array
		 */
		public function files( array $files, $base = null ) {
			if( !is_null( $base ) ) {
				$base = '/^'.preg_quote( $base, '/' ).'/';
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
		 * Vérifie les permissions sur un répertoire.
		 *
		 * @todo is_executable
		 *
		 * @param string $directory
		 * @param string $permission
		 * @return array
		 */
		public function directoryPermission( $directory, $permission = 'r' ) {
			if( !in_array( $permission, array( 'r', 'w' ) ) ) {
				trigger_error( sprintf( __( 'Paramètre non permis dans %s::%s: %s. Paramètres permis: \'r\' ou \'w\'' ), __CLASS__, __FUNCTION__, $permission ), E_USER_WARNING );
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
		 * Vérifie les permissions sur des répertoires.
		 *
		 * @param array $directories
		 * @param string $base Le répertoire de base de l'application (en général ROOT.DS)
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
		 * Vérifie les paermissions sur un fichier.
		 *
		 * @todo is_executable
		 *
		 * @param string $file
		 * @param string $permission
		 * @return array
		 */
		public function filePermission( $file, $permission = 'r' ) {
			if( !in_array( $permission, array( 'r', 'w' ) ) ) {
				trigger_error( sprintf( __( 'Paramètre non permis dans %s::%s: %s. Paramètres permis: \'r\' ou \'w\'' ), __CLASS__, __FUNCTION__, $permission ), E_USER_WARNING );
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
		 * Vérifie la présence de modèles odt.
		 *
		 * @todo utiliser files avec un préfixe
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
		 * Validation d'une valeur par-rapport à une règle de validation.
		 * Retourne un message d'erreur en cas de non validation, null sinon.
		 *
		 * @param mixed $value La valeur à tester.
		 * @param string $rule Le nom de la règle à vérifier.
		 * @param array $ruleParams Les paramètres à passer à la règle.
		 * @return string
		 */
		protected function _validate( $value, $rule, $ruleParams = array() ) {
			$allowEmpty = $ruleParams['allowEmpty'];
			unset( $ruleParams['allowEmpty'] );
			$message = null;

			if( method_exists( 'Xvalidation', $rule ) || method_exists( 'Validation', $rule ) ) {
				// FIXME: nettoyage des URL contenant %s (pour le CG 58) et des espaces
				$testValue = $value;
				if( $rule == 'url' ) {
					if( stripos( $testValue, '%s' ) !== false ) {
						$testValue = str_replace( '%s', 'XXXX', $testValue );
					}
					if( stripos( $testValue, ' ' ) !== false ) {
						$testValue = str_replace( ' ', '%20', $testValue );
					}
				}

				if( isset( $ruleParams['rule'] ) ) {
					$ruleParams = $ruleParams['rule'];
				}

				if( method_exists( 'Xvalidation', $rule ) ) {
					$Validator =  Xvalidation::getInstance();
					$validate = call_user_func_array( array( $Validator, $rule ), Set::merge( array( $testValue ), $ruleParams ) );
				}
				else {
					$Validator =  'Validation';
					$testRuleParams = $ruleParams;

					if( $testRuleParams[0] == $rule ) {
						$testRuleParams[0] = $testValue;
					}

					$validate = call_user_func_array( array( $Validator, $rule ), $testRuleParams );
				}

				if( !( $allowEmpty && empty( $value ) ) && ( is_null( $value ) || !$validate ) ) {
					$message = "Validate::{$rule}";
					if( isset( $ruleParams[0] ) && $ruleParams[0] == $rule ) {
						unset( $ruleParams[0] );
					}
					$sprintfParams = Set::merge( array( __( $message ) ), $ruleParams );
					for( $i = 1 ; ( $i <= count( $sprintfParams ) - 1 ) ; $i++ ) {
						if( is_array( $sprintfParams[$i] ) ) {
							$sprintfParams[$i] = implode( ', ', $sprintfParams[$i] );
						}
					}
					$message = call_user_func_array( 'sprintf', $sprintfParams );
				}
			}
			else {
				$message = "La méthode de validation {$rule} n'existe pas.";
			}

			return $message;
		}

		/**
		 * Valide la valeur donnée par un chemin de configuration par une règle
		 * de validation.
		 *
		 * @param string $path Le chemin de la configuration.
		 * @param string $rule Le nom de la règle à vérifier.
		 * @param array $ruleParams Les paramètres à passer à la règle.
		 * @return array
		 */
		public function validateConfigurePath( $path, $rule, $ruleParams = array() ) {
			$value = Configure::read( $path );

			$message = $this->_validate( $value, $rule, $ruleParams );

			return array(
				'success' => is_null( $message ),
				'value' => var_export( $value, true ),
				'message' => $message
			);
		}

		/**
		 * Lecture des chemins de configuration et validation (à minima, les
		 * valeurs ne pourront pas être vides).
		 *
		 * @param array $paths Les chemins à vérifier
		 * @return array
		 */
		public function configure( array $paths ) {
			$return = array();
			$defaults = array(
				'allowEmpty' => false,
//				'required' => true,
			);

			foreach( Set::normalize( $paths ) as $path => $rules ) {
				if( !is_array( $rules ) ) {
					$rules = array( array( 'rule' => $rules ) );
				}

				foreach( $rules as $rule ) {
					$rule = Set::merge( $defaults, $rule );
					if( !isset( $return[$path] ) || empty( $return[$path] ) || $return[$path]['success'] ) {
						$ruleParams = $rule;
						unset( $ruleParams['rule'] );
						$validate = $this->validateConfigurePath( $path, $rule['rule'], $ruleParams );
						$return[$path] = $validate;
					}
				}
			}
			ksort( $return );

			return $return;
		}

		/**
		 * Vérifie qu'un logiciel sse trouve dans une version donnée.
		 *
		 * @param string $software Le nom du logiciel
		 * @param string $actual La version du logiciel
		 * @param string $low La version minimale
		 * @param string $high La version maximale
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
		 * @fixme La fonction readTimeout n'est pas dans le plugin
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

		/**
		 * Vérifie l'accès à un WebService.
		 *
		 * @param string $wsdl
		 * @param string $message Le gabarit du message à utiliser en cas d'erreur.
		 * @return array
		 */
		public function webservice( $wsdl, $message = "Le WebService n' est pas accessible (%s)" ) {
			$result = array();

			try {
				$client = @new SoapClient( $wsdl, array( 'exceptions' => 1 ) );
				$result['success'] = true;
			} catch( Exception $e ) {
				$result['success'] = false;
				$result['message'] = sprintf( $message, $e->getMessage() );
			}

			return $result;
		}

		/**
		 * Vérifie l'accès à une machine distante.
		 *
		 * @see http://www.php.net/manual/fr/function.fsockopen.php#65631
		 *
		 * @param string $hostname
		 * @param string $port
		 * @param string $message
		 * @return array
		 */
		public function socket( $hostname, $port, $message = "La machine distante n' est pas accessible (%s)" ) {
			$timeout=10;
			Set_Time_Limit(0);  //Time for script to run .. not sure how it works with 0 but you need it
//			Ignore_User_Abort(True); //this will force the script running at the end
			$handle = fsockopen( $hostname, $port, $errno, $errstr, $timeout );
			$result = array(
				'success' => !empty( $handle )
			);
			if( !$result['success'] ){
				$result['message'] = sprintf( $message, "{$errno}: {$errstr}" );
			}
			else {
				fclose( $handle );
			}

			return $result;
		}

		/**
		 * Vérifie la présence d'extensions PEAR
		 *
		 * @param array $extensions
		 * @param boolean $base A true, vérifie la présence des classes PEAR et PEAR_Registry.
		 * @return array
		 */
		public function pearExtensions( $extensions, $base = true ) {
			$results = array();

			$success = @include_once( 'PEAR.php' );
			if( $base ) {
				$results['PEAR'] = array(
					'success' => $success,
					'message' => ( $success ? null : "PEAR n'est pas installé. Installez le paquet php-pear (sous Ubuntu, en ligne de commande, faire: <code>sudo apt-get install php-pear</code>)" )
				);
			}

			$success = @include_once( 'PEAR/Registry.php' );
			if( $base ) {
				$results['Registry'] = array(
					'success' => $success,
					'message' => ( $success ? null : "PEAR_Registry n'est pas installé" )
				);
			}

			$Registry = null;
			if( class_exists( 'PEAR_Registry' ) ) {
				$Registry = @new PEAR_Registry();
			}

			foreach( $extensions as $extension ) {
				$success = ( is_null( $Registry ) ? false : @$Registry->packageExists( $extension ) );
				$results[$extension] = array(
					'success' => $success,
					'message' => ( $success ? null : sprintf( "L'extension PEAR %s n'est pas installée. Pour l'installer, en ligne de commande, faire: <code>sudo pear install %s</code>", $extension, $extension ) )
				);
			}

			return $results;
		}

		/**
		 * Vérification d'une configuration de CakeEmail dans le fichier
		 * app/Config/email.php.
		 *
		 * EmailComponent est déprécié depuis la version 2.0 de CakePHP.
		 *
		 * @todo ::transportClass
		 *
		 * @param string $name Le nom de la configuration
		 * @return array
		 */
		public function cakeEmailConfig( $name ) {
			$settingsFile = 'app/Config/email.php';
			$settingsMailFields = array( 'from', 'replyTo', 'readReceipt', 'returnPath', 'sender', 'to', 'cc', 'bcc' );

			$configure = array();
			$tests = array();

			try {
				$Email = new CakeEmail( $name );
				$settings = $Email->config();
			} catch( Exception $e ) {
				$Email = null;
				$settings = array();
			}

			if( empty( $settings ) ) {
				$configure["Présence de la clé {$name} dans le fichier {$settingsFile}"] = array(
					'success' => false,
					'message' => "Configuration manquante ou erronée dans le fichier {$settingsFile}",
				);
			}
			else {
				// La configuration est-elle présente ?
				$configure[$settingsFile] = array(
					'success' => true,
					'message' => null,
				);

				// Vérification des adresses mail
				foreach( $settingsMailFields as $emailAddress ) {
					if( isset( $settings[$emailAddress] ) && !empty( $settings[$emailAddress] ) ) {
						foreach( (array)$settings[$emailAddress] as $address ) {
							$success = Validation::email( $address, true );

							$configure["{$emailAddress}.{$address}"] = array(
								'success' => $success,
								'message' => ( $success ? null : "L'adresse mail {$address} n'est pas valide" ),
							);
						}
					}
				}

				// Test de connexion au serveur SMTP
				if( !is_null( $Email ) && ( isset( $settings['transport'] ) && strtolower( $settings['transport'] ) == 'smtp' ) ) {
					$tests['Connexion au serveur'] = $this->socket( $settings['host'], $settings['port'] );
				}

				// Test d'envoi de mail factice
				$success = false;
				if( !is_null( $Email ) ) {
					try {
						$Email->subject( 'Test' );
						$Email->to( $Email->from() );
						$Email->transport( 'Debug' );
						$result = $Email->send( 'Test' );
						$success = !empty( $result );
					} catch( Exception $e ) {
						$success = false;
					}
				}
				$tests['Envoi factice'] = array(
					'success' => $success,
					'message' => ( !empty( $success ) ? null : 'Erreur lors du test d\'envoi de mail factice' )
				);
			}

			return array( 'configure' => $configure, 'tests' => $tests );
		}
	}
?>