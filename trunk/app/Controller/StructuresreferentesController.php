<?php
	class StructuresreferentesController extends AppController
	{

		public $name = 'Structuresreferentes';
		public $uses = array( 'Structurereferente', 'Referent', 'Orientstruct', 'Typeorient', 'Zonegeographique', 'Apre', 'Option' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Structuresreferentes:edit'
		);

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'typevoie', $this->Option->typevoie() );

			$optionsradio = $this->Structurereferente->allEnumLists();
			$this->set( 'optionsradio', $optionsradio );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$typeorient = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.lib_type_orient'
					)
				)
			);
			$this->set( 'typeorient', $typeorient );

			$structuresreferentes = $this->Structurereferente->find(
				'all',
				array(
					'recursive' => -1
				)
			);

			App::import( 'Behaviors', 'Occurences' );
			$this->Structurereferente->Behaviors->attach( 'Occurences' );
			$this->set( 'occurences', $this->Structurereferente->occurencesExists() );

			$this->set( 'structuresreferentes', $structuresreferentes);
		}

		public function add() {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			$this->set( 'options', $this->Typeorient->listOptions() );
			$zg = $this->Zonegeographique->find(
				'list',
				array(
					'fields' => array(
						'Zonegeographique.id',
						'Zonegeographique.libelle'
					)
				)
			);
			$this->set( 'zglist', $zg );

			$type = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient',
					)
				)
			);
			$this->set( 'type', $type );

			if( !empty( $this->request->data ) ) {
				if( $this->Structurereferente->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'structuresreferentes', 'action' => 'index' ) );
				}
			}

			$this->render( 'add_edit' );
		}

		public function edit( $structurereferente_id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $structurereferente_id ), 'error404' );
			$this->set( 'options', $this->Typeorient->listOptions() );
			$zg = $this->Zonegeographique->find(
				'list',
				array(
					'fields' => array(
						'Zonegeographique.id',
						'Zonegeographique.libelle'
					)
				)
			);
			$this->set( 'zglist', $zg );

			$type = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.lib_type_orient'
					)
				)
			);
			$this->set( 'type', $type );

			if( !empty( $this->request->data ) ) {
				if( $this->Structurereferente->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'structuresreferentes', 'action' => 'index' ) );
				}
			}
			else {
				$structurereferente = $this->Structurereferente->find(
					'first',
					array(
						'conditions' => array(
							'Structurereferente.id' => $structurereferente_id,
						),
						'contain' => array(
							'Zonegeographique'
						)
					)
				);
				$this->request->data = $structurereferente;
			}

			$this->render( 'add_edit' );
		}

		public function delete( $structurereferente_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $structurereferente_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$structurereferente = $this->Structurereferente->find(
				'first',
				array( 'conditions' => array( 'Structurereferente.id' => $structurereferente_id )
				)
			);

			// Mauvais paramètre
			if( empty( $structurereferente_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Structurereferente->delete( array( 'Structurereferente.id' => $structurereferente_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'structuresreferentes', 'action' => 'index' ) );
			}
		}
	}

?>