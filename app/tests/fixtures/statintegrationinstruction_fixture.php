<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StatintegrationinstructionFixture extends CakeAppTestFixture {
		var $name = 'Statintegrationinstruction';
		var $table = 'statintegrationinstruction';
		var $import = array( 'table' => 'statintegrationinstruction', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'nom_fichier' => 'NRSACGIR_IRSACG_201101.RCV',
				'activites' => '438601',
				'adresses' => '220062',
				'adresses_foyers' => '272750',
				'aidesagricoles' => '0',
				'allocationssoutienfamilial' => '57750',
				'conditionsactivitesprealables' => '44',
				'creancesalimentaires' => '144891',
				'detailsaccosocfams' => '1714',
				'detailsaccosocindis' => '2320',
				'detailsdifdisps' => '6798',
				'detailsdiflogs' => '7150',
				'detailsdifsocs' => '8072',
				'detailsnatmobs' => '14498',
				'detailsressourcesmensuelles' => '294962',
				'dossiers_rsa' => '157130',
				'dossierscaf' => '441259',
				'dsps' => '15257',
				'foyers' => '157131',
				'grossesses' => '15825',
				'identificationsflux' => '114',
				'informationseti' => '137',
				'infosagricoles' => '1',
				'modescontact' => '12662',
				'orientations' => '3',
				'paiementsfoyers' => '21314',
				'parcours' => '1871',
				'personnes' => '445252',
				'prestations' => '884039',
				'rattachements' => '336878',
				'ressources' => '247569',
				'ressourcesmensuelles' => '290018',
				'suivisappuisorientation' => '6126',
				'suivisinstruction' => '16801',
				'titres_sejour' => '8362',
				'transmissionsflux' => '35',
			),
		);
	}

?>