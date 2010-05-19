<?php

class NataccosocfamFixture extends CakeTestFixture {
 var $name = 'Nataccosocfam';
 var $table = 'nataccosocfams';
 var $import = array( 'table' => 'nataccosocfams', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '0410',
 'name' => 'Logement',
 ),
 array(
 'id' => '2',
 'code' => '0411',
 'name' => 'Endettement',
 ),
 array(
 'id' => '3',
 'code' => '0412',
 'name' => 'Familiale',
 ),
 array(
 'id' => '4',
 'code' => '0413',
 'name' => 'Autres',
 ),
 );
}

?>