<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Zonegeographique');

	class ZonegeographiqueTestCase extends CakeAppModelTestCase {

		function testListeCodesInseeLocalites() {
			$result = $this->Zonegeographique->listeCodesInseeLocalites(null, $filtre_zone_geo = true);
			$this->assertEqual(array(), $result);

			$codesFiltres = '34090';
			$result = $this->Zonegeographique->listeCodesInseeLocalites($codesFiltres, $filtre_zone_geo = true);
			$expected = array('34090' => '34090 Pole Montpellier-Nord');
			$this->assertEqual($result, $expected);

			$codesFiltres = '34080';
			$result = $this->Zonegeographique->listeCodesInseeLocalites($codesFiltres, $filtre_zone_geo = true);
			$expected = array('34080' => '34080 Pole Montpellier Ouest');
			$this->assertEqual($result, $expected);

			$codesFiltres = '34070';
			$result = $this->Zonegeographique->listeCodesInseeLocalites($codesFiltres, $filtre_zone_geo = true);
			$expected = array('34070' => '34070 Pole Montpellier Sud-Est');
			$this->assertEqual($result, $expected);

			$codesFiltres = '31337';
			$result = $this->Zonegeographique->listeCodesInseeLocalites($codesFiltres, $filtre_zone_geo = true);
			$this->assertEqual(array(), $result);
		}
	}

?>
