<?php
	/**
	 * Code source de la classe Nonorientes66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Nonorientes66Controller ... (CG 66).
	 *
	 * @package app.Controller
	 */
	class Nonorientes66Controller extends AppController
	{
		public $name = 'Nonorientes66';

		public $uses = array(
			'Nonoriente66',
			'Option'
		);

		public $helpers = array( 'Fileuploader' );

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		public $components = array(
			'Fileuploader',
			'Jetons2'
		);

		public function _setOptions() {
			$this->set( 'options',  $this->Nonoriente66->allEnumLists() );
		}
		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		*/

		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		* FIXME: traiter les valeurs de retour
		*/

		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		*   Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
		*/

		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		*   Téléchargement des fichiers préalablement associés à un traitement donné
		*/

		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		*   Fonction permettant d'accéder à la page pour lier les fichiers à l'Orientation
		*/

		public function filelink( $id ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array();
			$nonoriente66 = $this->Nonoriente66->find(
				'first',
				array(
					'conditions' => array(
						'Nonoriente66.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $nonoriente66['Nonoriente66']['personne_id'];
			$dossier_id = $this->Nonoriente66->Personne->dossierId( $personne_id );

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$redirect_url = $this->Session->read( "Savedfilters.{$this->name}.{$this->action}" );
				if( !empty( $redirect_url ) ) {
					$this->Session->delete( "Savedfilters.{$this->name}.{$this->action}" );
					$this->redirect( Router::url( $redirect_url, true ) );
				}
			}

			if( !empty( $this->request->data ) ) {
				$this->Nonoriente66->begin();

				$saved = $this->Nonoriente66->updateAll(
					array( 'Nonoriente66.haspiecejointe' => '\''.$this->request->data['Nonoriente66']['haspiecejointe'].'\'' ),
					array(
						'"Nonoriente66"."personne_id"' => $personne_id,
						'"Nonoriente66"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "Nonoriente66.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Nonoriente66->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Nonoriente66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'nonoriente66' ) );
			$this->_setOptions();
		}
	}
?>