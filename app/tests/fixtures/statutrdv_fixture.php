<?php

class StatutrdvFixture extends CakeTestFixture {
 var $name = 'Statutrdv';
 var $table = 'statutsrdvs';
 var $import = array( 'table' => 'statutsrdvs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'En cours',
 ),
 );
}

?>