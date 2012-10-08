<?php
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
		 *
		 * @param Controller $controller
		 * @param array $settings
		 */
		public function initialize( &$controller, $settings = array( ) ) {
			$this->controller = $controller;
		}

		/**
		 * Création d'un répertoire temporaire (inscriptible par tout le monde) de manière récursive
		 * si nécessaire. Si le répertoire existe déjà, et que les permissions ne sont pas suffisantes, on
		 * essaie de le rendre inscriptible pour tout le monde.
		 *
		 * @param string $path Le chemin du répertoire temporaire à créer
		 * @return boolean true si le répertoire existe et est inscriptible, false sinon
		 */
		public function makeTmpDir( $path ) {
			$umask = 0777;
			$success = false;

			if( is_dir( $path ) ) { // Le chemin existe déjà
				$acutalmask = fileperms( $path );
				if( $acutalmask >= $umask ) { // Permissions suffisantes
					$success = true;
				}
				else {
					$return = chmod( $path, $umask );
				}
			}
			else {
				$oldUmask = umask( 0 );
				$success = @mkdir( $path, $umask, true );
				umask( $oldUmask );
			}

			return $success;
		}

		/**
		 * Concactène les pdfs grâce à pdftk (écrits dans un répertoire temporaire) et renvoit le résultat.
		 *
		 * @param array $pdfs
		 * @param string $modelName
		 * @return mixed
		 */
		public function concatPdfs( $pdfs, $modelName ) {
			$pdfTmpDir = rtrim( Configure::read( 'Cohorte.dossierTmpPdfs' ), '/' ).'/'.session_id().'/'.$modelName;
			/* $old = umask(0);
			  @mkdir( $pdfTmpDir, 0777, true ); /// FIXME: vérification
			  umask($old); */
			$this->makeTmpDir( $pdfTmpDir );

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
		 * Parcourt l'array de réponses renvoyée lors d'un appel à la méthode GedoooXXXBehavior::gedTests()
		 * et renvoit true lorsque tous les éléments ont une clé 'success' à true, false sinon.
		 *
		 * @param array $response
		 * @return boolean
		 */
		protected function _checkResponseAsBoolean( $response ) {
			foreach( $response as $key => $return ) {
				if( !$return['success'] ) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Vérification de l'état du serveur Gedooo.
		 *
		 * @param boolean $asBoolean Doit-on renvoyer un array avec les différentes vérifications, ou un résumé
		 * @param boolean $setFlash Doit-on afficher un message d'erreur s'il Gedooo est mal configuré
		 * @return mixed
		 */
		public function check( $asBoolean = false, $setFlash = false ) {
			App::import( 'Behavior', 'Gedooo.Gedooo' );

			$GedModel = ClassRegistry::init( 'User' );
			$GedModel->Behaviors->attach( 'Gedooo' );
			$response = @$GedModel->gedTests();

			// FIXME: traductions
			$traductions = array(
				'status' => 'Accès au WebService',
				'file_exists' => 'Présence du modèle de test',
				'print' => 'Test d\'impression',
			);

			if( $setFlash ) {
				if( !$response[$traductions['file_exists']] ) {
					$this->controller->Session->setFlash( 'Il n\'est pas certain que le serveur Gedooo fonctionne car le modèle de document de test n\'existe pas. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'notice' ) );
				}
				else if( !$response[$traductions['status']] ) {
					$this->controller->Session->setFlash( 'Impossible de se connecter au serveur Gedooo. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'error' ) );
				}
				else if( !$response[$traductions['print']] ) {
					$this->controller->Session->setFlash( 'Impossible d\'imprimer avec le serveur Gedooo. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'error' ) );
				}
				else {
					if( !$this->_checkResponseAsBoolean( $response ) ) {
						$this->controller->Session->setFlash( 'Impossible d\'imprimer avec le serveur Gedooo. Veuillez contacter votre administrateur système.', 'default', array( 'class' => 'error' ) );
					}
				}
			}
			else if( $asBoolean ) {
				return $this->_checkResponseAsBoolean( $response );
			}
			else {
				return $response;
			}
		}

		/**
		 * Envoit les en-têtes (content-type pdf, taille du fichier, nom du fichier) et le contenu d'un fichier à
		 * télécharger par le client.
		 *
		 * @param string $content Le contenu du fichier à envoyer à l'utilisateur
		 * @param string $filename Le nom du fichier envoyé à l'utilisateur
		 */
		public function sendPdfContentToClient( $content, $filename ) {
			header( 'Content-type: application/pdf' );
			header( 'Content-Length: '.strlen( $content ) );
			header( "Content-Disposition: attachment; filename={$filename}" );

			echo $content;
			die();
		}

		/**
		 * The beforeRedirect method is invoked when the controller's redirect method is called but before
		 * any further action. If this method returns false the controller will not continue on to redirect
		 * the request.
		 * The $url, $status and $exit variables have same meaning as for the controller's method.
		 *
		 * @param Controller $controller
		 * @param mixed $url
		 * @param integer $status
		 * @param boolean $exit
		 */
		public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			parent::beforeRedirect( $controller, $url, $status, $exit );
		}

	}
?>