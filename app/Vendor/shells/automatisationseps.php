<?php

	class AutomatisationsepsShell extends AppShell
	{

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);

		public $verbose;

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* PostgreSQL valide
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
		}

		public function main() {
			if( Configure::read( 'Cg.departement' ) != 93 ) {
				$this->out( 'Ce shell n\'est utile que pour le CG 93' );
				$this->_stop( 1 );
			}

			$this->Propopdo = ClassRegistry::init( 'Propopdo' );
			$this->Nonrespectsanctionep93 = ClassRegistry::init( 'Nonrespectsanctionep93' );

			$propospdos = $this->Propopdo->find(
				'all',
				array(
					'joins' => array(
						array(
							'table'      => 'decisionspropospdos',
							'alias'      => 'Decisionpropopdo',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Decisionpropopdo.propopdo_id = Propopdo.id' )
						),
						array(
							'table'      => 'decisionspdos',
							'alias'      => 'Decisionpdo',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Decisionpropopdo.decisionpdo_id = Decisionpdo.id' )
						),
					),
					'contain' => false,
					'conditions' => array(
						'Decisionpdo.libelle LIKE' => 'DO 19%',
						'Decisionpropopdo.datedecisionpdo IS NOT NULL',
						//La date de décision de la PDO doit être supérieure à celle de la validation du CER
						// Une fois la décision émise, le CEr doit être validé par la suite et non pas avant
						// + intervalle d'1 mois entre la date de décision et la validation du CER
						'Propopdo.personne_id NOT IN (
							SELECT contratsinsertion.personne_id
								FROM contratsinsertion
								WHERE
									contratsinsertion.personne_id = Propopdo.personne_id
									AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) >= Decisionpropopdo.datedecisionpdo
									AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) <= ( Decisionpropopdo.datedecisionpdo + INTERVAL \''.Configure::read( 'Nonrespectsanctionep93.intervalleCerDo19' ).'\' )
						)',
						// Qui n'ont pas de dossier d'EP pas encore associé à une commission
						'Propopdo.personne_id NOT IN (
							SELECT dossierseps.personne_id
								FROM dossierseps
								WHERE
									dossierseps.personne_id = Propopdo.personne_id
									AND dossierseps.themeep = \'nonrespectssanctionseps93\'
									AND dossierseps.id NOT IN (
										SELECT passagescommissionseps.dossierep_id
											FROM passagescommissionseps
											WHERE passagescommissionseps.dossierep_id = dossierseps.id
									)
						)',
						// Et qui n'ont pas de dossier d'EP en train de passer en commission
						'Propopdo.personne_id NOT IN (
							SELECT dossierseps.personne_id
								FROM dossierseps
								INNER JOIN passagescommissionseps ON (
									passagescommissionseps.dossierep_id = dossierseps.id
								)
								WHERE
									dossierseps.personne_id = Propopdo.personne_id
									AND dossierseps.themeep = \'nonrespectssanctionseps93\'
									AND passagescommissionseps.etatdossierep NOT IN ( \'traite\', \'annule\' )
						)',
						// Et qui n'ont pas de dossier d'EP passé en commission depuis moins que le délai entre deux passages en commission
						'Propopdo.personne_id NOT IN (
							SELECT dossierseps.personne_id
								FROM dossierseps
								INNER JOIN passagescommissionseps ON (
									passagescommissionseps.dossierep_id = dossierseps.id
								)
								INNER JOIN commissionseps ON (
									passagescommissionseps.commissionep_id = commissionseps.id
								)
								WHERE
									dossierseps.personne_id = Propopdo.personne_id
									AND dossierseps.themeep = \'nonrespectssanctionseps93\'
									AND passagescommissionseps.etatdossierep IN ( \'traite\', \'annule\' )
									AND ( commissionseps.dateseance + INTERVAL \''.Configure::read( 'Nonrespectsanctionep93.intervalleCerDo19' ).' days\' ) <= NOW()
						)',
					)
				)
			);

			if( count( $propospdos ) > 0 ) {
				$this->Propopdo->begin();
				$success = true;
				foreach( $propospdos as $propopdo ) {
					$nbpassagespcd = $this->Nonrespectsanctionep93->Dossierep->find(
						'count',
						array(
							'conditions' => array(
								'Dossierep.themeep' => 'nonrespectssanctionseps93',
								'Nonrespectsanctionep93.origine' => 'pdo',
								'Nonrespectsanctionep93.propopdo_id' => $propopdo['Propopdo']['id'],
								'Nonrespectsanctionep93.sortienvcontrat' => 0,
								'Nonrespectsanctionep93.active' => 0
							),
							'joins' => array(
								array(
									'alias' => 'Nonrespectsanctionep93',
									'table' => 'nonrespectssanctionseps93',
									'type' => 'INNER',
									'conditions' => array(
										'Nonrespectsanctionep93.dossierep_id = Dossierep.id'
									)
								)
							),
							'contain' => false
						)
					);

					$dossierep = array(
						'Dossierep' => array(
							'personne_id' => $propopdo['Propopdo']['personne_id'],
							'themeep' => 'nonrespectssanctionseps93',
						),
						'Nonrespectsanctionep93' => array(
							'propopdo_id' => $propopdo['Propopdo']['id'],
							'origine' => 'pdo',
							'rgpassage' => ( $nbpassagespcd + 1 )
						)
					);

					$success = $this->Nonrespectsanctionep93->saveAll( $dossierep, array( 'atomic' => false ) ) && $success;
				}

				if( $success ) {
					$this->Propopdo->commit();
					$this->out( sprintf( 'Succès pour l\'enregistrement des %s dossiers EP pour la thématique "non respect / sanctions (CG 93)"', count( $propospdos ) ) );
				}
				else {
					$this->Propopdo->rollback();
					$this->err( sprintf( 'Erreur(s) lors de l\'enregistrement des %s dossiers EP pour la thématique "non respect / sanctions (CG 93)"', count( $propospdos ) ) );
				}
			}
			else {
				$this->out( 'Aucun dossier EP pour la thématique "non respect / sanctions (CG 93)" à traiter' );
			}
		}
	}
?>