<?php

class GroupFixture extends CakeTestFixture {
 var $name = 'Group';
 var $table = 'groups';
 var $import = array( 'table' => 'groups', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'name' => 'Administrateurs',
 'parent_id' => '0',
 ),
 array(
 'id' => '2',
 'name' => 'Utilisateurs',
 'parent_id' => '0',
 ),
 array(
 'id' => '3',
 'name' => 'Sous_Administrateurs',
 'parent_id' => '1',
 ),
 );
}

?>