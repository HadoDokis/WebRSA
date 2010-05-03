<?php

class TypocontratFixture extends CakeTestFixture {
 var $name = 'Typocontrat';
 var $table = 'typoscontrats';
 var $import = array( 'table' => 'typoscontrats', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'lib_typo' => 'Premier contrat',
 ),
 array(
 'id' => '2',
 'lib_typo' => 'Renouvellement',
 ),
 array(
 'id' => '3',
 'lib_typo' => 'Redéfinition',
 ),
 );
}

?>