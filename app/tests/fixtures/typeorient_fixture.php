<?php

class TypeorientFixture extends CakeTestFixture {
 var $name = 'Typeorient';
 var $table = 'typesorients';
 var $import = array( 'table' => 'typesorients', 'connection' => 'default', 'records' => false);
 var $records = array(
 array(
 'id' => '1',
 'parentid' => null,
 'lib_type_orient' => 'Emploi',
 'modele_notif' => 'notif_orientation_cg66_mod3',
 'modele_notif_cohorte' => null,
 ),
 array(
 'id' => '2',
 'parentid' => null,
 'lib_type_orient' => 'Socioprofessionnelle',
 'modele_notif' => 'notif_orientation_cg66_mod1',
 'modele_notif_cohorte' => null,
 ),
 array(
 'id' => '3',
 'parentid' => null,
 'lib_type_orient' => 'Social',
 'modele_notif' => 'notif_orientation_cg66_mod2',
 'modele_notif_cohorte' => null,
 ),
 );
}

?>