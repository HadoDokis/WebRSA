<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class StatintegrationbeneficiaireFixture extends CakeAppTestFixture {
		var $name = 'Statintegrationbeneficiaire';
		var $table = 'statintegrationbeneficiaire';
		var $import = array( 'table' => 'statintegrationbeneficiaire', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'nom_fichier' => 'NRSACGIM_RSABEM_20110204_B0512204.RCV',
				'activites' => '448250',
				'adresses' => '225754',
				'adresses_foyers' => '279162',
				'allocationssoutienfamilial' => '58793',
				'anomalies' => '0',
				'avispcgdroitrsa' => '1641',
				'avispcgpersonnes' => '3272',
				'calculsdroitsrsa' => '237717',
				'condsadmins' => '1455',
				'controlesadministratifs' => '468684',
				'creances' => '182',
				'creancesalimentaires' => '145920',
				'derogations' => '1113',
				'detailscalculsdroitsrsa' => '109780',
				'detailsdroitsrsa' => '158963',
				'detailsressourcesmensuelles' => '305377',
				'dossiers_rsa' => '160352',
				'dossierscaf' => '451919',
				'evenements' => '82',
				'foyers' => '160353',
				'grossesses' => '16554',
				'identificationsflux' => '115',
				'infosagricoles' => '1',
				'liberalites' => '0',
				'personnes' => '454546',
				'prestations' => '904868',
				'rattachements' => '343801',
				'reducsrsa' => '195',
				'ressources' => '253459',
				'ressourcesmensuelles' => '298871',
				'situationsdossiersrsa' => '158963',
				'suspensionsdroits' => '1652',
				'suspensionsversements' => '11494',
				'transmissionsflux' => '36',
			),
		);
	}

?>