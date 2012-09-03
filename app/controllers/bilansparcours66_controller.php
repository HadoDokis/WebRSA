<?php
	/**
	* Gestion des bilans de parcours pour le conseil général du département 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Bilansparcours66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Ajax', 'Fileuploader' );

		public $uses = array( 'Bilanparcours66', 'Option', 'Pdf', 'Dossierep'  );
		public $components = array( 'Gedooo.Gedooo', 'Fileuploader' );

		public $commeDroit = array(
			'add' => 'Bilansparcours66:edit'
		);

		public $aucunDroit = array( 'choixformulaire', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );


		/**
		*
		*/

		protected function _setOptions() {
			$options = array();

			$options = $this->Bilanparcours66->enums();
			$typevoie = $this->Option->typevoie();
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );

			$options = Set::insert( $options, 'typevoie', $typevoie );

			$options[$this->modelClass]['structurereferente_id'] = $this->{$this->modelClass}->Structurereferente->listOptions();
			$options[$this->modelClass]['referent_id'] = $this->{$this->modelClass}->Referent->listOptions();
			$options[$this->modelClass]['nvsansep_referent_id'] = $this->{$this->modelClass}->Referent->find( 'list' );
			$options[$this->modelClass]['nvparcours_referent_id'] = $this->{$this->modelClass}->Referent->find( 'list' );

			$this->set( compact( 'options' ) );

			$this->set( 'rsaSocle', $this->Option->natpf() );

			$options['Bilanparcours66']['duree_engag'] = $this->Option->duree_engag_cg66();

			$typesorients = $this->Bilanparcours66->Typeorient->find('list');
			$this->set(compact('typesorients'));
			$structuresreferentes = $this->Bilanparcours66->Structurereferente->find('list');
			$this->set(compact('structuresreferentes'));
			$autresstructuresreferentes = $this->{$this->modelClass}->Structurereferente->listOptions();
			$this->set(compact('autresstructuresreferentes'));

			$options = Set::merge( $options, $this->Dossierep->Passagecommissionep->Decisionsaisinebilanparcoursep66->enums() );
			$options = Set::merge( $options, $this->Dossierep->Passagecommissionep->Decisiondefautinsertionep66->enums() );
			
			$options = Set::merge( $options, $this->Bilanparcours66->Dossierpcg66->Decisiondossierpcg66->enums() );

			$typeorientprincipale = Configure::read( 'Orientstruct.typeorientprincipale' );
			$options['Bilanparcours66']['typeorientprincipale_id'] = $this->Bilanparcours66->Typeorient->listRadiosOptionsPrincipales( $typeorientprincipale['SOCIAL'] );

			$options['Bilanparcours66']['nvtypeorient_id'] = $this->Bilanparcours66->Typeorient->listOptionsUnderParent();
			$options['Bilanparcours66']['nvstructurereferente_id'] = $this->Bilanparcours66->Structurereferente->list1Options( array( 'orientation' => 'O' ) );
			$options['Saisinebilanparcoursep66']['typeorient_id'] = $options['Bilanparcours66']['nvtypeorient_id'];
			$options['Saisinebilanparcoursep66']['structurereferente_id'] = $options['Bilanparcours66']['nvstructurereferente_id'];

			$this->set( compact( 'options' ) );
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
			$bilanparcours66 = $this->Bilanparcours66->find(
				'first',
				array(
					'conditions' => array(
						'Bilanparcours66.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						),
						'Personne',
						'Orientstruct' => array(
							'fields' => array(
								'personne_id'
							)
						)
					)
				)
			);

			$personne_id = Set::classicExtract( $bilanparcours66, 'Bilanparcours66.personne_id' );

			$dossier_id = $this->Bilanparcours66->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Bilanparcours66->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Bilanparcours66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$saved = $this->Bilanparcours66->updateAll(
					array( 'Bilanparcours66.haspiecejointe' => '\''.$this->data['Bilanparcours66']['haspiecejointe'].'\'' ),
					array(
						'"Bilanparcours66"."orientstruct_id"' => Set::classicExtract( $bilanparcours66, 'Bilanparcours66.orientstruct_id' ),
						'"Bilanparcours66"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Bilanparcours66.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Bilanparcours66->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'bilansparcours66','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Bilanparcours66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/bilanspourcours66/index/'.$personne_id );
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'bilanparcours66' ) );
		}

		/**
		*
		*/

		public function index( $personne_id = null ) {
// 			$conditions = array( 'Orientstruct.date_valid IS NOT NULL', 'Orientstruct.structurereferente_id IS NOT NULL' );
// 			if( !empty( $personne_id ) ) {
// 				$conditions['Orientstruct.personne_id'] =  $personne_id;
// 			}
/*
			$nborientstruct = $this->Bilanparcours66->Orientstruct->find(
				'count',
				array(
					'conditions' => $conditions
				)
			);*/

			$this->paginate = array(
				'contain' => array(
					'Personne' => array(
						'fields' => array( 'qual', 'nom', 'prenom' ),
						'Foyer' => array(
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01',
									'Adressefoyer.typeadr' => 'D'
								),
								'Adresse'
							)
						)
					),
					'Referent' => array(
						'Structurereferente'
					),
					'Orientstruct' => array(
						'Typeorient',
						'Structurereferente'
					),
					'Contratinsertion' => array(
						'Structurereferente' => array(
							'Typeorient',
						),
					),
					'Saisinebilanparcoursep66' => array(
						'Dossierep' => array(
							'Passagecommissionep' => array(
								'fields' => array(
									'etatdossierep'
								),
								'Decisionsaisinebilanparcoursep66'
							)
						)
					),
					'Defautinsertionep66' => array(
						'Dossierep' => array(
							'Passagecommissionep' => array(
								'fields' => array(
									'etatdossierep'
								),
								'Decisiondefautinsertionep66'
							)
						)
					),
					'Fichiermodule'
				),
				'conditions' => array(
					'Bilanparcours66.personne_id' => $personne_id
				),
				'limit' => 10,
				'order' => array( 'Bilanparcours66.created DESC', 'Bilanparcours66.id DESC' )
			);

			$this->set( 'options', $this->Bilanparcours66->Saisinebilanparcoursep66->Dossierep->enums() );
			$bilansparcours66 = $this->paginate( $this->Bilanparcours66 );

			// INFO: containable ne permet pas de passer dans les virtualFields maison
			foreach( $bilansparcours66 as $key => $bilanparcours66 ) {
				$bilansparcours66[$key]['Referent']['nom_complet'] = implode(
					' ',
					array(
						$bilansparcours66[$key]['Referent']['qual'],
						$bilansparcours66[$key]['Referent']['nom'],
						$bilansparcours66[$key]['Referent']['prenom']
					)
				);

				$bilansparcours66[$key]['Personne']['nom_complet'] = implode(
					' ',
					array(
						@$bilansparcours66[$key]['Orientstruct']['Personne']['qual'],
						@$bilansparcours66[$key]['Orientstruct']['Personne']['nom'],
						@$bilansparcours66[$key]['Orientstruct']['Personne']['prenom']
					)
				);

			}
			$this->_setOptions();
			$this->set( compact( 'bilansparcours66', 'nborientstruct', 'struct' )  );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		* TODO: que modifie-t'on ? Dans quel cas peut-on supprimer ?
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		* Ajout ou modification du bilan de parcours d'un allocataire.
		*
		* Le bilan de parcours entraîne:
		*	- pour le thème réorientation/saisinesbilansparcourseps66
		*		* soit un maintien de l'orientation, avec reconduction du CER, sans passage en EP
		*		* soit une saisine de l'EP locale, commission parcours
		*
		* FIXME: modification du bilan
		*
		* @param integer $id Pour un ajout, l'id technique de la personne; pour une
		*	modification, l'id technique du bilan de parcours.
		* @return void
		* @precondition L'allocataire existe et possède une orientation
		* @access protected
		*/

		protected function _add_edit( $id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
					if( $this->action == 'edit' ) {
						$bilanparcours66 = $this->Bilanparcours66->find(
							'first',
							array(
								'contain' => false,
								'conditions' => array( 'Bilanparcours66.id' => $id )
							)
						);
 						$personne_id = Set::classicExtract( $bilanparcours66, 'Bilanparcours66.personne_id' );
 						$id = $personne_id;
					}
					$this->redirect( array( 'action' => 'index', $id ) );
			}

			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			// TODO
			else if( $this->action == 'edit' ) {
				$bilanparcours66 = $this->Bilanparcours66->find(
					'first',
					array(
						'contain' => array(
							'Personne',
							'Saisinebilanparcoursep66' => array(
								'Dossierep' => array(
									'Passagecommissionep' => array(
										'conditions' => array(
											'Passagecommissionep.etatdossierep' => 'traite'
										),
										'Commissionep',
										'Decisionsaisinebilanparcoursep66' => array(
											'order' => 'Decisionsaisinebilanparcoursep66.etape ASC'
										)
									)
								)
							),
							'Defautinsertionep66' => array(
								'Dossierep' => array(
									'Passagecommissionep' => array(
										'conditions' => array(
											'Passagecommissionep.etatdossierep' => 'traite'
										),
										'Commissionep',
										'Decisiondefautinsertionep66' => array(
											'order' => 'Decisiondefautinsertionep66.etape ASC'
										)
									)
								)
							),
							'Contratinsertion',
							'Orientstruct' => array(
								'Typeorient',
								'Structurereferente'
							)
						),
						'conditions' => array( 'Bilanparcours66.id' => $id )
					)
				);
				$this->assert( !empty( $bilanparcours66 ), 'error404' );

				$personne_id = $bilanparcours66['Bilanparcours66']['personne_id'];

				if ( $bilanparcours66['Bilanparcours66']['proposition'] == 'parcours' ) {
					$bilanparcours66['Saisinebilanparcoursep66']['structurereferente_id'] = implode( '_', array( $bilanparcours66['Saisinebilanparcoursep66']['typeorient_id'], $bilanparcours66['Saisinebilanparcoursep66']['structurereferente_id']) );
					$passagecommissionep = $this->Dossierep->Passagecommissionep->find(
						'first',
						array(
							'conditions' => array(
								'Passagecommissionep.dossierep_id IN ( '.$this->Dossierep->sq(
									array(
										'fields' => array(
											'dossierseps.id'
										),
										'alias' => 'dossierseps',
										'conditions' => array(
											'dossierseps.themeep' => 'saisinesbilansparcourseps66'
										),
										'joins' => array(
											array(
												'table' => 'saisinesbilansparcourseps66',
												'alias' => 'saisinesbilansparcourseps66',
												'type' => 'INNER',
												'conditions' => array(
													'saisinesbilansparcourseps66.dossierep_id = dossierseps.id',
													'saisinesbilansparcourseps66.bilanparcours66_id' => $id
												)
											)
										)
									)
								).' )'
							),
							'contain' => array(
								'Commissionep',
								'Decisionsaisinebilanparcoursep66' => array(
									'order' => array( 'Decisionsaisinebilanparcoursep66.etape ASC' )
								)
							)
						)
					);
					$this->set( compact( 'passagecommissionep' ) );
				}
				elseif ( $bilanparcours66['Bilanparcours66']['proposition'] == 'audition' ) {
					$passagecommissionep = $this->Dossierep->Passagecommissionep->find(
						'first',
						array(
							'conditions' => array(
								'Passagecommissionep.dossierep_id IN ( '.$this->Dossierep->sq(
									array(
										'fields' => array(
											'dossierseps.id'
										),
										'alias' => 'dossierseps',
										'conditions' => array(
											'dossierseps.themeep' => 'defautsinsertionseps66'
										),
										'joins' => array(
											array(
												'table' => 'defautsinsertionseps66',
												'alias' => 'defautsinsertionseps66',
												'type' => 'INNER',
												'conditions' => array(
													'defautsinsertionseps66.dossierep_id = dossierseps.id',
													'defautsinsertionseps66.bilanparcours66_id' => $id
												)
											)
										)
									)
								).' )'
							),
							'contain' => array(
								'Commissionep',
								'Decisiondefautinsertionep66' => array(
									'order' => array( 'Decisiondefautinsertionep66.etape ASC' )
								)
							)
						)
					);
					
					$dossierpcg66 = $this->Bilanparcours66->Dossierpcg66->find(
						'first',
						array(
							'conditions' => array(
								'Dossierpcg66.bilanparcours66_id' => $id
							),
							'contain' => array(
								'Decisiondossierpcg66' => array(
									'Decisionpcg66',
									'order' => array( 'Decisiondossierpcg66.datevalidation DESC' )
								)
							)
						)
					);

					$this->set( compact( 'passagecommissionep', 'dossierpcg66' ) );
				}
			}

			// INFO: pour passer de 74 à 29 modèles utilisés lors du find count
			$this->Bilanparcours66->Personne->unbindModelAll();

			// On s'assure que la personne existe bien
			$nPersonnes = $this->Bilanparcours66->Personne->find(
				'count',
				array(
					'contain' => false,
					'conditions' => array( 'Personne.id' => $personne_id )
				)
			);
			$this->assert( ( $nPersonnes == 1 ), 'error404' );

			$contrat = $this->Bilanparcours66->Contratinsertion->find(
				'first',
				array(
					'contain' => false,
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'recursive' => -1,
					'order' => 'Contratinsertion.date_saisi_ci DESC'
				)
			);

			// Si le formulaire a été renvoyé
			if( !empty( $this->data ) ) {
				$this->Bilanparcours66->begin();
// debug( $this->data );


				if ( ( !isset( $passagecommissionep ) || empty( $passagecommissionep ) ) && $this->action == 'edit' ) {
					$dossierep = $this->Dossierep->Saisinebilanparcoursep66->find(
						'first',
						array(
							'conditions' => array(
								'Saisinebilanparcoursep66.bilanparcours66_id' => $id
							)
						)
					);

					if ( !empty( $dossierep ) ) {
						$this->Dossierep->Saisinebilanparcoursep66->deleteAll( array( 'Saisinebilanparcoursep66.bilanparcours66_id' => $id ) );
						$this->Dossierep->delete( $dossierep['Saisinebilanparcoursep66']['dossierep_id'] );
					}
					else {
						$dossierep = $this->Dossierep->Defautinsertionep66->find(
							'first',
							array(
								'conditions' => array(
									'Defautinsertionep66.bilanparcours66_id' => $id
								)
							)
						);
						$this->Dossierep->Defautinsertionep66->deleteAll( array( 'Defautinsertionep66.bilanparcours66_id' => $id ) );
						$this->Dossierep->delete( $dossierep['Defautinsertionep66']['dossierep_id'] );
					}
					$success = $this->Bilanparcours66->sauvegardeBilan( $this->data );
// debug($success);
				}
				elseif ( $this->action == 'edit' ) {
					$success = $this->Bilanparcours66->save( $this->data );
				}
				elseif ( $this->action == 'add' ) {
					$success = $this->Bilanparcours66->sauvegardeBilan( $this->data );
				}

				if( !empty( $contrat['Contratinsertion'] ) ) {
					//Modification de la position du CER lorsque le bilan est créé et que le CER existe
					$success = $this->{$this->modelClass}->Contratinsertion->updateAll(
						array( 'Contratinsertion.positioncer' => '\'attrenouv\'' ),
						array(
							'"Contratinsertion"."personne_id"' => $contrat['Contratinsertion']['personne_id'],
							'"Contratinsertion"."id"' => $contrat['Contratinsertion']['id']
						)
					) && $success;
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Bilanparcours66->commit();

					if ( isset( $this->data['Bilanparcours66']['proposition'] ) && $this->data['Bilanparcours66']['proposition'] == 'traitement' && isset( $this->data['Bilanparcours66']['maintienorientation'] ) && $this->data['Bilanparcours66']['maintienorientation'] == 1 ) {
						$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->redirect( array( 'controller' => 'bilansparcours66', 'action' => 'index', $personne_id ) );
					}

				}
				else {
					$this->Bilanparcours66->rollback();
				}
			}
			// Premier accès à la page
			else {
				if( $this->action == 'edit' ) {
					$this->data = $bilanparcours66;

					$referent = $this->{$this->modelClass}->Referent->find(
						'first',
						array(
							'conditions'=>array(
								'Referent.id' => $bilanparcours66['Bilanparcours66']['referent_id']
							),
							'contain'=>false
						)
					);
					$this->data['Bilanparcours66']['referent_id'] = $bilanparcours66['Bilanparcours66']['structurereferente_id'].'_'.$bilanparcours66['Bilanparcours66']['referent_id'];
				}
				else {
					$orientstruct = $this->Bilanparcours66->Orientstruct->find(
						'first',
						array(
							'fields' => array(
								'Orientstruct.id',
								'Orientstruct.personne_id',
								'Orientstruct.structurereferente_id',//ajout arnaud
							),
							'conditions' => array(
								'Orientstruct.personne_id' => $personne_id,
								'Orientstruct.date_valid IS NOT NULL'
							),
							'contain' => array(
								'Structurereferente',
								'Referent'
							),
							'order' => array( 'Orientstruct.date_valid DESC' )
						)
					);

// 					$this->assert( !empty( $orientstruct ), 'error500' );

					if( !empty(  $orientstruct ) ){
						$this->data['Bilanparcours66']['orientstruct_id'] = $orientstruct['Orientstruct']['id'];
						//ajout arnaud
						$this->data['Bilanparcours66']['structurereferente_id'] = $orientstruct['Orientstruct']['structurereferente_id'];
						$this->data['Bilanparcours66']['referent_id'] = $orientstruct['Structurereferente']['id'].'_'.$orientstruct['Referent']['id'];
					}
				}

				$this->data = Set::insert($this->data, 'Pe', $this->data);
			}

			if (!isset($this->data['Bilanparcours66']['sitfam']) || empty($this->data['Bilanparcours66']['sitfam'])) {
				$sitfam = $this->Bilanparcours66->Personne->Foyer->find(
					'first',
					array(
						'fields' => array(
							'Foyer.id',
							'Foyer.sitfam'
						),
						'joins' => array(
							array(
								'table' => 'personnes',
								'alias' => 'Personne',
								'type' => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Personne.foyer_id = Foyer.id',
									'Personne.id' => $personne_id
								)
							)
						),
						'contain'=>false
					)
				);
				$nbenfant = $this->Bilanparcours66->Personne->Foyer->nbEnfants($sitfam['Foyer']['id']);
				///FIXME: voir si isolement correspond à l'isolement prévu dans la table foyer
				//if ($sitfam['Foyer']['sitfam'] == 'ISO') {
				if (in_array($sitfam['Foyer']['sitfam'], array('CEL', 'DIV', 'ISO', 'SEF', 'SEL', 'VEU'))) {
					if ($nbenfant==0) {
						$this->data['Bilanparcours66']['sitfam']='isole';
					}
					else {
						$this->data['Bilanparcours66']['sitfam']='isoleenfant';
					}
				}
				elseif (in_array($sitfam['Foyer']['sitfam'], array('MAR', 'PAC', 'RPA', 'RVC', 'VIM'))) {
					if ($nbenfant==0) {
						$this->data['Bilanparcours66']['sitfam']='couple';
					}
					else {
						$this->data['Bilanparcours66']['sitfam']='coupleenfant';
					}
				}
			}

			// INFO: si on utilise fields pour un modèle (le modèle principal ?), on n'a pas la relation belongsTo (genre Foyer belongsTo Dossier)
			// INFO: http://stackoverflow.com/questions/3865349/containable-fails-to-join-in-belongsto-relationships-when-fields-are-used-in-ca
			// http://cakephp.lighthouseapp.com/projects/42648/tickets/1174-containable-fails-to-join-in-belongsto-relationships-when-fields-are-used
			// Recherche des informations de la personne
			$personne = $this->Bilanparcours66->Personne->find(
				'first',
				array(
					'conditions' => array( 'Personne.id' => $personne_id ),
					'contain' => array(
						'Orientstruct' => array(
							'fields' => array( 'typeorient_id', 'structurereferente_id', 'date_valid' ),
							'Typeorient' => array(
								'fields' => array(
									'lib_type_orient'
								)
							),
							'order' => "Orientstruct.date_valid DESC",
							'conditions' => array( 'Orientstruct.statut_orient' => 'Orienté' ),
						),
						'Foyer' => array(
							'fields' => array(
								'id'
							),
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse' => array(
									'fields' => array(
										'numvoie',
										'typevoie',
										'nomvoie',
										'codepos',
										'locaadr'
									)
								)
							),
							'Dossier' => array(
								'fields' => array(
									'numdemrsa',
									'matricule',
								)
							),
							'Modecontact' => array(
								'fields' => array(
									'autorutitel',
									'numtel',
									'autorutiadrelec',
									'adrelec'
								)
							)
						),
						'Prestation' => array(
							'fields' => array(
								'rolepers'
							)
						)
					)
				)
			);

			//Précochage du bouton radio selon l'origine du bilan de parcours ou le type d'orientation de l'allocataire
			if( $this->action == 'add' ) {
				if( isset( $bilanparcours66 ) && in_array( $bilanparcours66['Bilanparcours66']['examenauditionpe'], array( 'noninscriptionpe', 'radiationpe' ) ) ) {
					$typeformulaire = 'cg';
				}
				else {
					$typeformulaire = 'cg';
					$orientation = $this->Bilanparcours66->Orientstruct->find(
						'first',
						array(
							'conditions' => array(
								'Orientstruct.personne_id' => $personne_id,
								'Orientstruct.statut_orient' => 'Orienté'
							),
							'contain' => array(
								'Typeorient'
							),
							'order' => array( 'Orientstruct.date_valid DESC' )
						)
					);
					$typeorient_id = Set::classicExtract( $orientation, 'Typeorient.id' );
					if( !empty( $typeorient_id ) ) {
						if( $this->Bilanparcours66->Orientstruct->Typeorient->isProOrientation($typeorient_id) && ( !isset( $this->params['named'] ) || empty( $this->params['named'] ) ) ){
							$typeformulaire = 'pe';
						}
					}
				}
			}
			else {
				$typeformulaire = $bilanparcours66['Bilanparcours66']['typeformulaire'];
			}

			/// Si le nombre de dossiers d'EP en cours est > 0, 
			/// alors on ne peut pas créer de bilan pour la thématique concernée par le dossier EP
			$dossiersepsencours = array(
				'defautsinsertionseps66' => !$this->Bilanparcours66->ajoutPossibleThematique66( 'defautsinsertionseps66', $personne_id ),
				'saisinesbilansparcourseps66' => !$this->Bilanparcours66->ajoutPossibleThematique66( 'saisinesbilansparcourseps66', $personne_id )
			);

			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );
			$this->set( 'typeformulaire', $typeformulaire );
