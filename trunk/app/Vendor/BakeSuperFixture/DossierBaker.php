<?php
	/**
	 * DossierBaker file
	 *
	 * PHP 5.3
	 *
	 * @package SuperFixture
	 * @subpackage Test.Case.Utility.SuperFixture
	 */

	App::uses('BSFObject', 'SuperFixture.Utility');
	App::uses('BakeSuperFixtureInterface', 'SuperFixture.Interface');
	
	$requires = array(
		'Dossier', 'Foyer', 'Personne'
	);
	foreach ($requires as $require) {
		require_once 'Element'.DS.$require.'ElementBaker.php';
	}
	
	require_once 'WebrsaBaker.php';

	/**
	 * Generateur de SuperFixture
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */
	class DossierBaker extends WebrsaBaker implements BakeSuperFixtureInterface
	{
		/**
		 * Callback après getData
		 * @params array BSFOject
		 */
		public function afterGetData(array $datas) {
			foreach (self::find('Personne', $datas) as $personne) {
				foreach (self::find('Prestation', $personne->contain) as $prestation) {
					if ($prestation->fields['rolepers']['value'] !== 'DEM') {
						continue;
					}
					
					if (!isset($prestation->contain)) {
						$personne->contain = array();
					}
					
					$personne->contain[] = $this->getPersonneOrientstruct();
					break;
				}
			}
			
			return $datas;
		}
		
		/**
		 * @return \BSFObject
		 */
		public function getPersonneOrientstruct() {
			$orientstruct = new BSFObject('Orientstruct');
			$orientstruct->fields = array(
			   'typeorient_id' => array('auto' => true, 'foreignkey' => $this->Typeorient->getName()),
			   'structurereferente_id' => array('auto' => true, 'foreignkey' => $this->Structurereferente->getName()),
			   'date_valid' => array('auto' => true),
			   'statut_orient' => array('value' => 'Orienté'),
			   'referent_id' => array('auto' => true, 'foreignkey' => $this->Referent->getName()),
			   'etatorient' => array('value' => 'proposition'),
			   'rgorient' => array('value' => '1'),
			   'referentorientant_id' => array('auto' => true, 'foreignkey' => $this->Referent->getName()),
			   'structureorientante_id' => array('auto' => true, 'foreignkey' => $this->Structurereferente->getName()),
			   'user_id' => array('auto' => true, 'foreignkey' => $this->User->getName()),
			   'haspiecejointe' => array('value' => '0'),
			   'origine' => array('value' => 'manuelle'),
			);
			
			return $orientstruct;
		}
	}