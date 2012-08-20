<?php
	App::import( 'Sanitize' );

	class Gestionsanctionep58 extends AppModel
	{
		public $name = 'Gestionsanctionep58';

		public $useTable = false;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Conditionnable',/*
			'Enumerable' => array(
				'fields' => array(
					'etatcommissionep'
				)
			),*/
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				58 => array(
					'Sanctionep58/finsanction1.odt',
					'Sanctionep58/finsanction2.odt',
					'Sanctionrendezvousep58/finsanction1.odt',
					'Sanctionrendezvousep58/finsanction2.odt',
				)
			)
		);

		/**
		*
		*/

		public function search( $statutSanctionep, $criteressanctionseps, $mesCodesInsee, $filtre_zone_geo, $lockedDossiers ) {
			$Personne = ClassRegistry::init( 'Personne' );
			$Ep = ClassRegistry::init( 'Ep' );

			/// Conditions de base
			$conditions = $Ep->sqRestrictionsZonesGeographiques(
				'Commissionep.ep_id',
				$filtre_zone_geo,
				$mesCodesInsee
			);


			if( !empty( $statutSanctionep ) ) {
				if( $statutSanctionep == 'Gestion::traitement' ) {
					if( !empty( $criteressanctionseps['Decision']['sanction'] ) ) {
						if( $criteressanctionseps['Decision']['sanction'] == 'N' ) {
							$conditions[] = array(
								'AND' => array(
									'Decisionsanctionep58.arretsanction IS NULL',
									'Decisionsanctionrendezvousep58.arretsanction IS NULL'
								)
							);
						}
						else {
							$conditions[] = array(
								'OR' => array(
									'Decisionsanctionep58.arretsanction IS NOT NULL',
									'Decisionsanctionrendezvousep58.arretsanction IS NOT NULL'
								)
							);
						}
					}
				}
				else if( $statutSanctionep == 'Gestion::visualisation' ) {
					$conditions[] = array(
						'OR' => array(
							'Decisionsanctionep58.arretsanction IS NOT NULL',
							'Decisionsanctionrendezvousep58.arretsanction IS NOT NULL'
						)
					);
				}
			}

			// Il faut que la décision1 ou la décision 2 soit une sanction
			$conditions[] = array(
				'OR' => array(
					'Decisionsanctionep58.decision' => 'sanction',
					'Decisionsanctionep58.decision2' => 'sanction',
					'Decisionsanctionrendezvousep58.decision' => 'sanction',
					'Decisionsanctionrendezvousep58.decision2' => 'sanction'
				)
			);

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criteressanctionseps, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsDossier( $conditions, $criteressanctionseps );
			$conditions = $this->conditionsPersonne( $conditions, $criteressanctionseps );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteressanctionseps );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			// Conditions pour les jointures
			$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
			$conditions['Calculdroitrsa.toppersdrodevorsa'] = '1';
			$conditions['Situationdossierrsa.etatdosrsa'] = $Personne->Orientstruct->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert();
			$conditions[] = array(
				'OR' => array(
					'Adressefoyer.id IS NULL',
					'Adressefoyer.id IN ( '
						.$Personne->Foyer->Adressefoyer->sqDerniereRgadr01('Adressefoyer.foyer_id')
					.' )'
				)
			);


			// Le dernier passage d'un dossier d'EP
			$conditions[] = 'Passagecommissionep.id IN ( '.$Personne->Dossierep->Passagecommissionep->sqDernier().' )';

			// Un passage traité pour une commission traitée
			$conditions['Commissionep.etatcommissionep'] = 'traite';
			$conditions['Passagecommissionep.etatdossierep'] = 'traite';

			// FIXME -> une des deux thématiques ou les deux
			if( isset( $criteressanctionseps['Dossierep']['themeep'] ) && !empty( $criteressanctionseps['Dossierep']['themeep'] ) ) {
				$conditions['Dossierep.themeep'] = $criteressanctionseps['Dossierep']['themeep'];
			}
			else {
				$conditions['Dossierep.themeep'] = array( 'sanctionseps58', 'sanctionsrendezvouseps58' );
			}


			if ( isset($criteressanctionseps['Ep']['regroupementep_id']) && !empty($criteressanctionseps['Ep']['regroupementep_id']) ) {
				$conditions[] = array('Ep.regroupementep_id'=>$criteressanctionseps['Ep']['regroupementep_id']);
			}

			if ( isset($criteressanctionseps['Commissionep']['name']) && !empty($criteressanctionseps['Commissionep']['name']) ) {
				$conditions[] = array('Commissionep.name'=>$criteressanctionseps['Commissionep']['name']);
			}

			if ( isset($criteressanctionseps['Commissionep']['identifiant']) && !empty($criteressanctionseps['Commissionep']['identifiant']) ) {
				$conditions[] = array('Commissionep.identifiant'=>$criteressanctionseps['Commissionep']['identifiant']);
			}

			if ( isset($criteressanctionseps['Structurereferente']['ville']) && !empty($criteressanctionseps['Structurereferente']['ville']) ) {
				$conditions[] = array('Commissionep.villeseance'=>$criteressanctionseps['Structurereferente']['ville']);
			}

			/// Critères sur le Comité - date du comité
			if( isset( $criteressanctionseps['Commissionep']['dateseance'] ) && !empty( $criteressanctionseps['Commissionep']['dateseance'] ) ) {
				$valid_from = ( valid_int( $criteressanctionseps['Commissionep']['dateseance_from']['year'] ) && valid_int( $criteressanctionseps['Commissionep']['dateseance_from']['month'] ) && valid_int( $criteressanctionseps['Commissionep']['dateseance_from']['day'] ) );
				$valid_to = ( valid_int( $criteressanctionseps['Commissionep']['dateseance_to']['year'] ) && valid_int( $criteressanctionseps['Commissionep']['dateseance_to']['month'] ) && valid_int( $criteressanctionseps['Commissionep']['dateseance_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Commissionep.dateseance BETWEEN \''.implode( '-', array( $criteressanctionseps['Commissionep']['dateseance_from']['year'], $criteressanctionseps['Commissionep']['dateseance_from']['month'], $criteressanctionseps['Commissionep']['dateseance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteressanctionseps['Commissionep']['dateseance_to']['year'], $criteressanctionseps['Commissionep']['dateseance_to']['month'], $criteressanctionseps['Commissionep']['dateseance_to']['day'] ) ).'\'';
				}
			}


			$query = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Foyer->fields(),
					$Personne->Foyer->Adressefoyer->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Dossierep->fields(),
					$Personne->Dossierep->Passagecommissionep->fields(),
					$Personne->Dossierep->Passagecommissionep->Decisionsanctionep58->fields(),
					$Personne->Dossierep->Passagecommissionep->Decisionsanctionrendezvousep58->fields(),
					$Personne->Dossierep->Passagecommissionep->Commissionep->fields(),
					$Personne->Dossierep->Passagecommissionep->Commissionep->Ep->fields(),
					$Personne->Dossierep->Passagecommissionep->Commissionep->Ep->Regroupementep->fields()
				),
				'joins'=>array(
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Dossierep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->join( 'Passagecommissionep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->Passagecommissionep->join( 'Commissionep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->Passagecommissionep->join( 'Decisionsanctionep58', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Dossierep->Passagecommissionep->join( 'Decisionsanctionrendezvousep58', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Dossierep->Passagecommissionep->Commissionep->join( 'Ep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->Passagecommissionep->Commissionep->Ep->join( 'Regroupementep', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
// 				'order' => array( '"Commissionep"."dateseance" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		 *
		 */
		public function themes() {
			return array(
				'sanctionseps58' => __d( 'dossierep', 'ENUM::THEMEEP::sanctionseps58', true ),
				'sanctionsrendezvouseps58' =>  __d( 'dossierep', 'ENUM::THEMEEP::sanctionsrendezvouseps58', true ),
			);
		}

		/**
		 * Retourne les données nécessaires à l'impression des courriers des personnes passées en EP pour sanction
		 * Les données contiennent les informations de la personne
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $passagecommissionep_id ) {
			$typesvoies = ClassRegistry::init( 'Option' )->typevoie();
			$Personne = ClassRegistry::init( 'Personne' );

			$querydata = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Foyer->fields(),
					$Personne->Foyer->Adressefoyer->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Dossierep->fields(),
					$Personne->Dossierep->Passagecommissionep->fields(),
					$Personne->Dossierep->Passagecommissionep->Decisionsanctionep58->fields(),
					$Personne->Dossierep->Passagecommissionep->Decisionsanctionrendezvousep58->fields(),
					$Personne->Dossierep->Passagecommissionep->Commissionep->fields(),
					$Personne->Dossierep->Passagecommissionep->Commissionep->Ep->fields(),
					$Personne->Dossierep->Passagecommissionep->Commissionep->Ep->Regroupementep->fields()
				),
				'joins' => array(
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Dossierep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->join( 'Passagecommissionep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->Passagecommissionep->join( 'Commissionep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->Passagecommissionep->join( 'Decisionsanctionep58', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Dossierep->Passagecommissionep->join( 'Decisionsanctionrendezvousep58', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Dossierep->Passagecommissionep->Commissionep->join( 'Ep', array( 'type' => 'INNER' ) ),
					$Personne->Dossierep->Passagecommissionep->Commissionep->Ep->join( 'Regroupementep', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Passagecommissionep.id' => $passagecommissionep_id,
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.id IN ( '.$Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
					)
				),
				'contain' => false
			);
			return $querydata;
		}

		/**
		 * Retourne le PDF par défaut généré pour l'impression du courrier de fin de sanciton 1
		 *
		 * @param type $id Id de la personne
		 * @param type $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getPdfSanction( $niveauSanction, $passagecommissionep_id, $themeep, $user_id ) {
			$Option = ClassRegistry::init( 'Option' );
			$Personne = ClassRegistry::init( 'Personne' );

			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				)
			);

			$querydata = $this->getDataForPdf( $passagecommissionep_id );

			$personne = $Personne->find( 'first', $querydata );

			/// Récupération de l'utilisateur
			$user = ClassRegistry::init( 'User' )->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$personne['User'] = $user['User'];

			if( empty( $personne ) ) {
				$this->cakeError( 'error404' );
			}

			$modeleName = Inflector::classify( $themeep );

			if( $niveauSanction == '1' ){
				$modeleodt = "{$modeleName}/finsanction1.odt";
			}
			else{
				$modeleodt = "{$modeleName}/finsanction2.odt";
			}
// debug($personne);
// die();
			return $this->ged(
				$personne,
				$modeleodt,
				false,
				$options
			);
		}


		/**
		 * Retourne le PDF concernant le questionnaire de la personne non orientée
		 *
		 * @param string $search
		 * @param integer $user_id
		 * @return string
		 */
		public function getCohortePdfSanction( $niveauSanction, $statutSanctionep, $mesCodesInsee, $filtre_zone_geo, $search, $page, $user_id ) {

			$querydata = $this->search( $statutSanctionep, $search, $mesCodesInsee, $filtre_zone_geo, null );

			$querydata['limit'] = 100;
			$querydata['offset'] = ( ( $page ) <= 1 ? 0 : ( $querydata['limit'] * ( $page - 1 ) ) );

			$Personne = ClassRegistry::init( 'Personne' );
			$gestionssanctionseps58 = $Personne->find( 'all', $querydata );

			$themeseps = Set::extract( $gestionssanctionseps58, '/Dossierep/themeep' );

			$pdfs = array();
			foreach( $themeseps as $i => $themeep ) {
				$passagecommissionep_id = $gestionssanctionseps58[$i]['Passagecommissionep']['id'];
				$pdfs[] = $this->getPdfSanction( $niveauSanction, $passagecommissionep_id, $themeep, $user_id );
			}

			return $pdfs;
		}
	}
?>