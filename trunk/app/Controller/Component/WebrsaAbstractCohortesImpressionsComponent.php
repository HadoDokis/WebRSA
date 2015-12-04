<?php
	/**
	 * Code source de la classe WebrsaAbstractCohortesImpressionsComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );
	require_once( APPLIBS.'cmis.php' );

	/**
	 * La classe WebrsaAbstractCohortesImpressionsComponent ...
	 *
	 * @package app.Controller.Component
	 */
	abstract class WebrsaAbstractCohortesImpressionsComponent extends WebrsaAbstractRecherchesNewComponent
	{
		/**
		 * Components utilisés par ce component
		 *
		 * @var array
		 */
		public $components = array( 'Allocataires', 'Gedooo.Gedooo' );

		/**
		 * Retourne un array avec clés de paramètres complétées en fonction du
		 * contrôleur; cette méthode surcharge celle du parent en ajout la clé suivante:
		 *	- documentPath: le chemin (au sens Hash::get()) du document PDF
		 *	  individuel, utilisé dans la méthode _concat()
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _params( array $params = array() ) {
			$params += array(
				'documentPath' => 'Pdf.document'
			);

			return $params + parent::_params( $params );
		}

		/**
		 * Retourne un array de PDF, sous la clé <FIXME> à partir du query, ou
		 * le nombre de documents n'ayant pas pu être imprimés.
		 *
		 * @param array $query
		 * @param array $params
		 * @return integer|array
		 */
		abstract protected function _pdfs( array $query, array $params );

		/**
		 * Envoi du résultat au navigateur, en cas d'erreur du post-traitement,
		 * ou de document concaténé vide.
		 *
		 * @param boolean $success Succès de l'opération de post-traitement
		 * @param string $content Document concaténé à envoyer
		 * @param array $params
		 */
		protected function _send( $success, $content, array $params ) {
			$Controller = $this->_Collection->getController();

			if( $success !== false && $content !== false ) {
				$fileName = sprintf( '%s-%s-%s.pdf', $Controller->request->params['controller'], $Controller->request->params['action'], date( "Ymd-H\hi" ) );
				$this->Gedooo->sendPdfContentToClient( $content, $fileName );
				die();
			}
			else {
				$Controller->Session->setFlash( 'Erreur lors de l\'impression en cohorte.', 'flash/error' );
				$Controller->redirect( $Controller->referer() );
			}
		}

		/**
		 * Post-traitement des résultats de la requête (par exemple pour la mise
		 * à jour d'une date d'impression).
		 * Cette fonction doit retourner vrai pour que l'envoi se fasse.
		 *
		 * @param array $results
		 * @param array $params
		 * @return boolean
		 */
		protected function _postProcess( array $results, array $params ) {
			return true;
		}

		/**
		 * Retourne la concaténation des pdfs contenus dans les résultats ou
		 * message d'erreur éventuel.
		 *
		 * @param integer|array $results
		 * @param array $params
		 * @return string
		 */
		protected function _concat( $results, array $params ) {
			$Controller = $this->_Collection->getController();

			if( !is_array( $results ) ) {
				$msgstr = "Erreur lors de l'impression en cohorte: %d documents n'ont pas pu être imprimés. Abandon de l'impression de la cohorte. Demandez à votre administrateur d'exécuter cake/console/cake generationpdfs orientsstructs -username <username> (où <username> est l'identifiant de l'utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression)";
				$Controller->Session->setFlash( sprintf( $msgstr, $results ), 'flash/error' );
				$Controller->redirect( $Controller->referer() );
			}

			$content = $this->Gedooo->concatPdfs(
				Hash::extract( $results, "{n}.{$params['documentPath']}" ),
				Inflector::camelize( "{$Controller->request->params['controller']}_{$Controller->request->params['action']}" )
			);

			return $content;
		}

		/**
		 * Cohorte d'impressions.
		 *
		 * @fixme: cohortes à la volée
		 *
		 * @param array $params
		 */
		final public function impressions( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$defaults = array( 'limit' => false );
			$params = $this->_params( $params + $defaults );

			// Initialisation de la recherche
			$this->_initializeSearch( $params );

			// Récupération des valeurs du formulaire de recherche
			$filters = $this->_filters( $params );

			// Récupération du query
			$query = $this->_query( $filters, $params );

			$results = $this->_pdfs( $query, $params );

			$content = $this->_concat( $results, $params );

			$success = ( $content !== false ) && $this->_postProcess( $results, $params );

			$this->_send( $success, $content, $params );
		}
	}
?>