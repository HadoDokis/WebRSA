<?php
	App::import( 'Sanitize' );

	class Indicateursuivi extends AppModel
	{
		public $name = 'Indicateursuivi';
		public $useTable = false;
		public $actsAs = array('Conditionnable');

		public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
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
		}


	}
?>