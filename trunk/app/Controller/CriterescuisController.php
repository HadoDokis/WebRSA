<?php
	/**
	 * Code source de la classe CriterescuisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriterescuisController implémente un moteur de recherche par CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Controller
	 */
	class CriterescuisController extends AppController
	{
		public $name = 'Criterescuis';

		public $uses = array( 'Criterecui', 'Cui', 'Option', 'Structurereferente' );

		public $helpers = array( 'Csv', 'Search' );

		public $components = array(
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'Search.Prg' => array( 'actions' => array( 'index' ) )
		);

		public $aucunDroit = array( 'exportcsv' );

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions(){
			$options = array();
			$struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$this->set( 'struct', $struct );
			$this->set( 'rolepers', $this->Option->rolepers() );
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

			$qual = $this->Option->qual();
			$this->set( 'qual', $qual );
			$options = $this->Cui->enums();

			$this->set( 'oridemrsa', $this->Option->oridemrsa() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );

			$valeursSecteurcui = $this->Cui->Secteurcui->find(
				'all',
				array(
					'order' => array( 'Secteurcui.isnonmarchand DESC', 'Secteurcui.name ASC' )
				)
			);
			$secteur_isnonmarchand_id = Hash::extract( $valeursSecteurcui, '{n}.Secteurcui[isnonmarchand=1].id' );

			$secteurscuis = $this->Cui->Secteurcui->find(
				'list',
				array(
					'contain' => false,
					'order' => array( 'Secteurcui.name' )
				)
			);

            $employeursCui = $this->Cui->Partenaire->find(
				'list',
				array(
					'conditions' => array(
						'Partenaire.iscui' => '1'
					),
					'order' => array( 'Partenaire.libstruc ASC' )
				)
			);

            $this->set( compact( 'secteur_isnonmarchand_id', 'secteurscuis', 'employeursCui', 'options', 'qual' ) );
		}

		/**
		 * Moteur de recherche par CUI.
		 *
		 * @return void
		 */
		public function index() {
			if( !empty( $this->request->data ) ) {
				$paginate = $this->Criterecui->search(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);
				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );
				$paginate['conditions'][] = WebrsaPermissions::conditionsDossier();

				$this->paginate = $paginate;
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
				$criterescuis = $this->paginate( 'Cui', array(), array(), $progressivePaginate );

				foreach( $criterescuis as $i => $criterecui ) {
					if( !empty( $criterecui['Partenaire']['libstruc'] ) ) {
						$nomemployeur = $criterecui['Partenaire']['libstruc'];
					}
					else {
						$nomemployeur = $criterecui['Cui']['nomemployeur'];
					}
					$criterescuis[$i]['Cui']['nomemployeur'] = $nomemployeur;
				}

				$this->set( 'criterescuis', $criterescuis );
			}
			$this->_setOptions();
			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true, 'conditions' => array( 'orientation' => 'O' ) ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );
		}

		/**
		 * Export du tableau en CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$querydata = $this->Criterecui->search(
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();

			$cuis = $this->Cui->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'cuis' ) );
		}
	}
?>