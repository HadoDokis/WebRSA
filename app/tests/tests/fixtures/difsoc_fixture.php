<?php

class DifsocFixture extends CakeTestFixture {
 var $name = 'Difsoc';
 var $table = 'difsocs';
 var $import = array( 'table' => 'difsocs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'code' => '0401',
 'name' => 'Aucune difficulté',
 ),
 array(
 'id' => '2',
 'code' => '0402',
 'name' => 'Santé',
 ),
 array(
 'id' => '3',
 'code' => '0403',
 'name' => 'Reconnaissance de la qualité du travailleur handicapé',
 ),
 array(
 'id' => '4',
 'code' => '0404',
 'name' => 'Lecture, écriture ou compréhension du fançais',
 ),
 array(
 'id' => '5',
 'code' => '0405',
 'name' => 'Démarches et formalités administratives',
 ),
 array(
 'id' => '6',
 'code' => '0406',
 'name' => 'Endettement',
 ),
 array(
 'id' => '7',
 'code' => '0407',
 'name' => 'Autres',
 ),
 );
}

?>