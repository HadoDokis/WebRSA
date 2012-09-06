<?php
	class DetailsdroitsrsaController extends AppController
	{

		public $name = 'Detailsdroitsrsa';
		public $uses = array( 'Detaildroitrsa',  'Option' , 'Dossier', 'Detailcalculdroitrsa');
		
		public $commeDroit = array(
			'view' => 'Detailsdroitsrsa:index'
		);

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'topsansdomfixe', $this->Option->topsansdomfixe() );
			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'topfoydrodevorsa', $this->Option->topfoydrodevorsa() );
			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'sousnatpf', $this->Option->sousnatpf() );	    
		}

		public function index( $dossier_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$detaildroitrsa = $this->Detaildroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $dossier_id
					),
					'contain' => array( 'Detailcalculdroitrsa' )
				)
			) ;

			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'detaildroitrsa', $detaildroitrsa );
		}

		public function view( $detaildroitrsa_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $detaildroitrsa_id ), 'error404' );

			$detaildroitrsa = $this->Detaildroitrsa->find(
				'first',
				array(
					'conditions' => array(
						'Detaildroitrsa.id' => $detaildroitrsa_id
					),
				'recursive' => -1
				)
			);

			$this->assert( !empty( $detaildroitrsa ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $detaildroitrsa['Detaildroitrsa']['dossier_id'] );
			$this->set( 'detaildroitrsa', $detaildroitrsa );
		}
	}

?>