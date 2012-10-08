<?php
    class ExtractviewfieldsShell extends AppShell
    {
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);


		/**
		* Initialisation: lecture des paramètres et du fichier
		*/

		public function initialize() {
			parent::initialize();

			$args = $this->Dispatch->args;
			$controller = ( isset( $args[0] ) ? $args[0] : null );
			$action = ( isset( $args[1] ) ? $args[1] : null );

			$dir = VIEWS.$controller;
			$file = $dir.DS."{$action}.ctp";

			if( empty( $controller ) || !file_exists( $dir ) ) {
				$this->error( 1, "( empty( {$controller} ) || !file_exists( {$dir} ) )" );
			}

			if( empty( $action ) || !file_exists( $file ) ) {
				$this->error( 2, "( empty( {$action} ) || !file_exists( {$file} ) )" );
			}

			$this->lines = file( $file );
			if( empty( $this->lines ) ) {
				$this->error( 3, "( empty( \$this->lines ) )" );
			}
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$this->out();
			$this->out( 'Extraction des champs utilisés dans les vues.' );
			$this->out();
			$this->hr( 1, '*' );
		}


		/**
		* ...
		*/

		public function main() {
			$fields = array(
				'point' => array(),
				'array' => array()
			);

			// Collecte
			foreach( $this->lines as $line ) {
				// User.name
				if( preg_match_all( "/.*'([A-Z]\w+)(\.|\.[0-9]+\.)(\w+)'.*/", $line, $matches ) ) {
					for( $i = 0 ; $i < count( $matches[1][0] ) ; $i++ ) {
						// Pas input->( ..
						if( !preg_match( "/.*((\->|::)(input|read|sort|read|data)|params\.paging\.).*/", $matches[0][$i] ) ) {
							$fields['point'][] = "{$matches[1][$i]}{$matches[2][$i]}{$matches[3][$i]}";
						}
					}
				}
				// User']['name
				if( preg_match_all( "/.*([A-Z]\w+)('\]\[')(\w+)'\].*/", $line, $matches ) ) {
					for( $i = 0 ; $i < count( $matches[1][0] ) ; $i++ ) {
						$fields['array'][] = "{$matches[1][$i]}.{$matches[3][$i]}";
					}
				}
			}

			$fields['full'] = array_merge( $fields['point'], $fields['array'] );

			$count = 0;
			foreach( $fields as $key => $items) {
				$count++;

				// Rubrique
				if( $count > 1 ) {
					$this->hr();
				}
				$this->out( $key );
				$this->hr();

				// Nettoyage
				sort( $items );
				$items = array_unique( $items );

				// Affichage
				foreach( $items as $item ) {
					$this->out( "{$item}" );
				}
			}

			$this->out();
			$this->_stop( 0 );
		}
    }
?>