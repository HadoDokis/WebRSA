<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Dossier');

	class DossierTestCase extends CakeAppModelTestCase {

		function testBeforeSave() {
			$result = $this->Dossier->beforeSave();
			$this->assertTrue($result);
		}

		function testFindByZones() {
			//debug($this->Dossier->findByZones());
			$zonegeo = array(
				array(
					'id' => '1',
					'codeinsee' => '34090',
					'libelle' => 'Pole Montpellier-Nord',
				),
				array(
					'id' => '2',
					'codeinsee' => '34070',
					'libelle' => 'Pole Montpellier Sud-Est',
				),
				array(
					'id' => '3',
					'codeinsee' => '34080',
					'libelle' => 'Pole Montpellier Ouest',
				),
			);
			$result = $this->Dossier->findByZones($zonegeo, null);		
			$this->assertTrue($result);
		}

		function testSearch() {

			$params = array(/*
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
						'foyerid' => null,
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
					),*/
			);
			// Si $params est initialisé, la classe 'Sanitize' n'est pas trouvée
			// Fatal error: Class 'Sanitize' not found in /home/localhost/www/webrsa/app/models/dossier.php on line 250 
			$result = $this->Dossier->search(null, null, $params);
			var_dump($result);
			//$result = $this->Dossier->search($zonegeo, null);
		}
	}
?>
