<?php
	class Defautsinsertionseps66Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		/**
		*
		*/

		protected function _setOptions() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Zonegeographique' )->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Adresse' )->listeCodesInsee() );
			}
		}

		/**
		*
		*/

		protected function _selectionPassageDefautinsertionep66( $qdName, $actionbp ) {
			$personnes = array();
			
			if( !empty( $this->data ) ) {
				$queryData = $this->Defautinsertionep66->{$qdName}($this->data);
				$queryData['limit'] = 10;

				$this->paginate = array( 'Personne' => $queryData );
				$personnes = $this->paginate( $this->Defautinsertionep66->Dossierep->Personne );
			}
			
			$this->_setOptions();
			$this->set( compact( 'personnes' ) );
			
			$this->set( compact( 'actionbp' ) );
			
			$this->render( $this->action, null, 'selectionnoninscrits' ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionnoninscrits() {
			$this->_selectionPassageDefautinsertionep66( 'qdNonInscrits', 'noninscriptionpe' );
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageDefautinsertionep66( 'qdRadies', 'radiationpe' );
		}
	}
?>