// 			$this->set( 'typeformulaire', array( 'cg', 'pe'  ) );
			$this->set( compact( 'dossiersepsencours' ) );
			$this->set( 'urlmenu', '/bilanspourcours66/index/'.$personne_id );

			$this->set( compact( 'personne' ) );
			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*   Fonction pour annuler le Bilan de parcours pour le CG66
		*/

		public function cancel( $id ) {
			$bilan = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						'Bilanparcours66.id' => $id
					),
					'contain' => false
				)
			);
// 			$orientstruct_id = Set::classicExtract( $bilan, 'Bilanparcours66.orientstruct_id' );
// 			$orientstruct = $this->{$this->modelClass}->Orientstruct->find(
// 				'first',
// 				array(
// 					'conditions' => array(
// 						'Orientstruct.id' => $orientstruct_id
// 					),
// 					'contain' => false
// 				)
// 			);

			$personne = $this->Bilanparcours66->Personne->find(
				'first',
				array(
					'fields' => array( 'id' ),
					'conditions' => array(
						'Personne.id' => $bilan['Bilanparcours66']['personne_id']
					),
					'recursive' => -1
				)
			);

			// On cherche le dossier EP créé par le bilan de parcours lors de l'enregistrement
			// Dans un premier temps, on regarde si le dossier EP est lié à une demande de réorientation (saisinebilanparcoursep66)
			$dossierep = $this->Dossierep->Saisinebilanparcoursep66->find(
				'first',
				array(
					'conditions' => array(
						'Saisinebilanparcoursep66.bilanparcours66_id' => $id
					)
				)
			);
			// Si c'est le cas, on supprime le dossier d'EP dans la thématique en question
			if ( !empty( $dossierep ) ) {
				$this->Dossierep->Saisinebilanparcoursep66->deleteAll( array( 'Saisinebilanparcoursep66.bilanparcours66_id' => $id ) );
				$this->Dossierep->delete( $dossierep['Saisinebilanparcoursep66']['dossierep_id'] );
			}
			// Sinon on cherche dans l'autre thématique (defautinsertionep66) et on supprime le dossier d'EP
			else {
				$dossierep = $this->Dossierep->Defautinsertionep66->find(
					'first',
					array(
						'conditions' => array(
							'Defautinsertionep66.bilanparcours66_id' => $id
						)
					)
				);
				$this->Dossierep->Defautinsertionep66->deleteAll( array( 'Defautinsertionep66.bilanparcours66_id' => $id ) );
				$this->Dossierep->delete( $dossierep['Defautinsertionep66']['dossierep_id'] );
			}

			$this->{$this->modelClass}->updateAll(
				array( 'Bilanparcours66.positionbilan' => '\'annule\'' ),
				array(
					'"Bilanparcours66"."id"' => $id
				)
			);
			$this->redirect( array( 'action' => 'index', $personne['Personne']['id'] ) );
		}

		/**
		*
		*/

		public function bilanparcoursGedooo( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Bilanparcours66->getStoredPdf( $id );

			$this->assert( !empty( $pdf ), 'error404' );
			$this->assert( !empty( $pdf['Pdf']['document'] ), 'error500' ); // FIXME: ou en faire l'impression ?

			$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], "{$id}.pdf" );
		}

		/**
		*   Courrier d'information à l'allocataire avant passage en EP
		*/

		public function courrier_information( $id ) {
			$this->assert( !empty( $id ), 'error404' );
			$pdf = $this->Bilanparcours66->getPdfCourrierInformation( $id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'Courrier_Information.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier d\'information', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>