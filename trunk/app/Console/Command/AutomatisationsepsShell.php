<?php
	App::uses( 'XShell', 'Console/Command' );
	/**
	 *
	 */
	class AutomatisationsepsShell extends XShell
	{

		/**
		 *
		 * @return type
		 */
		public function getOptionParser() {
			$parser = parent::getOptionParser();
			$parser->description( 'Le script a pour but de détecter les personnes possédant une DO19 mais n\'ayant pas signer de CER 1 mois suivant la décision du PCG sur cette DO19.' );
			return $parser;
		}

		/**
		 *
		 */
		public function main() {

			$out = array( );

			if( Configure::read( 'Cg.departement' ) != 93 ) {
				$out[] = 'Ce shell n\'est utile que pour le CG 93';
			}
			else {

				$this->Propopdo = ClassRegistry::init( 'Propopdo' );
				$this->Nonrespectsanctionep93 = ClassRegistry::init( 'Nonrespectsanctionep93' );

				$propospdos = $this->Propopdo->find(
						'all', array(
					'joins' => array(
						array(
							'table' => 'decisionspropospdos',
							'alias' => 'Decisionpropopdo',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Decisionpropopdo.propopdo_id = Propopdo.id' )
						),
						array(
							'table' => 'decisionspdos',
							'alias' => 'Decisionpdo',
							'type' => 'INNER',
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


					$this->XProgressBar->start( count( $propospdos ) );
					foreach( $propospdos as $propopdo ) {
						$nbpassagespcd = $this->Nonrespectsanctionep93->Dossierep->find(
								'count', array(
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

						$tmpSuccess = $this->Nonrespectsanctionep93->saveAll( $dossierep, array( 'atomic' => false ) );
						$success = !empty( $tmpSuccess ) && $success;
						$this->XProgressBar->next();
					}

					if( $success ) {
						$this->Propopdo->commit();
						$out[] = '<success>'.sprintf( 'Succès pour l\'enregistrement des %s dossiers EP pour la thématique "non respect / sanctions (CG 93)"', count( $propospdos ) ).'</success>';
					}
					else {
						$this->Propopdo->rollback();
						$out[] = '<error>'.sprintf( 'Erreur(s) lors de l\'enregistrement des %s dossiers EP pour la thématique "non respect / sanctions (CG 93)"', count( $propospdos ) ).'</error>';
					}
				}
				else {
					$out[] = '<success>Aucun dossier EP pour la thématique "non respect / sanctions (CG 93)" à traiter</success>';
				}
			}

			$this->out();
			$this->out( $out );
		}

	}
?>