<?php

class NatmobFixture extends CakeTestFixture {
 var $name = 'Natmob';
 var $table = 'natmobs';
 var $import = array( 'table' => 'natmobs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '2501',
 'name' => 'Sur la commune',
 ),
 array(
 'id' => '2',
 'code' => '2502',
 'name' => 'Sur le département',
 ),
 array(
 'id' => '3',
 'code' => '2503',
 'name' => 'Sur un autre département',
 ),
 );
}

?>