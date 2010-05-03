<?php

class JetonFixture extends CakeTestFixture {
 var $name = 'Jeton';
 var $table = 'jetons';
 var $import = array( 'table' => 'jetons', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '7',
 'dossier_id' => '1',
 'php_sid' => '385d9429f7e184ace3c3793d00f860e6',
 'user_id' => '6',
 'created' => '2010-03-09 11:43:01',
 'modified' => '2010-03-09 12:11:01',
 ),
 );
}

?>