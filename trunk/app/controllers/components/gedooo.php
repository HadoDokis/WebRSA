<?php
	App::import( 'Core', 'HttpSocket' );

	@set_time_limit( 0 );
	// Mémoire maximum allouée à l'exécution de ce script
	@ini_set( 'memory_limit', '512M' );
	// Temps maximum d'exécution du script (en secondes)
	@ini_set( 'max_execution_time', 2000 );
	// Temps maximum (en seconde), avant que le script n'arrête d'attendre la réponse de Gedooo
	@ini_set( 'default_socket_timeout', 12000 );

	class GedoooComponent extends Component
	{
		/**
		* The initialize method is called before the controller's beforeFilter method.
		*/

		public function initialize( &$controller, $settings = array() ) {
			$this->controller = &$controller;
		}

		/**
		*
		*/

		public function concatPdfs( $pdfs, $modelName ) {
			$pdfTmpDir = rtrim( Configure::read( 'Cohorte.dossierTmpPdfs' ), '/' ).'/'.session_id().'/'.$modelName;
			$old = umask(0);
			@mkdir( $pdfTmpDir, 0777, true ); /// FIXME: vérification
			umask($old);

			foreach( $pdfs as $i => $pdf ) {
				file_put_contents( "{$pdfTmpDir}/{$i}.pdf", $pdf );
			}

			exec( "pdftk {$pdfTmpDir}/*.pdf cat output {$pdfTmpDir}/all.pdf" ); // FIXME: nom de fichier cohorte-orientation-20100423-12h00.pdf

			if( !file_exists( "{$pdfTmpDir}/all.pdf" ) ) {
				// INFO: on nettoie quand même avant de partir
				exec( "rm {$pdfTmpDir}/*.pdf" );
				exec( "rmdir {$pdfTmpDir}" );

				return false;
			}

			$c = file_get_contents( "{$pdfTmpDir}/all.pdf" );

			exec( "rm {$pdfTmpDir}/*.pdf" );
			exec( "rmdir {$pdfTmpDir}" );

			return $c; /// FIXME: false si problème
		}

		/**
		* Vérification de l'état du serveur Gedooo.
		*
		* @param $asBoolean boolean Doit-on renvoyer un array avec les différentes vérifications, ou un résumé
		* @param $setFlash boolean Doit-on afficher un message d'erreur s'il Gedooo est mal configuré
		* @param $testServer boolean Doit-on tester une impression pour voir si Gedoo fonctionne correctement ?
		*/

		public function check( $asBoolean = false, $setFlash = false, $testServer = false ) {
			$HttpSocket = new HttpSocket();
			$result = @$HttpSocket->get( GEDOOO_WSDL );

			$response = array(
				'file_exists' => file_exists( GEDOOO_TEST_FILE ),
				'status' => ( $HttpSocket->response['status']['code'] == 200 ),
				'content-type' => ( $HttpSocket->response['header']['Content-Type'] == 'text/xml' ),
			);

			// Testons une impression, n'importe laquelle
			if( $testServer ) {
				$response['print'] = false;

				if( $response['status'] && $response['content-type'] ) {
					$User = ClassRegistry::init( 'User' );
					if( !in_array( 'Gedooo', array_keys( Set::normalize( $User->actsAs ) ) ) ) {
						$User->Behaviors->attach( 'Gedooo' );
					}
					$response['print'] = $User->ged( array(), basename( GEDOOO_TEST_FILE ) );
					$response['print'] = preg_match( '/^%PDF\-[0-9]/m', $response['print'] );
				}
			}
			
			if( $setFlash ) {
				if( !$response['file_exists'] ) {
					$this->controller->Session->setFlash( 'Il n\'est pas certain que le serveur Gedooo fonctionne. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'notice' ) );
				}
				else if( !( $response['status'] && $response['content-type'] ) ) {
					$this->controller->Session->setFlash( 'Impossible de se connecter au serveur Gedooo. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'error' ) );
				}
				else if( $testServer && !$response['print'] ) { // FIXME: même message si le modèle odt n'a pas été trouvé
					$this->controller->Session->setFlash( 'Impossible d\'imprimer avec le serveur Gedooo. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'error' ) );
				}
			}
			else if( $asBoolean ) {
				foreach( $response as $return ) {
					if( !$return ) {
						return false;
					}
				}
				return true;
			}
			else {
				return $response;
			}
		}

		/**
		*
		*/

		public function sendPdfContentToClient( $content, $filename ) {
			header( 'Content-type: application/pdf' );
			header( 'Content-Length: '.strlen( $content ) );
			header( "Content-Disposition: attachment; filename={$filename}" );

			echo $content;
			die();
		}

		/** *******************************************************************
			The beforeRedirect method is invoked when the controller's redirect method
			is called but before any further action. If this method returns false the
			controller will not continue on to redirect the request.
			The $url, $status and $exit variables have same meaning as for the controller's method.
		******************************************************************** */
		public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			parent::beforeRedirect( $controller, $url, $status , $exit );
		}
	}
?>