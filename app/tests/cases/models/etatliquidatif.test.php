<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model','Etatliquidatif');
	App::import('Core', 'Sanitize');

	class EtatliquidatifTestCase extends CakeAppModelTestCase
	{

		function testListeApres() {
			$expected=array(
				'fields' => array(
					0 => 'Apre.id',
					1 => 'Apre.personne_id',
					2 => 'Apre.numeroapre',
					3 => 'Apre.statutapre',
					4 => 'Apre.datedemandeapre',
					5 => 'Apre.mtforfait',
					6 => 'Apre.montantaverser',
					7 => 'Apre.nbenf12',
					8 => 'Apre.nbpaiementsouhait',
					9 => 'Apre.montantdejaverse',
					10 => 'Personne.nom',
					11 => 'Personne.prenom',
					12 => 'Personne.qual',
					13 => 'Dossier.numdemrsa',
					14 => 'Adresse.locaadr',
					15 => 'Adresse.numvoie',
					16 => 'Adresse.nomvoie',
					17 => 'Adresse.complideadr',
					18 => 'Adresse.compladr',
					19 => 'Adresse.typevoie',
					20 => 'Adresse.codepos'
				),
				'joins' => array(
					0 => array(
				        'table' => 'personnes',
				        'alias' => 'Personne',
				        'type' => 'INNER',
				        'foreignKey' => '',
				        'conditions' => array(
							0 => 'Apre.personne_id = Personne.id'
						)
				    ),
					1 => array(
				        'table' => 'foyers',
				        'alias' => 'Foyer',
				        'type' => 'INNER',
				        'foreignKey' => '',
				        'conditions' => array(
				                0 => 'Personne.foyer_id = Foyer.id'
						)
					),
					2 => array(
				        'table' => 'dossiers',
				        'alias' => 'Dossier',
				        'type' => 'INNER',
				        'foreignKey' => '',
				        'conditions' => array(
				                0 => 'Foyer.dossier_id = Dossier.id'
				            )
				    ),
					3 => array(
						'table' => 'adressesfoyers',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.id = Adressefoyer.foyer_id',
							1 => 'Adressefoyer.rgadr = \'01\'',
							2 => 'Adressefoyer.id IN ( SELECT MAX(adressesfoyers.id)
								FROM adressesfoyers
								WHERE adressesfoyers.rgadr = \'01\'
								GROUP BY adressesfoyers.foyer_id
							)'
						)
					),
					4 => array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Adresse.id = Adressefoyer.adresse_id'
						)
					),
					5 => array(
						'table' => 'formsqualifs',
						'alias' => 'Formqualif',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Formqualif.apre_id'
						)
					),
					6 => array(
						'table' => 'formspermsfimo',
						'alias' => 'Formpermfimo',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Formpermfimo.apre_id'
						)
					),
					7 => array(
						'table' => 'actsprofs',
						'alias' => 'Actprof',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Actprof.apre_id'
						)
					),
					8 => array(
						'table' => 'permisb',
						'alias' => 'Permisb',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Permisb.apre_id'
						)
					),
					9 => array(
						'table' => 'amenagslogts',
						'alias' => 'Amenaglogt',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Amenaglogt.apre_id'
						)
					),
					10 => array(
						'table' => 'accscreaentr',
						'alias' => 'Acccreaentr',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Acccreaentr.apre_id'
						)
					),
					11 => array(
						'table' => 'acqsmatsprofs',
						'alias' => 'Acqmatprof',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Acqmatprof.apre_id'
						)
					),
					12 => array(
						'table' => 'locsvehicinsert',
						'alias' => 'Locvehicinsert',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Apre.id = Locvehicinsert.apre_id'
						)
					),
				),
				'recursive' => 1,
				'conditions' => array(
					0 => 'ici ma condition',
					'Apre.eligibiliteapre' => 'O'
				)
			);
			$result=$this->Etatliquidatif->listeApres("ici ma condition");
			$this->assertEqual($result,$expected);
		}

		function testListeApresEtatLiquidatif() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeEtatLiquidatif($conditions, $etatliquidatif_id);
			$this->assertFalse($result);
		}
	
		function testListeApresEtatLiquidatifNonTermine() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeApresEtatLiquidatifNonTermine($conditions, $etatliquidatif_id);
			$expected = array(
				'fields' => array(
					'0' => 'Apre.id',
					'1' => 'Apre.personne_id',
					'2' => 'Apre.numeroapre',
					'3' => 'Apre.statutapre',
					'4' => 'Apre.datedemandeapre',
					'5' => 'Apre.mtforfait',
					'6' => 'Apre.montantaverser',
					'7' => 'Apre.nbenf12',
					'8' => 'Apre.nbpaiementsouhait',
					'9' => 'Apre.montantdejaverse',
					'10' => 'Personne.nom',
					'11' => 'Personne.prenom',
					'12' => 'Personne.qual',
					'13' => 'Dossier.numdemrsa',
					'14' => 'Adresse.locaadr',
					'15' => 'Adresse.numvoie',
					'16' => 'Adresse.nomvoie',
					'17' => 'Adresse.complideadr',
					'18' => 'Adresse.compladr',
					'19' => 'Adresse.typevoie',
					'20' => 'Adresse.codepos',
					'21' => '( COALESCE( "Formqualif"."montantaide", 0 ) + COALESCE( "Formpermfimo"."montantaide", 0 ) + COALESCE( "Actprof"."montantaide", 0 ) + COALESCE( "Permisb"."montantaide", 0 ) + COALESCE( "Amenaglogt"."montantaide", 0 ) + COALESCE( "Acccreaentr"."montantaide", 0 ) + COALESCE( "Acqmatprof"."montantaide", 0 ) + COALESCE( "Locvehicinsert"."montantaide", 0 ) ) AS "Apre__montanttotal"'
				),
			'joins' => array(
					'0' => array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array('0' => 'Apre.personne_id = Personne.id'),
					),
					'1' => array(
						'table' => 'foyers',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions'=> array('0' => 'Personne.foyer_id = Foyer.id'),
					),
					'2' => array(
						'table' => 'dossiers',
				 		'alias' => 'Dossier',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array('0' => 'Foyer.dossier_id = Dossier.id'),
					),
					'3' => array(
						'table' => 'adressesfoyers',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'0' => 'Foyer.id = Adressefoyer.foyer_id',
							'1' => 'Adressefoyer.rgadr = '01'',
							'2' => 'Adressefoyer.id IN ( SELECT MAX(adressesfoyers.id) FROM adressesfoyers WHERE adressesfoyers.rgadr = '01' GROUP BY adressesfoyers.foyer_id )',
						),
					),
					'4' => array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array('0' => 'Adresse.id = Adressefoyer.adresse_id'),
					),
					'5' => array(
						'table' => 'formsqualifs',
						'alias' => 'Formqualif',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array('0' => 'Apre.id = Formqualif.apre_id'),
					),
					'6' => array(
						'table' => 'formspermsfimo',
						'alias' => 'Formpermfimo',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array('0' => 'Apre.id = Formpermfimo.apre_id'),
					),
					[7]=> array(5) { ["table"]=> string(9) "actsprofs" ["alias"]=> string(7) "Actprof" ["type"]=> string(10) "LEFT OUTER" ["foreignKey"]=> bool(false) ["conditions"]=> array(1) { [0]=> string(25) "Apre.id = Actprof.apre_id" } } [8]=> array(5) { ["table"]=> string(7) "permisb" ["alias"]=> string(7) "Permisb" ["type"]=> string(10) "LEFT OUTER" ["foreignKey"]=> bool(false) ["conditions"]=> array(1) { [0]=> string(25) "Apre.id = Permisb.apre_id" } } [9]=> array(5) { ["table"]=> string(12) "amenagslogts" ["alias"]=> string(10) "Amenaglogt" ["type"]=> string(10) "LEFT OUTER" ["foreignKey"]=> bool(false) ["conditions"]=> array(1) { [0]=> string(28) "Apre.id = Amenaglogt.apre_id" } } [10]=> array(5) { ["table"]=> string(12) "accscreaentr" ["alias"]=> string(11) "Acccreaentr" ["type"]=> string(10) "LEFT OUTER" ["foreignKey"]=> bool(false) ["conditions"]=> array(1) { [0]=> string(29) "Apre.id = Acccreaentr.apre_id" } } [11]=> array(5) { ["table"]=> string(13) "acqsmatsprofs" ["alias"]=> string(10) "Acqmatprof" ["type"]=> string(10) "LEFT OUTER" ["foreignKey"]=> bool(false) ["conditions"]=> array(1) { [0]=> string(28) "Apre.id = Acqmatprof.apre_id" } } [12]=> array(5) { ["table"]=> string(15) "locsvehicinsert" ["alias"]=> string(14) "Locvehicinsert" ["type"]=> string(10) "LEFT OUTER" ["foreignKey"]=> bool(false) ["conditions"]=> array(1) { [0]=> string(32) "Apre.id = Locvehicinsert.apre_id" } } } ["recursive"]=> int(1) ["conditions"]=> array(3) { [0]=> string(16) "ici ma condition" [1]=> string(266) "Apre.id IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE etatsliquidatifs.datecloture IS NULL AND apres_etatsliquidatifs.etatliquidatif_id = 1 )" ["Apre.eligibiliteapre"]=> string(1) "O" } }
		}

		function testListeApresEtatLiquidatifNonTerminePourVersement() {
			$conditions = 'ici ma condition';
			$etatliquidatif_id = '1';
			$result = $this->Etatliquidatif->listeApresEtatLiquidatifNonTerminePourVersement($conditions, $etatliquidatif_id);
		}

		function testListeApresSansEtatLiquidatif() {

		}

		function testListeApresPourEtatLiquidatif() {

		}

		function testHopeyra() {

		}

		function testPdf() {

		}

	}
?>
