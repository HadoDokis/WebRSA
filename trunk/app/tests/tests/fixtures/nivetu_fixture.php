<?php

class NivetuFixture extends CakeTestFixture {
 var $name = 'Nivetu';
 var $table = 'nivetus';
 var $import = array( 'table' => 'nivetus', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '1201',
 'name' => 'Niveau I/II: enseignement supérieur',
 ),
 array(
 'id' => '2',
 'code' => '1202',
 'name' => 'Niveau III: BAC + 2',
 ),
 array(
 'id' => '3',
 'code' => '1203',
 'name' => 'Niveau IV: BAC ou équivalent',
 ),
 array(
 'id' => '4',
 'code' => '1204',
 'name' => 'Niveau V: CAP/BEP',
 ),
 array(
 'id' => '5',
 'code' => '1205',
 'name' => 'Niveau Vbis: fin de scolarité obligatoire',
 ),
 array(
 'id' => '6',
 'code' => '1206',
 'name' => 'Niveau VI: pas de niveau',
 ),
 array(
 'id' => '7',
 'code' => '1207',
 'name' => 'Niveau VII: jamais scolarisé',
 ),
 );
}

?>