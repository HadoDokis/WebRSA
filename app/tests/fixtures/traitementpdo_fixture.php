<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class TraitementpdoFixture extends CakeAppTestFixture {
		var $name = 'Traitementpdo';
		var $table = 'traitementspdos';
		var $import = array( 'table' => 'traitementspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'descriptionpdo_id' => '1',
				'traitementtypepdo_id' => '1',
				'datereception' => null,
				'datedepart' => null,
				'hascourrier' => '0',
				'hasrevenu' => '0',
				'haspiecejointe' => '0',
				'hasficheanalyse' => '0',
				'saisonnier' => '0',
				'nrmrcs' => null,
				'dtdebutactivite' => null,
				'raisonsocial' => null,
				'dtdebutperiode' => null,
				'datefinperiode' => null,
				'dtprisecompte' => null,
				'dtecheance' => null,
				'forfait' => null,
				'mtaidesub' => null,
				'chaffvnt' => null,
				'chaffsrv' => null,
				'benefoudef' => null,
				'ammortissements' => null,
				'salaireexploitant' => null,
				'provisionsnonded' => null,
				'moinsvaluescession' => null,
				'autrecorrection' => null,
				'nbmoisactivite' => null,
				'mnttotalpriscompte' => null,
				'revenus' => null,
				'benefpriscompte' => null,
				'aidesubvreint' => null,
				'dureedepart' => null,
				'dureefinperiode' => null,
				'dateecheance' => null,
				'daterevision' => null,
				'personne_id' => null,
				'ficheanalyse' => null,
				'clos' => '0'
			)
		);
	}

?>
