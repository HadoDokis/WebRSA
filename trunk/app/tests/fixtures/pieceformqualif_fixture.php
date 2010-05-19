<?php

class PieceformqualifFixture extends CakeTestFixture {
 var $name = 'Pieceformqualif';
 var $table = 'piecesformsqualifs';
 var $import = array( 'table' => 'piecesformsqualifs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Attestation d\'entrée en formation',
 ),
 array(
 'id' => '2',
 'libelle' => 'Devis nominatif détaillé précisant l\'intitulé de la formation, son lieu, dates prévisionnelles de début et fin d\'action, durée en heure, jours et mois, contenu (heures et modules), l\'organisation de la formation, le coût global ainsi que la participation éventuelle du stagiaire',
 ),
 array(
 'id' => '3',
 'libelle' => 'Facture ou devis',
 ),
 );
}

?>