<?php
	/**
	 * Code source de la classe Cers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cers93Controller permet la gestion des CER du CG 93 (hors workflow).
	 *
	 * @package app.Controller
	 */
	class Cers93Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Cers93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array( 'Jetons2' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Webrsa' );

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Cer93' );
		
		public $aucunDroit = array( 'ajax', 'ajaxref', 'ajaxstruct' );

		
		/**
		 * Ajax pour les coordonnées de la structure référente liée (CG 93).
		 *
		 * @param type $structurereferente_id
		 */
		public function ajaxstruct( $structurereferente_id = null ) {
			Configure::write( 'debug', 0 );
			$this->set( 'typesorients', $this->Cer93->Contratinsertion->Personne->Orientstruct->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) ) );

			$dataStructurereferente_id = Set::extract( $this->request->data, 'Contratinsertion.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$qd_struct = array(
				'conditions' => array(
					'Structurereferente.id' => $structurereferente_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$struct = $this->Cer93->Contratinsertion->Structurereferente->find( 'first', $qd_struct );


			$this->set( 'struct', $struct );
			$this->render( 'ajaxstruct', 'ajax' );
		}
		
		
		/**
		 * Ajax pour les coordonnées du référent (CG 58, 66, 93).
		 *
		 * @param integer $referent_id
		 */
		public function ajaxref( $referent_id = null ) {
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->request->data, 'Contratinsertion.referent_id' ) );
			}

			$referent = array( );
			if( !empty( $referent_id ) ) {
				$qd_referent = array(
					'conditions' => array(
						'Referent.id' => $referent_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$referent = $this->Cer93->Contratinsertion->Structurereferente->Referent->find( 'first', $qd_referent );
			}
			$this->set( 'referent', $referent );
			$this->render( 'ajaxref', 'ajax' );
		}
		
		
		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @param integer $personne_id L'id technique de l'allocataire auquel le CER est attaché.
		 * @return void
		 * @throws NotFoundException
		 */
		public function index( $personne_id = null ) {
			if( !$this->Cer93->Contratinsertion->Personne->exists( $personne_id ) ) {
				throw new NotFoundException();
			}

			$querydata = array(
				'contain' => array(
					'Cer93'
				),
				'conditions' => array(
					'Contratinsertion.personne_id' => $personne_id
				)
			);

			$results = $this->Cer93->Contratinsertion->find( 'all', $querydata );

			$this->set( 'cers93', $results );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 * Formulaire d'ajout d'un élémént.
		 *
		 * @return void
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un <élément>.
		 *
		 * @return void
		 * @throws NotFoundException
		 */
		public function edit( $id = null ) {
			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			else {
				$this->Cer93->Contratinsertion->id = $id;
				$personne_id = $this->Cer93->Contratinsertion->field( 'personne_id' );
			}

			// Le dossier auquel appartient la personne
			$dossier_id = $this->Cer93->Contratinsertion->Personne->dossierId( $personne_id );

			// On s'assure que l'id passé en paramètre et le dossier lié existent bien
			if( empty( $personne_id ) || empty( $dossier_id ) ) {
				throw new NotFoundException();
			}

			// Tentative d'acquisition du jeton sur le dossier
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Tentative de sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Cer93->Contratinsertion->begin();

				if( $this->Cer93->saveFormulaire( $this->request->data ) ) {
					$this->Cer93->Contratinsertion->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cer93->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Cer93->Contratinsertion->find(
					'first',
					array(
						'fields' => array_merge(
							$this->Cer93->Contratinsertion->fields(),
							$this->Cer93->fields()
						),
						'conditions' => array(
							'Contratinsertion.id' => $id
						),
						'joins' => array(
							$this->Cer93->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
						),
						'contain' => false
					)
				);
// 				debug($this->request->data );
			}

			$Informationpe = ClassRegistry::init( 'Informationpe' );
			// Lecture des informations non modifiables
			$personne = $this->Cer93->Contratinsertion->Personne->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Cer93->Contratinsertion->Personne->fields(),
						$this->Cer93->Contratinsertion->Personne->Prestation->fields(),
						$this->Cer93->Contratinsertion->Personne->Dsp->fields(),
						$this->Cer93->Contratinsertion->Personne->DspRev->fields(),
						$this->Cer93->Contratinsertion->Personne->Foyer->fields(),
						$this->Cer93->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Cer93->Contratinsertion->Personne->Foyer->Dossier->fields(),
						array(
							$this->Cer93->Contratinsertion->vfRgCiMax( '"Personne"."id"' ),
							'Historiqueetatpe.identifiantpe',
							'Historiqueetatpe.etat'
						)
					),
					'joins' => array(
						$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
						$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cer93->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
						$this->Cer93->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
						$this->Cer93->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
						$this->Cer93->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
						$this->Cer93->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cer93->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cer93->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Personne.id' => $personne_id,
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ( '.$this->Cer93->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'Dsp.id IS NULL',
								'Dsp.id IN ( '.$this->Cer93->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'DspRev.id IS NULL',
								'DspRev.id IN ( '.$this->Cer93->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'Informationpe.id IS NULL',
								'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
							)
						),
						array(
							'OR' => array(
								'Historiqueetatpe.id IS NULL',
								'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
							)
						)
					),
					'contain' => false
				)
			);
			
			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $personne['DspRev']['id'] ) ) {
				$personne['Dsp'] = $personne['DspRev'];
				unset( $personne['DspRev'], $personne['Dsp']['id'], $personne['Dsp']['dsp_id'] );
			}
			
			$etatInscription = null;
			if( !empty( $personne['Historiqueetatpe']['etat'] ) && ( $personne['Historiqueetatpe']['etat'] == 'inscription' ) ) {
				$etatInscription = '1';
			}
			$this->set( 'etatInscription', $etatInscription );
// debug($personne);
			// Récupération des informations de composition du foyer de l'allocataire
			$composfoyerscers93 = $this->Cer93->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'Prestation.rolepers'
					),
					'conditions' => array( 'Personne.foyer_id' => $personne['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);

			// Options
			$options = array(
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Cer93->Contratinsertion->Structurereferente->listOptions()
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
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Cer93->Contratinsertion->Structurereferente->listOptions(),
					'referent_id' => $this->Cer93->Contratinsertion->Referent->listOptions()
				),
				'Foyer' => array(
					'sitfam' => ClassRegistry::init( 'Option' )->sitfam()
				),
				'Dsp' => array(
					'natlog' => ClassRegistry::init( 'Option' )->natlog()
				)
			);
			$options = array_merge( $this->Cer93->Contratinsertion->Personne->Dsp->enums(), $this->Cer93->enums(), $options );

			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'options', 'personne', 'composfoyerscers93' ) );
			$this->render( 'edit' );
		}
	}
?>
