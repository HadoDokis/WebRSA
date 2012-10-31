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
		public $components = array( 'Jetons2' );

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Histochoixcer93' );

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

			// Recherche du contrat pour l'affichage
			$contratinsertion = $this->Histochoixcer93->Cer93->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => array(
						'Cer93' => array(
							'Compofoyercer93',
							'Diplomecer93',
							'Expprocer93',
							'Histochoixcer93' => array(
								'order' => array( 'Histochoixcer93.etape ASC' )
							),
							'Sujetcer93'
						),
						'Structurereferente' => array(
							'Typeorient'
						),
						'Referent'
					)
				)
			);

			$sousSujetsIds = Set::filter( Set::extract( $contratinsertion, '/Cer93/Sujetcer93/Cer93Sujetcer93/soussujetcer93_id' ) );
			if( !empty( $sousSujetsIds ) ) { 
				$sousSujets = $this->Histochoixcer93->Cer93->Sujetcer93->Soussujetcer93->find( 'list', array( 'conditions' => array( 'Soussujetcer93.id' => $sousSujetsIds ) ) );
				foreach( $contratinsertion['Cer93']['Sujetcer93'] as $key => $values ) {
					if( isset( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) ) {
						$contratinsertion['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => $sousSujets[$values['Cer93Sujetcer93']['soussujetcer93_id']] );
					}
					else {
						$contratinsertion['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => null );
					}
				}
			}

			if( empty( $this->request->data ) ) {
				$this->request->data = $this->Histochoixcer93->prepareFormData(
					$contratinsertion,
					$etape,
					$this->Session->read( 'Auth.User.id' )
				);
			}
/*
			$options = array_merge(
				$this->Histochoixcer93->enums(),
				array(
					'Cer93' => array(
						'formeci' => ClassRegistry::init( 'Option' )->forme_ci()
					)
				)
			);
			*/
			// Options
			$options = array(
				'Cer93' => array(
					'formeci' => ClassRegistry::init( 'Option' )->forme_ci()
				),
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Histochoixcer93->Cer93->Contratinsertion->Structurereferente->listOptions(),
					'referent_id' => $this->Histochoixcer93->Cer93->Contratinsertion->Referent->listOptions()
				),
				'Prestation' => array(
					'rolepers' => ClassRegistry::init( 'Option' )->rolepers()
				),
				'Personne' => array(
					'qual' => ClassRegistry::init( 'Option' )->qual()
				),
				'Adresse' => array(
					'typevoie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Serviceinstructeur' => array(
					'typeserins' => ClassRegistry::init( 'Option' )->typeserins()
				),
				'Expprocer93' => array(
					'metierexerce_id' => $this->Histochoixcer93->Cer93->Expprocer93->Metierexerce->find( 'list' ),
					'secteuracti_id' => $this->Histochoixcer93->Cer93->Expprocer93->Secteuracti->find( 'list' )
				),
				'Foyer' => array(
					'sitfam' => ClassRegistry::init( 'Option' )->sitfam()
				),
				'Dsp' => array(
					'natlog' => ClassRegistry::init( 'Option' )->natlog()
				),
				'dureehebdo' => array_range( '0', '39' ),
				'dureecdd' => ClassRegistry::init( 'Option' )->duree_cdd(),
				'Structurereferente' => array(
					'type_voie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Naturecontrat' => array(
					'naturecontrat_id' => $this->Histochoixcer93->Cer93->Naturecontrat->find( 'list' )
				)
			);
			$options = Set::merge(
				$this->Histochoixcer93->Cer93->Contratinsertion->Personne->Dsp->enums(),
				$this->Histochoixcer93->Cer93->enums(),
				$this->Histochoixcer93->enums(),
				$options
			);
// debug($options);
			$this->set( 'options', $options );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'contratinsertion', $contratinsertion );
			$this->set( 'userConnected', $this->Session->read( 'Auth.User.id' ) );
// 			$this->set( 'urlmenu', '/cers93/index/'.$personne_id );

			if( in_array( $this->action, array( 'attdecisioncpdv', 'attdecisioncg' ) ) ) {
				$this->render( 'decision' );
			}
		}
	}
?>
