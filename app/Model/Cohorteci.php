<?php
	/**
	 * Fichier source de la classe Cohorteci.
	 *
	 * PHP 5.3
	 *
	 * @package app.models
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import( 'Sanitize' );

	/**
	 * La classe Cohorteci fournit un traitement des filtres de recherche concernant les CER.
	 *
	 * @package app.models
	 */
	class Cohorteci extends AppModel
	{
		public $name = 'Cohorteci';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		 * Retourne un querydata résultant du traitement du formulaire de recherche des cohortes de CER.
		 *
		 * @param type $statutValidation
		 * @param array $mesCodesInsee La liste des codes INSEE à laquelle est lié l'utilisateur
		 * @param boolean $filtre_zone_geo L'utilisateur est-il limité au niveau des zones géographiques ?
		 * @param array $criteresci Critères du formulaire de recherche
		 * @param mixed $lockedDossiers
		 * @return array
		 */
		public function search( $statutValidation, $mesCodesInsee, $filtre_zone_geo, $criteresci, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array(/* '1 = 1' */);

			if( !empty( $statutValidation ) ) {
				if( $statutValidation == 'Decisionci::nouveauxsimple' ) {
					$conditions[] = '( ( Contratinsertion.forme_ci = \'S\' ) AND ( ( Contratinsertion.decision_ci = \'E\' ) OR ( Contratinsertion.decision_ci IS NULL ) ) )';
				}
				else if( $statutValidation == 'Decisionci::nouveauxparticulier' ) {
					$conditions[] = '( ( Contratinsertion.forme_ci = \'C\' ) AND ( ( Contratinsertion.decision_ci = \'E\' ) OR ( Contratinsertion.decision_ci IS NULL ) ) )';
				}
				else if( $statutValidation == 'Decisionci::nouveaux' ) {
					$conditions[] = '( ( Contratinsertion.decision_ci = \'E\' ) OR ( Contratinsertion.decision_ci IS NULL ) )';
				}
				else if( $statutValidation == 'Decisionci::valides' ) {
					$conditions[] = 'Contratinsertion.decision_ci IS NOT NULL';
					$conditions[] = 'Contratinsertion.decision_ci <> \'E\'';
				}

				if( Configure::read( 'Cg.departement' ) == 93 ) {
					// Si on veut valider des CER complexes, on s'assurera qu'ils ne sont
					// pas en EP pour validation de contrat complexe, ou alors dans un état annulé
					if( in_array( $statutValidation, array( 'Decisionci::nouveaux'/*, 'Decisionci::enattente'*/ ) ) ) {
						$ModeleContratcomplexeep93 = ClassRegistry::init( 'Contratcomplexeep93' );
						$conditions[] = 'Contratinsertion.id NOT IN (
							'.$ModeleContratcomplexeep93->sq(
								array(
									'fields' => array( 'contratscomplexeseps93.contratinsertion_id' ),
									'alias' => 'contratscomplexeseps93',
									'joins' => array(
										array(
											'table'      => 'dossierseps',
											'alias'      => 'dossierseps',
											'type'       => 'INNER',
											'foreignKey' => false,
											'conditions' => array( 'dossierseps.id = contratscomplexeseps93.dossierep_id' )
										),
									),
									'conditions' => array(
										'contratscomplexeseps93.contratinsertion_id = Contratinsertion.id',
										'dossierseps.id NOT IN (
											'.$ModeleContratcomplexeep93->Dossierep->Passagecommissionep->sq(
												array(
													'fields' => array( 'passagescommissionseps.dossierep_id' ),
													'alias' => 'passagescommissionseps',
													'conditions' => array(
														'passagescommissionseps.dossierep_id = dossierseps.id',
														'passagescommissionseps.etatdossierep' => 'annule',
													),
												)
											).'
										)'
									),
								)
							).'
						)';

					}
				}
			}

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				if( is_array( $lockedDossiers ) ) {
					$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
				}
				$conditions[] = "NOT {$lockedDossiers}";
			}

			/// Critères
