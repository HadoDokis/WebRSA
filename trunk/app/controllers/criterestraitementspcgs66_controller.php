<?php
	App::import('Sanitize');

	class Criterestraitementspcgs66Controller extends AppController
	{
		public $uses = array( 'Criteretraitementpcg66', 'Traitementpcg66', 'Option' );
		public $helpers = array( 'Default', 'Default2', 'Ajax', 'Locale', 'Csv', 'Search' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'typepdo', $this->Traitementpcg66->Personnepcg66->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Traitementpcg66->Personnepcg66->Dossierpcg66->Originepdo->find( 'list' ) );
			$this->set( 'descriptionpdo', $this->Traitementpcg66->Descriptionpdo->find( 'list' ) );
			$this->set( 'motifpersonnepcg66', $this->Traitementpcg66->Personnepcg66->Situationpdo->find( 'list' ) );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			$options = $this->Traitementpcg66->enums();
// 			$etatdossierpcg = $options['Traitementpcg66']['etatdossierpcg'];
// 			
// 			$options = array_merge(
// 				$options,
// 				$this->Traitementpcg66->Personnepcg66->Traitementpcg66->enums()
// 			);
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$params = $this->data;
			if( !empty( $params ) ) {
				$this->paginate = array( 'Traitementpcg66' => $this->Criteretraitementpcg66->search( $this->data ) );
				$this->paginate['Traitementpcg66']['limit'] = 10;

				$criterestraitementspcgs66 = $this->paginate( 'Traitementpcg66' );

				$this->set( compact( 'criterestraitementspcgs66' ) );
			}

			$this->_setOptions();
			$this->render( $this->action );
		}

	}
?>