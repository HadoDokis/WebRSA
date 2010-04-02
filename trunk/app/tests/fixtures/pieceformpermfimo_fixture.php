<?php

class PieceformpermfimoFixture extends CakeTestFixture {
 var $name = 'Pieceformpermfimo';
 var $table = 'piecesformspermsfimo';
 var $import = array( 'table' => 'piecesformspermsfimo', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Photocopie du permis de conduire',
 ),
 array(
 'id' => '2',
 'libelle' => 'Devis nominatif détaillé précisant l\'intitulé de la formation, son lieu, dates prévisionnelles de début et fin d\'action, durée en heure jours et mois, contenu (heures et modules), l\'organisation de la formation, le coût global ainsi que la participation éventuelle du stagiaire.',
 ),
 array(
 'id' => '3',
 'libelle' => 'Evaluation des connaissances et compétences professionnelles (ECCP)',
 ),
 array(
 'id' => '4',
 'libelle' => 'Facture ou devis',
 ),
 );
}

?>