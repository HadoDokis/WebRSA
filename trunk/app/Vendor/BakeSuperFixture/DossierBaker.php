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

	/**
	 * Generateur de SuperFixture
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */
	class DossierBaker implements BakeSuperFixtureInterface {
		/**
		 * BSFObject globaux
		 * 
		 * @var BSFObject
		 */
		public static $User;
		public static $Typeorient;
		public static $Structurereferente;
		public static $Referent;
		public static $Serviceinstructeur;
		public static $Group;
		
		/**
		 * Permet d'obtenir les informations nécéssaire pour générer la SuperFixture
		 * 
		 * @return array
		 */
		public static function getData() {
			// Objets stockés
			$dossiers = array();
			$adresses = array();
			$globals = self::initializeGlobals();
			
			// Passage pour chaques combinaisons des valeurs suivantes
			$etatdosrsa = array(
				0, 1, 2, 3, 4, 5, 6, 'Z'
			);
			$natpf = array(
				'RCD', 'RCI', 'RCJ', 'RCU', 'RSD', 'RSI', 'RSJ', 'RSU'
			);
			
			foreach ($etatdosrsa as $etat) {
				foreach ($natpf as $nat) {
					$data = self::completeDossier($etat, $nat);
					$dossiers = array_merge($dossiers, $data['dossiers']);
					$adresses = array_merge($adresses, $data['adresses']);
				}
			}
			return array_merge($globals, $adresses, $dossiers);
		}
		
		/**
		 * Création des BSFObject pour usage global
		 * 
		 * @return array
		 */
		public static function initializeGlobals() {
			/**
			 * Serviceinstucteur
			 */
			self::$Serviceinstructeur = new BSFObject('Serviceinstructeur', array('lib_service' => array('auto' => true)));
			
			/**
			 * Group
			 */
			self::$Group = new BSFObject('Group', array('name' => array('auto' => true, 'faker' => 'city')));
			
			/**
			 * User
			 */
			self::$User = new BSFObject('User', array(
				'type' => array('value' => 'cg'),
				'serviceinstructeur_id' => array('foreignkey' => self::$Serviceinstructeur->getName()),
				'group_id' => array('foreignkey' => self::$Group->getName()),
			));
			
			/**
			 * Typeorient
			 */
			self::$Typeorient = new BSFObject('Typeorient', array('lib_type_orient' => array('auto' => true), 'actif' => array('value' => 'O')));
			
			/**
			 * Structurereferente
			 */
			self::$Structurereferente = new BSFObject('Structurereferente', array(
				'typeorient_id' => array('foreignkey' => self::$Typeorient->getName()),
				'contratengagement' => array('value' => 'N'),
				'apre' => array('value' => 'N'),
				'orientation' => array('value' => 'O'),
				'pdo' => array('value' => 'N'),
				'actif' => array('value' => 'O'),
				'typestructure' => array('value' => 'msp'),
				'cui' => array('value' => 'N'),
			));
			
			/**
			 * Referent
			 */
			self::$Referent = new BSFObject('Referent', array('structurereferente_id' => array('foreignkey' => self::$Structurereferente->getName())));
			
			return array(self::$Serviceinstructeur, self::$Group, self::$User, self::$Typeorient, self::$Structurereferente, self::$Referent);
		}
		
		/**
		 * Permet d'obtenir un Dossier avec tout ses contain de base
		 * 
		 * @param mixed $etat
		 * @param mixed $nat
		 * @return array
		 */
		public static function completeDossier($etat = null, $nat = null) {
			if (empty(self::$User)) {
				self::initializeGlobals();
			}
			
			$etat = $etat ?: array(0, 1, 2, 3, 4, 5, 6, 'Z');
			$nat = $nat ?: array('RCD', 'RCI', 'RCJ', 'RCU', 'RSD', 'RSI', 'RSJ', 'RSU');
			
			$Faker = Faker\Factory::create('fr_FR');
			$dossiers = array();
			$personnes = array();
			$adresses = array();
			
			$dossierElement = new DossierElementBaker();
			$dossiers[] = $dossierElement->get();
			$dossier =& $dossiers[count($dossiers)-1];

			/**
			 * Foyer
			 */
			$foyerElement = new FoyerElementBaker();
			$foyers[] = $foyerElement->get();
			$foyer =& $foyers[count($foyers)-1];
			$foyer->fields['dossier_id'] = array('foreignkey' => $dossier->getName());

			/**
			 * Adresses
			 */
			$adresseFields = array(
				'numvoie' => array('auto' => true, 'faker' => array('rule' => 'regexify', '[1-9][0-9]{0,2}')),
				'codepos' => array('auto' => true, 'faker' => array('rule' => 'regexify', '[1-9][0-9]{4}')),
				'pays' => array('value' => 'FRA'),
				'numcom' => array('auto' => true, 'faker' => array('rule' => 'regexify', '[1-9][0-9]{4}')),
				'nomcom' => array('auto' => true, 'faker' => array('rule' => 'city')),
			);

			$regex = '/^([\\w\\-\']+) /';
			for ($i=1; $i<=3; $i++) {
				${'adr'.$i} = $Faker->streetName;
				preg_match($regex, ${'adr'.$i}, ${'mat'.$i});
			}
			
			$adresses[] = new BSFObject('Adresse', $adresseFields+array(
				'nomvoie' => array('value' => strtoupper(substr($adr1, strlen($mat1[0])))),
				'libtypevoie' => array('value' => strtoupper($mat1[1]))
			));
			$adresse1 =& $adresses[count($adresses)-1];
			$adresses[] = new BSFObject('Adresse', $adresseFields+array(
				'nomvoie' => array('value' => strtoupper(substr($adr2, strlen($mat2[0])))),
				'libtypevoie' => array('value' => strtoupper($mat2[1]))
			));
			$adresse2 =& $adresses[count($adresses)-1];
			$adresses[] = new BSFObject('Adresse', $adresseFields+array(
				'nomvoie' => array('value' => strtoupper(substr($adr3, strlen($mat3[0])))),
				'libtypevoie' => array('value' => strtoupper($mat3[1]))
			));
			$adresse3 =& $adresses[count($adresses)-1];

			/**
			 * Adressefoyer
			 * NOTE :	Double foreign key, il faut enregistrer les adresses avant les dossiers
			 *			Donc on spécifie manuellement les foreign key sur Adresse
			 */
			$foyer->contain = array(
				new BSFObject('Adressefoyer', 
					array('rgadr' => array('value' => '01'), 'adresse_id' => array('foreignkey' => $adresse1->getName()))
				),
				new BSFObject('Adressefoyer', 
					array('rgadr' => array('value' => '02'), 'adresse_id' => array('foreignkey' => $adresse2->getName()))
				),
				new BSFObject('Adressefoyer', 
					array('rgadr' => array('value' => '03'), 'adresse_id' => array('foreignkey' => $adresse3->getName()))
				),
			);

			/**
			 * Calculdroitrsa
			 */
			$calculsdroitsrsas[] = new BSFObject('Calculdroitrsa', 
				array('toppersdrodevorsa' => array('auto' => true, 'in_array' => array('0', '1')))
			);
			$calculdroitrsa =& $calculsdroitsrsas[count($calculsdroitsrsas)-1];

			/**
			 * Personnes
			 * NOTE :
			 *		- 50% de chances que le demandeur soit un homme
			 *		- 50% de chances d'avoir un conjoin dans le foyer
			 *		- 40% de chances d'avoir un enfants supplémentaire dans le foyer par boucles
			 *			-> donne environ 1-2 enfants par foyer en moyenne, lors du test, il y avait entre 0 et 6 enfants pour 64 foyers
			 */
			$male = $Faker->randomDigit >= 5; // 1/2 chances que le personne soit un homme
			$personneElement = new PersonneElementBaker();
			$personnes[] = $personneElement->get($adulte = true, $male);
			$personnePrincipale =& $personnes[count($personnes)-1];
			$personnePrincipale->contain = array(
				new BSFObject('Prestation', array('natprest' => array('value' => 'RSA'), 'rolepers' => array('value' => 'DEM'))),
				$calculdroitrsa
			);
			$foyer->contain[] = $personnePrincipale;

			if ($Faker->randomDigit >= 5) {
				$personnes[] = $personneElement->get($adulte = true, !$male);
				$personneConjoint =& $personnes[count($personnes)-1];
				$personneConjoint->contain = array(
					new BSFObject('Prestation', array('natprest' => array('value' => 'RSA'), 'rolepers' => array('value' => 'CJT'))),
				);
				$foyer->contain[] = $personneConjoint;
			}

			while ($Faker->randomDigit >= 6) {
				$personnes[] = $personneElement->get($adulte = false);
				$personne =& $personnes[count($personnes)-1];
				$foyer->contain[] = $personne;
			}

			/**
			 * Detaildroitrsa
			 */
			$detaildroitrsas[] = new BSFObject('Detaildroitrsa', 
				array('topsansdomfixe' => array('value' => 0), 'topfoydrodevorsa' => array('value' => 1))
			);
			$detaildroitrsa =& $detaildroitrsas[count($detaildroitrsas)-1];

			/**
			 * Detailcalculdroitrsa
			 */
			$detaildroitrsa->contain = array(
				new BSFObject('Detailcalculdroitrsa', array('natpf' => array('auto' => true, 'in_array' => (array)$nat))),
			);

			/**
			 * Situationdossierrsa
			 */
			$dossier->contain = array(
				new BSFObject('Situationdossierrsa', array('etatdosrsa' => array('auto' => true, 'in_array' => (array)$etat))),
				$detaildroitrsa,
				$foyer,
			);
			
			/**
			 * Orientstruct
			 */
			$orientstruct = new BSFObject('Orientstruct');
			$orientstruct->fields = array(
			   'personne_id' => array('foreignkey' => $personnePrincipale->getName()),
			   'typeorient_id' => array('auto' => true, 'foreignkey' => self::$Typeorient->getName()),
			   'structurereferente_id' => array('auto' => true, 'foreignkey' => self::$Structurereferente->getName()),
			   'date_valid' => array('auto' => true),
			   'statut_orient' => array('value' => 'Orienté'),
			   'referent_id' => array('auto' => true, 'foreignkey' => self::$Referent->getName()),
			   'etatorient' => array('value' => 'proposition'),
			   'rgorient' => array('value' => '1'),
			   'referentorientant_id' => array('auto' => true, 'foreignkey' => self::$Referent->getName()),
			   'structureorientante_id' => array('auto' => true, 'foreignkey' => self::$Structurereferente->getName()),
			   'user_id' => array('auto' => true, 'foreignkey' => self::$User->getName()),
			   'haspiecejointe' => array('value' => '0'),
			   'origine' => array('value' => 'manuelle'),
			);
			$personnePrincipale->contain[] = $orientstruct;
			
			return compact('dossiers', 'personnes', 'adresses');
		}
	}