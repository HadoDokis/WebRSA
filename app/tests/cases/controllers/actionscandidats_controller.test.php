<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Actionscandidats');
	App::import('Controller', 'Option');

	class TestActionscandidatsController extends ActionscandidatsController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Actionscandidats';

		public function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}

		public function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}

		public function _stop($status = 0) {
			$this->stopped = $status;
		}

		public function assert( $condition, $error = 'error500', $parameters = array() ) {
			$this->condition = $condition;
			$this->error = $error;
			$this->parameters = $parameters;
		}

	}

	class ActionscandidatsControllerTest extends CakeAppControllerTestCase {

	        function testBeforeFilter() {
			$this->ActionscandidatsController->beforeFilter();
			$expected = array(
				'ABE' => 'Abbaye',
				'ACH' => 'Ancien chemin',
				'AGL' => 'Agglomération',
				'AIRE' => 'Aire',
				'ALL' => 'Allée',
				'ANSE' => 'Anse',
				'ARC' => 'Arcade',
				'ART' => 'Ancienne route',
				'AUT' => 'Autoroute',
				'AV' => 'Avenue',
				'BAST' => 'Bastion',
				'BCH' => 'Bas chemin',
				'BCLE' => 'Boucle',
				'BD' => 'Boulevard',
				'BEGI' => 'Béguinage',
				'BER' => 'Berge',
				'BOIS' => 'Bois',
				'BRE' => 'Barriere',
				'BRG' => 'Bourg',
				'BSTD' => 'Bastide',
				'BUT' => 'Butte',
				'CALE' => 'Cale',
				'CAMP' => 'Camp',
				'CAR' => 'Carrefour',
				'CARE' => 'Carriere',
				'CARR' => 'Carre',
				'CAU' => 'Carreau',
				'CAV' => 'Cavée',
				'CGNE' => 'Campagne',
				'CHE' => 'Chemin',
				'CHEM' => 'Cheminement',
				'CHEZ' => 'Chez',
				'CHI' => 'Charmille',
				'CHL' => 'Chalet',
				'CHP' => 'Chapelle',
				'CHS' => 'Chaussée',
				'CHT' => 'Château',
				'CHV' => 'Chemin vicinal',
				'CITE' => 'Cité',
				'CLOI' => 'Cloître',
				'CLOS' => 'Clos',
				'COL' => 'Col',
				'COLI' => 'Colline',
				'COR' => 'Corniche',
				'COTE' => 'Côte(au)',
				'COTT' => 'Cottage',
				'COUR' => 'Cour',
				'CPG' => 'Camping',
				'CRS' => 'Cours',
				'CST' => 'Castel',
				'CTR' => 'Contour',
				'CTRE' => 'Centre',
				'DARS' => 'Darse',
				'DEG' => 'Degré',
				'DIG' => 'Digue',
				'DOM' => 'Domaine',
				'DSC' => 'Descente',
				'ECL' => 'Ecluse',
				'EGL' => 'Eglise',
				'EN' => 'Enceinte',
				'ENC' => 'Enclos',
				'ENV' => 'Enclave',
				'ESC' => 'Escalier',
				'ESP' => 'Esplanade',
				'ESPA' => 'Espace',
				'ETNG' => 'Etang',
				'FG' => 'Faubourg',
				'FON' => 'Fontaine',
				'FORM' => 'Forum',
				'FORT' => 'Fort',
				'FOS' => 'Fosse',
				'FOYR' => 'Foyer',
				'FRM' => 'Ferme',
				'GAL' => 'Galerie',
				'GARE' => 'Gare',
				'GARN' => 'Garenne',
				'GBD' => 'Grand boulevard',
				'GDEN' => 'Grand ensemble',
				'GPE' => 'Groupe',
				'GPT' => 'Groupement',
				'GR' => 'Grand(e) rue',
				'GRI' => 'Grille',
				'GRIM' => 'Grimpette',
				'HAM' => 'Hameau',
				'HCH' => 'Haut chemin',
				'HIP' => 'Hippodrome',
				'HLE' => 'Halle',
				'HLM' => 'HLM',
				'ILE' => 'Ile',
				'IMM' => 'Immeuble',
				'IMP' => 'Impasse',
				'JARD' => 'Jardin',
				'JTE' => 'Jetée',
				'LD' => 'Lieu dit',
				'LEVE' => 'Levée',
				'LOT' => 'Lotissement',
				'MAIL' => 'Mail',
				'MAN' => 'Manoir',
				'MAR' => 'Marche',
				'MAS' => 'Mas',
				'MET' => 'Métro',
				'MF' => 'Maison forestiere',
				'MLN' => 'Moulin',
				'MTE' => 'Montée',
				'MUS' => 'Musée',
				'NTE' => 'Nouvelle route',
				'PAE' => 'Petite avenue',
				'PAL' => 'Palais',
				'PARC' => 'Parc',
				'PAS' => 'Passage',
				'PASS' => 'Passe',
				'PAT' => 'Patio',
				'PAV' => 'Pavillon',
				'PCH' => 'Porche - petit chemin',
				'PERI' => 'Périphérique',
				'PIM' => 'Petite impasse',
				'PKG' => 'Parking',
				'PL' => 'Place',
				'PLAG' => 'Plage',
				'PLAN' => 'Plan',
				'PLCI' => 'Placis',
				'PLE' => 'Passerelle',
				'PLN' => 'Plaine',
				'PLT' => 'Plateau(x)',
				'PN' => 'Passage à niveau',
				'PNT' => 'Pointe',
				'PONT' => 'Pont(s)',
				'PORQ' => 'Portique',
				'PORT' => 'Port',
				'POT' => 'Poterne',
				'POUR' => 'Pourtour',
				'PRE' => 'Pré',
				'PROM' => 'Promenade',
				'PRQ' => 'Presqu\'île',
				'PRT' => 'Petite route',
				'PRV' => 'Parvis',
				'PSTY' => 'Peristyle',
				'PTA' => 'Petite allée',
				'PTE' => 'Porte',
				'PTR' => 'Petite rue',
				'QU' => 'Quai',
				'QUA' => 'Quartier',
				'R' => 'Rue',
				'RAC' => 'Raccourci',
				'RAID' => 'Raidillon',
				'REM' => 'Rempart',
				'RES' => 'Résidence',
				'RLE' => 'Ruelle',
				'ROC' => 'Rocade',
				'ROQT' => 'Roquet',
				'RPE' => 'Rampe',
				'RPT' => 'Rond point',
				'RTD' => 'Rotonde',
				'RTE' => 'Route',
				'SEN' => 'Sentier',
				'SQ' => 'Square',
				'STA' => 'Station',
				'STDE' => 'Stade',
				'TOUR' => 'Tour',
				'TPL' => 'Terre plein',
				'TRA' => 'Traverse',
				'TRN' => 'Terrain',
				'TRT' => 'Tertre(s)',
				'TSSE' => 'Terrasse(s)',
				'VAL' => 'Val(lée)(lon)',
				'VCHE' => 'Vieux chemin',
				'VEN' => 'Venelle',
				'VGE' => 'Village',
				'VIA' => 'Via',
				'VLA' => 'Villa',
				'VOI' => 'Voie',
				'VTE' => 'Vieille route',
				'ZA' => 'Zone artisanale',
				'ZAC' => 'Zone d\'aménagement concerte',
				'ZAD' => 'Zone d\'aménagement différé',
				'ZI' => 'Zone industrielle',
				'ZONE' => 'Zone',
				'ZUP' => 'Zone à urbaniser en priorité',
			);
			$this->assertEqual($this->ActionscandidatsController->viewVars['typevoie'], $expected);
	        }
/*
		function testIndex()  {
			$this->ActionscandidatsController->index();
			debug($this->ActionscandidatsController->params);
			$this->assertNotNull($this->ActionscandidatsController->params['paging']);
	        }

		function testAdd() {
			$this->ActionscandidatsController->add();
	        }

	        function testEdit() {
			$this->ActionscandidatsController->edit();
	        }

	        function test_add_edit() {
			$this->ActionscandidatsController->_add_edit();
	        }

	        function testDelete() {
			$id = '1';
			$this->ActionscandidatsController->delete($id);
			this->assertNull();

	        }

	        function testView() {
			$id = '1';
			$this->ActionscandidatsController->view();
	        }
*/
	}

?>
