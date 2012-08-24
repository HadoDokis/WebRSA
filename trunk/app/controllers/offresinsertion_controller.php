<?php
	class OffresinsertionController extends AppController
	{

		public $name = 'Offresinsertion';
		public $uses = array( 'Offreinsertion', 'Actioncandidat', 'Contactpartenaire', 'Partenaire', 'Option' );
		
		public $helpers = array( 'Default2', 'Fileuploader' );
		
		public $commeDroit = array(
			'view' => 'Offresinsertion:index'
		);
		
		public $components = array(
			'Prg2' => array(
				'actions' => array(
					'index' => array( 'filter' => 'Search' )
				)
			)
		);
		
		public function _setOptions() {
			$options = array();
			$options = $this->Actioncandidat->enums();
			$listeActions = $this->Actioncandidat->find( 'list', array( 'order' => 'Actioncandidat.name ASC' ) );
			$listePartenaires = $this->Actioncandidat->Partenaire->find( 'list', array( 'order' => 'Partenaire.libstruc ASC' ) );
			$listeContacts = $this->Actioncandidat->Contactpartenaire->find( 'list', array( 'order' => 'Contactpartenaire.nom ASC' ) );
			$options['Partenaire']['typevoie'] = $this->Option->typevoie();

// 			$options = Set::merge( $options, $typevoie );
			$this->set( compact( 'options', 'listeActions', 'listePartenaires', 'listeContacts', 'typevoie' ) );
		}

		public function index() {
			if( !empty( $this->data ) ) {
				$queryData = $this->Offreinsertion->search( $this->data );
				$queryData['limit'] = 150;
				$this->paginate = $queryData;
				$search = $this->paginate( $this->Actioncandidat );

				$this->set( compact( 'search' ) );
			}
			$this->_setOptions();
		}
		
		public function view( $actioncandidat_id = null ) {
			$this->assert( is_numeric( $actioncandidat_id ), 'error404' );
			
			$actioncandidat = $this->Actioncandidat->find(
				'first', 
				array(
					'conditions' => array(
						'Actioncandidat.id' => $actioncandidat_id
					),
					'contain' => array(
						'Fichiermodule'
					)
				)
			);
			$this->assert( !empty( $actioncandidat ), 'invalidParameter' );

			// Retour à l'entretien en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'offresinsertion', 'action' => 'index' ) );
			}

			$this->_setOptions();
			$this->set( compact( 'actioncandidat' ) );
		}
	}
?>