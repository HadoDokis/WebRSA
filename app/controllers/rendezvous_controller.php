<?php
App::import( 'Helper', 'Locale' );
	class RendezvousController extends AppController
	{

		public $name = 'Rendezvous';
		public $uses = array( 'Rendezvous', 'Option' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );
        public $components = array( 'Gedooo' );
		public $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm' );

		public $commeDroit = array(
			'view' => 'Rendezvous:index',
			'add' => 'Rendezvous:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'struct', $this->Rendezvous->Structurereferente->listOptions() );
			$this->set( 'permanences', $this->Rendezvous->Permanence->listOptions() );
			$this->set( 'statutrdv', $this->Rendezvous->Statutrdv->find( 'list' ) );
		}


		/**
		*   Ajax pour les coordonnées du référent APRE
		*/

		public function ajaxreffonct( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->data, 'Rendezvous.referent_id' ) );
			}

			$referent = array();
			if( !empty( $referent_id ) ) {
				$referent = $this->Rendezvous->Referent->findbyId( $referent_id, null, null, -1 );
			}

			$this->set( 'referent', $referent );
			$this->render( 'ajaxreffonct', 'ajax' );
		}

		/**
		*
		*/

		public function index( $personne_id = null ) {
			$this->Rendezvous->Personne->unbindModelAll();
			$nbrPersonnes = $this->Rendezvous->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => false
				)
			);
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$this->Rendezvous->forceVirtualFields = true;
			$rdvs = $this->Rendezvous->find(
				'all',
				array(
					'fields' => array(
						'Rendezvous.id',
						'Rendezvous.personne_id',
						'Personne.nom_complet',
						'Structurereferente.lib_struc',
						'Referent.nom_complet',
						'Permanence.libpermanence',
						'Typerdv.libelle',
						'Statutrdv.libelle',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Rendezvous.objetrdv',
						'Rendezvous.commentairerdv'
					),
					'contain' => array(
						'Personne',
						'Structurereferente',
						'Referent',
						'Statutrdv',
						'Permanence',
						'Typerdv'
					),
					'conditions' => array(
						'Rendezvous.personne_id' => $personne_id
					)
				)
			);
			$this->Rendezvous->forceVirtualFields = false;

			$this->set( compact( 'rdvs' ) );
			$this->set( 'personne_id', $personne_id );
		}


		/**
		*
		*/

		public function view( $rendezvous_id = null ) {
			$this->Rendezvous->forceVirtualFields = true;
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'fields' => array(
						'Rendezvous.personne_id',
						'Personne.nom_complet',
						'Structurereferente.lib_struc',
						'Referent.nom_complet',
						'Referent.fonction',
						'Permanence.libpermanence',
						'Typerdv.libelle',
						'Statutrdv.libelle',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Rendezvous.objetrdv',
						'Rendezvous.commentairerdv'
					),
					'conditions' => array(
						'Rendezvous.id' => $rendezvous_id
					),
					'recursive' => 0
				)
			);

			$this->assert( !empty( $rendezvous ), 'invalidParameter' );


			$this->set( 'rendezvous', $rendezvous );
			$this->set( 'personne_id', $rendezvous['Rendezvous']['personne_id'] );
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
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$rdv_id = $id;
				$rdv = $this->Rendezvous->findById( $rdv_id, null, null, -1 );
				$this->assert( !empty( $rdv ), 'invalidParameter' );

				$personne_id = $rdv['Rendezvous']['personne_id'];
				$dossier_id = $this->Rendezvous->dossierId( $rdv_id );
			}

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			$this->Rendezvous->begin();

			$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Rendezvous->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			$referents = $this->Rendezvous->Referent->listOptions();
			$this->set( 'referents', $referents );


			if( !empty( $this->data ) ){
				if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {

						$this->Jetons->release( $dossier_id );
						$this->Rendezvous->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $personne_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else{
				if( $this->action == 'edit' ) {
					$this->data = $rdv;

				}
			}
			$this->Rendezvous->commit();

			$struct_id = Set::classicExtract( $this->data, "{$this->modelClass}.structurereferente_id" );
			$this->set( 'struct_id', $struct_id );

			$referent_id = Set::classicExtract( $this->data, "{$this->modelClass}.referent_id" );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );


			$permanence_id = Set::classicExtract( $this->data, "{$this->modelClass}.permanence_id" );
			$permanence_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $permanence_id );
			$this->set( 'permanence_id', $permanence_id );

			$typerdv = $this->Rendezvous->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		* FIXME: delete n'est pas implémenté
		*/

		public function delete($id) {
            $this->Default->delete( $id );
		}

        function gedooo( $rdv_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            $rdv = $this->Rendezvous->find(
                'first',
                array(
                    'conditions' => array(
                        'Rendezvous.id' => $rdv_id
                    )
                )
            );


            ///Pour le choix entre les différentes notifications possibles
            $modele = $rdv['Typerdv']['modelenotifrdv'];

            $this->Rendezvous->Personne->Foyer->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );

            $adresse = $this->Rendezvous->Personne->Foyer->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $rdv['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $rdv['Adresse'] = $adresse['Adresse'];

            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $rdv['User'] = $user['User'];
            $rdv['Serviceinstructeur'] = $user['Serviceinstructeur'];

            $dossier = $this->Rendezvous->Personne->Foyer->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $rdv['Personne']['foyer_id']
                    )
                )
            );
            $rdv['Dossier_RSA'] = $dossier['Dossier'];

            ///Pour la qualité de la personne
            $rdv['Personne']['qual'] = Set::extract( $qual, Set::extract( $rdv, 'Personne.qual' ) );
            ///Pour l'adresse de la structure référente
            $rdv['Structurereferente']['type_voie'] = Set::extract( $typevoie, Set::classicExtract( $rdv, 'Structurereferente.type_voie' ) );
            ///Pour la date du rendez-vous
            $LocaleHelper = new LocaleHelper();
            $rdv['Rendezvous']['daterdv'] =  $LocaleHelper->date( '%d/%m/%Y', Set::classicExtract( $rdv, 'Rendezvous.daterdv' ) );
