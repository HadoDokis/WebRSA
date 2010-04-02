<?php

	class FoyerFixture extends CakeTestFixture {
		 var $name = 'Foyer';
		 var $table = 'foyers';
		 var $import = array('table' => 'foyers', 'connection' => 'default', 'records' => false);
		 var $records = array(
								array(
										'id' => '1',
										'dossier_rsa_id' => '1',
										'sitfam' => null,
										'ddsitfam' => null,
										'typeocclog' => null,
										'mtvallocterr' => null,
										'mtvalloclog' => null,
										'contefichliairsa' => null,
								),
								array(
										'id' => '2',
										'dossier_rsa_id' => '2',
										'sitfam' => null,
										'ddsitfam' => null,
										'typeocclog' => null,
										'mtvallocterr' => null,
										'mtvalloclog' => null,
										'contefichliairsa' => null,
								),
		 );
	}

?>
