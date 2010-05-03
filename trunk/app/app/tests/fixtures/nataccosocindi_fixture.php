<?php

class NataccosocindiFixture extends CakeTestFixture {
 var $name = 'Nataccosocindi';
 var $table = 'nataccosocindis';
 var $import = array( 'table' => 'nataccosocindis', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '0415',
 'name' => 'Pas d\'accompagnement individuel',
 ),
 array(
 'id' => '2',
 'code' => '0416',
 'name' => 'Santé',
 ),
 array(
 'id' => '3',
 'code' => '0417',
 'name' => 'Emploi',
 ),
 array(
 'id' => '4',
 'code' => '0418',
 'name' => 'Insertion professionnelle',
 ),
 array(
 'id' => '5',
 'code' => '0419',
 'name' => 'Formation',
 ),
 array(
 'id' => '6',
 'code' => '0420',
 'name' => 'Autres',
 ),
 );
}

?>