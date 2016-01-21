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

		public $helpers = array(
			'Default', 
			'Locale', 
			'Cake1xLegacy.Ajax', 
			'Xform', 
			'Xhtml', 
			'Fileuploader', 
			'Default2',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		public $components = array(
			'Default', 
			'Gedooo.Gedooo', 
			'Fileuploader', 
			'Jetons2', 
			'DossiersMenus', 
			'InsertionsAllocataires',
			'Cohortes',
			'Search.SearchPrg' => array(
				'actions' => array( 
					'cohorte_isemploi' => array(
						'filter' => 'Search'
					),
					'cohorte_imprimeremploi' => array(
						'filter' => 'Search'
					),
				)
			),
		);

		public function _setOptions() {
			$this->set( 'options', (array)Hash::get( $this->Nonoriente66->enums(), 'Nonoriente66' ) );
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
					$this->redirect( $redirect_url );
				}
			}

			if( !empty( $this->request->data ) ) {
				$this->Nonoriente66->begin();

				$saved = $this->Nonoriente66->updateAllUnBound(
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
		
		/**
		 * Cohorte
		 */
		public function cohorte_isemploi() {
			$Cohorte = $this->Components->load( 'WebrsaCohortesNonorientes66New' );
			$Cohorte->cohorte( array( 'modelName' => 'Personne', 'modelRechercheName' => 'WebrsaCohorteNonoriente66Isemploi' ) );
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_isemploi() {
			$Cohorte = $this->Components->load( 'WebrsaCohortesNonorientes66New' );
			$Cohorte->exportcsv( array( 'modelName' => 'Personne', 'modelRechercheName' => 'WebrsaCohorteNonoriente66Isemploi' ) );
		}
		
		/**
		 * Lance l'impression d'un questionnaire dans le cadre d'un Allocataire non inscrits au Pôle Emploi
		 * 
		 * @param integer $personne_id
		 */
		public function imprimeremploi( $personne_id ) {
			$pdf = $this->Nonoriente66->getDefaultPdf($personne_id, $this->Session->read( 'Auth.User.id' ));
			$success = $this->Nonoriente66->saveImpression($personne_id, $this->Session->read( 'Auth.User.id' ));

			if( !empty( $pdf ) && $success ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'nonorientation-%d-%s.pdf', $personne_id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
		
		/**
		 * Cohorte
		 */
		public function cohorte_imprimeremploi() {
			$Cohorte = $this->Components->load( 'WebrsaCohortesNonorientes66ImpressionsNew' );
			$Cohorte->search( 
				array( 'modelName' => 'Personne', 'modelRechercheName' => 'WebrsaCohorteNonoriente66Imprimeremploi' ) 
			);
		}
		
		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv_imprimeremploi() {
			$Cohorte = $this->Components->load( 'WebrsaCohortesNonorientes66ImpressionsNew' );
			$Cohorte->exportcsv( 
				array( 'modelName' => 'Personne', 'modelRechercheName' => 'WebrsaCohorteNonoriente66Imprimeremploi' ) 
			);
		}
		
		/**
		 * Impression de la cohorte
		 */
		public function cohorte_imprimeremploi_impressions() {
			$Cohortes = $this->Components->load( 'WebrsaCohortesNonorientes66ImpressionsNew' );
			$Cohortes->impressions( 
				array( 
					'modelName' => 'Personne', 
					'modelRechercheName' => 'WebrsaCohorteNonoriente66Imprimeremploi',
					'configurableQueryFieldsKey' => 'Dossierspcgs66.cohorte_imprimeremploi'
				) 
			);
		}
	}
?>