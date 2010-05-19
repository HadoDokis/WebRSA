<?php

class PieceamenaglogtFixture extends CakeTestFixture {
 var $name = 'Pieceamenaglogt';
 var $table = 'piecesamenagslogts';
 var $import = array( 'table' => 'piecesamenagslogts', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Bail ou contrat de location',
 ),
 array(
 'id' => '2',
 'libelle' => 'Devis pour les frais d\'agence',
 ),
 array(
 'id' => '3',
 'libelle' => 'Devis ou facture frais de déménagement',
 ),
 array(
 'id' => '4',
 'libelle' => 'Contrat ou devis assurance habitation',
 ),
 array(
 'id' => '5',
 'libelle' => 'Facture ouverture compteurs EDF/GDF',
 ),
 array(
 'id' => '6',
 'libelle' => 'Versement caution logement',
 ),
 array(
 'id' => '7',
 'libelle' => 'Facture',
 ),
 );
}

?>