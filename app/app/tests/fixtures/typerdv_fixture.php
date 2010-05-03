<?php

class TyperdvFixture extends CakeTestFixture {
 var $name = 'Typerdv';
 var $table = 'typesrdv';
 var $import = array( 'table' => 'typesrdv', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Pour Contrat d\'insertion',
 'modelenotifrdv' => 'modele_convoc_ci',
 ),
 array(
 'id' => '2',
 'libelle' => 'Pour l\'orientation',
 'modelenotifrdv' => 'modele_convoc_orient',
 ),
 );
}

?>