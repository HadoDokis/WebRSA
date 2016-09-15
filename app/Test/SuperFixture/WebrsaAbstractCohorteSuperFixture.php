<?php
	/**
	 * Code source de la classe WebrsaAbstractCohorteSuperFixture.
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */

	App::uses('SuperFixtureInterface', 'SuperFixture.Interface');

	/**
	 * WebrsaAbstractCohorteSuperFixture
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */
	class WebrsaAbstractCohorteSuperFixture implements SuperFixtureInterface {
		/**
		 * Fixtures à charger en plus pour un bon fonctionnement
		 * 
		 * @var array
		 */
		public static $fixtures = array(
			'Personne',
			'Typeorient',
			'Structurereferente',
			'Referent',
			'User',
			'Nonoriente66',
		);
		
		/**
		 * Permet d'obtenir les informations nécéssaire pour charger la SuperFixture
		 * 
		 * @return array
		 */
		public static function getData() {
			return array(
				'Orientstruct' => array(
					array(
						'personne_id' => 1,
						'typeorient_id' => 1,
						'structurereferente_id' => 1,
						'propo_algo' => null,
						'valid_cg' => null,
						'date_propo' => null,
						'date_valid' => '2009-06-24',
						'statut_orient' => 'Orienté',
						'date_impression' => null,
						'daterelance' => null,
						'statutrelance' => null,
						'date_impression_relance' => null,
						'referent_id' => null,
						'etatorient' => null,
						'rgorient' => 1,
						'structureorientante_id' => null,
						'referentorientant_id' => null,
						'user_id' => null,
						'haspiecejointe' => '0',
						'origine' => 'manuelle',
						'typenotification' => null,
					)
				),
				'Serviceinstructeur' => array(
					array(
						'lib_service' => 'test',
						'type_voie' => 'test',
						'code_insee' => '12345',
						'numdepins' => '123',
						'typeserins' => 'S',
						'numcomins' => '123',
						'numagrins' => '12',
					)
				),
				'Structurereferente' => array(
					array(
						'lib_struc' => 'test',
						'typeorient_id' => 1,
					)
				)
			);
		}
	}

