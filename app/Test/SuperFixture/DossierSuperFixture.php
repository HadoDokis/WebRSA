<?php
	/**
	 * Code source de la classe SuperFixture.
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */

	App::uses('SuperFixtureInterface', 'SuperFixture.Interface');

	/**
	 * SuperFixture
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */
	class DossierSuperFixture implements SuperFixtureInterface {
		/**
		 * Fixtures à charger à vide
		 * 
		 * @var array
		 */
		public static $fixtures = array(
			'app.Dossier',
			'app.Avispcgdroitrsa',
			'app.Detaildroitrsa',
			'app.Foyer',
			'app.Jeton',
			'app.Situationdossierrsa',
			'app.Infofinanciere',
			'app.Suiviinstruction',
			'app.Personne',
			'app.Dernierdossierallocataire',
			'app.Prestation',
			'app.Dossierpcg66',
			'app.Decisiondossierpcg66',
			'app.Personnepcg66',
			'app.Traitementpcg66',
			'app.Decisionpdo',
			'app.Typepdo',
			'app.Detailcalculdroitrsa',
			'app.Calculdroitrsa',
			'app.Adresse',
			'app.AdresseCanton',
			'app.Canton',
			'app.Adressefoyer',
			'app.User',
			'app.Typeorient',
			'app.Structurereferente',
			'app.Referent',
			'app.Orientstruct',
		);
		
		/**
		 * Permet d'obtenir les informations nécéssaire pour charger la SuperFixture
		 * 
		 * @return array
		 */
		public static function getData() {
			return array();
		}
	}
