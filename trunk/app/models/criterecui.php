<?php
	/**
	 * Fichier source de la classe Criterecui.
	 *
	 * PHP 5.3
	 *
	 * @package app.models
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import( 'Sanitize' );

	/**
	 * La classe Criterecui s'occupe du moteur de recherche des CUIs (CG 58, 66 et 93).
	 *
	 * @package app.models
	 */
	class Criterecui extends AppModel
	{
		public $name = 'Criterecui';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		 * Traitement du formulaire de recherche concernant les CUIs.
		 *
		 * @param array $mesCodesInsee La liste des codes INSEE à laquelle est lié l'utilisateur
		 * @param boolean $filtre_zone_geo L'utilisateur est-il limité au niveau des zones géographiques ?
		 * @param array $criterescuis Critères du formulaire de recherche
		 * @return array
		 */
		public function search( $mesCodesInsee, $filtre_zone_geo, $criterescuis ) {
			/// Conditions de base
			$conditions = array();

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criterescuis, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criterescuis );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criterescuis );

			/// Critères
			$datecontrat = Set::extract( $criterescuis, 'Cui.datecontrat' );
			$secteur = Set::extract( $criterescuis, 'Cui.secteur' );
			$nir = Set::extract( $criterescuis, 'Cui.nir' );


			/// Critères sur le CI - date de saisi contrat
			if( isset( $criterescuis['Cui']['datecontrat'] ) && !empty( $criterescuis['Cui']['datecontrat'] ) ) {
				$valid_from = ( valid_int( $criterescuis['Cui']['datecontrat_from']['year'] ) && valid_int( $criterescuis['Cui']['datecontrat_from']['month'] ) && valid_int( $criterescuis['Cui']['datecontrat_from']['day'] ) );
				$valid_to = ( valid_int( $criterescuis['Cui']['datecontrat_to']['year'] ) && valid_int( $criterescuis['Cui']['datecontrat_to']['month'] ) && valid_int( $criterescuis['Cui']['datecontrat_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Cui.datecontrat BETWEEN \''.implode( '-', array( $criterescuis['Cui']['datecontrat_from']['year'], $criterescuis['Cui']['datecontrat_from']['month'], $criterescuis['Cui']['datecontrat_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescuis['Cui']['datecontrat_to']['year'], $criterescuis['Cui']['datecontrat_to']['month'], $criterescuis['Cui']['datecontrat_to']['day'] ) ).'\'';
				}
			}

			// Secteur du contrat
			if( !empty( $secteur ) ) {
				$conditions[] = 'Cui.secteur = \''.Sanitize::clean( $secteur ).'\'';
			}


			/// Référent
			if( !empty( $referent_id ) ) {
				$conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
			}

			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$query = array(
				'fields' => array(
					'Cui.id',
					'Cui.personne_id',
					'Cui.secteur',
					'Cui.datecontrat',
					'Cui.nomemployeur',
					'Cui.datedebprisecharge',
					'Cui.datefinprisecharge',
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
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt',
					'PersonneReferent.referent_id',
					'Prestation.rolepers'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Cui.personne_id' )
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
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					)
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>