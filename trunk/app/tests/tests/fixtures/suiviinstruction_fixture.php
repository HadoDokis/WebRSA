<?php

class SuiviinstructionFixture extends CakeTestFixture {
 var $name = 'Suiviinstruction';
 var $table = 'suivisinstruction';
 var $import = array( 'table' => 'suivisinstruction', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'dossier_rsa_id' => '1',
 'etatirsa' => '03',
 'date_etat_instruction' => '2010-03-09',
 'nomins' => 'auzolat',
 'prenomins' => 'arnaud',
 'numdepins' => '030',
 'typeserins' => 'F',
 'numcomins' => '189',
 'numagrins' => '11',
 ),
 );
}

?>