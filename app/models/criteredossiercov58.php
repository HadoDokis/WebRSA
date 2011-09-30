<?php
	/**
	* Séance d'équipe pluridisciplinaire.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Criteredossiercov58 extends AppModel
	{
		public $name = 'Criteredossiercov58';
		public $useTable = false;


		public function search( $mesCodesInsee, $filtre_zone_geo, $criteresdossierscovs58 ) {
			/// Conditions de base

			$conditions = array();

			/// Filtre zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			if ( isset($criteresdossierscovs58['Dossiercov58']['etapecov']) && !empty($criteresdossierscovs58['Dossiercov58']['etapecov']) ) {
				$conditions[] = array('Dossiercov58.etapecov'=>$criteresdossierscovs58['Dossiercov58']['etapecov']);
			}


			if ( isset($criteresdossierscovs58['Dossiercov58']['themecov58_id']) && !empty($criteresdossierscovs58['Dossiercov58']['themecov58_id']) ) {
				$conditions[] = array('Dossiercov58.themecov58_id'=>$criteresdossierscovs58['Dossiercov58']['themecov58_id']);
			}

			$fieldsPers = array( 'nom', 'prenom', 'nir' );
			foreach( $fieldsPers as $field ){
				if ( isset($criteresdossierscovs58['Personne'][$field]) && !empty($criteresdossierscovs58['Personne'][$field]) ) {
					$conditions[] = array("Personne.$field ILIKE"=> $this->wildcard( $criteresdossierscovs58['Personne'][$field]) );
				}
			}

			$fieldsDoss = array( 'matricule', 'numdemrsa' );
			foreach( $fieldsDoss as $fieldDos ){
				if ( isset($criteresdossierscovs58['Dossier'][$fieldDos]) && !empty($criteresdossierscovs58['Dossier'][$fieldDos]) ) {
					$conditions[] = array("Dossier.$fieldDos ILIKE"=> $this->wildcard( $criteresdossierscovs58['Dossier'][$fieldDos]) );
				}
			}

			if ( isset($criteresdossierscovs58['Cov58']['sitecov58_id']) && !empty($criteresdossierscovs58['Cov58']['sitecov58_id']) ) {
				$conditions[] = array('Cov58.sitecov58_id'=>$criteresdossierscovs58['Cov58']['sitecov58_id']);
			}

			/// Critères sur la date de la COV
			if( isset( $criteresdossierscovs58['Cov58']['datecommission'] ) && !empty( $criteresdossierscovs58['Cov58']['datecommission'] ) ) {
				$valid_from = ( valid_int( $criteresdossierscovs58['Cov58']['datecommission_from']['year'] ) && valid_int( $criteresdossierscovs58['Cov58']['datecommission_from']['month'] ) && valid_int( $criteresdossierscovs58['Cov58']['datecommission_from']['day'] ) );
				$valid_to = ( valid_int( $criteresdossierscovs58['Cov58']['datecommission_to']['year'] ) && valid_int( $criteresdossierscovs58['Cov58']['datecommission_to']['month'] ) && valid_int( $criteresdossierscovs58['Cov58']['datecommission_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Cov58.datecommission BETWEEN \''.implode( '-', array( $criteresdossierscovs58['Cov58']['datecommission_from']['year'], $criteresdossierscovs58['Cov58']['datecommission_from']['month'], $criteresdossierscovs58['Cov58']['datecommission_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresdossierscovs58['Cov58']['datecommission_to']['year'], $criteresdossierscovs58['Cov58']['datecommission_to']['month'], $criteresdossierscovs58['Cov58']['datecommission_to']['day'] ) ).'\'';
				}
			}

			$joins = array(
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.id = Dossiercov58.personne_id' ),
				),
				array(
					'table'      => 'foyers',
					'alias'      => 'Foyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Foyer.id = Personne.foyer_id' ),
				),
				array(
					'table'      => 'dossiers',
					'alias'      => 'Dossier',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Foyer.dossier_id = Dossier.id' ),
				),
				array(
					'table'      => 'covs58',
					'alias'      => 'Cov58',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Cov58.id = Dossiercov58.cov58_id' ),
				),
				/*,
				array(
					'table'      => 'referents',
					'alias'      => 'Referent',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Referent.id = Dossiercov58.referent_id' ),
				),
				array(
					'table'      => 'structuresreferentes',
					'alias'      => 'Structurereferente',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Structurereferente.id = Referent.structurereferente_id' ),
				),
				array(
					'table'      => 'defautsinsertionseps66',
					'alias'      => 'Defautinsertionep66',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Dossiercov58.id = Defautinsertionep66.Dossiercov58_id' ),
				),
				array(
					'table'      => 'saisinesepsbilansparcours66',
					'alias'      => 'SaisineepDossiercov58',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Dossiercov58.id = SaisineepDossiercov58.Dossiercov58_id' ),
				),
				array(
					'table'      => 'dossierseps',
					'alias'      => 'Dossierep',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						'OR' => array(
							'Defautinsertionep66.dossierep_id = Dossierep.id',
							'SaisineepDossiercov58.dossierep_id = Dossierep.id',
						)
					),
				)*/
			);


			$query = array(
				'fields' => array(
					'Dossiercov58.id',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58_id',
					'Dossiercov58.cov58_id',
					'Dossiercov58.etapecov',
					'Cov58.datecommission',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Dossier.numdemrsa'/*,
					'Personne.nom_complet'*/
				),
				'joins' => $joins,
				'contain' => false,
//                 'order' => array( '"Dossiercov58"."datecommission" ASC' ),
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>