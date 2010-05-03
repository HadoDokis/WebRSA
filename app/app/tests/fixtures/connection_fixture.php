<?php

class ConnectionFixture extends CakeTestFixture {
 var $name = 'Connection';
 var $table = 'connections';
 var $import = array( 'table' => 'connections', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '10',
 'user_id' => '6',
 'php_sid' => 'c9dbb4e03e95c9acff4af83ec65189a0',
 'created' => '2010-03-10 12:27:04',
 'modified' => '2010-03-10 12:27:04',
 ),
 );
}

?>