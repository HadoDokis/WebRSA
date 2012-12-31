<?php
	/**
	 * Code source de la classe Histoschoixcers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Histoschoixcers93Controller permet la gestion des historiques du CG 93 (hors workflow).
	 *
	 * @package app.Controller
	 */
	class Histoschoixcers93Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Histoschoixcers93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array( 'Jetons2', 'DossiersMenus' );

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Histochoixcer93' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Checkboxes' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'attdecisioncg' => 'create',
			'attdecisioncpdv' => 'create',
			'aviscadre' => 'create',
			'premierelecture' => 'create',
			'secondelecture' => 'create',
		);

		/**
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function attdecisioncpdv( $contratinsertion_id ) {
			return $this->_decision( $contratinsertion_id, '02attdecisioncpdv' );
		}

		/**
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function attdecisioncg( $contratinsertion_id ) {
			return $this->_decision( $contratinsertion_id, '03attdecisioncg' );
		}

		/**
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function premierelecture( $contratinsertion_id ) {
			return $this->_decision( $contratinsertion_id, '04premierelecture' );
		}

		/**
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function secondelecture( $contratinsertion_id ) {
			return $this->_decision( $contratinsertion_id, '05secondelecture' );
		}

		/**
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function aviscadre( $contratinsertion_id ) {
			return $this->_decision( $contratinsertion_id, '06attaviscadre' );
		}

		/**
		 * FIXME: decision()
		 *
		 * @param integer $contratinsertion_id
		 * @param string $etape
		 * @throws NotFoundException
		 * @return void
		 */
		protected function _decision( $contratinsertion_id, $etape ) {
			// On s'assure que l'id passé en paramètre existe bien
			if( empty( $contratinsertion_id ) ) {
				throw new NotFoundException();
			}

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $this->Histochoixcer93->Cer93->Contratinsertion->personneId( $contratinsertion_id ) ) ) );

			$this->Histochoixcer93->Cer93->Contratinsertion->id = $contratinsertion_id;
			$personne_id = $this->Histochoixcer93->Cer93->Contratinsertion->field( 'personne_id' );

			// Le dossier auquel appartient la personne
			$dossier_id = $this->Histochoixcer93->Cer93->Contratinsertion->Personne->dossierId( $personne_id );

			// On s'assure que le dossier lié existe bien
			if( empty( $dossier_id ) ) {
				throw new NotFoundException();
			}

			// Tentative d'acquisition du jeton sur le dossier
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'cers93', 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Histochoixcer93->begin();

				$saved = $this->Histochoixcer93->saveDecision( $this->request->data );

				if( $saved ) {
					$this->Histochoixcer93->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'cers93', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Histochoixcer93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$contratinsertion = $this->Histochoixcer93->Cer93->dataView( $contratinsertion_id );


			if( empty( $this->request->data ) ) {
				$this->request->data = $this->Histochoixcer93->prepareFormData(
					$contratinsertion,
					$etape,
					$this->Session->read( 'Auth.User.id' )
				);
			}

			$commentairesnormescers93 = $this->Histochoixcer93->Commentairenormecer93->find(
				'all',
				array(
					'order' => array( 'Commentairenormecer93.isautre ASC', 'Commentairenormecer93.name ASC' )
				)
			);

			$commentairenormecer93_isautre_id = Hash::extract( $commentairesnormescers93, '{n}.Commentairenormecer93[isautre=1]' );
			if( !empty( $commentairenormecer93_isautre_id ) ) {
				$commentairenormecer93_isautre_id = $commentairenormecer93_isautre_id[0]['id'];
			}
			else {
				$commentairenormecer93_isautre_id = null;
			}

			$options = array(
				'Commentairenormecer93' => array(
					'commentairenormecer93_id' => Set::combine( $commentairesnormescers93, '{n}.Commentairenormecer93.id', '{n}.Commentairenormecer93.name' )
				)
			);

			$options = Set::merge(
				$options,
				$this->Histochoixcer93->Cer93->optionsView()
			);
// debug($options);
			$this->set( 'options', $options );

			$this->set( 'commentairenormecer93_isautre_id', $commentairenormecer93_isautre_id );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'contratinsertion', $contratinsertion );
			$this->set( 'userConnected', $this->Session->read( 'Auth.User.id' ) );


			if( in_array( $this->action, array( 'attdecisioncpdv', 'attdecisioncg' ) ) ) {
				$this->render( 'decision' );
			}
		}
	}
?>
