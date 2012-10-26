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
		public $components = array( 'Gedooo.Gedooo', 'Jetons2' );

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


		public function indexparams() {

		}

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
				'fields' => array(
					$this->Cer93->Contratinsertion->Fichiermodule->sqNbFichiersLies( $this->Cer93->Contratinsertion, 'nb_fichiers_lies' ),
					'Cer93.positioncer',
					'Cer93.formeci',
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
					'Cer93.id',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Contratinsertion.rg_ci',
					'Contratinsertion.decision_ci'
				),
				'contain' => array(
					'Cer93'
				),
				'conditions' => array(
					'Contratinsertion.personne_id' => $personne_id,
					'Cer93.id IS NOT NULL'
				),
				'order' => array(
					'Cer93.id DESC' // FIXME
				),
				'order' => array( 'Contratinsertion.dd_ci DESC' )
			);

			$results = $this->Cer93->Contratinsertion->find( 'all', $querydata );

			$options = array(
				'Contratinsertion' => array(
					'decision_ci' => ClassRegistry::init( 'Option' )->decision_ci()
				)
			);
			$options = Set::merge( $options, $this->Cer93->enums() );

			$disabledLinks = array(
				'Cers93::edit' => '!in_array( \'#Cer93.positioncer#\', array( \'00enregistre\' ) ) || ( \'%permission%\' == \'0\' )' ,
				'Cers93::signature' => '!in_array( \'#Cer93.positioncer#\', array( \'00enregistre\', \'01signe\' ) ) || ( \'%permission%\' == \'0\' )' ,
				'Histoschoixcers93::attdecisioncpdv' => '!in_array( \'#Cer93.positioncer#\', array( \'01signe\', \'02attdecisioncpdv\' ) ) || ( \'%permission%\' == \'0\' )',
				'Histoschoixcers93::attdecisioncg' => '!in_array( \'#Cer93.positioncer#\', array( \'02attdecisioncpdv\', \'03attdecisioncg\' ) ) || ( \'%permission%\' == \'0\' )',
				'Histoschoixcers93::premierelecture' => '!in_array( \'#Cer93.positioncer#\', array( \'03attdecisioncg\', \'04premierelecture\' ) ) || ( \'%permission%\' == \'0\' )',
				'Histoschoixcers93::secondelecture' => '!in_array( \'#Cer93.positioncer#\', array( \'04premierelecture\', \'05secondelecture\' ) ) || ( \'%permission%\' == \'0\' )',
				'Histoschoixcers93::aviscadre' => '!in_array( \'#Cer93.positioncer#\', array( \'05secondelecture\', \'06attaviscadre\' ) ) || ( \'%permission%\' == \'0\' )',
			);

			$this->set( 'options', $options);
			$this->set( 'cers93', $results );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'disabledLinks', $disabledLinks );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );
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
debug($this->request->data);
				if( $this->Cer93->saveFormulaire( $this->request->data ) ) {
					$this->Cer93->Contratinsertion->commit(); //FIXME
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cer93->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			if( empty( $this->request->data ) ) {
				$this->request->data = $this->Cer93->prepareFormData( $personne_id, ( ( $this->action == 'add' ) ? null : $id ), $this->Session->read( 'Auth.User.id' ) );
			}

			$naturescontrats = $this->Cer93->Naturecontrat->find(
				'all',
				array(
					'order' => array( 'Naturecontrat.name ASC' )
				)
			);
			$naturecontratDuree = Set::extract( '/Naturecontrat[isduree=1]', $naturescontrats );
			$naturecontratDuree = Set::extract( '/Naturecontrat/id', $naturecontratDuree );
			$this->set( 'naturecontratDuree', $naturecontratDuree );
			
			$sujetscers93 = $this->Cer93->Sujetcer93->find(
				'all',
				array(
					'order' => array( 'Sujetcer93.name ASC' )
				)
			);
			
			$soussujetscers93 = $this->Cer93->Sujetcer93->Soussujetcer93->find(
				'list',
				array(
					'fields' => array(
						'Soussujetcer93.id',
						'Soussujetcer93.name',
						'Soussujetcer93.sujetcer93_id',
					),
					'order' => array( 'Soussujetcer93.sujetcer93_id ASC', 'Soussujetcer93.name ASC' )
				)
			);
			debug($soussujetscers93);
			$this->set( 'soussujetscers93', $soussujetscers93 );

			// Options
			$options = array(
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Cer93->Contratinsertion->Structurereferente->listOptions(),
					'referent_id' => $this->Cer93->Contratinsertion->Referent->listOptions()
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
					'metierexerce_id' => $this->Cer93->Expprocer93->Metierexerce->find( 'list' ),
					'secteuracti_id' => $this->Cer93->Expprocer93->Secteuracti->find( 'list' )
				),
				'Foyer' => array(
					'sitfam' => ClassRegistry::init( 'Option' )->sitfam()
				),
				'Dsp' => array(
					'natlog' => ClassRegistry::init( 'Option' )->natlog()
				),
				'Naturecontrat' => array(
					'naturecontrat_id' => Set::combine( $naturescontrats, '{n}.Naturecontrat.id', '{n}.Naturecontrat.name' )
				),
				'Sujetcer93' => array(
					'sujetcer93_id' => Set::combine( $sujetscers93, '{n}.Sujetcer93.id', '{n}.Sujetcer93.name' )
				),
				'dureehebdo' => array_range( '0', '39' ),
				'dureecdd' => ClassRegistry::init( 'Option' )->duree_cdd()
			);
			$options = array_merge( $this->Cer93->Contratinsertion->Personne->Dsp->enums(), $this->Cer93->enums(), $options );

			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'options' ) );
			$this->render( 'edit' );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );
		}

		/**
		 * Fonction permettant de saisir la date de signature du CER.
		 * Le statut du CER est également mis à jour à la valeur "signé"
		 *
		 * @param integer $id du contratinsertion en question
		 * @return void
		 */
		public function signature( $id ) {
			// On s'assure que l'id passé en paramètre existe bien
			if( empty( $id ) ) {
				throw new NotFoundException();
			}

			$this->Cer93->Contratinsertion->id = $id;
			$personne_id = $this->Cer93->Contratinsertion->field( 'personne_id' );

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

			if( !empty( $this->request->data ) ) {
				$this->Cer93->begin();
				$saved = $this->Cer93->save( $this->request->data );
				if( $saved ) {
					$this->Cer93->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cer93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			if( empty( $this->request->data ) ) {
				$this->request->data = $this->Cer93->prepareFormData( $personne_id, $id, $this->Session->read( 'Auth.User.id' ) );
			}

			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );
		}
		
		/**
		 * Imprime un CER 93.
		 * INFO: http://localhost/webrsa/trunk/cers93/impression/44327
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function impression( $contratinsertion_id = null ) {
			$pdf = $this->Cer93->getDefaultPdf( $contratinsertion_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$contratinsertion_id}_nouveau.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de contrat d\'insertion.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>