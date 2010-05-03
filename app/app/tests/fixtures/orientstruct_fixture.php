<?php

class OrientstructFixture extends CakeTestFixture {
 var $name = 'Orientstruct';
 var $table = 'orientsstructs';
 var $import = array( 'table' => 'orientsstructs', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '2',
 'personne_id' => '1',
 'typeorient_id' => '1',
 'structurereferente_id' => '1',
 'propo_algo' => null,
 'valid_cg' => '1',
 'date_propo' => '2010-03-09',
 'date_valid' => '2010-03-09',
 'statut_orient' => 'Orienté',
 'date_impression' => null,
 'daterelance' => null,
 'statutrelance' => 'E',
 'date_impression_relance' => null,
 ),
 array(
 'id' => '1',
 'personne_id' => '1',
 'typeorient_id' => '1',
 'structurereferente_id' => '1',
 'propo_algo' => null,
 'valid_cg' => null,
 'date_propo' => '2010-03-09',
 'date_valid' => '2010-03-09',
 'statut_orient' => 'Orienté',
 'date_impression' => null,
 'daterelance' => null,
 'statutrelance' => 'E',
 'date_impression_relance' => null,
 ),
 );
}

?>