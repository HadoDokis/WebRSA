<?php

	class PeriodeimmersionFixture extends CakeTestFixture {
		var $name = 'Periodeimmersion';
		var $table = 'periodesimmersion';
		var $import = array( 'table' => 'periodesimmersion', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'cui_id' => '1',
				'nomentaccueil' => 'nomentacceuil?',
				'numvoieentaccueil' => null,
				'typevoieentaccueil' => 'R?',
				'nomvoieentaccueil' => 'nomvoieentacceuil?',
				'compladrentaccueil' => null,
				'numtelentaccueil' => null,
				'emailentaccueil' => null,
				'codepostalentaccueil' => '34000',
				'villeentaccueil' => 'Montpellier',
				'siretentaccueil' => null,
				'activiteentaccueil' => null,
				'datedebperiode' => null,
				'datefinperiode' => null,
				'nbjourperiode' => null,
				'codeposteaffectation' => null,
				'objectifimmersion' => null,
				'datesignatureimmersion' => null,
			)
		);
	}

?>
