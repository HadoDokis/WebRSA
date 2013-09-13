<?php
	/**
	 * Code source de la classe CriteresapresController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriteresapresController implémente un moteur de recherche par APREs (CG 66 et 93).
	 *
	 * @package app.Controller
	 */
	class CriteresapresController extends AppController
	{
		public $name = 'Criteresapres';

		public $uses = array( 'Critereapre', 'Aideapre66', 'Apre',  'Apre66', 'Tiersprestataireapre', 'Option' );

		public $helpers = array( 'Locale', 'Csv', 'Xform', 'Xhtml', 'Xpaginator', 'Search' );

		public $components = array(
			'Gestionzonesgeos',
			'Search.Prg' => array( 'actions' => array( 'all', 'eligible' ) )
		);

		/**
		 * Changement du temps d'exécution maximum.
		 *
		 * @return void
		 */
		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			parent::beforeFilter();
		}

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$options = $this->Apre->allEnumLists();
// 			$optionsaides = $this->Apre->Aideapre66->allEnumLists();
			$options = Set::merge( $options, $this->Apre66->Aideapre66->allEnumLists() );
			$this->set( 'options', $options );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
			$this->set( 'printed', $this->Option->printed() );


			$this->set( 'themes', $this->Apre66->Aideapre66->Themeapre66->find( 'list' ) );
			$this->set( 'typesaides', $this->Apre66->Aideapre66->Typeaideapre66->listOptions() );
			/// Liste des tiers prestataires
			$this->set( 'tiers', $this->Tiersprestataireapre->find( 'list' ) );

			$this->set( 'structures', $this->Apre->Structurereferente->listeParType( array( 'apre' => true ) ) );

			$this->set( 'referents', $this->Apre->Referent->listOptions() );
		}

		/**
		 * Moteur de recherche par APREs (tous critères confondus).
		 *
		 * @return void
		 */
		public function all() {
			$this->_index( 'Critereapre::all' );
		}

		/**
		 * Moteur de recherche par APREs éligibles.
		 *
		 * @return void
		 */
		public function eligible() {
			$this->_index( 'Critereapre::eligible' );
		}

		/**
		 * Moteur de recherche par APREs.
		 *
		 * @return void
		 */
		public function _index( $etatApre = null ){
			$this->assert( !empty( $etatApre ), 'invalidParameter' );

			$params = $this->request->data;
			if( !empty( $params ) ) {
				$queryData = $this->Critereapre->search(
					$etatApre,
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);
				$paginate = $queryData;
				$paginate['conditions'][] = WebrsaPermissions::conditionsDossier();
				$paginate['limit'] = 10;

				$this->paginate = $paginate;
				$apres = $this->paginate( 'Apre' );

				///
				unset( $queryData['fields'] );
				$queryData['recursive'] = -1;

				$joins = array(
					array(
						'table'      => 'apres_comitesapres',
						'alias'      => 'ApreComiteapre',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'ApreComiteapre.apre_id = Apre.id' )
					),
					array(
						'table'      => 'comitesapres',
						'alias'      => 'Comiteapre',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'ApreComiteapre.comiteapre_id = Comiteapre.id'
						)
					),
				);

				$queryData['joins'] = array_merge( $queryData['joins'], $joins );

				///Nb d'APREs appartenant à un comité et dont la décision a été/va être prise
				$attenteDecision = array(
					'conditions' => array(
						'ApreComiteapre.apre_id IS NOT NULL',
						'ApreComiteapre.decisioncomite IS NULL'
					)
				);
				$attenteDecisionsApres = $this->Apre->find(
					'count',
					Set::merge( $queryData, $attenteDecision )
				);

				///Nb d'APREs en attente de traitement(n'appartenant à aucun comité et n'ayant aucune décision de prise)
				$attenteTraitement = array(
					'conditions' => array(
						'ApreComiteapre.apre_id IS NULL'
					)
				);
				$attenteTraitementApres = $this->Apre->find(
					'count',
					Set::merge( $queryData, $attenteTraitement )
				);


				$this->set( 'attenteDecisionsApres', $attenteDecisionsApres );
				$this->set( 'attenteTraitementApres', $attenteTraitementApres );

				$this->set( 'apres', $apres );
			}

			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->_setOptions();

			switch( $etatApre ) {
				case 'Critereapre::all':
					$this->set( 'pageTitle', 'Toutes les APREs' );
					$statutApre = Set::classicExtract( $this->request->data, 'Filtre.statutapre' );
					if( $statutApre == 'F' ) {
						$this->render( 'forfaitaire' );
					}
					else {
						if( Configure::read( 'Cg.departement' ) == 93 ){
							$this->render( 'formulaire' );
						}
						else{
							$this->render( 'formulaire66' );
						}
					}
					break;
				case 'Critereapre::forfaitaire':
					$this->set( 'pageTitle', 'APREs forfaitaires' );
					$this->render( 'forfaitaire' );
					break;
				case 'Critereapre::eligible':
					$this->set( 'pageTitle', 'Eligibilité des APREs' );
					$this->render( 'visualisation' );
					break;
			}
		}

		/**
		 * Export du tableau en CSV.
		 *
		 * @return void
		 */
		public function exportcsv( $action = 'all' ) {
			$querydata = $this->Critereapre->search(
				"Critereapre::{$action}",
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();
			$apres = $this->Apre->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'apres' ) );

			$this->_setOptions();

			switch( $action ) {
				case 'all':
					$this->render( 'exportcsv' );
					break;
				default:
					$this->render( 'exportcsveligible' );
			}
		}
	}
?>