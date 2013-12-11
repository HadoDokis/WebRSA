<?php
	/**
	 * Fichier source de la classe Cohorteci.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Cohorteci fournit un traitement des filtres de recherche concernant les CER.
	 *
	 * @package app.Model
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
		 * @param array $criteresci Critères du formulaire de recherche
		 * @param array $etatsdosrsa Une restriction éventuelle sur les états du dossier
		 * @return array
		 */
		public function search( $statutValidation, array $criteresci, array $etatsdosrsa = array() ) {
			/// Conditions de base
			$conditions = array();

            $this->Contratinsertion = ClassRegistry::init( 'Contratinsertion' );

            $conditions[] = array(
				array(
                    'OR' => array(
                        'Adressefoyer.id IS NULL',
                        'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
                    )
                ),
                'Prestation.rolepers' => array( 'DEM', 'CJT' )
			);

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

			/// Critères
// 			$created = Set::extract( $criteresci, 'Filtre.created' );
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
//			$referentParcours = Set::extract( $criteresci, 'PersonneReferent.id' );
//			$structureParcours = Set::extract( $criteresci, 'Structurereferente.id' );


			/// Critères sur la date de saisie du CER - champ created
//			foreach( array( 'created' ) as $timestampDate ) {
//				if( isset( $criteresci['Contratinsertion'][$timestampDate] ) && !empty( $criteresci['Contratinsertion'][$timestampDate] ) ) {
//					$valid_from = ( valid_int( $criteresci['Contratinsertion']["{$timestampDate}_from"]['year'] ) && valid_int( $criteresci['Contratinsertion']["{$timestampDate}_from"]['month'] ) && valid_int( $criteresci['Contratinsertion']["{$timestampDate}_from"]['day'] ) );
//                    $valid_to = ( valid_int( $criteresci['Contratinsertion']["{$timestampDate}_to"]['year'] ) && valid_int( $criteresci['Contratinsertion']["{$timestampDate}_to"]['month'] ) && valid_int( $criteresci['Contratinsertion']["{$timestampDate}_to"]['day'] ) );
//                    if( $valid_from && $valid_to ) {
//						$conditions[] = 'DATE ( Contratinsertion.created ) <= \''.implode( '-', array( $criteresci['Contratinsertion']["{$timestampDate}_from"]['year'], $criteresci['Contratinsertion']["{$timestampDate}_from"]['month'], $criteresci['Contratinsertion']["{$timestampDate}_from"]['day'] ) ).'\' AND DATE( Contratinsertion.created ) >= \''.implode( '-', array( $criteresci['Contratinsertion']["{$timestampDate}_to"]['year'], $criteresci['Contratinsertion']["{$timestampDate}_to"]['month'], $criteresci['Contratinsertion']["{$timestampDate}_to"]['day'] ) ).'\'';
//
//					}
//                }
//			}

            $conditions = $this->conditionsDates( $conditions, $criteresci, 'Contratinsertion.created' );
            $conditions = $this->conditionsDates( $conditions, $criteresci, 'Contratinsertion.dd_ci' );
            $conditions = $this->conditionsDates( $conditions, $criteresci, 'Contratinsertion.df_ci' );
            $conditions = $this->conditionsDates( $conditions, $criteresci, 'Contratinsertion.datevalidation_ci' );




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
			if( ( !isset( $criteresci['Situationdossierrsa']['etatdosrsa'] ) || empty( $criteresci['Situationdossierrsa']['etatdosrsa'] ) ) && !empty( $etatsdosrsa ) ) {
				$criteresci['Situationdossierrsa']['etatdosrsa']  = $etatsdosrsa;
			}

			$conditions = $this->conditionsAdresse( $conditions, $criteresci );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criteresci );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresci );

			// ...
			if( !empty( $decision_ci ) ) {
				$conditions[] = 'Contratinsertion.decision_ci = \''.Sanitize::clean( $decision_ci, array( 'encode' => false ) ).'\'';
			}

			// ...
			if( !empty( $positioncer ) ) {
				$conditions[] = 'Contratinsertion.positioncer = \''.Sanitize::clean( $positioncer, array( 'encode' => false ) ).'\'';
			}


			// Personne chargée du suiv
			if( !empty( $personne_suivi ) ) {
				$conditions[] = 'Contratinsertion.pers_charg_suivi = \''.Sanitize::clean( $personne_suivi, array( 'encode' => false ) ).'\'';
			}

			// Forme du contrat
			if( !empty( $forme_ci ) ) {
				$conditions[] = 'Contratinsertion.forme_ci = \''.Sanitize::clean( $forme_ci, array( 'encode' => false ) ).'\'';
			}

			/// Structure référente
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Contratinsertion.structurereferente_id = \''.Sanitize::clean( $structurereferente_id, array( 'encode' => false ) ).'\'';
			}

			/// Référent
			if( !empty( $referent_id ) ) {
				$conditions[] = 'Referent.id = \''.Sanitize::clean( suffix( $referent_id ), array( 'encode' => false ) ).'\'';
			}

			// Filtrer par durée du CER
			$duree_engag = Hash::get( $criteresci, 'Contratinsertion.duree_engag' );
			if( !empty( $duree_engag ) ) {
				if( Configure::read( 'Cg.departement' ) != 93 ) {
					$conditions['Contratinsertion.duree_engag'] = $duree_engag;
				}
				else {
					$durees_engags = ClassRegistry::init( 'Option' )->duree_engag();
					$conditions[] = array(
						'OR' => array(
							'Contratinsertion.duree_engag' => $duree_engag,
							'Cer93.duree' => str_replace( ' mois', '', $durees_engags[$duree_engag] ),
						)
					);
				}
			}

			// Recherche des CER en cours de validité sur une période donnée
			if( Hash::get( $criteresci, 'Contratinsertion.periode_validite' ) ) {
				$encours_validite_from = date_cakephp_to_sql( Hash::get( $criteresci, 'Contratinsertion.periode_validite_from' ) );
				$encours_validite_to = date_cakephp_to_sql( Hash::get( $criteresci, 'Contratinsertion.periode_validite_to' ) );

				if( !empty( $encours_validite_from ) && !empty( $encours_validite_to ) ) {
					$conditions[] = array(
						'Contratinsertion.decision_ci' => 'V',
						'OR' => array(
							// Le nombre de CER dont la date de début est inférieure ou égale au 1er jour du mois M ET dont la date de fin est supérieure ou égale au 1er jour du mois M
							array(
								'Contratinsertion.dd_ci <=' => $encours_validite_from,
								'Contratinsertion.df_ci >=' => $encours_validite_from,
							),
							// Le nombre de CER dont la date de début est supérieure ou égale au 1er jour du mois M ET dont la date de début est inférieure ou égale au dernier jour du mois M
							array(
								'Contratinsertion.dd_ci >=' => $encours_validite_from,
								'Contratinsertion.dd_ci <=' => $encours_validite_to,
							),
						)
					);
				}
			}

			// Liste des CERs arrivant à échéance -> dont la date de fin est pour le mois en cours
			$echeanceproche = Hash::get( $criteresci, 'Filtre.echeanceproche' );
			if( $echeanceproche ) {
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

			// Liste des CERs échus -> dont la date de fin est au plus tard la date du jour
			$arriveaecheance = Hash::get( $criteresci, 'Filtre.arriveaecheance' );
			if( $arriveaecheance ) {
				$conditions[] = 'Contratinsertion.id IN (
					SELECT
						contratsinsertion.id
					FROM
						contratsinsertion
						WHERE
							date_trunc( \'day\', contratsinsertion.df_ci ) <= DATE( NOW() )
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
							positioncer = \'nonvalid\'
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

            // Filtre pour le CG66 afin d'exclure les CERs dont la date de tacite reconduction est non vide
            if( Configure::read( 'Cg.departement' ) == 66 ) {
                $istacitereconduction = Set::extract( $criteresci, 'Contratinsertion.istacitereconduction' );
                if( isset( $istacitereconduction ) && !empty( $istacitereconduction ) ) {
                    $conditions[] = 'Contratinsertion.datetacitereconduction IS NULL';
                }
            }


            /// Structure référente du parcours lié au référent du parcours
			/*if( !empty( $structureParcours ) ) {
				$conditions[] = 'Structurereferente.id = \''.Sanitize::clean( $structureParcours, array( 'encode' => false ) ).'\'';
			}*/
            // Référent du parcours en cours de l'allocataire
            /*$sqDernierReferent = $this->Contratinsertion->Personne->PersonneReferent->sqDerniere( 'Personne.id' );
			if( !empty( $referentParcours ) ) {
				$conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( suffix( $referentParcours ), array( 'encode' => false ) ).'\'';
			}*/

			$this->Dossier = ClassRegistry::init( 'Dossier' );

			// Dernière orientation
			$conditions[] = array(
				'OR' => array(
					'Orientstruct.id IS NULL',
					'Orientstruct.id IN ( '.$this->Contratinsertion->Personne->Orientstruct->sqDerniere().' )',
				)
			);

			$typeorient_id = Hash::get( $criteresci, 'Orientstruct.typeorient' );
			if( !empty( $typeorient_id ) ) {
				$conditions['Orientstruct.typeorient_id'] = $typeorient_id;
			}
			else if( $typeorient_id != '' && $typeorient_id == 0 ) {
				$conditions[] = 'Orientstruct.id IS NULL';
			}

			$querydata = array(
				'fields' => array_merge(
					$this->Contratinsertion->fields(),
                    $this->Contratinsertion->Personne->fields(),
                    $this->Contratinsertion->Personne->Prestation->fields(),
                    $this->Contratinsertion->Referent->fields(),
                    $this->Contratinsertion->Personne->Foyer->fields(),
                    $this->Contratinsertion->Personne->Foyer->Dossier->fields(),
                    $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->fields(),
                    $this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
                    $this->Contratinsertion->Personne->PersonneReferent->fields(),
					array(
						'Cer93.duree',
						'Typeorient.lib_type_orient',
						$this->Contratinsertion->Referent->sqVirtualField( 'nom_complet' )
					)
                ),
				'recursive' => -1,
				'joins' => array(
                    $this->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
                    $this->Contratinsertion->join( 'Personne', array( 'type' => 'INNER' ) ),
                    $this->Contratinsertion->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
                    $this->Contratinsertion->join( 'Propodecisioncer66', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Contratinsertion->Personne->join( 'Orientstruct',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array( 'Orientstruct.statut_orient' => 'Orienté' )
						)
					),
                    $this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
                    $this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
                    $this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
                    $this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
                    $this->Contratinsertion->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
                    $this->Contratinsertion->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
                    $this->Contratinsertion->Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
                    /*$this->Contratinsertion->Personne->join(
                        'PersonneReferent',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								"PersonneReferent.id IN ( {$sqDernierReferent} )"
                            )
                        )
                    )*/
				),
				'limit' => 10,
				'order' => 'Contratinsertion.df_ci ASC',
				'conditions' => $conditions
			);

			// Référent du parcours
			$querydata = $this->Contratinsertion->Personne->PersonneReferent->completeQdReferentParcours( $querydata, $criteresci );

			return $querydata;
		}
	}
?>