<?php

class DifdispFixture extends CakeTestFixture {
 var $name = 'Difdisp';
 var $table = 'difdisps';
 var $import = array( 'table' => 'difdisps', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '0501',
 'name' => 'Aucune difficulté',
 ),
 array(
 'id' => '2',
 'code' => '0502',
 'name' => 'La garde d\'enfant de moins de 6 ans',
 ),
 array(
 'id' => '3',
 'code' => '0503',
 'name' => 'La garde d\'enfant(s) de plus de 6 ans',
 ),
 array(
 'id' => '4',
 'code' => '0504',
 'name' => 'La garde d\'enfant(s) ou de proche(s) invalide(s)',
 ),
 array(
 'id' => '5',
 'code' => '0505',
 'name' => 'La charge de proche(s) dépendant(s)',
 ),
 );
}

?>