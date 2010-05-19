<?php

class PieceacqmatprofFixture extends CakeTestFixture {
 var $name = 'Pieceacqmatprof';
 var $table = 'piecesacqsmatsprofs';
 var $import = array( 'table' => 'piecesacqsmatsprofs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Attestation d\'entrée en formation ou contrat de travail',
 ),
 array(
 'id' => '2',
 'libelle' => 'Facture ou devis (en rapport avec le poste de travail',
 ),
 array(
 'id' => '3',
 'libelle' => 'Justificatif de la liste de matériel nécessaire',
 ),
 );
}

?>