// 			$date_saisi_ci = Set::extract( $criteresci, 'Filtre.date_saisi_ci' );
			$decision_ci = Set::extract( $criteresci, 'Filtre.decision_ci' );
			$datevalidation_ci = Set::extract( $criteresci, 'Filtre.datevalidation_ci' );
			$dd_ci = Set::extract( $criteresci, 'Filtre.dd_ci' );
			$df_ci = Set::extract( $criteresci, 'Filtre.df_ci' );
			$locaadr = Set::extract( $criteresci, 'Filtre.locaadr' );
			$numcomptt = Set::extract( $criteresci, 'Filtre.numcomptt' );
			$nir = Set::extract( $criteresci, 'Filtre.nir' );
			$natpf = Set::extract( $criteresci, 'Filtre.natpf' );
			$personne_suivi = Set::extract( $criteresci, 'Filtre.pers_charg_suivi' );
			$forme_ci = Set::extract( $criteresci, 'Filtre.forme_ci' );
			$structurereferente_id = Set::extract( $criteresci, 'Filtre.structurereferente_id' );
			$referent_id = Set::extract( $criteresci, 'Filtre.referent_id' );
			$matricule = Set::extract( $criteresci, 'Filtre.matricule' );
			$positioncer = Set::extract( $criteresci, 'Filtre.positioncer' );


			/// Critères sur le CI - dates du CER (date de saisie, date de début, date de fin)
			foreach( array( 'date_saisi_ci', 'dd_ci', 'df_ci' ) as $typeDate ) {
				if( isset( $criteresci['Filtre'][$typeDate] )  ) {
					if( is_array( $criteresci['Filtre'][$typeDate] ) && !empty( $criteresci['Filtre'][$typeDate]['day'] ) && !empty( $criteresci['Filtre'][$typeDate]['month'] ) && !empty( $criteresci['Filtre'][$typeDate]['year'] ) ) {
						$conditions["Filtre.{$typeDate}"] = "{$criteresci['Filtre'][$typeDate]['year']}-{$criteresci['Filtre'][$typeDate]['month']}-{$criteresci['Filtre'][$typeDate]['day']}";
					}
					else if( ( is_int( $criteresci['Filtre'][$typeDate] ) || is_bool( $criteresci['Filtre'][$typeDate] ) || ( $criteresci['Filtre'][$typeDate] == '1' ) ) && isset( $criteresci['Filtre']["{$typeDate}_from"] ) && isset( $criteresci['Filtre']["{$typeDate}_to"] ) ) {
						$criteresci['Filtre']["{$typeDate}_from"] = $criteresci['Filtre']["{$typeDate}_from"]['year'].'-'.$criteresci['Filtre']["{$typeDate}_from"]['month'].'-'.$criteresci['Filtre']["{$typeDate}_from"]['day'];
						$criteresci['Filtre']["{$typeDate}_to"] = $criteresci['Filtre']["{$typeDate}_to"]['year'].'-'.$criteresci['Filtre']["{$typeDate}_to"]['month'].'-'.$criteresci['Filtre']["{$typeDate}_to"]['day'];

						$conditions[] = 'Contratinsertion.'.$typeDate.' BETWEEN \''.$criteresci['Filtre']["{$typeDate}_from"].'\' AND \''.$criteresci['Filtre']["{$typeDate}_to"].'\'';
					}
				}
			}




			// Trouver le dernier contrat d'insertion pour chacune des personnes du jeu de résultats
			if( isset( $criteresci['Contratinsertion']['dernier'] ) && $criteresci['Contratinsertion']['dernier'] ) {
				$conditions[] = 'Contratinsertion.id IN (
					SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							contratsinsertion.personne_id = Contratinsertion.personne_id
						ORDER BY
							contratsinsertion.rg_ci DESC,
							contratsinsertion.id DESC
						LIMIT 1
				)';
			}

			// On a un filtre par défaut sur l'état du dossier si celui-ci n'est pas renseigné dans le formulaire.
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );
			$etatdossier = Set::extract( $criteresci, 'Situationdossierrsa.etatdosrsa' );
			if( !isset( $criteresci['Situationdossierrsa']['etatdosrsa'] ) || empty( $criteresci['Situationdossierrsa']['etatdosrsa'] ) ) {
				$criteresci['Situationdossierrsa']['etatdosrsa']  = $Situationdossierrsa->etatOuvert();
			}

			$conditions = $this->conditionsAdresse( $conditions, $criteresci, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criteresci );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresci );

			// ...
			if( !empty( $decision_ci ) ) {
				$conditions[] = 'Contratinsertion.decision_ci = \''.Sanitize::clean( $decision_ci ).'\'';
			}

			// ...
			if( !empty( $positioncer ) ) {
				$conditions[] = 'Contratinsertion.positioncer = \''.Sanitize::clean( $positioncer ).'\'';
			}

			// ...
			if( !empty( $datevalidation_ci ) && dateComplete( $criteresci, 'Filtre.datevalidation_ci' ) ) {
				$datevalidation_ci = $datevalidation_ci['year'].'-'.$datevalidation_ci['month'].'-'.$datevalidation_ci['day'];
				$conditions[] = 'Contratinsertion.datevalidation_ci = \''.$datevalidation_ci.'\'';
			}


			// Personne chargée du suiv
			if( !empty( $personne_suivi ) ) {
				$conditions[] = 'Contratinsertion.pers_charg_suivi = \''.Sanitize::clean( $personne_suivi ).'\'';
			}

			// Forme du contrat
			if( !empty( $forme_ci ) ) {
				$conditions[] = 'Contratinsertion.forme_ci = \''.Sanitize::clean( $forme_ci ).'\'';
			}

			/// Structure référente
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Contratinsertion.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
			}

			/// Référent
			if( !empty( $referent_id ) ) {
				$conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
			}

			// Liste des CERs arrivant à échéance -> dont la date de fin est pour le mois en cours
			if( isset( $criteresci['Filtre']['arriveaecheance'] ) && !empty( $criteresci['Filtre']['arriveaecheance'] ) ) {
				$conditions[] = 'Contratinsertion.id IN (
					SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							date_trunc( \'day\', contratsinsertion.df_ci ) >= DATE( NOW() )
							AND date_trunc( \'day\', contratsinsertion.df_ci ) <= ( DATE( NOW() ) + INTERVAL \''.Configure::read( 'Criterecer.delaiavanteecheance' ).'\' )
 				)';
			}


			// Pour le CG66 : filtre permettant de retourner les CERs non validés et notifiés il y a 1 mois et demi
			if( isset( $criteresci['Filtre']['notifienouveaux'] ) && !empty( $criteresci['Filtre']['notifienouveaux'] ) ) {
				$conditions[] = 'Contratinsertion.id IN (
					SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							positioncer = \'nonvalidnotifie\'
							AND ( date_trunc( \'day\', contratsinsertion.datenotification ) + INTERVAL \''.Configure::read( 'Criterecer.delaidetectionnonvalidnotifie' ).'\' ) <= DATE( NOW() )
							AND contratsinsertion.id IN (
								SELECT c.id
									FROM contratsinsertion AS c
									WHERE
										c.personne_id = "Personne"."id"
									ORDER BY dd_ci DESC
									LIMIT 1
							)
 				)';
			}

			$this->Dossier = ClassRegistry::init( 'Dossier' );
			$this->Contratinsertion = ClassRegistry::init( 'Contratinsertion' );

			$query = array(
				'fields' => array_merge(
					$this->Contratinsertion->Propodecisioncer66->fields(),
					array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Contratinsertion.num_contrat',
						'Contratinsertion.referent_id',
						'Contratinsertion.structurereferente_id',
						'Contratinsertion.rg_ci',
						'Contratinsertion.decision_ci',
						'Contratinsertion.forme_ci',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
						'Contratinsertion.forme_ci',
						'Contratinsertion.datevalidation_ci',
						'Contratinsertion.duree_engag',
						'Contratinsertion.positioncer',
						'Contratinsertion.date_saisi_ci',
						'Contratinsertion.datedecision',
						'Contratinsertion.pers_charg_suivi',
						'Contratinsertion.observ_ci',
						'Contratinsertion.datenotification',
						'Dossier.id',
						'Dossier.numdemrsa',
						'Dossier.dtdemrsa',
						'Dossier.matricule',
						'Personne.id',
						'Personne.nom',
						'Personne.prenom',
						'Personne.dtnai',
						'Personne.nir',
						'Personne.qual',
						'Personne.nomcomnai',
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.compladr',
						'Adresse.locaadr',
						'Adresse.codepos',
						'Adresse.numcomptt',
						'PersonneReferent.referent_id',
						'Prestation.rolepers',
						'Situationdossierrsa.etatdosrsa'
					)
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Contratinsertion.personne_id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
						)
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						//'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'personnes_referents',
						'alias'      => 'PersonneReferent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'PersonneReferent.personne_id = Personne.id',
							'PersonneReferent.dfdesignation IS NULL'
						)
					),
					array(
						'table'      => 'proposdecisionscers66',
						'alias'      => 'Propodecisioncer66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Propodecisioncer66.contratinsertion_id = Contratinsertion.id'
						)
					),
				),
				'limit' => 10,
				'order' => 'Contratinsertion.df_ci ASC',
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>