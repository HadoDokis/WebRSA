<?php
	App::import('Sanitize');
	class CohortespdosController extends AppController {

		public $name = 'Cohortespdos';
		public $uses = array( 'Canton', 'Cohortepdo', 'Option', 'Dossier', 'Situationdossierrsa', 'Propopdo', 'Typenotifpdo', 'Typepdo', 'Decisionpdo', 'Traitementtypepdo',  'User', 'Zonegeographique', 'Personne' );
		public $helpers = array( 'Csv', 'Paginator', 'Search' );

		public $paginate = array(
			'limit' => 20,
		);

		public $components = array( 'Jetons', 'Prg' => array( 'actions' => array( 'avisdemande', 'valide' ) ) );

		/**
		*/

//		function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'avisdemande', 'valide' ) ) ) );
//			parent::__construct();
//			$this->components[] = 'Jetons';
//		}

		function beforeFilter(){
			parent::beforeFilter();
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatAttente()) );
			$this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
			$this->set( 'traitementtypepdo', $this->Traitementtypepdo->find( 'list' ) );
			$this->set( 'pieecpres', $this->Option->pieecpres() );
			$this->set( 'commission', $this->Option->commission() );
			$this->set( 'motidempdo', $this->Option->motidempdo() );
			$this->set( 'motifpdo', $this->Option->motifpdo() );
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
		}

		//*********************************************************************

		function avisdemande() {
			$this->_index( 'Decisionpdo::nonvalide' );
		}

		//---------------------------------------------------------------------

		function valide() {
			$this->_index( 'Decisionpdo::valide' );
		}

		//*********************************************************************

		function _index( $statutValidationAvis = null ) {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}
			$this->assert( !empty( $statutValidationAvis ), 'invalidParameter' );

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$this->Dossier->begin();
			if( !empty( $this->data ) ) {
				if( !empty( $this->data['Propopdo'] ) ) {
					$valid = $this->Propopdo->saveAll( $this->data['Propopdo'], array( 'validate' => 'only', 'atomic' => false ) );

					$personne_id = Set::extract(  $this->data, 'Propopdo.{n}.personne_id'  );

					if( $valid ) {
						$this->Dossier->begin();
						$saved = $this->Propopdo->saveAll( $this->data['Propopdo'], array( 'validate' => 'first', 'atomic' => false ) );
						if( $saved ) {
							// FIXME ?
							foreach( $personne_id as $i => $pers ) {
								$dossier_id =  $this->Personne->dossierId( $pers );
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
							$this->Dossier->commit();
							$this->data['Propopdo'] = array(); //FIXME: voir si on peut mieux faire
						}
						else {
							$this->Dossier->rollback();
						}
					}
				}

				if( ( $statutValidationAvis == 'Decisionpdo::nonvalide' ) || ( ( $statutValidationAvis == 'Decisionpdo::valide' ) && !empty( $this->data ) ) || ( ( $statutValidationAvis == 'Decisionpdo::enattente' ) && !empty( $this->data ) ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$queryData = $this->Cohortepdo->search( $statutValidationAvis, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

					$queryData['limit'] = 10;
					$this->paginate = array( 'Personne' => $queryData );
					$cohortepdo = $this->paginate( 'Personne' );

					$this->Dossier->commit();
					$this->set( 'cohortepdo', $cohortepdo );
				}
			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			switch( $statutValidationAvis ) {
				case 'Decisionpdo::nonvalide':
					$this->set( 'pageTitle', 'Nouvelles demandes PDOs' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Decisionpdo::valide':
					$this->set( 'pageTitle', 'PDOs validés' );
					$this->render( $this->action, null, 'visualisation' );
					break;
			}
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$_limit = 10;
			$params = $this->Cohortepdo->search( 'Decisionpdo::valide', $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );

			unset( $params['limit'] );
			$pdos = $this->Propopdo->Personne->find( 'all', $params );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'pdos' ) );
		}
	}
?>