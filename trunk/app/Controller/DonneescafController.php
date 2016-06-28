<?php
	/**
	 * Code source de la classe DonneescafController.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */

	/**
	 * La classe DonneescafController ...
	 *
	 * @package app.Controller
	 */
	class DonneescafController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Donneescaf';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'DossiersMenus',
			'Jetons2',
			'Allocataires'
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Personne',
			'Foyer',
			'Option',
		);
		
		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
		);
		
		/**
		 * Liste des donnees d'une personne
		 * 
		 * @param integer $personne_id
		 */
		public function personne($personne_id) {
			$this->assert(valid_int($personne_id), 'invalidParameter');
			$this->set('dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu(array('personne_id' => $personne_id)));
			
			$this->set('personnes', $personnes = $this->Personne->find(
				'all', array(
					'contain' => array(
						'Dossiercaf',
						'Prestation',
						'Rattachement',
						'Ressource' => array(
							'Ressourcemensuelle' => 'Detailressourcemensuelle'
						),
						'Calculdroitrsa' => array(
							'conditions' => array(
								'OR' => array(
									'Calculdroitrsa.toppersdrodevorsa IS NOT NULL',
									'Calculdroitrsa.toppersentdrodevorsa IS NOT NULL',
									'Calculdroitrsa.mtpersressmenrsa IS NOT NULL',
									'Calculdroitrsa.mtpersabaneursa IS NOT NULL',
								)
							)
						),
						'Activite',
						'Allocationsoutienfamilial' => array(
							'conditions' => array(
								'OR' => array(
									'Allocationsoutienfamilial.sitasf IS NOT NULL',
									'Allocationsoutienfamilial.parassoasf IS NOT NULL',
									'Allocationsoutienfamilial.ddasf IS NOT NULL',
									'Allocationsoutienfamilial.dfasf IS NOT NULL',
								)
							)
						),
						'Creancealimentaire',
						'Grossesse',
						'Infoagricole' => array(
							'conditions' => array(
								'OR' => array(
									'Infoagricole.mtbenagri IS NOT NULL',
									'Infoagricole.regfisagri IS NOT NULL',
									'Infoagricole.dtbenagri IS NOT NULL',
								)
							),
							'Aideagricole'
						),
						'Avispcgpersonne' => array('Derogation', 'Liberalite'),
						// 'Aviscgssdompersonne', // Lien non fait dans Personne - reservé aux DOM
						'Suiviappuiorientation',
						'Dsp' => array(
							'fields' => Hash::merge($this->Personne->Dsp->fields(),
								array(
									'("Dsp"."sitpersdemrsa" IS NOT NULL OR '
									.'"Dsp"."topisogroouenf" IS NOT NULL OR '
									.'"Dsp"."topdrorsarmiant" IS NOT NULL OR '
									.'"Dsp"."drorsarmianta2" IS NOT NULL OR '
									.'"Dsp"."topcouvsoc" IS NOT NULL) AS "Dsp__have_generalite"',
									'("Dsp"."accosocfam" IS NOT NULL OR '
									.'"Dsp"."libcooraccosocfam" IS NOT NULL OR '
									.'"Dsp"."accosocindi" IS NOT NULL OR '
									.'"Dsp"."libcooraccosocindi" IS NOT NULL OR '
									.'"Dsp"."soutdemarsoc" IS NOT NULL) AS "Dsp__have_comsitsoc"',
									'("Dsp"."nivetu" IS NOT NULL OR '
									.'"Dsp"."nivdipmaxobt" IS NOT NULL OR '
									.'"Dsp"."annobtnivdipmax" IS NOT NULL OR '
									.'"Dsp"."topqualipro" IS NOT NULL OR '
									.'"Dsp"."libautrqualipro" IS NOT NULL OR '
									.'"Dsp"."topcompeextrapro" IS NOT NULL OR '
									.'"Dsp"."libcompeextrapro" IS NOT NULL) AS "Dsp__have_nivetu"'
								)
							),
							'Detaildifsoc',
							'Detailaccosocfam',
							'Detailaccosocindi',
							'Detaildifdisp',
							'Detailnatmob',
							'Detaildiflog',
						),
						'Parcours' => array(
							'fields' => Hash::merge($this->Personne->Parcours->fields(),
								array(
									'("Parcours"."natparcocal" IS NOT NULL OR '
									.'"Parcours"."natparcomod" IS NOT NULL OR '
									.'"Parcours"."toprefuparco" IS NOT NULL OR '
									.'"Parcours"."motimodparco" IS NOT NULL) AS "Parcours__have_parcours"',
								)
							)
						),
						'Orientation',
						'Titresejour',
						'Informationeti',
						'Conditionactiviteprealable',
					),
					'conditions' => array('Personne.id' => $personne_id)
				)
			));
			
			$this->set('options', $this->_options());
			
			// Pour liste personnes dans les tabs
			$this->set('personnes_list', $this->_getPersonnes_list($personnes[0]['Personne']['foyer_id']));
			
			/**
			 * Extraction du contain au 1er niveau
			 */
			$this->_extractAndSet(
				array(
					'Rattachement', 'Ressource', 'Activite', 'Allocationsoutienfamilial',
					'Creancealimentaire', 'Grossesse', 'Infoagricole', 'Avispcgpersonne', 
					'Derogation', 'Liberalite', 'Suiviappuiorientation', 'Parcours',
					'Orientation', 'Titresejour', 'Informationeti', 'Conditionactiviteprealable',
				), $personnes
			);
			
			/**
			 * Extraction du contain aux autres niveaux
			 */
			$this->_extractAndSet(
				array(
					'Ressourcemensuelle', 'Detailressourcemensuelle', 'Derogation', 
					'Liberalite', 'Detaildifsoc', 'Detailaccosocfam', 'Detailaccosocindi',
					'Detaildifdisp', 'Detailnatmob', 'Detaildiflog', 'Aideagricole'
				),
				array(0 => array(
					'Ressourcemensuelle' => Hash::extract($personnes, '0.Ressource.{n}.Ressourcemensuelle.{n}'),
					'Detailressourcemensuelle' 
						=> Hash::extract($personnes, '0.Ressource.{n}.Ressourcemensuelle.{n}.Detailressourcemensuelle.{n}'),
					'Derogation' => Hash::extract($personnes, '0.Avispcgpersonne.{n}.Derogation.{n}'),
					'Liberalite' => Hash::extract($personnes, '0.Avispcgpersonne.{n}.Liberalite.{n}'),
					'Detaildifsoc' => Hash::extract($personnes, '0.Dsp.Detaildifsoc.{n}'),
					'Detailaccosocfam' => Hash::extract($personnes, '0.Dsp.Detailaccosocfam.{n}'),
					'Detailaccosocindi' => Hash::extract($personnes, '0.Dsp.Detailaccosocindi.{n}'),
					'Detaildifdisp' => Hash::extract($personnes, '0.Dsp.Detaildifdisp.{n}'),
					'Detailnatmob' => Hash::extract($personnes, '0.Dsp.Detailnatmob.{n}'),
					'Detaildiflog' => Hash::extract($personnes, '0.Dsp.Detaildiflog.{n}'),
					'Aideagricole' => Hash::extract($personnes, '0.Infoagricole.{n}.Aideagricole.{n}'),
				))
			);
		}
		
		/**
		 * Liste des donnees d'un foyer
		 * 
		 * @param integer $foyer_id
		 */
		public function foyer($foyer_id) {
			$this->assert(valid_int($foyer_id), 'invalidParameter');
			$this->set('dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu(array('foyer_id' => $foyer_id)));
			
			$this->set('foyers', $foyers = $this->Foyer->find(
				'all', array(
					'contain' => array(
						'Dossier' => array(
							'Situationdossierrsa' => array(
								'Suspensiondroit',
								'Suspensionversement',
							),
							'Detaildroitrsa' => array(
								'Detailcalculdroitrsa',
								'fields' => array_merge(
									$this->Foyer->Dossier->Detaildroitrsa->fields(),
									array(
										'("Detaildroitrsa".topsansdomfixe IS NOT NULL OR '
										.'"Detaildroitrsa".nbenfautcha IS NOT NULL OR '
										.'"Detaildroitrsa".oridemrsa IS NOT NULL OR '
										.'"Detaildroitrsa".dtoridemrsa IS NOT NULL OR '
										.'"Detaildroitrsa".topfoydrodevorsa IS NOT NULL) AS "Detaildroitrsa__have_tronccommun"',
										'("Detaildroitrsa"."ddelecal" IS NOT NULL OR '
										.'"Detaildroitrsa"."dfelecal" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtrevminigararsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtpentrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtlocalrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtrevgararsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtpfrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtalrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtressmenrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtsanoblalimrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtredhosrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtredcgrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtcumintegrsa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mtabaneursa" IS NOT NULL OR '
										.'"Detaildroitrsa"."mttotdrorsa" IS NOT NULL) AS "Detaildroitrsa__have_mntcalculdroit"',
									)
								)
							),
							'Avispcgdroitrsa' => array(
								'Condadmin',
								'Reducrsa',
							),
							'Infofinanciere',
							'Suiviinstruction',
						),
						'Adressefoyer' => array(
							'Adresse' => array(
								'fields' => Hash::merge(
									$this->Foyer->Adressefoyer->Adresse->fields(),
									array(
										'("Adresse"."typeres" IS NOT NULL OR '
										.'"Adresse"."topresetr" IS NOT NULL) AS "Adresse__have_comp"'
									)
								)
							)
						),
						'Evenement',
						'Controleadministratif',
						'Creance',
						'Modecontact',
						'Paiementfoyer',
					),
					'conditions' => array('Foyer.id' => $foyer_id)
				)
			));
			
			$this->set('options', $this->_options());
			
			// Pour liste personnes dans les tabs
			$this->set('personnes_list', $this->_getPersonnes_list($foyer_id));
			
			/**
			 * Extraction du contain au 1er niveau
			 */
			$this->_extractAndSet(
				array(
					'Adressefoyer', 'Evenement', 'Controleadministratif', 'Creance',
					'Modecontact', 'Paiementfoyer'
				), $foyers
			);
			
			/**
			 * Extraction du contain aux autres niveaux
			 */
			$this->_extractAndSet(
				array(
					'Suspensionversement', 'Suspensiondroit', 'Detailcalculdroitrsa', 
					'Condadmin', 'Reducrsa', 'Infofinanciere', 'Suiviinstruction'
				),
				array(0 => array(
					'Suspensiondroit' => Hash::extract($foyers, '0.Dossier.Situationdossierrsa.Suspensiondroit.{n}'),
					'Suspensionversement' => Hash::extract($foyers, '0.Dossier.Situationdossierrsa.Suspensionversement.{n}'),
					'Detailcalculdroitrsa' => Hash::extract($foyers, '0.Dossier.Detaildroitrsa.Detailcalculdroitrsa.{n}'),
					'Condadmin' => Hash::extract($foyers, '0.Dossier.Avispcgdroitrsa.Condadmin.{n}'),
					'Reducrsa' => Hash::extract($foyers, '0.Dossier.Avispcgdroitrsa.Reducrsa.{n}'),
					'Infofinanciere' => Hash::extract($foyers, '0.Dossier.Infofinanciere.{n}'),
					'Suiviinstruction' => Hash::extract($foyers, '0.Dossier.Suiviinstruction.{n}'),
				))
			);
		}
		
		/**
		 * Envoi une variable à la vue contenant le contain d'un enregistrement
		 * 
		 * Exemple:
		 *  $toExtract = array('Prestation')
		 *	$data = array(0 => array('Prestation' => array(0 => array('id' => 1))))
		 *	une variable nommé <strong>$prestations</strong> contiendra : array(0 => array('Prestation' => array('id' => 1)))
		 * 
		 * @param array $toExtract - Liste des contain à extraire
		 * @param array $data - Données du find all
		 */
		protected function _extractAndSet($toExtract, $data) {
			foreach ($toExtract as $extractName) {
				$varName = Inflector::pluralize(Inflector::underscore($extractName));
				$$varName = array();
				foreach (Hash::extract($data, '0.'.$extractName) as $extractedData) {
					${$varName}[][$extractName] = $extractedData;
				}
				$this->set($varName, $$varName);
			}
		}
		
		/**
		 * Permet d'obtenir la liste des Personnes d'un foyer pour affichage des onglets
		 * 
		 * @param integer $foyer_id
		 * @return array
		 */
		protected function _getPersonnes_list($foyer_id) {
			$this->Foyer->forceVirtualFields = true;
			return $this->Foyer->find(
				'all', array(
					'fields' => array(
						'Personne.id',
						'Personne.nom_complet',
						'Prestation.rolepers',
					),
					'contain' => false,
					'joins' => array(
						$this->Foyer->join('Personne'),
						$this->Foyer->Personne->join('Prestation'),
					),
					'conditions' => array(
						'Personne.foyer_id' => $foyer_id
					),
					'order' => array(
						'("Prestation"."rolepers" = \'DEM\')' => 'DESC NULLS LAST',
						'("Prestation"."rolepers" = \'CJT\')' => 'DESC NULLS LAST',
						'("Prestation"."rolepers" = \'ENF\')' => 'DESC NULLS LAST',
						'Personne.nom' => 'ASC',
						'Personne.prenom' => 'ASC',
						'Personne.id' => 'ASC',
					)
				)
			);
		}
		
		/**
		 * Ajoute les options extraites des données CAF
		 * 
		 * @return array
		 */
		protected function _options() {
			return Hash::merge(
				$this->Personne->enums(),
				$this->Allocataires->options(),
				$this->Foyer->Dossier->Situationdossierrsa->Suspensionversement->enums(),
				$this->Foyer->Creance->enums(),
				$this->Personne->Suiviappuiorientation->enums(),
				$this->Foyer->Dossier->Suiviinstruction->enums(),
				$this->Personne->Activite->enums(),
				$this->Personne->Dsp->enums(),
				$this->Personne->Dsp->Detaildifsoc->enums(),
				$this->Personne->Dsp->Detailaccosocfam->enums(),
				$this->Personne->Dsp->Detailaccosocindi->enums(),
				$this->Personne->Dsp->Detaildifdisp->enums(),
				$this->Personne->Dsp->Detailnatmob->enums(),
				$this->Personne->Dsp->Detaildiflog->enums(),
				array(
					'Rattachement' => array(
						'typepar' => $this->Option->typepar(),
					),
					'Detailressourcemensuelle' => array(
						'natress' => $this->Option->natress(),
						'abaneu' => $this->Option->abaneu()
					),
					'Activite' => array(
						'reg' => $this->Option->reg(),
						'act' => $this->Option->act(),
						'natcontrtra' => array(
							'CA' => 'Contrat aidé (CAV/CIRMA)',
							'CDD' => 'Contrat à durée déterminé',
							'CDI' => 'Contrat à durée indéterminé',
							'CUI' => 'Contrat unique d\'insertion',
							'CU1' => 'CUI non marchand (CAE) Etat',
							'CU2' => 'CUI marchand (CIE) Etat',
							'CU3' => 'CUI non marchand (CAE) Cg',
							'CU4' => 'CUI marchand (CIE) Cg',
							'INT' => 'Intérim',
							'AUT' => 'Autre (CAE, CIE, CEC, …)',
							'AEN' => 'Autoentrepreneur',
							'VDI' => 'Vendeur à domicile',
						)
					),
					'Allocationsoutienfamilial' => array(
						'sitasf' => $this->Option->sitasf(),
						'parassoasf' => $this->Option->parassoasf(),
					),
					'Creancealimentaire' => array(
						'etatcrealim' => $this->Option->etatcrealim(),
						'orioblalim' => $this->Option->orioblalim(),
						'motidiscrealim' => $this->Option->motidiscrealim(),
					),
					'Grossesse' => array(
						'natfingro' => $this->Option->natfingro(),
					),
					'Infoagricole' => array(
						'regfisagri' => $this->Option->regfisagri(),
					),
					'Avispcgpersonne' => array(
						'avisevaressnonsal' => array(
							'D' => 'Avis demandé au CG',
							'A' => 'Accord du CG',
							'R' => 'Refus du CG',
						),
						'excl' => array(
							'T' => 'Exclusion totale portant sur la personne et sur ses ressources',
							'P' => 'Exclusion partielle portant uniquement sur la personne',
						)
					),
					'Derogation' => array(
						'typedero' => $this->Option->typedero(),
						'avisdero' => $this->Option->avisdero(),
					),
					'Dossier' => array(
						'typeinsrmi' => array(
							'A' => 'Organisme agréé',
							'C' => 'CCAS',
							'F' => 'CAF',
							'S' => 'Service social'
						)
					),
					'Foyer' => array(
						'regagrifam' => array(
							'NSA' => 'NON SALARIE AGRICOLE',
							'SA ' => 'SALARIE AGRICOLE',
						)
					),
					'Adressefoyer' => array(
						'etatadr' => array(
							'CO' => 'Adresse vérifiée au regard du contrat Médiapost de la Cnaf',
							'VO' => 'Forcage du nom de la voie',
							'VC' => 'Forçage du nom de la voie et du nom de la commune',
							'NC' => 'Adresse non contrôlée',
							'AU' => 'Autre situation non répertoriée',
						)
					),
					'Situationdossierrsa' => array(
						'motirefursa' => array(
							'F02' => 'Fin de droit immédiate / moins de 25 ans et personne à charge ',
							'F04' => 'Fin de droit immédiate / titre de séjour non valide',
							'F09' => 'Fin de droit immédiate / résidence non conforme',
							'F85' => 'Fin de droit immédiate / pas d\'allocataire (si allocataire décédé par exemple)',
							'F97' => 'Fin de droit immédiate / bénéficiaire AAH réduite',
							'FDD' => 'ETI n\'ouvrant pas droit',
							'DSD' => 'Demande sans droit (demande pièces justificatives)',
							'FDB' => 'Etudiant rémunération insuffisante',
							'PCG' => 'Refus suite décision PCG',
						),
						'moticlorsa' => $this->Option->moticlorsa(),
					),
					'Suspensiondroit' => array(
						'motisusdrorsa' => array(
							'DA' => 'Suspension Dossier : Situation de famille',
							'DB' => 'Suspension Dossier : Ressources',
							'DC' => 'Suspension Dossier : Enquête administrative',
							'DD' => 'Suspension Dossier : Enquête sociale',
							'DE' => 'Suspension Dossier : Abs imprimé campagne contrôle',
							'DF' => 'Suspension Dossier : Absence avis changement CAF',
							'DG' => 'Suspension Dossier : Décès Madame',
							'DH' => 'Suspension Dossier : Décès Monsieur',
							'DI' => 'Suspension Dossier : Autre motif',
							'DJ' => 'Suspension Dossier : Présence paiemt réimp/arrêté',
							'DK' => 'Suspension Dossier : Abs réponse contrôle ASSEDIC',
							'DL' => 'Suspension Dossier : Pli non distribuable (ex-NPAI)',
							'DM' => 'Suspension Dossier : Résidence inconnue',
							'DN' => 'Suspension Dossier : Diverg. droits SS susp anc.mod',
							'DO' => 'Suspension Dossier : Diverg. droits AV susp anc.mod',
							'DP' => 'Suspension Dossier : Contrôle ASF hors d\'état',
							'DQ' => 'Suspension Dossier : Election domicile non renouvelé',
							'DR' => 'Suspension Dossier : Gestion de personne',
							'GF' => 'Suspension Groupe Prestation : Situation de famille',
							'GR' => 'Suspension Groupe Prestation : Contrôle activité ressources',
							'GA' => 'Suspension Groupe Prestation : Enquête administrative',
							'GS' => 'Suspension Groupe Prestation : Enquête sociale',
							'GC' => 'Suspension Groupe Prestation : Abs. imprimé campagne contrôle',
							'GI' => 'Suspension Groupe Prestation : Imprimé chang. CAF non fourni',
							'GX' => 'Suspension Groupe Prestation : Autre motif',
							'GE' => 'Suspension Groupe Prestation : Forfait ETI non fourni',
							'GJ' => 'Suspension Groupe Prestation : RSA: suspension PCG',
							'GK' => 'Suspension Groupe Prestation : RSA: contrat insertion',
							'GL' => 'Suspension Groupe Prestation : RSA: action non engagée',
						),
						'natgroupfsus' => array(
							'RSA' => 'RSA socle+activité',
							'RSX' => 'RSA socle uniquement',
							'RCX' => 'RSA activité uniquement',
							'DIF' => 'PF différentielles',
							'HOS' => 'PF hospitalisation',
							'ISO' => 'PF isolement',
						)
					),
					'Detailcalculdroitrsa' => array(
						'sousnatpf' => $this->Option->sousnatpf(),
					),
					'Condadmin' => array(
						'aviscondadmrsa' => $this->Option->aviscondadmrsa(),
						'moticondadmrsa' => array(
							'NR' => 'Non respect contrat insertion',
							'NS' => 'Demande d\'avis au Cg sans suspension administrative du droit',
							'EU' => 'Dossier européen',
							'AU' => 'Autre motif',
						),
					),
					'Avispcgdroitrsa' => array(
						'avisdestpairsa' => $this->Option->avisdestpairsa(),
						'typeperstie' => $this->Option->typeperstie(),
					),
					'Evenement' => array(
						'fg' => $this->Option->fg(),
					),
					'Controleadministratif' => array(
						'famcibcontro' => array(
							'01' => 'Situation Professionnelle',
							'02' => 'Logement',
							'03' => 'Etat Civil',
							'04' => 'Ressources',
							'05' => 'Situation Familiale, Charge enfant',
							'06' => 'Divers',
							'07' => 'Fraude',
						),
						'natcibcontro' => array(
							'RSA' => 'Ciblage lié à un droit RSA',
							'AUR' => 'Ciblage lié à une prestation autre que le RSA',
							'SIT' => 'Ciblage non lié à une prestation',
						),
						'commacontro' => array(
							'CAF' => 'Commande contrôle par CAF',
							'CGA' => 'Commande contrôle par conseil général',
							'NAT' => 'Commande contrôle national',
							'API' => 'API',
							'DEM' => 'Autres',
							'RMI' => 'RMI',
						),
						'typecontro' => array(
							'AG' => 'Agent assermenté',
							'EE' => 'Echanges extérieurs',
							'PI' => 'Appel de pièce',
						),
						'typeimpaccontro' => array(
							'0' => 'Pas d\'impact',
							'1' => 'Impact financier RSA sur fond CG ',
							'2' => 'Impact sur dossier sans répercussion financière RSA',
						)
					),
					'Infofinanciere' => array(
						'natpfcre' => $this->Option->natpfcre(),
						'typeopecompta' => $this->Option->typeopecompta(),
						'sensopecompta' => $this->Option->sensopecompta(),
					),
					'Parcours' => array(
						'natparcocal' => array(
							'AS' => 'Parcours professionnel et appui social',
							'PP' => 'Parcours professionnel ',
							'PS' => 'Parcours social',
						),
						'natparcomod' => array(
							'AS' => 'Parcours professionnel et appui social',
							'PP' => 'Parcours professionnel ',
							'PS' => 'Parcours social',
						),
						'motimodparco' => array(
							'CL' => 'Critère local',
							'EA' => 'Entretien approfondi',
						),
					),
					'Titresejour' => array(
						'nattitsej' => array(
							'AND' => 'Titre d\'identit‚ d\'andorran',
							'APF' => 'Attestation préfectorale',
							'APS' => 'Autorisation provisoire de s‚jour',
							'APT' => 'Autorisation provisoire de travail',
							'AUT' => 'Autres',
							'CRA' => 'Certificat de résidence de ressortissant Algérien',
							'CRC' => 'Carte de Ressortissant Communautaire ou Suisse',
							'CRE' => 'Carte de résident',
							'CST' => 'Carte de séjour temporaire',
							'CTS' => 'Contrat de travail saisonnier',
							'DIS' => 'Dispense de titre de séjour',
							'DCE' => 'Document de circulation pour étranger mineur',
							'FRO' => 'Carte de frontalier',
							'MON' => 'Passeport mon‚gasque',
							'OFP' => 'Livret de famille ou acte de naissance OFPRA',
							'OMI' => 'Certificat delivré par l\'ANAEM(ex-OMI) valant autorisation séjour',
							'PDC' => 'Membres du personnel diplomatiques et consulaires',
							'RAF' => 'Récépissé admission en France au titre de l\'asile',
							'RDA' => 'Récépissé dépôt demande d\'asile',
							'REF' => 'Certificat de refugié',
							'RCS' => 'Récépissé renouvellement de carte de séjour temporaire',
							'RPI' => 'Récépissé reconnaissance protection internationale',
							'RRA' => 'Récécissé renouvel. certificat résidence ressortissant algérien',
							'RRE' => 'Récépissé carte résident',
							'RSA' => 'Récépissé dépôt demande statut refugié ou adm bénéfice asile',
							'RSR' => 'Récépissé dépôt demande statut refugié',
							'RTS' => 'Récépissé demande TSJ',
							'RUN' => 'Récépissé de 1ère demande de titre de s‚jour',
							'RVA' => 'Récépissé demande TSJ valant autorisation de séjour',
							'VAC' => 'Visa délivré par l\'Autorité Consulaire',
							'VLS' => 'Visa de long séjour',
							'CVC' => 'Conversion Cristal - Valeur de transposition',
						),
						'menttitsej' => array(
							'AC' => 'Absence Condition Mention',
							'AD' => 'Apatride',
							'AM' => 'Autres mentions',
							'AO' => 'Fourniture TS tardive - Attente justificatif OFII',
							'AP' => 'Activité professionnelle et résidence de moins de 5ans',
							'A5' => 'Activité professionnelle et résidence de plus de 5ans',
							'AS' => 'Etranger admis au titre de l\'asile',
							'AT' => 'Il autorise son titulaire … travailler',
							'CA' => 'Constatant le dépôt d\'une demande d\'asile',
							'CN' => 'A déposé un recours devant le CNDA',
							'CR' => 'Constatant le dépôt d\'une demande de rééxamen',
							'CS' => 'Carte spéciale jusqu\'en 03/1996',
							'DO' => 'Décision favorable de l\'OFPRA/CNDA',
							'DR' => 'A demandé le statut de réfugié',
							'DT' => 'A demandé la délivrance d\'un premier titre de séjour',
							'DS' => 'Droit au séjour',
							'EO' => 'Etudiant- Attente justificatif OFII',
							'ET' => 'Etudiant - Justificatif OFII reçue',
							'IO' => 'Visiteur - Attente justificatif OFII',
							'JT' => 'Jugement de tutelle',
							'MF' => 'Membre de la famille',
							'PE' => 'Permettant l\'établissement (L.M. du 29/09/94)',
							'PF' => 'Bénéficiaire PF - RMI avant 18 ans',
							'PR' => 'Permanent',
							'PS' => 'Protection subsidiaire',
							'PT' => 'Autorisation provisoire de travail',
							'RE' => 'Retraité (titre de séjour date début < à 11/2006)',
							'RF' => 'Regroupe les N° de procédure 07,08,09,17,18 ou 19 pour l\'introduction en France',
							'RM' => 'Mayotte condition résidence 15 ans',
							'RR' => 'Reconnu réfugié / Certificat de réfugié',
							'RS' => 'Option régime de S.S. français',
							'RT' => 'Retraité',
							'R5' => 'Condition résidence vérifiée',
							'SA' => 'Salarié - Justificatif OFII reçue',
							'SC' => 'Scientifique et résidence de moins de 5 ans',
							'S5' => 'Scientifique et résidence de plus de 5 ans',
							'SO' => 'Salarié - Attente justificatifn OFII',
							'TO' => 'Travailleur temporaire - Attente justificatif OFII',
							'TT' => 'Travailleur temporaire- Justificatif OFII reçue',
							'VF' => 'Vie privée ou familiale et résidence de moins de 5ans',
							'VI' => 'Visiteur - Justificatif OFII reçue',
							'V5' => 'Vie privée et familiale et résidence de plus de 5ans',
							'VO' => 'Vie privée et familiale et attente justificatif OFII',
						)
					),
					'Informationeti' => array(
						'acteti' => $this->Option->acteti(),
						'regfiseti' => $this->Option->regfiseti(),
						'regfisetia1' => $this->Option->regfisetia1(),
					),
					'Modecontact' => array(
						'nattel' => $this->Option->nattel(),
						'matetel' => $this->Option->matetel(),
						'autorutitel' => $this->Option->autorutitel(),
						'autorutiadrelec' => $this->Option->autorutiadrelec(),
					),
					'Paiementfoyer' => array(
						'modepai' => array(
							'CB' => 'Cheques',
							'CE' => 'Caisse nationale d\'epargne ptt',
							'CP' => 'Caisse d\'epargne ecureuil',
							'ES' => 'Especes caisse',
							'LB' => 'Lettre cheque bancaire',
							'LP' => 'Lettre cheque ptt',
							'PI' => 'Paiement interne',
							'VB' => 'Virement bancaire',
							'VP' => 'Virement postal',
						),
						'titurib' => array(
							'MEL' => 'Monsieur et mademoiselle',
							'MEM' => 'Monsieur et madame',
							'MLE' => 'Mademoiselle',
							'MME' => 'Madame',
							'MOL' => 'Monsieur ou mademoiselle',
							'MOM' => 'Monsieur ou madame',
							'MON' => 'Monsieur',
							'RSO' => 'Raison sociale',
						)
					)
				)
			);
			
			// ^["']{0,1}([^'" ]*)["']{0,1}[ :]*(.*)
			// '$1' => '$2',
			
			// Import dico > po
			// <ValeurCodeCaract>([^<]+)</ValeurCodeCaract><LibelleCodeCaract>([^<]+)</LibelleCodeCaract>
			// msgid "ENUM::NATCRE::$1"\nmsgstr "$2"\n\n
			
			// Remplacement par .po
			// '([^']+)' => '([^']+)',
			// msgid "ENUM::NATCRE::$1"\nmsgstr "$2"\n
			
			// validate Inlist
			// msgid "ENUM::MOTIINDU::([^"]+)"\nmsgstr.*\n\n
			// '$1', 
		}
		
		
	}
?>
