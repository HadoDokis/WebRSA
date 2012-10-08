<?php
	App::import('Sanitize');

	class CriteresciController extends AppController
	{
		public $name = 'Criteresci';

		public $uses = array( 'Cohorteci', 'Action', 'Contratinsertion', 'Option', 'Referent', 'Situationdossierrsa' );
		public $helpers = array( 'Csv', 'Ajax', 'Search' );
		public $components = array( 'Gestionzonesgeos', 'Search.Prg' => array( 'actions' => array( 'index' ) ) );

		public $aucunDroit = array( 'constReq', 'ajaxreferent' );

		/**
		 *
		 */
		protected function _setOptions() {
			$struct = ClassRegistry::init( 'Structurereferente' )->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$this->set( 'struct', $struct );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$personne_suivi = $this->Contratinsertion->find(
				'list',
				array(
					'fields' => array(
						'Contratinsertion.pers_charg_suivi',
						'Contratinsertion.pers_charg_suivi'
					),
					'order' => 'Contratinsertion.pers_charg_suivi ASC',
					'group' => 'Contratinsertion.pers_charg_suivi',
				)
			);
			$this->set( 'personne_suivi', $personne_suivi );
			$this->set( 'natpf', $this->Option->natpf() );

			$this->set( 'decision_ci', $this->Option->decision_ci() );
			$this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );
			$this->set( 'numcontrat', $this->Contratinsertion->allEnumLists() );

			$this->set( 'action', $this->Action->find( 'list' ) );

			$forme_ci = array();
			if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
			}
			else if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			}
			$this->set( 'forme_ci', $forme_ci );

			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatOuvert()) );
// 			$this->set( 'etatdossier', $this->Option->etatdosrsa( ) );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'qual', $this->Option->qual() );

			$this->set(
				'trancheage',
				array(
					'- 25 ans',
					'25 - 35 ans',
					'35 - 45 ans',
					'45 - 55 ans',
					'+ 55 ans'
				)
			); // INFO: pas dans view
		}

		/**
		 * Ajax pour lien référent - structure référente
		 *
		 * @param type $structurereferente_id
		 * @return type
		 */
		public function _selectReferents( $structurereferente_id ) {
			$conditions = array();

			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = $structurereferente_id;
			}

			$referents = $this->Referent->find(
				'all',
				array(
					'fields' => array( 'Referent.id', 'Referent.nom', 'Referent.prenom' ),
					'conditions' => $conditions,
					'recursive' => -1
				)
			);

			return $referents;
		}

		/**
		 *
		 */
		public function ajaxreferent() {
			Configure::write( 'debug', 2 );
			$referents = $this->_selectReferents( Set::classicExtract( $this->request->data, 'Filtre.structurereferente_id' ) );
			$options = array( '<option value=""></option>' );
			foreach( $referents as $referent ) {
				$options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
			} ///FIXME: à mettre dans la vue
			echo implode( '', $options );
			$this->render( null, 'ajax' );
		}

		/**
		 *
		 */
		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$params = $this->request->data;

			if( !empty( $params ) ) {
				$paginate = $this->Cohorteci->search(
					null,
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data,
					false
				);
				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );

				$this->paginate = $paginate;
				$contrats = $this->paginate( 'Contratinsertion' );

				$this->set( 'contrats', $contrats );
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			/// Population du select référents liés aux structures
			$conditions = array();
			$structurereferente_id = Set::classicExtract( $this->request->data, 'Filtre.structurereferente_id' );

			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = Set::classicExtract( $this->request->data, 'Filtre.structurereferente_id' );
			}

			$referents = $this->Referent->find(
				'all',
				array(
					'recursive' => -1,
					'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
					'conditions' => $conditions
				)
			);

			if( !empty( $referents ) ) {
				$ids = Set::extract( $referents, '/Referent/id' );
				$values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
				$referents = array_combine( $ids, $values );
			}

			$this->set( 'referents', $referents );
			$this->_setOptions();
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Cohorteci->search(
				null,
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->request->params['named'], '__' ),
				false
			);

			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$contrats = $this->Contratinsertion->find( 'all', $querydata );

			/// Population du select référents liés aux structures
			$structurereferente_id = Set::classicExtract( $this->request->data, 'Contratinsertion.structurereferente_id' );
			$referents = $this->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );

			$this->layout = '';
			$this->set( compact( 'contrats' ) );
			$this->_setOptions();
		}
	}
?>