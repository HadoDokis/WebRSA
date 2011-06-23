<?php
	class Defautsinsertionseps66Controller extends AppController
	{
		public $components = array( 'Prg' => array( 'actions' => array( 'selectionnoninscrits', 'selectionradies' ) ) );

		public $helpers = array( 'Default2' );

		/**
		*
		*/

		protected function _selectionPassageDefautinsertionep66( $qdName, $actionbp ) {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$mesCodesInsee = ClassRegistry::init( 'Zonegeographique' )->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
			}
			else {
				$mesCodesInsee = ClassRegistry::init( 'Adresse' )->listeCodesInsee();
			}
			$this->set( compact( 'mesCodesInsee' ) );

			if( !empty( $this->data ) ) {
// debug($this->data);
				$queryData = $this->Defautinsertionep66->{$qdName}( $this->data, ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				$queryData['limit'] = 10;

				$this->paginate = array( 'Personne' => $queryData );
				$personnes = $this->paginate( $this->Defautinsertionep66->Dossierep->Personne );
			}

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