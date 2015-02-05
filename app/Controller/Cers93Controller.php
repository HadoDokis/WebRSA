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
		public $components = array( 'Gedooo.Gedooo', 'Jetons2', 'DossiersMenus', 'InsertionsAllocataires' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Cake1xLegacy.Ajax',
			'Locale',
			'Webrsa',
			'Romev3',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			)
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Cer93', 'Catalogueromev3' );

		/**
		 * Actions non soumises aux droits.
		 *
		 * @var array
		 */
		public $aucunDroit = array( 'ajax', 'ajaxref', 'ajaxstruct' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxref' => 'read',
			'ajaxstruct' => 'read',
			'cancel' => 'update',
			'delete' => 'delete',
			'edit' => 'update',
			'edit_apres_signature' => 'update',
			'impression' => 'read',
			'impressionDecision' => 'read',
			'index' => 'read',
			'indexparams' => 'read',
			'signature' => 'update',
			'view' => 'read',
		);

		/**
		 * Action vide pour obtenir l'écran de paramétrage.
		 *
		 * @return void
		 */
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
				'contain' => array( 'Typeorient' ),
				'order' => null
			);
			$struct = $this->Cer93->Contratinsertion->Structurereferente->find( 'first', $qd_struct );

			$options = array(
				'Structurereferente' => array(
					'type_voie' => ClassRegistry::init( 'Option' )->typevoie()
				)
			);
			$this->set( 'options', $options );

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
		 * FIXME: à bouger dans le modèle Contratinsertion + remplacer celui du ContratsinsertionController
		 * (CG 58, 93)
		 *
		 * @param type $modele
		 * @param type $personne_id
		 * @return type
		 */
		protected function _qdThematiqueEp( $modele, $personne_id ) {
			$fields = array(
				'Dossierep.id',
				'Dossierep.personne_id',
				'Dossierep.themeep',
				'Dossierep.created',
				'Dossierep.modified',
				'Passagecommissionep.etatdossierep',
				'Contratinsertion.dd_ci',
				'Contratinsertion.df_ci',
				"{$modele}.id",
			);

			// FIXME: d'autres champs pour le CG 58 ?
			if( $modele == 'Signalementep93' ) {
				$fields[] = 'Signalementep93.date';
				$fields[] = 'Signalementep93.rang';
			}
			else if( $modele == 'Contratcomplexeep93' ) {
				$fields[] = 'Contratcomplexeep93.created';
			}

			return array(
				'fields' => $fields,
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => Inflector::tableize( $modele ),
					'Dossierep.id NOT IN ( '.$this->Cer93->Contratinsertion->{$modele}->Dossierep->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							)
						)
					).' )'
				),
				'joins' => array(
					$this->Cer93->Contratinsertion->{$modele}->Dossierep->join( $modele, array( 'type' => 'INNER' ) ),
					$this->Cer93->Contratinsertion->{$modele}->join( 'Contratinsertion', array( 'type' => 'INNER' ) ),
					$this->Cer93->Contratinsertion->{$modele}->Dossierep->join( 'Passagecommissionep', array( 'type' => 'LEFT OUTER' ) ),
				),
			);
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

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->_setEntriesAncienDossier( $personne_id, 'Contratinsertion' );

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
					'Contratinsertion.decision_ci',
					'Contratinsertion.datedecision',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.forme_ci',
				),
				'contain' => array(
					'Cer93' => array(
						'Histochoixcer93' => array(
							'fields' => array(
								'Histochoixcer93.isrejet'
							)
						)
					)
				),
				'conditions' => array(
					'Contratinsertion.personne_id' => $personne_id,
					'Cer93.id IS NOT NULL'
				),
				'order' => array( 'Contratinsertion.dd_ci DESC', 'Contratinsertion.rg_ci DESC' )
			);

			$results = $this->Cer93->Contratinsertion->find( 'all', $querydata );

			$options = array(
				'Contratinsertion' => array(
					'decision_ci' => ClassRegistry::init( 'Option' )->decision_ci()
				)
			);
			$options = Set::merge( $options, $this->Cer93->enums() );

			// Partie passage en EP
			// Liste des dossiers d'EP en cours pour la thématique Signalementep93
			$qdSignalementseps93 = $this->_qdThematiqueEp( 'Signalementep93', $personne_id );
			$signalementseps93 = $this->Cer93->Contratinsertion->Signalementep93->Dossierep->find( 'all', $qdSignalementseps93 );

			// Liste des dossiers d'EP en cours pour la thématique Contratcomplexeep93
			$qdContratscomplexeseps93 = $this->_qdThematiqueEp( 'Contratcomplexeep93', $personne_id );
			$contratscomplexeseps93 = $this->Cer93->Contratinsertion->Contratcomplexeep93->Dossierep->find( 'all', $qdContratscomplexeseps93 );

			// L'allocataire peut-il passer en EP ?
			$erreursCandidatePassage = $this->Cer93->Contratinsertion->Signalementep93->Dossierep->getErreursCandidatePassage( $personne_id );

			// Si c'est un rejet responsable (CPDV), on n'imprime pas la décision
			$IsRejet = false;
			foreach( $results as $i => $result ) {
				$NbHistos = count( $result['Cer93']['Histochoixcer93'] );
				if( !empty( $NbHistos ) ) {
					$dernierHisto = $result['Cer93']['Histochoixcer93'][$NbHistos-1];
					if( $dernierHisto['isrejet'] == '1' ){
						$IsRejet = true;
					}
				}
				$results[$i]['Cer93']['rejetcpdv'] = $IsRejet;
			}

			// TODO: en faire une méthode pour pouvoir l'utiliser dans les autres méthodes
			// Logique d'activation / désactiviation des liens dans la vue

			// Permissions concernant les différentes action liées à un CER
			// @see Cers93Controller::index()
			$user_type = $this->Session->read( 'Auth.User.type' );

			$positionscers = array();
			foreach( (array)Hash::extract( $results, '{n}.Cer93.positioncer' ) as $positioncer ) {
				if( strpos( $positioncer, '99' ) === false ) {
					$positionscers[] = $positioncer;
				}
			}

			$disabledLinks = array(
				'Cers93::add' => empty( $positionscers ), //On bloque l'ajout tant qu'il existe un CER non validé, rejeté ou annulé
				'Cers93::edit' => '!( in_array( \'#Cer93.positioncer#\', array( \'00enregistre\' ) ) && ( \'%permission%\' == \'1\' ) )',
				'Cers93::edit_apres_signature' => '!(
					(
						( \'externe_cpdv\' === \''.$user_type.'\' && in_array( \'#Cer93.positioncer#\', array( \'01signe\', \'02attdecisioncpdv\' ) ) )
						|| ( \'cg\' === \''.$user_type.'\' && !in_array( \'#Cer93.positioncer#\', array( \'00enregistre\' ) ) )
					)
					&& ( \'%permission%\' == \'1\' )
				)',
				'Cers93::signature' => '!( in_array( \'#Cer93.positioncer#\', array( \'00enregistre\' ) ) && ( \'%permission%\' == \'1\' ) )' ,
				'Histoschoixcers93::attdecisioncpdv' => '!( in_array( \'#Cer93.positioncer#\', array( \'01signe\' ) ) && ( \'%permission%\' == \'1\' ) )',
				'Histoschoixcers93::attdecisioncg' => '!( in_array( \'#Cer93.positioncer#\', array( \'02attdecisioncpdv\' ) ) && ( \'%permission%\' == \'1\' ) )',
				// Début FIXME: SSI l'utilisateur est CG
				'Histoschoixcers93::premierelecture' => '!( in_array( \'#Cer93.positioncer#\', array( \'03attdecisioncg\' ) ) && ( \'%permission%\' == \'1\' ) )',
				'Histoschoixcers93::secondelecture' => '!( in_array( \'#Cer93.positioncer#\', array( \'04premierelecture\' ) ) && ( \'%permission%\' == \'1\' ) )',
				'Histoschoixcers93::aviscadre' => '!( in_array( \'#Cer93.positioncer#\', array( \'05secondelecture\' ) ) && ( \'%permission%\' == \'1\' ) )',
				// Fin FIXME: SSI l'utilisateur est CG
				'Signalementseps::add' => '!( (
					// Contrat validé
					\'#Contratinsertion.decision_ci#\' == \'V\'
					// En cours, avec une durée de tolérance
					&& (
						( strtotime( \'#Contratinsertion.dd_ci#\' ) <= time() )
						&& ( strtotime( \'#Contratinsertion.df_ci#\' ) + ( Configure::read( \'Signalementep93.dureeTolerance\' ) * 24 * 60 * 60 ) >= time() )
					)
					// Aucun contrat de la personne n\'est en cours de passage en EP actuellement
					&& ( \''.( count( $signalementseps93 ) + count( $contratscomplexeseps93 ) ).'\' == \'0\' )
					//
					&& ( \''.count( $erreursCandidatePassage ).'\' == \'0\' )
				)
				&& ( \'%permission%\' == \'1\' ) )',
				'Cers93::impression' => '!( \'%permission%\' == \'1\' )' ,
				'Cers93::delete' => '!( in_array( \'#Cer93.positioncer#\', array( \'00enregistre\' ) ) && ( \'%permission%\' == \'1\' ) )',
				'Cers93::cancel' => '!( \'cg\' === \''.$user_type.'\' && !in_array( \'#Cer93.positioncer#\', array( \'00enregistre\', \'01signe\' ) ) && ( \'%permission%\' == \'1\' ) )',
				'Cers93::impressionDecision' => '!( in_array( \'#Cer93.positioncer#\', array( \'99rejete\', \'99valide\' ) ) && ( \'#Cer93.rejetcpdv#\' != true ) && ( \'%permission%\' == \'1\' ) )'
			);

			$this->set( 'erreursCandidatePassage', $erreursCandidatePassage );
			$this->set( 'signalementseps93', $signalementseps93 );
			$this->set( 'contratscomplexeseps93', $contratscomplexeseps93 );
			$this->set( 'options', $options);
			$this->set( 'optionsdossierseps', $this->Cer93->Contratinsertion->Signalementep93->Dossierep->Passagecommissionep->enums() );
			$this->set( 'cers93', $results );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'disabledLinks', $disabledLinks );
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

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

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

				if( $this->Cer93->saveFormulaire( $this->request->data, $this->Session->read( 'Auth.User.type' ) ) ) {
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

			if( empty( $this->request->data ) ) {
				try {
					$this->request->data = $this->Cer93->prepareFormDataAddEdit( $personne_id, ( ( $this->action == 'add' ) ? null : $id ), $this->Session->read( 'Auth.User.id' ) );
				}
				catch( Exception $e ) {
					$this->Session->setFlash( $e->getMessage(), 'flash/error' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
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

			// =================================================================

			$listeSujets = $this->Cer93->Sujetcer93->find(
				'all',
				array(
					'contain' => array(
						'Soussujetcer93' => array(
							'order' => array( 'Soussujetcer93.sujetcer93_id ASC', 'Soussujetcer93.name ASC' ),
							'Valeurparsoussujetcer93' => array(
								'order' => array( 'Valeurparsoussujetcer93.isautre DESC', 'Valeurparsoussujetcer93.name ASC' )
							)
						)
					),
					'order' => array( 'Sujetcer93.isautre DESC', 'Sujetcer93.name ASC' )
				)
			);

			$sujets_ids_valeurs_autres = array();
			$sujets_ids_soussujets_autres = array();
			foreach( $listeSujets as $sujetcer93 ) {
				if( !empty( $sujetcer93['Soussujetcer93'] ) ) {
					foreach( $sujetcer93['Soussujetcer93'] as $soussujetcer93 ) {
						if( $soussujetcer93['isautre'] == '1' ) {
							$sujets_ids_soussujets_autres[] = $sujetcer93['Sujetcer93']['id'];
						}
						if( !empty( $soussujetcer93['Valeurparsoussujetcer93'] ) ) {
							foreach( $soussujetcer93['Valeurparsoussujetcer93'] as $valeurparsousujetcer93 ) {
								if( $valeurparsousujetcer93['isautre'] == '1' ) {
									$sujets_ids_valeurs_autres[] = $sujetcer93['Sujetcer93']['id'];
								}
							}
						}
					}
				}
			}
			$sujets_ids_valeurs_autres = array_unique( $sujets_ids_valeurs_autres );
			$sujets_ids_soussujets_autres = array_unique( $sujets_ids_soussujets_autres );
			$this->set( compact( 'sujets_ids_valeurs_autres', 'sujets_ids_soussujets_autres' ) );

			// -----------------------------------------------------------------

			$sujetscers93 = $this->Cer93->Sujetcer93->find(
				'all',
				array(
					'order' => array( 'Sujetcer93.isautre DESC', 'Sujetcer93.name ASC' )
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
			$this->set( 'soussujetscers93', $soussujetscers93 );


			$tmpValeursparsoussujetscers93 = $this->Cer93->Sujetcer93->Soussujetcer93->Valeurparsoussujetcer93->find(
				'all',
				array(
					'fields' => array(
						'( "Soussujetcer93"."id" || \'_\'|| "Valeurparsoussujetcer93"."id" ) AS "Valeurparsoussujetcer93__id"',
						'Valeurparsoussujetcer93.name',
						'Soussujetcer93.sujetcer93_id',
					),
					'joins' => array(
						$this->Cer93->Sujetcer93->Soussujetcer93->Valeurparsoussujetcer93->join( 'Soussujetcer93', array( 'type' => 'INNER' ) )
					),
					'order' => array( 'Valeurparsoussujetcer93.soussujetcer93_id ASC', 'Valeurparsoussujetcer93.name ASC' )
				)
			);

			$valeursparsoussujetscers93 = array();
			foreach( $tmpValeursparsoussujetscers93 as $tmp ) {
				if( !isset( $valeursparsoussujetscers93[$tmp['Soussujetcer93']['sujetcer93_id']] ) ) {
					$valeursparsoussujetscers93[$tmp['Soussujetcer93']['sujetcer93_id']] = array();
				}

				$valeursparsoussujetscers93[$tmp['Soussujetcer93']['sujetcer93_id']][$tmp['Valeurparsoussujetcer93']['id']] = $tmp['Valeurparsoussujetcer93']['name'];
			}

			// Liste des valeurs possédant un champ texte dans Valeursparsoussujetscers93
			$valeursAutre = $this->Cer93->Sujetcer93->Soussujetcer93->Valeurparsoussujetcer93->find(
				'all',
				array(
					'order' => array( 'Valeurparsoussujetcer93.isautre DESC', 'Valeurparsoussujetcer93.name ASC' )
				)
			);
			$autrevaleur_isautre_id = Hash::extract( $valeursAutre, '{n}.Valeurparsoussujetcer93[isautre=1]' );
			$autrevaleur_isautre_id = Hash::format( $autrevaleur_isautre_id, array( '{n}.soussujetcer93_id', '{n}.id' ), '%d_%d' );

			$this->set( 'autrevaleur_isautre_id', $autrevaleur_isautre_id );
			$this->set( 'valeursparsoussujetscers93', $valeursparsoussujetscers93 );

			// Liste des valeurs possédant un champ texte dans Soussujetscers93
			$valeursSoussujet = $this->Cer93->Sujetcer93->Soussujetcer93->find(
				'all',
				array(
					'order' => array( 'Soussujetcer93.isautre DESC', 'Soussujetcer93.name ASC' )
				)
			);
			$autresoussujet_isautre_id = Hash::extract( $valeursSoussujet, '{n}.Soussujetcer93[isautre=1].id' );
// 			$autresoussujet_isautre_id = Hash::format( $autresoussujet_isautre_id, array( '{n}.sujetcer93_id', '{n}.id' ), '%%d' );
			$this->set( 'autresoussujet_isautre_id', $autresoussujet_isautre_id );

			// =================================================================

			// Options
			$options = array(
				'Contratinsertion' => array(
					'structurereferente_id' => $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ),
					'referent_id' => $this->Cer93->Contratinsertion->Referent->listOptions()
				),
				'Prestation' => array(
					'rolepers' => ClassRegistry::init( 'Option' )->rolepers()
				),
				'Personne' => array(
					'qual' => ClassRegistry::init( 'Option' )->qual()
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
				'Valeurparsoussujetcer93' => array(
					'soussujetcer93_id' => Set::combine( $valeursAutre, '{n}.Valeurparsoussujetcer93.id', '{n}.Valeurparsoussujetcer93.name' )
				),
				'dureehebdo' => array_range( '0', '39' ),
				'dureecdd' => ClassRegistry::init( 'Option' )->duree_cdd()
			);

			$options = Set::merge(
				$this->Cer93->Contratinsertion->Personne->Dsp->enums(),
				$this->Cer93->enums(),
				$this->Cer93->Expprocer93->enums(),
				$options,
				$this->Catalogueromev3->dependantSelects()
			);

			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'options' ) );
			$this->set( 'urlmenu', '/cers93/index/'.$personne_id );
			$this->render( 'edit' );
		}

		/**
		 * Permet la modification d'un CER après sa signature.
		 *
		 * @param integer $id L'id dans la table contratsinsertion
		 * @throws NotFoundException
		 */
		public function edit_apres_signature( $id ) {
			$query = array(
				'fields' => array(
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Cer93.id',
					'Cer93.duree',
					'Cer93.positioncer'
				),
				'joins' => array(
					$this->Cer93->Contratinsertion->join( 'Cer93', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
				'conditions' => array(
					'Contratinsertion.id' => $id
				)
			);
			$contratinsertion = $this->Cer93->Contratinsertion->find( 'first', $query );

			if( empty( $contratinsertion ) ) {
				throw new NotFoundException();
			}

			// Vérification des droits de l'utilisateur
			$personne_id = Hash::get( $contratinsertion, 'Contratinsertion.personne_id' );
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$dossier_id = Hash::get( $dossierMenu, 'Dossier.id' );

			// On vérifie que l'action puisse être exécutée
			$user_type = $this->Session->read( 'Auth.User.type' );
			$positioncer = Hash::get( $contratinsertion, 'Cer93.positioncer' );

			$block = !(
				( 'externe_cpdv' === $user_type && in_array( $positioncer, array( '01signe', '02attdecisioncpdv' ) ) )
				|| ( 'cg' === $user_type && !in_array( $positioncer, array( '00enregistre' ) ) )
			);

			if( $block === true ) {
				$msgid = 'Impossible de modifier le CER %d après signature (position: %s) pour l\'utilisateur %s (type: %s)';
				$msgstr = sprintf( $msgid, $id, $positioncer, $this->Session->read( 'Auth.User.username' ), $user_type );
				throw new RuntimeException( $msgstr );
			}

			// Début du traitement
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				return $this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cer93->Contratinsertion->begin();

				$success = $this->Cer93->Contratinsertion->saveAll( $this->request->data, array( 'atomic' => false, 'validate' => 'only' ) )
					&& $this->Cer93->Contratinsertion->updateAllUnbound(
						array(
							'Contratinsertion.dd_ci' => "'".date_cakephp_to_sql( Hash::get( $this->request->data, 'Contratinsertion.dd_ci' ) )."'",
							'Contratinsertion.df_ci' => "'".date_cakephp_to_sql( Hash::get( $this->request->data, 'Contratinsertion.df_ci' ) )."'"
						),
						array( 'Contratinsertion.id' => Hash::get( $contratinsertion, 'Contratinsertion.id' ) )
					)
					&& $this->Cer93->updateAllUnbound(
						array( 'Cer93.duree' => Hash::get( $this->request->data, 'Cer93.duree' ) ),
						array( 'Cer93.id' => Hash::get( $contratinsertion, 'Cer93.id' ) )
					)
					&&  $this->Cer93->Contratinsertion->updateRangsContratsPersonne( $personne_id );

				if( $success ) {
					$this->Cer93->Contratinsertion->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					return $this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cer93->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $contratinsertion;
			}

			$contratinsertion = $this->Cer93->dataView( $id );
			$options = $this->Cer93->optionsView();
			$urlmenu = "/cers93/index/{$personne_id}";

			$this->set( compact( 'dossierMenu', 'options', 'urlmenu', 'contratinsertion' ) );
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

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

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
				$this->request->data = $this->Cer93->prepareFormDataAddEdit( $personne_id, $id, $this->Session->read( 'Auth.User.id' ) );
			}

			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/cers93/index/'.$personne_id );
		}

		/**
		 * Imprime un CER 93.
		 * INFO: http://localhost/webrsa/trunk/cers93/impression/44327
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function impression( $contratinsertion_id = null ) {
			$personne_id = $this->Cer93->Contratinsertion->personneId( $contratinsertion_id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$pdf = $this->Cer93->getDefaultPdf( $contratinsertion_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$contratinsertion_id}_nouveau.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de contrat d\'engagement réciproque.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Visualisation du CER 93.
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function view( $contratinsertion_id ) {
			$this->Cer93->Contratinsertion->id = $contratinsertion_id;
			$personne_id = $this->Cer93->Contratinsertion->field( 'personne_id' );
			$this->set( 'personne_id', $personne_id );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->set( 'options', $this->Cer93->optionsView() );
			$this->set( 'contratinsertion', $this->Cer93->dataView( $contratinsertion_id ) );
			$this->set( 'urlmenu', '/cers93/index/'.$personne_id );
		}

		/**
		 * Suppression d'un CER 93.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$dossier_id = $this->Cer93->Contratinsertion->dossierId( $id );
			$this->DossiersMenus->checkDossierMenu( array( 'id' => $dossier_id ) );

			$this->Jetons2->get( $dossier_id );

			$this->Cer93->Contratinsertion->begin();
			$success = $this->Cer93->Contratinsertion->Actioninsertion->deleteAll( array( 'Actioninsertion.contratinsertion_id' => $id ) );
			$success = $this->Cer93->Contratinsertion->delete( $id ) && $success;
			$this->_setFlashResult( 'Delete', $success );

			if( $success ) {
				$this->Cer93->Contratinsertion->commit();
				$this->Jetons2->release( $dossier_id );
			}
			else {
				$this->Cer93->Contratinsertion->rollback();
			}

			$this->redirect( $this->referer() );
		}

		/**
		 * Imprime une décision sur le CER 93.
		 * INFO: http://localhost/webrsa/trunk/cers93/printdecision/44327
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function impressionDecision( $contratinsertion_id = null ) {
			$personne_id = $this->Cer93->Contratinsertion->personneId( $contratinsertion_id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$pdf = $this->Cer93->getDecisionPdf( $contratinsertion_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$contratinsertion_id}.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Permet d'annuler un CER.
		 *
		 * @param integer $id L'id dans la table contratsinsertion
		 * @throws NotFoundException
		 */
		public function cancel( $id ) {
			$query = array(
				'fields' => array(
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
					'Contratinsertion.motifannulation',
					'Cer93.id',
					'Cer93.date_annulation',
					'Cer93.annulateur_id'
				),
				'joins' => array(
					$this->Cer93->Contratinsertion->join( 'Cer93', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
				'conditions' => array(
					'Contratinsertion.id' => $id
				)
			);
			$contratinsertion = $this->Cer93->Contratinsertion->find( 'first', $query );

			if( empty( $contratinsertion ) ) {
				throw new NotFoundException();
			}

			$personne_id = Hash::get( $contratinsertion, 'Contratinsertion.personne_id' );

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$dossier_id = Hash::get( $dossierMenu, 'Dossier.id' );
			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				return $this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cer93->Contratinsertion->begin();

				// Modification des règles de validation
				$this->Cer93->validate['date_annulation'] = array(
					'notEmpty' => array(
						'rule' => array( 'notEmpty' )
					),
					'date' => array(
						'rule' => array( 'date' )
					)
				);
				$this->Cer93->Contratinsertion->validate['motifannulation']['notEmpty'] = array(
					'rule' => array( 'notEmpty' )
				);

				$success = $this->Cer93->Contratinsertion->saveAll( $this->request->data, array( 'atomic' => false, 'validate' => 'only' ) )
					&& $this->Cer93->Contratinsertion->updateAllUnbound(
						array(
							'Contratinsertion.rg_ci' => null,
							'Contratinsertion.decision_ci' => "'A'",
							'Contratinsertion.datevalidation_ci' => null,
							'Contratinsertion.motifannulation' => "'".Hash::get( $this->request->data, 'Contratinsertion.motifannulation' )."'"
						),
						array( 'Contratinsertion.id' => Hash::get( $contratinsertion, 'Contratinsertion.id' ) )
					)
					&& $this->Cer93->updateAllUnbound(
						array(
							'Cer93.positioncer' => "'99annule'",
							'Cer93.date_annulation' => "'".date_cakephp_to_sql( Hash::get( $this->request->data, 'Cer93.date_annulation' ) )."'",
							'Cer93.annulateur_id' => $this->Session->read( 'Auth.User.id' )
						),
						array( 'Cer93.id' => Hash::get( $contratinsertion, 'Cer93.id' ) )
					)
					&& $this->Cer93->Contratinsertion->updateRangsContratsPersonne( $personne_id );

				if( $success ) {
					$this->Cer93->Contratinsertion->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					return $this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cer93->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				// Préparation des données pour le formulaire
				if( Hash::get( $contratinsertion, 'Cer93.date_annulation' ) === null ) {
					$contratinsertion['Cer93']['date_annulation'] = date( 'Y-m-d' );
				}
				// Affectation des données au formulaire
				$this->request->data = $contratinsertion;
			}

			$contratinsertion = $this->Cer93->dataView( $id );
			$options = $this->Cer93->optionsView();
			$urlmenu = "/cers93/index/{$personne_id}";

			$this->set( compact( 'dossierMenu', 'options', 'urlmenu', 'contratinsertion' ) );
		}
	}
?>