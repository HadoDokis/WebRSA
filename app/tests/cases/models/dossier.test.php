<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Dossier');
	App::import('Core', 'Sanitize');

	class DossierTestCase extends CakeAppModelTestCase {

		function testBeforeSave() {
			$result = $this->Dossier->beforeSave();
			$this->assertTrue($result);
		}

		function testFindByZones() {
			$zonegeo = array('ighr8');
			$result = $this->Dossier->findByZones($zonegeo, false);
			$expected = array(
				  '0' => '1',
  				  '1' => '2',
   				  '2' => '3',
  				  '3' => '4',
  				  '4' => '5',
  				  '5' => '1001',
  				  '6' => '2002',
  				  '7' => '3003',
  				  '8' => '4004',
			);
			$this->assertEqual($result, $expected);

			$zonegeo = array('ighr8');
			$result = $this->Dossier->findByZones($zonegeo, true);
			$expected = array(
				  '0' => '1',
			);
			$this->assertEqual($result, $expected);
		}

		function testSearch() {

			$params = array(
				'Dossier' => array (
						'id' => '1',
						'numdemrsa' => '456807VH',
						'dtdemrsa' => '2009-05-12',
						'dtdemrmi' => null,
						'numdepinsrmi' => null,
						'typeinsrmi' => null,
						'numcominsrmi' => null,
						'numagrinsrmi' => null,
						'numdosinsrmi' => null,
						'numcli' => null,
						'numorg' => 951,
						'fonorg' => 'CAF',
						'matricule' => 000000000000000,
						'statudemrsa' => 'N',
						'typeparte' => 'CG',
						'ideparte' => '095',
						'fonorgcedmut' => null,
						'numorgcedmut' => null,
						'matriculeorgcedmut' => null,
						'ddarrmut' => null,
						'codeposanchab' => null,
						'fonorgprenmut' => null,
						'numorgprenmut' => null,
						'dddepamut' => null,
						'detaildroitrsa_id' => null,
						'avispcgdroitrsa_id' => null,
						'organisme_id' => null,
						'dernier' => '1',
					),
				'Personne' => array(
						'id' => '1',
						'foyer_id' => '1',
						'qual' => 'MR',
						'nom' => 'Dupond',
						'prenom' => 'Azerty',
						'nomnai' => null,
						'prenom2' => null,
						'prenom3' => null,
						'nomcomnai' => null,
						'dtnai' => '1979-01-24',
						'rgnai' => '1',
						'typedtnai' => null,
						'nir' => null,
						'topvalec' => null,
						'sexe' => null,
						'nati' => null,
						'dtnati' => null,
						'pieecpres' => null,
						'idassedic' => null,
						'numagenpoleemploi' => null,
						'dtinscpoleemploi' => null,
						'numfixe' => null,
						'numport' => null,
					),
				'Adresse' => array(
						'id' => '1',
						'numvoie' => null,
						'typevoie' => 'R',
						'nomvoie' => 'de lilas',
						'complideadr' => null,
						'compladr' => null,
						'lieudist' => null,
						'numcomrat' => '     ',
						'numcomptt' => 'ighr8',
						'codepos' => '23458',
						'locaadr' => 'Mont de Marsan',
						'pays' => 'FRA',
						'canton' => null,
						'typeres' => null,
						'topresetr' => null,
						'foyerid' => '1',
					),
				'Canton' => array(
						'id' => '1',
						'typevoie' => 'R',
						'nomvoie' => 'pignon sur',
						'locaadr' => 'Montpellier',
						'codepos' => 34000,
						'numcomptt' => 12345,
						'canton' => 1,
						'zonegeographique_id' => '1',
					),
				'Detailcalculdroitrsa' => array(
						'id' => '1',
						'detaildroitrsa_id' => '1',
						'natpf' => null,
						'sousnatpf' => null,
						'ddnatdro' => null,
						'dfnatdro' => null,
						'mtrsavers' => null,
						'dtderrsavers' => null,
					),
				'Serviceinstructeur' => array(
						'id' => '1',
						'lib_service' => 'Service 1',
						'num_rue' => '16',
						'nom_rue' => 'collines',
						'complement_adr' => null,
						'code_insee' => '30900',
						'code_postal' => '30000',
						'ville' => 'Nimes',
						'numdepins' => '034',
						'typeserins' => 'P',
						'numcomins' => '111',
						'numagrins' => '11',
						'type_voie' => 'ARC',
					),
			);
			$filtre_zone_geo = true;
			$mesCodesInsee = array('30900');
			$result = $this->Dossier->search($mesCodesInsee, $filtre_zone_geo, $params);
			$this->assertTrue($result);
		}

	}
?>
