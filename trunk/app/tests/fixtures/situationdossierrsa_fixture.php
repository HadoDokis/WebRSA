<?php

class SituationdossierrsaFixture extends CakeTestFixture {
 var $name = 'Situationdossierrsa';
 var $table = 'situationsdossiersrsa';
 var $import = array( 'table' => 'situationsdossiersrsa', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'dossier_rsa_id' => '1',
 'etatdosrsa' => 'Z',
 'dtrefursa' => null,
 'moticlorsa' => null,
 'dtclorsa' => null,
 ),
 );
}

?>