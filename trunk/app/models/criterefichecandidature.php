<?php
	/**
	* Recherche de fiche de candidature (actioncandidat_personne)
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Criterefichecandidature extends AppModel
	{
		public $name = 'Criterefichecandidature';

		public $useTable = false;

		public $actsAs = array(
			'Conditionnable',
			'Formattable' => array(
				'suffix' => array( 'actioncandidat_id' ),
			)
		);

		public function search( $mesCodesInsee, $filtre_zone_geo, $criteresfichescandidature ) {
			/// Conditions de base

			$conditions = array();

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criteresfichescandidature, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criteresfichescandidature );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresfichescandidature );

			if ( isset($criteresfichescandidature['ActioncandidatPersonne']['actioncandidat_id']) && !empty($criteresfichescandidature['ActioncandidatPersonne']['actioncandidat_id']) ) {
				$conditions[] = array('ActioncandidatPersonne.actioncandidat_id'=>$criteresfichescandidature['ActioncandidatPersonne']['actioncandidat_id']);
			}

			if ( isset($criteresfichescandidature['ActioncandidatPersonne']['referent_id']) && !empty($criteresfichescandidature['ActioncandidatPersonne']['referent_id']) ) {
				$conditions[] = array('ActioncandidatPersonne.referent_id'=>$criteresfichescandidature['ActioncandidatPersonne']['referent_id']);
			}

			if ( isset($criteresfichescandidature['Partenaire']['libstruc']) && !empty($criteresfichescandidature['Partenaire']['libstruc']) ) {
				$conditions[] = array('Partenaire.id'=>$criteresfichescandidature['Partenaire']['libstruc']);
			}

			if ( isset($criteresfichescandidature['ActioncandidatPersonne']['positionfiche']) && !empty($criteresfichescandidature['ActioncandidatPersonne']['positionfiche']) ) {
				$conditions[] = array('ActioncandidatPersonne.positionfiche'=>$criteresfichescandidature['ActioncandidatPersonne']['positionfiche']);
			}

			/// Critères sur la date de demande RSA
// 			if( isset( $criteresfichescandidature['Dossier']['dtdemrsa'] ) && !empty( $criteresfichescandidature['Dossier']['dtdemrsa'] ) ) {
// 				$valid_from = ( valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_from']['day'] ) );
// 				$valid_to = ( valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $criteresfichescandidature['Dossier']['dtdemrsa_to']['day'] ) );
// 				if( $valid_from && $valid_to ) {
// 					$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $criteresfichescandidature['Dossier']['dtdemrsa_from']['year'], $criteresfichescandidature['Dossier']['dtdemrsa_from']['month'], $criteresfichescandidature['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresfichescandidature['Dossier']['dtdemrsa_to']['year'], $criteresfichescandidature['Dossier']['dtdemrsa_to']['month'], $criteresfichescandidature['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
// 				}
// 			}

			/// Critères sur la date de signature de la fiche de candidature
			if( isset( $criteresfichescandidature['ActioncandidatPersonne']['datesignature'] ) && !empty( $criteresfichescandidature['ActioncandidatPersonne']['datesignature'] ) ) {
				$valid_from = ( valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['year'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['month'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['day'] ) );
				$valid_to = ( valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['year'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['month'] ) && valid_int( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'ActioncandidatPersonne.datesignature BETWEEN \''.implode( '-', array( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['year'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['month'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['year'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['month'], $criteresfichescandidature['ActioncandidatPersonne']['datesignature_to']['day'] ) ).'\'';
				}
			}

			$joins = array(
				array(
					'table'      => 'actionscandidats',
					'alias'      => 'Actioncandidat',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Actioncandidat.id = ActioncandidatPersonne.actioncandidat_id' ),
				),
				array(
					'table'      => 'contactspartenaires',
					'alias'      => 'Contactpartenaire',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Contactpartenaire.id = Actioncandidat.contactpartenaire_id' ),
				),
				array(
					'table'      => 'partenaires',
					'alias'      => 'Partenaire',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Contactpartenaire.partenaire_id = Partenaire.id' ),
				),
				array(
					'table'      => 'motifssortie',
					'alias'      => 'Motifsortie',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Motifsortie.id = ActioncandidatPersonne.motifsortie_id' ),
				),
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.id = ActioncandidatPersonne.personne_id' ),
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
					'conditions' => array(
						'Foyer.id = Adressefoyer.foyer_id',
						'Adressefoyer.id IN (
							'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
						)'
					)
				),
				array(
					'table'      => 'adresses',
					'alias'      => 'Adresse',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
				),
				array(
					'table'      => 'referents',
					'alias'      => 'Referent',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Referent.id = ActioncandidatPersonne.referent_id' ),
				),
				array(
					'table'      => 'structuresreferentes',
					'alias'      => 'Structurereferente',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Structurereferente.id = Referent.structurereferente_id' ),
				),
				array(
					'table'      => 'situationsdossiersrsa',
					'alias'      => 'Situationdossierrsa',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
				)
			);

			$query = array(
				'fields' => array(
					'ActioncandidatPersonne.id',
					'ActioncandidatPersonne.actioncandidat_id',
					'ActioncandidatPersonne.personne_id',
					'ActioncandidatPersonne.referent_id',
					'ActioncandidatPersonne.datesignature',
					'ActioncandidatPersonne.positionfiche',
					'ActioncandidatPersonne.motifsortie_id',
					'ActioncandidatPersonne.sortiele',
					'Actioncandidat.name',
					'Partenaire.libstruc',
					'Motifsortie.name',
					'Personne.qual',
					'Personne.nom',
					'Personne.nomnai',
					'Personne.prenom',
					'Personne.nir',
					'Referent.qual',
					'Referent.nom',
					'Referent.prenom',
					'Dossier.matricule',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt'
				),
				'joins' => $joins,
				'contain' => false,
				'order' => array( '"ActioncandidatPersonne"."datesignature" ASC' ),
				'conditions' => $conditions
			);
			return $query;
		}
	}
?>