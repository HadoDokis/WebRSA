<?php
	/**
	 * Code source de la classe Indicateursuivi.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Indicateursuivi ...
	 *
	 * @package app.Model
	 */
	class Indicateursuivi extends AppModel
	{
		public $name = 'Indicateursuivi';
		public $useTable = false;
		public $actsAs = array('Conditionnable');

		/*public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
			$conditions = array();

			$conditions[] = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions[] = $this->conditionsDernierDossierAllocataire( $conditions, $params );
			$conditions[] = $this->conditionsPersonneFoyerDossier( $conditions, $params );

			if( isset( $params['Detailcalculdroitrsa']['natpf'] ) && !empty( $params['Detailcalculdroitrsa']['natpf'] ) ) {
				$conditions[] = "Dossier.id IN ( SELECT detailsdroitsrsa.dossier_id FROM detailsdroitsrsa INNER JOIN detailscalculsdroitsrsa ON detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id WHERE detailscalculsdroitsrsa.natpf ILIKE '%".Sanitize::paranoid( $params['Detailcalculdroitrsa']['natpf'] )."%' )";
			}

			if( isset($params['Orientstruct']['structurereferente_id']) && !empty($params['Orientstruct']['structurereferente_id']) ) {
				$structurereferente_id = $params['Orientstruct']['structurereferente_id'];
				$structurereferente_id = explode('_', $structurereferente_id);
				$conditions[] = 'Orientstruct.structurereferente_id = '.$structurereferente_id[1];
			}

			if( isset($params['Orientstruct']['referent_id']) && !empty($params['Orientstruct']['referent_id']) ) {
				$conditions[] = 'Orientstruct.referent_id = '.$params['Orientstruct']['referent_id'];
			}



			$query = array(
				'fields' => array(
					'"Dossier"."dtdemrsa"',
					'"Dossier"."matricule"',
					'"Dossier"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."dtnai"',
					'"Personne"."id"',
					'"Foyer"."id"',
					'"Prestation"."rolepers"',
					'"Dossier"."numdemrsa"',
					'"Adresse"."numvoie"',
					'"Adresse"."typevoie"',
					'"Adresse"."nomvoie"',
					'"Adresse"."compladr"',
					'"Adresse"."codepos"',
					'"Adresse"."locaadr"',
					'"Orientstruct"."referent_id"',
					'"Orientstruct"."rgorient"',
					'"Cov58"."datecommission"',
					'"PersonneReferent"."referent_id"',
					'"Contratinsertion"."dd_ci"',
					'"Contratinsertion"."df_ci"',
					'"Contratinsertion"."rg_ci"',
					//'"Historiqueetatpe"."date"',
					'"Commissionep"."dateseance"',
					'"Dossierep"."themeep"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.rolepers' => array( 'DEM', 'CJT' )
						)
					),
					array(
						'table'      => 'dossierseps',
						'alias'      => 'Dossierep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Dossierep.personne_id')
					),
					array(
						'table'      => 'contratsinsertion',
						'alias'      => 'Contratinsertion',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Contratinsertion.personne_id')
					),
					array(
						'table'      => 'orientsstructs',
						'alias'      => 'Orientstruct',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Orientstruct.personne_id'	)
					),
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Passagecommissionep.dossierep_id = Dossierep.id' )
					),
					array(
						'table'      => 'commissionseps',
						'alias'      => 'Commissionep',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Passagecommissionep.commissionep_id = Commissionep.id' )
					),
					array(
						'table'      => 'personnes_referents',
						'alias'      => 'PersonneReferent',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'PersonneReferent.personne_id = Personne.id' )
					),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'PersonneReferent.referent_id = Referent.id' )
					),
					array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id'
						)
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'dossierscovs58',
						'alias'      => 'Dossiercov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Dossiercov58.personne_id')
					),
					array(
						'table'      => 'passagescovs58',
						'alias'      => 'Passagecov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Passagecov58.dossiercov58_id = Dossiercov58.id' )
					),
					array(
						'table'      => 'covs58',
						'alias'      => 'Cov58',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Passagecov58.cov58_id = Cov58.id' )
					)
				),
				'limit' => 10,
				'order' => array( 'Personne.nom ASC' ),
				'conditions' => $conditions
			);
			return $query;
		}*/
		public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
			$conditions = array();

			$Dossier = ClassRegistry::init( 'Dossier' );
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			$conditions[] = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions[] = $this->conditionsDernierDossierAllocataire( $conditions, $params );
			$conditions[] = $this->conditionsPersonneFoyerDossier( $conditions, $params );

			// Filtre par orientation
			if( isset($params['Orientstruct']['structurereferente_id']) && !empty($params['Orientstruct']['structurereferente_id']) ) {
				$structurereferente_id = $params['Orientstruct']['structurereferente_id'];
				$structurereferente_id = explode('_', $structurereferente_id);
				$conditions[] = 'Orientstruct.structurereferente_id = '.$structurereferente_id[1];
			}

			if( isset($params['Orientstruct']['referent_id']) && !empty($params['Orientstruct']['referent_id']) ) {
				$conditions[] = 'Orientstruct.referent_id = '.$params['Orientstruct']['referent_id'];
			}

			// Filtre par chargé d'évaluation
			if( isset($params['Propoorientationcov58']['structureorientante_id']) && !empty($params['Propoorientationcov58']['structureorientante_id']) ) {
				$conditions[] = 'Propoorientationcov58.structureorientante_id = '.suffix( $params['Propoorientationcov58']['structureorientante_id'] );
			}

			if( isset($params['Propoorientationcov58']['referentorientant_id']) && !empty($params['Propoorientationcov58']['referentorientant_id']) ) {
				$conditions[] = 'Propoorientationcov58.referentorientant_id = '.$params['Propoorientationcov58']['referentorientant_id'];
			}


			// Conditions de base pour qu'un allocataire puisse passer en EP
			$conditions['Prestation.rolepers'] = array( 'DEM' );
			$conditions['Calculdroitrsa.toppersdrodevorsa'] = '1';
			$conditions['Situationdossierrsa.etatdosrsa'] = $Dossier->Situationdossierrsa->etatOuvert();
			$conditions[] = 'Adressefoyer.id IN ( '.$Dossier->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )';

			// La dernière orientation du demandeur
			$conditions[] = array(
				'OR' => array(
					'Orientstruct.id IS NULL',
					'Orientstruct.id IN ( '.$Dossier->Foyer->Personne->Orientstruct->sqDerniere().' )'
				)
			);

			// Le dernier PersonneReferent
			$conditions[] = 'PersonneReferent.id IN ( '.$Dossier->Foyer->Personne->PersonneReferent->sqDerniere( 'Personne.id' ).' )';

			// Le dernier contrat du demandeur
			$conditions[] = array(
				'OR' => array(
					'Contratinsertion.id IS NULL',
					'Contratinsertion.id IN ( '.$Dossier->Foyer->Personne->Contratinsertion->sqDernierContrat().' )'
				)
			);

			// Le dossier d'EP le plus récent
			$conditions[] = array(
				'OR' => array(
					'Dossierep.id IS NULL',
					'Dossierep.id IN ( '.$Dossier->Foyer->Personne->Dossierep->sqDernierPassagePersonne().' )'
				)
			);

			// La dernière information venant de Pôle Emploi, si celle-ci est une inscription
			$conditions[] = array(
				'OR' => array(
					'Informationpe.id IS NULL',
					'Informationpe.id IN ( '.$Informationpe->sqDerniere().' )'
				)
			);
			// TODO: conditions;
			//$conditions[] = array( 'Propoorientationcov58.referentorientant_id = \''.Sanitize::clean( $referent_id ).'\'' );

			$query = array(
				'fields' => array(
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Dossier.id',
					$Dossier->Foyer->Personne->sqVirtualField( 'nom_complet' ),
// 					'Personne.qual',
// 					'Personne.nom',
// 					'Personne.prenom',
					'Personne.dtnai',
					'Personne.id',
					'Foyer.id',
					'Prestation.rolepers',
					'Dossier.numdemrsa',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.compladr',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Orientstruct.id',
					'Orientstruct.date_valid',
					'Orientstruct.rgorient',
//					str_replace( 'Referent', 'Referentorient', $Dossier->Foyer->Personne->Referent->sqVirtualField( 'nom_complet' ) ),
					$Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58->Referentorientant->sqVirtualField( 'nom_complet' ),
					str_replace( 'Referent', 'Referentunique', $Dossier->Foyer->Personne->Referent->sqVirtualField( 'nom_complet' ) ),
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Contratinsertion.rg_ci',
					'Historiqueetatpe.etat',
					'Historiqueetatpe.date',
// 					'( CASE WHEN "Historiqueetatpe"."etat" = \'inscription\' THEN "Historiqueetatpe"."date" ELSE NULL END ) AS "Historiqueetatpe__date"',
					'Commissionep.dateseance',
					'Dossierep.themeep'
				),
				'recursive' => -1,
				'joins' => array(
					$Dossier->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Contratinsertion', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
//					array_words_replace( $Dossier->Foyer->Personne->Orientstruct->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ), array( 'Referent' => 'Referentorient' ) ),
					$Dossier->Foyer->Personne->join( 'Dossiercov58', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->Dossiercov58->join( 'Propoorientationcov58', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58->join( 'Referentorientant', array( 'type' => 'LEFT OUTER' ) ),


					$Dossier->Foyer->Personne->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) ),
					array_words_replace( $Dossier->Foyer->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ), array( 'Referent' => 'Referentunique' ) ),

					$Dossier->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					// Partie EP
					$Dossier->Foyer->Personne->join( 'Dossierep', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->Dossierep->join( 'Passagecommissionep', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->Dossierep->Passagecommissionep->join( 'Commissionep', array( 'type' => 'LEFT OUTER' ) ),

					$Informationpe->joinPersonneInformationpe(),
					$Informationpe->Historiqueetatpe->joinInformationpeHistoriqueetatpe(),
				),
				'order' => array( 'Personne.nom ASC' ),
				'limit' => 10,
				'conditions' => $conditions
			);
			return $query;
		}

	}
?>