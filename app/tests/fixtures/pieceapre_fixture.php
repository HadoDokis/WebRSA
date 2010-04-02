<?php

class PieceapreFixture extends CakeTestFixture {
 var $name = 'Pieceapre';
 var $table = 'piecesapre';
 var $import = array( 'table' => 'piecesapre', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '4',
 'libelle' => 'Attestation CAF datant du dernier mois de prestation versée',
 ),
 array(
 'id' => '5',
 'libelle' => 'Curriculum vitae',
 ),
 array(
 'id' => '6',
 'libelle' => 'Lettre motivée de l\'allocataire détaillant les besoins',
 ),
 array(
 'id' => '7',
 'libelle' => 'RIB de l\'allocataire ou de l\'organisme',
 ),
 );
}

?>