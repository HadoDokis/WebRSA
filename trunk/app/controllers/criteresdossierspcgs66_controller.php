<?php
	App::import('Sanitize');

	class Criteresdossierspcgs66Controller extends AppController
	{
		public $uses = array( 'Criteredossierpcg66', 'Dossierpcg66', 'Option' );
		public $helpers = array( 'Default', 'Default2', 'Ajax', 'Locale', 'Csv', 'Search' );

		public $components = array( 'Prg' => array( 'actions' => array( 'dossier', 'gestionnaire' ) ) );

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'typepdo', $this->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Dossierpcg66->Originepdo->find( 'list' ) );
			$this->set( 'descriptionpdo', $this->Dossierpcg66->Personnepcg66->Traitementpcg66->Descriptionpdo->find( 'list' ) );
			$this->set( 'motifpersonnepcg66', $this->Dossierpcg66->Personnepcg66->Situationpdo->find( 'list' ) );

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

			$options = $this->Dossierpcg66->enums();
			$etatdossierpcg = $options['Dossierpcg66']['etatdossierpcg'];
			
			$options = array_merge(
				$options,
				$this->Dossierpcg66->Personnepcg66->Traitementpcg66->enums()
			);
			$this->set( compact( 'options', 'etatdossierpcg' ) );
		}

		/**
		*
		*/

		private function _index( $searchFunction ) {
			$params = $this->data;
			if( !empty( $params ) ) {
				$this->paginate = $this->Criteredossierpcg66->{$searchFunction}( $this->data );
				$this->paginate = $this->_qdAddFilters( $this->paginate );
				$this->Dossierpcg66->forceVirtualFields = true;
				$criteresdossierspcgs66 = $this->paginate( 'Dossierpcg66' );
				
				foreach( $criteresdossierspcgs66 as $i => $criteredossierpcg66 ) {
					$dossierpcg66_id = Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.id' );

					$traitementspcgs66 = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->find(
						'all',
						array(
							'fields' => array(
								'Traitementpcg66.typetraitement'
							),
							'joins' => array(
								$this->Dossierpcg66->Personnepcg66->Traitementpcg66->join( 'Personnepcg66', array( 'type' => 'INNER' ) )
							),
							'conditions' => array(
								'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
							),
							'contain' => false
						)
					);
// 					debug( $traitementspcgs66 );
					//Liste des différents statuts de la personne
					$listeTraitementspcgs66 = Set::extract( $traitementspcgs66, '/Traitementpcg66/typetraitement' );

					$criteresdossierspcgs66[$i]['Dossierpcg66']['listetraitements'] = $listeTraitementspcgs66;
				}

				$this->set( compact( 'criteresdossierspcgs66' ) );
			}
// debug($params);
			$this->_setOptions();
			$this->render( $this->action );
		}

		/**
		*
		*/

		public function dossier() {
			$this->_index( 'searchDossier' );
		}
		
		/**
		*
		*/

		public function gestionnaire() {
			$this->_index( 'searchGestionnaire' );
		}
	}
?>