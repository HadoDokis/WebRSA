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

			// Un dossier possède un seul detail du droit RSA mais ce dernier possède plusieurs details de calcul
			// donc on limite au dernier detail de calcul du droit rsa
			$sqDernierDetailcalculdroitrsa = $Cui->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->sqDernier( 'Detaildroitrsa.id' );

			$conditions = array(
				'Prestation.natprest' => 'RSA',
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.id IN ( '.$Cui->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
					)
				),
				"Detailcalculdroitrsa.id IN ( {$sqDernierDetailcalculdroitrsa} )"
			);

			$conditions = $this->conditionsAdresse( $conditions, $criterescuis, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criterescuis );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criterescuis );


			/// Critères
			$datecontrat = Set::extract( $criterescuis, 'Cui.datecontrat' );
			$secteurcui_id = Set::extract( $criterescuis, 'Cui.secteurcui_id' );
			$isaci = Set::extract( $criterescuis, 'Cui.isaci' );
			$nir = Set::extract( $criterescuis, 'Cui.nir' );
			$oridemrsa = Set::extract( $criterescuis, 'Dossier.oridemrsa' );
			$handicap = Set::extract( $criterescuis, 'Cui.handicap' );
			$niveauformation = Set::extract( $criterescuis, 'Cui.niveauformation' );
			$compofamiliale = Set::extract( $criterescuis, 'Cui.compofamiliale' );
			$typecui = Set::extract( $criterescuis, 'Cui.typecui' );
            $positioncui66 = Set::extract( $criterescuis, 'Cui.positioncui66' );
            $decisioncui = Set::extract( $criterescuis, 'Cui.decisioncui' );
            $employeur_id = Set::extract( $criterescuis, 'Cui.partenaire_id' );


			// Origine de la demande
			if( !empty( $oridemrsa ) ) {
				$conditions[] = 'Detaildroitrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
			}

			/// Critères sur les dates du CUI - date de saisi du contrat, date de fin de titre de séjour,
            foreach( array( 'datecontrat', 'datefintitresejour' ) as $date ) {
                if( isset( $criterescuis['Cui'][$date] ) && !empty( $criterescuis['Cui'][$date] ) ) {
                    $valid_from = ( valid_int( $criterescuis['Cui']["{$date}_from"]['year'] ) && valid_int( $criterescuis['Cui']["{$date}_from"]['month'] ) && valid_int( $criterescuis['Cui']["{$date}_from"]['day'] ) );
                    $valid_to = ( valid_int( $criterescuis['Cui']["{$date}_to"]['year'] ) && valid_int( $criterescuis['Cui']["{$date}_to"]['month'] ) && valid_int( $criterescuis['Cui']["{$date}_to"]['day'] ) );
                    if( $valid_from && $valid_to ) {
                        $conditions[] = 'Cui.'.$date.' BETWEEN \''.implode( '-', array( $criterescuis['Cui']["{$date}_from"]['year'], $criterescuis['Cui']["{$date}_from"]['month'], $criterescuis['Cui']["{$date}_from"]['day'] ) ).'\' AND \''.implode( '-', array( $criterescuis['Cui']["{$date}_to"]['year'], $criterescuis['Cui']["{$date}_to"]['month'], $criterescuis['Cui']["{$date}_to"]['day'] ) ).'\'';
                    }
                }
            }

			// Type de CUI
			if( !empty( $typecui ) ) {
				$conditions[] = 'Cui.typecui = \''.Sanitize::clean( $typecui, array( 'encode' => false ) ).'\'';
			}

			// Secteur du contrat
			if( !empty( $secteurcui_id ) ) {
				$conditions[] = 'Cui.secteurcui_id = \''.Sanitize::clean( $secteurcui_id, array( 'encode' => false ) ).'\'';
			}

			// Hors ACI /ACI
			if( !empty( $isaci ) ) {
				$conditions[] = 'Cui.isaci = \''.Sanitize::clean( $isaci, array( 'encode' => false ) ).'\'';
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

			// Position du CUI
			if( !empty( $positioncui66 ) ) {
				$conditions[] = 'Cui.positioncui66 = \''.Sanitize::clean( $positioncui66, array( 'encode' => false ) ).'\'';
			}

            // Décision sur CUI
			if( !empty( $decisioncui ) ) {
				$conditions[] = 'Cui.decisioncui = \''.Sanitize::clean( $decisioncui, array( 'encode' => false ) ).'\'';
			}

 			// Sur l'employeur (partenaire)
            if( isset( $employeur_id ) && !empty( $employeur_id ) ) {
                $conditions[] = 'Cui.partenaire_id = \''.Sanitize::clean( $employeur_id, array( 'encode' => false ) ).'\'';
            }

 			// Sur le secteur proposé
            $secteurproposeId = Hash::get( $criterescuis, 'Cui.secteuremploipropose_id' );
            if( isset( $secteurproposeId ) && !empty( $secteurproposeId ) ) {
                $conditions[] = 'Cui.secteuremploipropose_id = \''.Sanitize::clean( $secteurproposeId, array( 'encode' => false ) ).'\'';
            }

 			// Sur le métier proposé
            $metierproposeId = Hash::get( $criterescuis, 'Cui.metieremploipropose_id' );
            if( isset( $metierproposeId ) && !empty( $metierproposeId ) ) {
                $conditions[] = 'Cui.metieremploipropose_id = \''.Sanitize::clean( suffix($metierproposeId), array( 'encode' => false ) ).'\'';
            }

 			// Sur le poste proposé
            $postepropose = Hash::get( $criterescuis, 'Cui.postepropose' );
            if( isset( $postepropose ) && !empty( $postepropose ) ) {
                $conditions[] = array('Cui.postepropose ILIKE \''.$this->wildcard( $postepropose ).'\'');
            }

			$query = array(
				'fields' => array_merge(
					$Cui->fields(),
					$Cui->Personne->fields(),
					$Cui->Partenaire->fields(),
					$Cui->Personne->Foyer->fields(),
					$Cui->Personne->Prestation->fields(),
					$Cui->Personne->Foyer->Dossier->fields(),
					$Cui->Personne->Foyer->Dossier->Situationdossierrsa->fields(),
					$Cui->Personne->Foyer->Dossier->Detaildroitrsa->fields(),
					$Cui->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->fields(),
					$Cui->Personne->Foyer->Adressefoyer->fields(),
					$Cui->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Cui->Personne->Calculdroitrsa->fields(),
                    array(
                        'Titresejour.dftitsej'
                    )
				),
				'joins' => array(
					$Cui->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Cui->join( 'Partenaire', array( 'type' => 'LEFT OUTER' ) ),
					$Cui->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Cui->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Cui->Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
                    $Cui->Personne->join( 'Titresejour', array( 'type' => 'LEFT OUTER' ) ),
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

			$query = $Cui->Personne->PersonneReferent->completeQdReferentParcours( $query, $criterescuis );

			return $query;
		}
	}
?>