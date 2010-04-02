<?php

class TypeactionFixture extends CakeTestFixture {
 var $name = 'Typeaction';
 var $table = 'typesactions';
 var $import = array( 'table' => 'typesactions', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Facilités offertes',
 ),
 array(
 'id' => '2',
 'libelle' => 'Autonomie sociale',
 ),
 array(
 'id' => '3',
 'libelle' => 'Logement',
 ),
 array(
 'id' => '4',
 'libelle' => 'Insertion professionnelle (stage, prestation, formation',
 ),
 array(
 'id' => '5',
 'libelle' => 'Emploi',
 ),
 );
}

?>