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
			$datas = array('globals' => self::initializeGlobals());
			
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
					
					// Niveau supplémentaire pour trier par modèle dans le bon ordre d'insertion en base
					foreach ($data as $key => $values) {
						if (!isset($datas[$key])) {
							$datas[$key] = array();
						}
						
						$datas[$key] = array_merge($datas[$key], (array)$values);
					}
				}
			}
			
			// On supprime le premier niveau de clef
			$results = array();
			foreach ($datas as $key => $data) {
				$results = array_merge($results, $data);
			}
			
			return $results;
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
			self::$Serviceinstructeur = new BSFObject(
				'Serviceinstructeur', array('lib_service' => array('auto' => true, 'faker' => 'city'))
			);
			
			/**
			 * Group
			 */
			self::$Group = new BSFObject('Group', array('name' => array('value' => 'Administrateurs')));
			
			/**
			 * User
			 */
			self::$User = new BSFObject('User', array(
				'type' => array('value' => 'cg'),
				'username' => array('value' => 'webrsa'),
				'password' => array('value' => '83a98ed2a57ad9734eb0a1694293d03c74ae8a57'),
				'group_id' => array('foreignkey' => self::$Group->getName()),
				'serviceinstructeur_id' => array('foreignkey' => self::$Serviceinstructeur->getName()),
				'nom' => array('auto' => true, 'faker' => 'lastName'),
				'prenom' => array('auto' => true, 'faker' => array('rule' => 'firstName')),
				'date_naissance' => array(
					'auto' => true, // NOTE : entre 1960 et 1999, un jour entre le 1er et le 28e (entre 17 et 56 ans en 2016)
					'faker' => array('rule' => 'regexify', '19[6-9][0-9]\-(1[0-2]|0[1-9])\-(2[0-8]|1[0-9]|0[1-9])'),
				),
				'date_deb_hab' => array('value' => '2010-01-01'),
				'date_fin_hab' => array('value' => '2050-12-30'),
				'filtre_zone_geo' => array('value' => false),
				'isgestionnaire' => array('value' => 'N'),
				'sensibilite' => array('value' => 'O'),
				'numtel' => array('auto' => true, 'faker' => array('rule' => 'regexify', '0[1-7][0-9]{8}')),
			));
			
			/**
			 * Typeorient
			 */
			self::$Typeorient = new BSFObject('Typeorient', array(
				'lib_type_orient' => array('auto' => true, 'faker' => 'city'),
				'actif' => array('value' => 'O'))
			);
			
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
				'libtypevoie' => array('value' => strtoupper($mat1[1])),
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
			$date1 = $Faker->regexify("(199[0-9]|200[0-9]|201[0-5]|201[0-5])\-(1[0-2]|0[1-9])\-(2[0-8]|1[0-9]|0[1-9])");
			$date2 = $Faker->regexify("(199[0-9]|200[0-9]|201[0-5]|201[0-5])\-(1[0-2]|0[1-9])\-(2[0-8]|1[0-9]|0[1-9])");
			$datetime1 = new DateTime($date1);
			$datetime2 = new DateTime($date2);
			
			if ($datetime1 < $datetime2) {
				$tmp = $date1;
				$date1 = $date2;
				$date2 = $tmp;
			}
			
			$foyer->contain = array(
				new BSFObject('Adressefoyer',
					array(
						'rgadr' => array('value' => '01'),
						'adresse_id' => array('foreignkey' => $adresse1->getName()),
						'dtemm' => array('value' => $date1),
					)
				),
				new BSFObject('Adressefoyer',
					array(
						'rgadr' => array('value' => '02'),
						'adresse_id' => array('foreignkey' => $adresse1->getName()),
						'dtemm' => array('value' => $date2)
					)
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
			$detaildroitrsa->fields = array(
				'dossier_id' => array('foreignkey' => $dossier->getName()),
			);

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
			
			/**
			 * Dernier dossier d'un allocataire
			 */
			$dernierdossierallocataire = new BSFObject('Dernierdossierallocataire');
			$dernierdossierallocataire->fields = array(
				'dossier_id' => array('foreignkey' => $dossier->getName()),
			);
			$personnePrincipale->contain[] = $dernierdossierallocataire;
			
			return compact('adresses', 'dossiers', 'personnes');
		}
	}