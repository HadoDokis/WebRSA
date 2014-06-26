<?php
	/**
	 * Code source de la classe CriteresciController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe CriteresciController ...
	 *
	 * @package app.Controller
	 */
	class CriteresciController extends AppController
	{
		public $name = 'Criteresci';

		public $uses = array( 'Cohorteci', 'Action', 'Contratinsertion', 'Option', 'Referent', 'Situationdossierrsa' );

		public $helpers = array( 'Csv', 'Cake1xLegacy.Ajax', 'Search' );

		public $components = array(
			'Gestionzonesgeos',
			'Search.SearchPrg' => array( 'actions' => array( 'index' ) ),
			'InsertionsAllocataires'
		);

		public $aucunDroit = array( 'constReq', 'ajaxreferent' );

		/**
		 *
		 */
		protected function _setOptions() {
// 			$struct = ClassRegistry::init( 'Structurereferente' )->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
// 			$this->set( 'struct', $struct );
			$this->set( 'struct', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referents', $this->Contratinsertion->Referent->listOptions() );

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
			$this->set( 'duree_engag', $this->Option->duree_engag() );
			$this->set( 'numcontrat', (array)Hash::get( $this->Contratinsertion->enums(), 'Contratinsertion' ) );

			$this->set( 'action', $this->Action->find( 'list' ) );

			$forme_ci = array();
			if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
			}
			else if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
				$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			}
			$this->set( 'forme_ci', $forme_ci );

 			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( ) );
			$this->set( 'qual', $this->Option->qual() );

			$this->set(
				'trancheage',
				array(
					'0_24' => '- 25 ans',
					'25_34' => '25 - 34 ans',
					'35_44' => '35 - 44 ans',
					'45_54' => '45 - 54 ans',
					'55_999' => '+ 55 ans'
				)
			);
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
					$this->request->data
				);

				$paginate = $this->Gestionzonesgeos->qdConditions( $paginate );
				$paginate['conditions'][] = WebrsaPermissions::conditionsDossier();
				$paginate = $this->_qdAddFilters( $paginate );

				$paginate['limit'] = 10;

				$progressivePaginate = !Set::classicExtract( $this->request->data, 'Contratinsertion.paginationNombreTotal' );
				$this->paginate = $paginate;

				$contrats = $this->paginate( 'Contratinsertion', array(), array(), $progressivePaginate  );

				$this->set( 'contrats', $contrats );
			}
			else {
				// Valeurs par défaut des filtres
				$progressivePaginate = SearchProgressivePagination::enabled( $this->name, $this->action );
				if( !is_null( $progressivePaginate ) ) {
					$this->request->data['Contratinsertion']['paginationNombreTotal'] = !$progressivePaginate;
				}
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			/// Population du select référents liés aux structures
			$conditions = array();
			$structurereferente_id = Set::classicExtract( $this->request->data, 'Contratinsertion.structurereferente_id' );

			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = Set::classicExtract( $this->request->data, 'Contratinsertion.structurereferente_id' );
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

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$typesorientsNiveau0 = $this->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O', 'Typeorient.parentid IS NULL' ), 'empty' => false ) );
				$this->set( compact( 'typesorientsNiveau0' ) );
			}

			$this->set( 'typesorients', $this->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ), 'empty' => true ) ) );
			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );

			$this->_setOptions();

			$options = (array)Hash::get( $this->viewVars, 'options' );
			// Ajout des éléments de listes déroulantes propres au CG 93
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$options = Hash::merge(
					$options,
					$this->Contratinsertion->Cer93->enums(),
					$this->Contratinsertion->Cer93->options( array( 'autre' => true, 'find' => true ) )
				);
				$options['Expprocer93']['metierexerce_id'] = $this->Contratinsertion->Cer93->Expprocer93->Metierexerce->find( 'list' );
				$options['Expprocer93']['secteuracti_id'] = $this->Contratinsertion->Cer93->Expprocer93->Secteuracti->find( 'list' );
			}
			$this->set( compact( 'options' ) );
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			ini_set( 'max_execution_time', 0 );
			ini_set( 'memory_limit', '512M' );

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Cohorteci->search(
				null,
				Hash::expand( $this->request->params['named'], '__' )
			);

			$querydata = $this->Gestionzonesgeos->qdConditions( $querydata );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();
			$querydata = $this->_qdAddFilters( $querydata );

			unset( $querydata['limit'] );

			$contrats = $this->Contratinsertion->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'contrats' ) );
			$this->_setOptions();
			$options = (array)Hash::get( $this->viewVars, 'options' );
			$options = Hash::merge( $options, $this->Contratinsertion->Cer93->enums() );
			$this->set( compact( 'options' ) );
		}
	}
?>