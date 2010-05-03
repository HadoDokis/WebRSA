<?php

class PiecepermisbFixture extends CakeTestFixture {
 var $name = 'Piecepermisb';
 var $table = 'piecespermisb';
 var $import = array( 'table' => 'piecespermisb', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'libelle' => 'Attestation sur l’honneur ou Cerfa 02',
 ),
 array(
 'id' => '2',
 'libelle' => 'Devis ou facture',
 ),
 );
}

?>