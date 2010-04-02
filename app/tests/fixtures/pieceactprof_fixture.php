<?php

class PieceactprofFixture extends CakeTestFixture {
 var $name = 'Pieceactprof';
 var $table = 'piecesactsprofs';
 var $import = array( 'table' => 'piecesactsprofs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Convention individuelle (pour les contrats aidés) *',
 ),
 array(
 'id' => '2',
 'libelle' => 'Contrat de travail (pour les contrats SIAE) *',
 ),
 array(
 'id' => '3',
 'libelle' => 'Facture ou devis',
 ),
 );
}

?>