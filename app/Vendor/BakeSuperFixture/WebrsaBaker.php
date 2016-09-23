<?php
	/**
	 * WebrsaBaker file
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
	 * Generateur de SuperFixture pour Webrsa
	 * 
	 * Rêgles de nommages :
	 * 
	 * Chaques modèles possède une fonction qui renvoi un array.
	 * Ces fonctions seront nommé get<Nom du modèle au pluriel>()
	 * Elle peuvent avoir des paramètres selon les besoins
	 * Certaines fonctions peuvent servir pour le contain, dans ce cas,
	 * elles portent le nom du Modèle sur lequel effectuer le contain suivi du
	 * nom de modèle cible, le tout au singulier : get<Model1><Model2>()
	 *
	 * @package SuperFixture
	 * @subpackage Utility
	 */
	class WebrsaBaker implements BakeSuperFixtureInterface
	{
		/**
		 * @var WebrsaBaker 
		 */
		protected static $_instance = null;
		
		/**
		 * BSFObject globaux
		 * 
		 * @var BSFObject
		 */
		public $User;
		public $Typeorient;
		public $Structurereferente;
		public $Referent;
		public $Serviceinstructeur;
		public $Group;
		
		/**
		 * @var Faker
		 */
		public $Faker;
		
		/**
		 * @var boolean true -> 3 adresses par foyers, false -> 1 seule adresse
		 */
		public $multipleAdresses = true;
		
		public $test = 'Webrsa';
		
		/**
		 * Constructeur
		 */
		public function __construct() {
			$this->initialize();
			self::$_instance = $this;
		}
		
		/**
		 * Singleton
		 */
		public static function getInstance() {
			if (self::$_instance === null) {
				self::$_instance = new WebrsaBaker();
			}
			
			return self::$_instance;
		}
		
		/**
		 * Permet d'obtenir une liste de BSFObject en explorant un array d'objects et leurs contain
		 * 
		 * @param string $modelName Nom du modèle des objects à trouver
		 * @param array $datas BSFObject
		 * @param array $results Laisser vide, se remplira avec les résultats de la recherche
		 * @param string $path Laisser vide, utile pour la récursivitée
		 */
		public static function find($modelName, $datas, &$result = array(), $path = 'base') {
			foreach ((array)$datas as $object) {
				if ($object->modelName === $modelName) {
					$result[$path][] = $object;
				} elseif (isset($object->contain)) {
					self::find($modelName, $object->contain, $result, $path.'.'.$object->modelName);
				}
			}
			
			return Hash::flatten($result);
		}
		
		/**
		 * Initialisation de la classe
		 */
		public function initialize() {
			$this->Faker = Faker\Factory::create('fr_FR');
		}
		
		/**
		 * Callback entre initialize et getData
		 */
		public function beforeGetData() {
			return array();
		}
		
		/**
		 * Callback après getData
		 * @params array BSFOject
		 */
		public function afterGetData(array $datas) {
			return $datas;
		}
		
		/**
		 * Permet d'obtenir les informations nécéssaire pour générer la SuperFixture
		 * 
		 * @return array
		 */
		public static function getData() {
			$instance = self::getInstance();
			
			// Objets stockés
			$datas = array_merge(
				array('globals' => $instance->initializeGlobals()),
				$instance->beforeGetData()
			);
			
			// Passage pour chaques combinaisons des valeurs suivantes
			$etatdosrsa = array(
				0, 1, 2, 3, 4, 5, 6, 'Z'
			);
			$natpf = array(
				'RCD', 'RCI', 'RCJ', 'RCU', 'RSD', 'RSI', 'RSJ', 'RSU'
			);
			
			foreach ($etatdosrsa as $etat) {
				foreach ($natpf as $nat) {
					$data = $instance->completeDossier($etat, $nat);
					
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
			
			return $instance->afterGetData($results);
		}
		
		/**
		 * Création des BSFObject pour usage global
		 * 
		 * @return array
		 */
		public function initializeGlobals() {
			/**
			 * Serviceinstucteur
			 */
			$this->Serviceinstructeur = new BSFObject(
				'Serviceinstructeur', array('lib_service' => array('auto' => true, 'faker' => 'city'))
			);
			
			/**
			 * Group
			 */
			$this->Group = new BSFObject('Group', array('name' => array('value' => 'Administrateurs')));
			
			/**
			 * User
			 */
			$this->User = new BSFObject('User', array(
				'type' => array('value' => 'cg'),
				'username' => array('value' => 'webrsa'),
				'password' => array('value' => '83a98ed2a57ad9734eb0a1694293d03c74ae8a57'),
				'group_id' => array('foreignkey' => $this->Group->getName()),
				'serviceinstructeur_id' => array('foreignkey' => $this->Serviceinstructeur->getName()),
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
			$this->Typeorient = new BSFObject('Typeorient', array(
				'lib_type_orient' => array('auto' => true, 'faker' => 'city'),
				'actif' => array('value' => 'O'))
			);
			
			/**
			 * Structurereferente
			 */
			$this->Structurereferente = new BSFObject('Structurereferente', array(
				'typeorient_id' => array('foreignkey' => $this->Typeorient->getName()),
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
			$this->Referent = new BSFObject('Referent', array('structurereferente_id' => array('foreignkey' => $this->Structurereferente->getName())));
			
			return array($this->Serviceinstructeur, $this->Group, $this->User, $this->Typeorient, $this->Structurereferente, $this->Referent);
		}
		
		/**
		 * @return array BSFObject
		 */
		public function getDossiers() {
			$dossierElement = new DossierElementBaker();
			return array($dossierElement->get());
		}
		
		/**
		 * @return array BSFObject
		 */
		public function getFoyers() {
			$foyerElement = new FoyerElementBaker();
			return array($foyerElement->get());
		}
		
		/**
		 * @return array BSFObject
		 */
		public function getAdresses() {
			$dp = Configure::read('Cg.departement');
			$adresseFields = array(
				'numvoie' => array('auto' => true, 'faker' => array('rule' => 'regexify', '[1-9][0-9]{0,2}')),
				'codepos' => array('auto' => true, 'faker' => array('rule' => 'regexify', $dp.'[0-9]{3}')),
				'pays' => array('value' => 'FRA'),
				'numcom' => array('auto' => true, 'faker' => array('rule' => 'regexify', $dp.'0([1-9][0-9]|[0-9][1-9])')),
				'nomcom' => array('auto' => true, 'faker' => array('rule' => 'city')),
			);

			$regex = '/^([\\w\\-\']+) /';
			for ($i=1; $i<=3; $i++) {
				${'adr'.$i} = $this->Faker->streetName;
				preg_match($regex, ${'adr'.$i}, ${'mat'.$i});
			}
			
			$adresses = array();
			
			$adresses[] = new BSFObject('Adresse', $adresseFields+array(
				'nomvoie' => array('value' => strtoupper(substr($adr1, strlen($mat1[0])))),
				'libtypevoie' => array('value' => strtoupper($mat1[1])),
			));
			
			if ($this->multipleAdresses) {
				$adresses[] = new BSFObject('Adresse', $adresseFields+array(
					'nomvoie' => array('value' => strtoupper(substr($adr2, strlen($mat2[0])))),
					'libtypevoie' => array('value' => strtoupper($mat2[1]))
				));
				$adresses[] = new BSFObject('Adresse', $adresseFields+array(
					'nomvoie' => array('value' => strtoupper(substr($adr3, strlen($mat3[0])))),
					'libtypevoie' => array('value' => strtoupper($mat3[1]))
				));
			}
			
			return $adresses;
		}
		
		/**
		 * @return array BSFObject
		 */
		public function getAdressesfoyers($adresses) {
			$adressesfoyers = array();
			$dates = array();
			
			foreach ($adresses as $adresse) {
				$dates[] = $this->Faker->regexify(
					"(199[0-9]|200[0-9]|201[0-5]|201[0-5])\-(1[0-2]|0[1-9])\-(2[0-8]|1[0-9]|0[1-9])"
				);
			}
			
			usort($dates, function($a, $b) {
				$datetime1 = new Datetime($a);
				$datetime2 = new Datetime($b);
				
				if ($datetime1 < $datetime2) {
					return 1; 
				} elseif ($datetime1 > $datetime2) {
					return -1;
				} else {
					return 0;
				}
			});
			
			$rgadr = 1;
			foreach ($adresses as $adresse) {
				$date = $dates[0];
				unset($dates[0]);
				sort($dates);
				
				$adressesfoyers[] = new BSFObject('Adressefoyer',
					array(
						'rgadr' => array('value' => '0'.$rgadr),
						'adresse_id' => array('foreignkey' => $adresse->getName()),
						'dtemm' => array('value' => $date),
					)
				);
				$rgadr++;
			}
			
			return $adressesfoyers;
		}
		
		/**
		 * @return BSFObject
		 */
		public function getPersonneCalculdroitrsa() {
			$calculdroitrsa = new BSFObject('Calculdroitrsa', 
				array('toppersdrodevorsa' => array('auto' => true, 'in_array' => array('0', '1')))
			);
			
			return $calculdroitrsa;
		}
		
		/**
		 * @params BSFObject $dossier BSFObject de dossier
		 * @params boolean $cgj Ajouter un conjoin
		 * @param integer $enf Nombre d'enfants
		 * @return array BSFObject
		 */
		public function getPersonnes(BSFObject &$dossier, $cjt = false, $enf = 0) {
			$personnes = array();
			$personneElement = new PersonneElementBaker();
			$male = $this->Faker->randomDigit >= 5; // 1/2 chances que le personne soit un homme
			
			// DEM
			$dem = $personneElement->get($adulte = true, $male);
			$dem->contain = $this->getPersonneContain($dossier, 'DEM');
			$personnes[] = $dem;
			
			// CJT
			if ($cjt) {
				$cjt = $personneElement->get($adulte = true, !$male);
				$cjt->contain = $this->getPersonneContain($dossier, 'CJT');
				$personnes[] = $cjt;
			}
			
			for ($i=2; $i<$enf+2; $i++) {
				$personnes[$i] = $personneElement->get($adulte = false);
				$personnes[$i]->contain = $this->getPersonneContain($dossier, 'ENF');
			}
			
			return $personnes;
		}
		
		/**
		 * @param BSFObject $dossier
		 * @param string $rolepers
		 * @param boolean $enf
		 * @return array BSFObject
		 */
		public function getPersonneContain(BSFObject &$dossier, $rolepers = 'DEM') {
			return $rolepers === 'ENF' 
				? array($this->getPersonnePrestation($rolepers))
				: array(
					$this->getPersonnePrestation($rolepers),
					$this->getPersonneCalculdroitrsa(),
					$this->getPersonneDernierdossierallocataire($dossier)
				)
			;
		}
		
		/**
		 * @params BSFObject $dossier BSFObject de dossier
		 * @return BSFObject
		 */
		public function getPersonneDernierdossierallocataire(BSFObject &$dossier) {
			$dernierdossierallocataire = new BSFObject('Dernierdossierallocataire');
			$dernierdossierallocataire->fields = array(
				'dossier_id' => array('foreignkey' => $dossier->getName()),
			);
			
			return $dernierdossierallocataire;
		}
		
		/**
		 * @params array $rolepers DEM / CJT / ENF
		 * @return BSFObject
		 */
		public function getPersonnePrestation($rolepers = 'DEM') {
			$prestation = new BSFObject('Prestation', array(
				'natprest' => array('value' => 'RSA'),
				'rolepers' => array('value' => $rolepers)
			));
			
			return $prestation;
		}
		
		/**
		 * @params BSFObject $dossier BSFObject de dossier
		 * @params array $natpf array('RCD', 'RCI', 'RCJ', ...)
		 * @return BSFObject
		 */
		public function getDetailsdroitsrsa(BSFObject &$dossier, $natpf) {
			$detaildroitrsa = new BSFObject('Detaildroitrsa', 
				array('topsansdomfixe' => array('value' => 0), 'topfoydrodevorsa' => array('value' => 1))
			);
			$detaildroitrsa->fields = array(
				'dossier_id' => array('foreignkey' => $dossier->getName()),
			);

			/**
			 * Detailcalculdroitrsa
			 */
			$detaildroitrsa->contain = array(
				$this->getDetaildroitrsaDetailcalculdroitrsa($natpf),
			);
			
			return array($detaildroitrsa);
		}
		
		/**
		 * @params array $nat array('RCD', 'RCI', 'RCJ', ...)
		 * @return BSFObject
		 */
		public function getDetaildroitrsaDetailcalculdroitrsa($natpf) {
			return new BSFObject('Detailcalculdroitrsa', 
				array('natpf' => array('auto' => true, 'in_array' => (array)$natpf))
			);
		}
		
		/**
		 * @params array $etatdosrsa array(0, 1, 2, 3, 4, 5, 6, 'Z')
		 * @return BSFObject
		 */
		public function getDossierSituationdossierrsa($etatdosrsa) {
			return new BSFObject('Situationdossierrsa',
				array('etatdosrsa' => array('auto' => true, 'in_array' => (array)$etatdosrsa))
			);
		}
		
		/**
		 * Permet d'obtenir un Dossier avec tout ses contain de base
		 * 
		 * @param mixed $etat
		 * @param mixed $nat
		 * @return array
		 */
		public function completeDossier($etat = null, $nat = null) {
			if (empty($this->User)) {
				$this->initializeGlobals();
			}
			
			$etat = $etat ?: array(0, 1, 2, 3, 4, 5, 6, 'Z');
			$nat = $nat ?: array('RCD', 'RCI', 'RCJ', 'RCU', 'RSD', 'RSI', 'RSJ', 'RSU');
			
			/**
			 * Dossier
			 */
			$dossiers = $this->getDossiers();

			/**
			 * Foyer
			 */
			$foyers = $this->getFoyers();
			$foyers[0]->fields['dossier_id'] = array('foreignkey' => $dossiers[0]->getName());

			/**
			 * Adresses
			 */
			$adresses = $this->getAdresses();
			
			/**
			 * Adresse foyer
			 */
			$foyers[0]->contain = $this->getAdressesfoyers($adresses);

			/**
			 * Personnes
			 * NOTE :
			 *		- 50% de chances que le demandeur soit un homme
			 *		- 50% de chances d'avoir un conjoin dans le foyer
			 *		- 40% de chances d'avoir un enfants supplémentaire dans le foyer par boucles
			 *			-> donne environ 1-2 enfants par foyer en moyenne, lors du test, 
			 *			   il y avait entre 0 et 6 enfants pour 64 foyers
			 */
			$cjt = $this->Faker->randomDigit >= 5;
			
			$enf = 0;
			while ($this->Faker->randomDigit >= 6) {
				$enf++;
			}
			
			$personnes = $this->getPersonnes($dossiers[0], $cjt, $enf);
			
			/**
			 * Detaildroitrsa
			 */
			$detailsdroitsrsa = $this->getDetailsdroitsrsa($dossiers[0], $nat);
			
			/**
			 * Situationdossierrsa
			 */
			$dossiers[0]->contain = array(
				$this->getDossierSituationdossierrsa($etat),
				$foyers[0],
			);
			
			return compact('adresses', 'dossiers', 'personnes', 'detailsdroitsrsa');
		}
	}
