<?php
	@ini_set( 'memory_limit', '2048M' );

	/**
	* AppShell::__construct
	* AppShell::initialize
	* AppShell::loadTasks
	* AppShell::startup
	* AppShell::_welcome
	* AppShell::help / AppShell::main / AppShell::command
	*/

	abstract class AppShell extends Shell
	{
		public $startTime = null;

		public $commands = array();

		public $operations = array();

		public $outfile = null;

		public $output = '';

		public $defaultParams = array(
			'log' => true,
			'logpath' => LOGS
		);

		public $log = true;

		public $logpath = LOGS;

		/**
		* Retourne un paramètre répondant à un type donné, lance une erreur ou
		* retourne sa valeur par défaut.
		*/

		public function _getNamedValue( $key, $type = 'string' ) {
			$value = $this->defaultParams[$key];

			if( isset( $this->params[$key] ) ) {
				if( ( $type == 'boolean' ) ) {
					if( in_array( $this->params[$key], array( 'true', 'false' ) ) ) {
						$value = ( $this->params[$key] == 'true' );
					}
					else {
						$this->error( "Veuillez entrer une valeur booléenne (true ou false) pour le paramètre -{$key}" );
					}
				}
				else if( ( $type == 'connection' ) ) {
					$allConnections = array_keys( ConnectionManager::enumConnectionObjects() );
					if( in_array( $this->params[$key], $allConnections ) ) {
						$value = $this->params[$key];
					}
					else {
						$this->error( "La connexion {$this->params[$key]} n'existe pas dans votre fichier app/config/database.php (valeurs possibles: ".implode( ', ', $allConnections ).")" );
					}
				}
				else if( ( $type == 'integer' ) ) {
					if( preg_match( '/^[0-9]+$/', $this->params[$key] ) ) {
						$value = $this->params[$key];
					}
					else {
						$this->error( "Veuillez entrer un nombre entier pour le paramètre -{$key}" );
					}
				}
				else if( ( $type == 'writeabledir' ) ) {
					if( is_dir( $this->params[$key] ) && is_writable( $this->params[$key] ) ) {
						$value = $this->params[$key];
					}
					else {
						$this->error( "Veuillez entrer le chemin d'un répertoire où vous avez un droit d'écriture pour le paramètre -{$key}" );
					}
				}
				else if( $type == 'string' ) {
					$value = $this->params[$key];
				}
			}

			return $value;
		}

		/**
		*
		*/

		public function _stop( $status = 0 ) {
			$outfile = rtrim( $this->logpath, '/' ).'/'.$this->outfile;
			$escapedApp = str_replace( '/', '\/', APP );
			if( preg_replace( '/^'.$escapedApp.'/', '', $outfile ) ) {
				$outfile = 'app/'.preg_replace( '/^'.$escapedApp.'/', '', $outfile );
			}

			$this->hr();
			$this->out();

			if( $this->log ) {
				$this->out( "Le fichier de log se trouve dans {$outfile}" );
			}

			$this->out(
				sprintf(
					"Script terminé avec %s en %s secondes.",
					( empty( $status ) ? 'succès' : 'erreurs' ),
					number_format( microtime( true ) - $this->startTime, 2 )
				)
			);

			if( $this->log ) {
				file_put_contents( $outfile, $this->output );
			}

			return parent::_stop( $status );
		}

		/**
		* Valeur par défaut du paramètre sous forme de string
		*/

		public function _defaultToString( $key ) {
			$value = Set::classicExtract( $this->defaultParams, $key );
			return $this->_valueToString( $value );
		}

		/**
		* Valeur sous forme de string
		*/

		public function _valueToString( $value ) {
			if( is_null( $value ) ) {
				return 'null';
			}

			switch( gettype( $value ) ) {
				case 'boolean':
					return ( $value ? 'true' : 'false' );
					break;
				default:
					return $value;
			}
		}

		/**
		* Outputs a series of minus characters to the standard output, acts as a visual separator.
		*
		* @param integer $newlines Number of newlines to pre- and append
		* @access public
		*/

		public function hr($newlines = 1) {
			$this->out( '---------------------------------------------------------------', $newlines );
		}

		/**
		* INFO: fix pour les caractères accentués
		*/

		public function out( $message = null, $newlines = 1 ) {
			if( !empty( $this->outfile ) && $this->log ) {
				$this->output .= "{$message}\n"; // FIXME: newlines
			}

			if( $this->_iconvEncoding['output_encoding'] == 'ISO-8859-1' )
				return parent::out( utf8_decode( $message ), $newlines );
			else {
				return parent::out( $message, $newlines );
			}
		}

		/**
		* INFO: fix pour les caractères accentués
		*/

		public function err( $message = null, $newlines = 1 ) {
			if( !empty( $this->outfile ) && $this->log ) {
				$this->output .= "Error: {$message}\n"; // FIXME: newlines
			}

			return parent::err( utf8_decode( $message ) );
		}

		/**
		* Initialisation: lecture des paramètres
		*/

		public function initialize() {
			//debug( $this->log ); // FIXME
			$this->log = $this->_getNamedValue( 'log', 'boolean' );
			$this->logpath = $this->_getNamedValue( 'logpath', 'writeabledir' );
		}

		/**
		*  Constructs this Shell instance.
		*/

		public function __construct( &$dispatch ) {
			parent::__construct( $dispatch );
			$this->startTime = microtime( true );

			foreach( Set::merge( get_this_class_methods( $this ), array( 'help' ) ) as $command ) {
				if( !preg_match( '/^_/', $command ) && ( $command != 'main' ) ) {
					$this->commands[] = $command;
				}
			}
			$this->operations = array_filter_values( $this->commands, array( 'all', 'help' ), true );

			$this->outfile = sprintf( '%s-%s.log', Inflector::underscore( $this->alias ), date( 'Ymd-His' ) );

			$this->log = $this->_getNamedValue( 'log', 'boolean' );

			$this->_iconvEncoding = iconv_get_encoding();

			// INFO: fix pour CakePHP 1.2
			if( CAKE_BRANCH == '1.2' ) {
				if( method_exists( $this,'initialize' ) ) {
					$this->initialize();
				}
				if( method_exists( $this,'loadTasks' ) ) {
					$this->loadTasks();
				}
			}
		}
	}
?>