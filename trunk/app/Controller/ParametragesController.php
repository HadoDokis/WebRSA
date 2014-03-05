<?php
	/**
	 * Code source de la classe ParametragesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ParametragesController ...
	 *
	 * @package app.Controller
	 */
	class ParametragesController extends AppController
	{

		public $name = 'Parametrages';
		public $uses = array( 'Dossier', 'Structurereferente', 'Zonegeographique' );

		public $commeDroit = array(
			'view' => 'Parametrages:index',
			'modulefse93' => 'Parametrages:index',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * Premier niveau du paramétrage, suivant le département.
		 */
		public function index() {
			$links = array(
				'Actions d\'insertion' => ( Configure::read( 'Cg.departement' ) != 66 )
					? array( 'controller' => 'actions', 'action' => 'index' )
					: null
				,
				'APREs' => array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'indexparams' ),
				'Cantons' => array( 'controller' => 'cantons', 'action' => 'index' ),
				'CERs' => ( Configure::read( 'Cg.departement' ) == 93 )
					? array( 'controller' => 'cers93', 'action' => 'indexparams' )
					: null
				,
                                'Codes ROME V3' => array( 'controller' => 'codesromev3', 'action' => 'index' ),
				'CUIs' => array( 'controller' => 'cuis', 'action' => 'indexparams' ),
				'DSPs' => array( 'controller' => 'gestionsdsps', 'action' => 'index' ),
				'Équipes pluridisciplinaires' => array( 'controller' => 'gestionseps', 'action' => 'index' ),
				'Fiches de prescription' => ( Configure::read( 'Cg.departement' ) == 93 )
					? array( 'controller' => 'parametrages', 'action' => 'fichesprescriptions93' )
					: null
				,
				'Liste des sanctions' => ( Configure::read( 'Cg.departement' ) == 58 )
					? array( 'controller' => 'listesanctionseps58', 'action' => 'index' )
					: null
				,
				'Module FSE' => ( Configure::read( 'Cg.departement' ) == 93 )
					? array( 'controller' => 'parametrages', 'action' => 'modulefse93' )
					: null
				,
				'Motifs de non validation de CER' => ( Configure::read( 'Cg.departement' ) == 66 )
					? array( 'controller' => 'motifscersnonvalids66', 'action' => 'index' )
					: null
				,
				'Objets de l\'entretien' => array( 'controller' => 'objetsentretien', 'action' => 'index' ),
				'PDOs' => array( 'controller' => 'pdos', 'action' => 'index' ),
				'Permanences' => array( 'controller' => 'permanences', 'action' => 'index' ),
				'Référents pour les structures' => array( 'controller' => 'referents', 'action' => 'index' ),
				'Rendez-vous' => array( 'controller' => 'gestionsrdvs', 'action' => 'index' ),
				'Services instructeurs' => array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ),
				'Sites d\'actions médico-sociale COVs' => ( Configure::read( 'Cg.departement' ) == 58 )
					? array( 'controller' => 'sitescovs58', 'action' => 'index' )
					: null
				,
				'Structures référentes' => array( 'controller' => 'structuresreferentes', 'action' => 'index' ),
				'Types d\'actions' => ( Configure::read( 'Cg.departement' ) != 66 )
					? array( 'controller' => 'typesactions', 'action' => 'index' )
					: null
				,
				'Types d\'orientations' => array( 'controller' => 'typesorients', 'action' => 'index' ),
				'Zones géographiques' => array( 'controller' => 'zonesgeographiques', 'action' => 'index' ),
			);

			$links = Hash::filter( $links );
			$this->set( compact( 'links' ) );
		}

		public function view( $param = null ) {
			$zone = $this->Zonegeographique->find(
				'first',
				array(
					'conditions' => array(
					)
				)
			);
			$this->set('zone', $zone);
		}

		public function modulefse93() {
			$links = array(
				__d( 'sortiesaccompagnementsd2pdvs93', '/Sortiesaccompagnementsd2pdvs93/index/:heading' ) => array( 'controller' => 'sortiesaccompagnementsd2pdvs93', 'action' => 'index' ),
			);

			$this->set( compact( 'links' ) );
		}

		public function fichesprescriptions93() {
			$links = array(
				__d( 'cataloguespdisfps93', '/Cataloguespdisfps93/search/:heading' ) => array( 'controller' => 'cataloguespdisfps93', 'action' => 'search' ),
			);
			foreach( array( 'Thematiquefp93', 'Categoriefp93', 'Filierefp93', 'Actionfp93', 'Prestatairefp93' ) as $modelName ) {
				$links[__d( 'cataloguespdisfps93', "/Cataloguespdisfps93/index/{$modelName}/:heading" )] = array( 'controller' => 'cataloguespdisfps93', 'action' => 'index', $modelName );
			}

			$this->set( compact( 'links' ) );
			$this->render( 'modulefse93' );
		}
	}

?>