//             debug( $LocaleHelper->date( '%d-%m-%Y', Set::classicExtract( $rdv, 'Rendezvous.daterdv' ) ) );
            $rdv['Rendezvous']['heurerdv'] = $LocaleHelper->date( 'Time::short', Set::classicExtract( $rdv, 'Rendezvous.heurerdv' ) );
            ///Pour l'adresse de la personne
            $rdv['Adresse']['typevoie'] = Set::extract( $typevoie, Set::extract( $rdv, 'Adresse.typevoie' ) );

            ///Pour le référent lié au RDV
            $structurereferente_id = Set::classicExtract( $rdv, 'Structurereferente.id' );
            $referents = $this->Rendezvous->Personne->Referent->referentsListe( $structurereferente_id );
            $this->set( 'referents', $referents );
            if( !empty( $referents ) ) {
                $rdv['Rendezvous']['referent_id'] = Set::extract( $referents, Set::classicExtract( $rdv, 'Rendezvous.referent_id' ) );
            }

            ///Pour les permanences liées aux structures référentes
            $perm = $this->Rendezvous->Personne->Referent->Structurereferente->Permanence->find(
                'first',
                array(
                    'conditions' => array(
                        'Permanence.id' => Set::classicExtract( $rdv, 'Rendezvous.permanence_id' )
                    )
                )
            );
            $rdv['Permanence'] = $perm['Permanence'];
            if( !empty( $perm ) ){
                $rdv['Permanence']['typevoie'] = Set::extract( $typevoie, Set::classicExtract( $rdv, 'Permanence.typevoie' ) );
            }
// debug( $rdv  );
// die();

            $this->Gedooo->generate( $rdv, 'RDV/'.$modele.'.odt' );
        }


	}
?>
