<?php

class AccoemploiFixture extends CakeTestFixture {
 var $name = 'Accoemploi';
 var $table = 'accoemplois';
 var $import = array( 'table' => 'accoemplois', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '1801',
 'name' => 'Pas d\'accompagnement',
 ),
 array(
 'id' => '2',
 'code' => '1802',
 'name' => 'Pole emploi',
 ),
 array(
 'id' => '3',
 'code' => '1803',
 'name' => 'Autres',
 ),
 );
}

?>