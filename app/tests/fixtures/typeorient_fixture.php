<?php

	class TypeorientFixture extends CakeTestFixture {
		var $name = 'Typeorient';
		var $table = 'typesorients';
		var $import = array( 'table' => 'typesorients', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'parentid' => null,
				'lib_type_orient' => 'Socioprofessionnelle',
				'modele_notif' => 'proposition_orientation_vers_pole_emploi',
				'modele_notif_cohorte' => 'proposition_orientation_vers_pole_emploi_cohorte',
			),
			array(
				'id' => '2',
				'parentid' => null,
				'lib_type_orient' => 'Social',
				'modele_notif' => 'proposition_orientation_vers_SS_ou_PDV',
				'modele_notif_cohorte' => 'proposition_orientation_vers_SS_ou_PDV_cohorte',
			),
			array(
				'id' => '3',
				'parentid' => null,
				'lib_type_orient' => 'Emploi',
				'modele_notif' => 'proposition_orientation_vers_SS_ou_PDV',
				'modele_notif_cohorte' => 'proposition_orientation_vers_SS_ou_PDV_cohorte',
			),
			array(
				'id' => '4',
				'parentid' => null,
				'lib_type_orient' => 'A sélectionner',
				'modele_notif' => 'aucun',
				'modele_notif_cohorte' => 'aucun',
			),
			array(
				'id' => '5',
				'parentid' => null,
				'lib_type_orient' => 'CAF',
				'modele_notif' => 'aucun',
				'modele_notif_cohorte' => 'aucun',
			),
		);
	}

?>
