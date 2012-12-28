<?php
	/**
	 * Fichier source de la classe Criterecui.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Criterecui s'occupe du moteur de recherche des CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Model
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
			$Cui = ClassRegistry::init( 'Cui' );

			$conditions = array(
				'Prestation.natprest' => 'RSA',
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.id IN ( '.$Cui->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
					)
				)
			);

			$conditions = $this->conditionsAdresse( $conditions, $criterescuis, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criterescuis );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criterescuis );
			

			/// Critères
			$datecontrat = Set::extract( $criterescuis, 'Cui.datecontrat' );
			$secteur = Set::extract( $criterescuis, 'Cui.secteur' );
			$nir = Set::extract( $criterescuis, 'Cui.nir' );
			$oridemrsa = Set::extract( $criterescuis, 'Dossier.oridemrsa' );
			$handicap = Set::extract( $criterescuis, 'Cui.handicap' );
			$niveauformation = Set::extract( $criterescuis, 'Cui.niveauformation' );
			$compofamiliale = Set::extract( $criterescuis, 'Cui.compofamiliale' );

			// Origine de la demande
			if( !empty( $oridemrsa ) ) {
				$conditions[] = 'Detaildroitrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
			}

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
				$conditions[] = 'Cui.secteur = \''.Sanitize::clean( $secteur, array( 'encode' => false ) ).'\'';
			}
			
			// Handicape ?
			if( !empty( $handicap ) ) {
				$conditions[] = 'Cui.handicap = \''.Sanitize::clean( $handicap, array( 'encode' => false ) ).'\'';
			}
			
			// Niveau de formation
			if( !empty( $niveauformation ) ) {
				$conditions[] = 'Cui.niveauformation = \''.Sanitize::clean( $niveauformation, array( 'encode' => false ) ).'\'';
			}
			
			// Composition du foyer
			if( !empty( $compofamiliale ) ) {
				$conditions[] = 'Cui.compofamiliale = \''.Sanitize::clean( $compofamiliale, array( 'encode' => false ) ).'\'';
			}
			

			$query = array(
				'fields' => array_merge(
					$Cui->fields(),
					$Cui->Personne->fields(),
					$Cui->Personne->Foyer->fields(),
					$Cui->Personne->Prestation->fields(),
					$Cui->Personne->Foyer->Dossier->fields(),
					$Cui->Personne->Foyer->Dossier->Situationdossierrsa->fields(),
					$Cui->Personne->Foyer->Dossier->Detaildroitrsa->fields(),
					$Cui->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->fields(),
					$Cui->Personne->Foyer->Adressefoyer->fields(),
					$Cui->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Cui->Personne->Calculdroitrsa->fields()
				),
				'joins' => array(
					$Cui->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Cui->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Cui->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Cui->Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Cui->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Cui->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Cui->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Cui->Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					$Cui->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'LEFT OUTER' ) ),
					$Cui->Personne->Foyer->Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'INNER' ) )
				),
				'conditions' => $conditions,
				'contain' => false,
				'limit' => 10
			);

			return $query;
		}
	}
?>