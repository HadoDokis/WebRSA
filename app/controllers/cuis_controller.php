<?php
	App::import( 'Helper', 'Locale' );

	class CuisController extends AppController
	{
		public $name = 'Cuis';
		public $uses = array( 'Cui', 'Option', 'Dossier', 'Serviceinstructeur', 'Adressefoyer', 'Detaildroitrsa', 'Infofinanciere', 'Detailcalculdroitrsa', 'Departement' );

		public $helpers = array( 'Default', 'Locale', 'Csv', 'Ajax', 'Xform' );
		public $components = array( 'RequestHandler', 'Gedooo' );
		public $aucunDroit = array( 'gedooo' );

		public $commeDroit = array(
			'add' => 'Cuis:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$typevoie = $this->Option->typevoie();
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );

			$dept = $this->Departement->find( 'list', array( 'fields' => array( 'numdep', 'name' ), 'contain' => false ) );
			$this->set( compact( 'dept' ) );

			$this->set( 'rsaSocle', $this->Option->natpf() );
		}

		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();

			$options = array();
			$options = $this->Cui->allEnumLists();
			$optionsperiode = $this->Cui->Periodeimmersion->allEnumLists();
			$options = Set::merge( $options, $optionsperiode );

			$typevoie = $this->Option->typevoie();
			$options = Set::insert( $options, 'typevoie', $typevoie );
			$this->set( compact( 'options' ) );

			return $return;
		}


		/**
		*
		*/

		public function index( $personne_id = null ) {
			$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			/**
			*   Précondition: La personne est-elle bien en Rsa Socle ?
			*/
			$alerteRsaSocle = $this->Cui->_prepare( $personne_id );
			$this->set( 'alerteRsaSocle', $alerteRsaSocle );

			$cuis = $this->Cui->find(
				'all',
				array(
					'conditions' => array(
						'Cui.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( compact( 'cuis' ) );
		}


		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Cui->field( 'personne_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			$valueAdressebis = null;
			$valueInscritPE = null;
			$valueIsBeneficiaire = null;
			if( $this->action == 'add' ) {
				$cui_id = null;
				$personne_id = $id;
				$nbrPersonnes = $this->Cui->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
				$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
				$valueAdressebis = 'N';
				$valueInscritPE = 'N';
				$valueIsBeneficiaire = null;

			}
			else if( $this->action == 'edit' ) {
				$cui_id = $id;
				$cui = $this->Cui->findById( $cui_id, null, null, 1 );
				$this->assert( !empty( $cui ), 'invalidParameter' );
				$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
				$valueAdressebis = Set::classicExtract( $cui, 'Cui.isadresse2' );
				$valueInscritPE = Set::classicExtract( $cui, 'Cui.isinscritpe' );
				$valueIsBeneficiaire = Set::classicExtract( $cui, 'Cui.isbeneficiaire' );
			}

			/// Peut-on prendre le jeton ?
			$this->Cui->begin();
			$dossier_id = $this->Cui->Personne->dossierId( $personne_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Cui->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'valueAdressebis', $valueAdressebis );
			$this->set( 'valueInscritPE', $valueInscritPE );
			$this->set( 'valueIsBeneficiaire', $valueIsBeneficiaire );
//             $this->set( 'cui_id', $cui_id );

			///On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			$personne = $this->{$this->modelClass}->Personne->detailsApre( $personne_id, $this->Session->read( 'Auth.User.id' ) );

			$this->set( 'personne', $personne );
			$this->set( 'referents', $this->Cui->Referent->find( 'list', array( 'recursive' => false ) ) );

			$this->set( 'structs', $this->Cui->Structurereferente->listOptions() );

			if( !empty( $this->data ) ){


				$this->{$this->modelClass}->create( $this->data );
				$success = $this->{$this->modelClass}->save();


				// Nettoyage des Periodes d'immersion
				$keys = array_keys( $this->Cui->Periodeimmersion->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );
				unset( $defaults['cui_id'] );
// debug($defaults);
				if( !empty( $this->data['Periodeimmersion'] ) ) {
					$this->data['Periodeimmersion'] = Set::merge( $defaults, $this->data['Periodeimmersion'] );
				}

				if( !empty( $this->data['Periodeimmersion'] ) ) {
					$Periodeimmersion = Set::filter( $this->data['Periodeimmersion'] );
//                     debug($Periodeimmersion);
					if( !empty( $Periodeimmersion ) ){
						$this->{$this->modelClass}->Periodeimmersion->create( $this->data );
						if( $this->action == 'add'  ) {
							$this->{$this->modelClass}->Periodeimmersion->set( 'cui_id', $this->{$this->modelClass}->getLastInsertID( ) );
						}
						else if( $this->action == 'edit' ) {
							$this->{$this->modelClass}->Periodeimmersion->set( 'cui_id', Set::classicExtract( $this->data, 'Cui.id' ) );
						}
						$success = $this->{$this->modelClass}->Periodeimmersion->save() && $success;
					}
				}


				if( $success  ) {
						$this->Jetons->release( $dossier_id );
						$this->{$this->modelClass}->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->{$this->modelClass}->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

			}
			else {
				if( $this-> action == 'edit' ){
					$this->data = $cui;
					if( !empty( $this->data['Periodeimmersion'] ) ) {
						$this->data['Periodeimmersion'] = $this->data['Periodeimmersion'][0];
					}

				}
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}



		/**
		*
		*/

		public function valider( $cui_id = null ) {

			$cui = $this->Cui->findById( $cui_id );
			$this->assert( !empty( $cui ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $cui['Cui']['personne_id'] ) );
			}

			$this->set( 'personne_id', $cui['Cui']['personne_id'] );

			if( !empty( $this->data ) ) {
				if( $this->Cui->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $cui['Cui']['personne_id']) );
				}
			}
			else {
				$this->data = $cui;
			}
			$this->_setOptions();
		}

		/**
		*
		*/

		public function gedooo( $id ) {
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();

			$cui = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						"{$this->modelClass}.id" => $id
					),
					'recursive' => 0
				)
			);

			$this->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => Set::classicExtract( $cui, 'Personne.foyer_id' ),
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$cui['Adresse'] = $adresse['Adresse'];

			$cui_id = Set::classicExtract( $cui, 'Actioncandidat.id' );

			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$LocaleHelper = new LocaleHelper();
			$cui['Personne']['qual'] = Set::enum( Set::classicExtract( $cui, 'Personne.qual' ), $qual );
			$cui['Personne']['dtnai'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $cui, 'Personne.dtnai' ) );
			$cui['Referent']['qual'] = Set::enum( Set::classicExtract( $cui, 'Referent.qual' ), $qual );

			$this->_setOptions();

			$pdf = $this->Cui->ged( $cui, 'CUI/cui.odt' );
			$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'cui-%s.pdf', date( 'Y-m-d' ) ) );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}



		/**
		*
		*/

		public function view( $id ) {
			$this->_setOptions();
			$this->Default->view( $id );
		}

	}
?>
