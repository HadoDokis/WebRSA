<?php
	/**
	 * Code source de la classe Cohortecer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.models
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortecer93 permet de rechercher le allocataires ne possédant pas de référent de parcours
	 * en cours.
	 *
	 * @package app.models
	 */
	class Cohortecer93 extends AppModel
	{
		/**
		 * @var string
		 */
		public $name = 'Cohortecer93';

		/**
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * @var array
		 */
		public $actsAs = array( 'Conditionnable' );

		/**
		 * Retourne un querydata résultant du traitement du formulaire de recherche des cohortes de référent
		 * du parcours.
		 *
		 * @param type $statut
		 * @param array $mesCodesInsee La liste des codes INSEE à laquelle est lié l'utilisateur
		 * @param boolean $filtre_zone_geo L'utilisateur est-il limité au niveau des zones géographiques ?
		 * @param array $search Critères du formulaire de recherche
		 * @param mixed $lockedDossiers
		 * @return array
		 */
		public function search( $statut, $mesCodesInsee, $filtre_zone_geo, $search, $lockedDossiers ) {
			$Personne = ClassRegistry::init( 'Personne' );

			$sqDerniereRgadr01 = $Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' );
			$sqDerniereOrientstruct = $Personne->Orientstruct->sqDerniere();
			$sqDernierContratinsertion = $Personne->sqLatest( 'Contratinsertion', 'rg_ci'/*, array( 'Contratinsertion.decision_ci' => 'V' )*/ );
			$sqDernierReferent = $Personne->PersonneReferent->sqDerniere( 'Personne.id' );
			$sqDernierRdv = $Personne->Rendezvous->sqDernier( 'Personne.id' );
			
			$sqDspId = 'SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1';
			$sqDspExists = "( {$sqDspId} ) IS NOT NULL";

			$conditions = array(
// 				'Contratinsertion.decision_ci' => 'E',
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						"Adressefoyer.id IN ( {$sqDerniereRgadr01} )"
					)
				),
				$sqDernierContratinsertion,
				array(
					"PersonneReferent.id IN ( {$sqDernierReferent} )",
					'PersonneReferent.dfdesignation IS NULL'
				),
				array(
					'OR' => array(
						'Rendezvous.id IS NULL',
						"Rendezvous.id IN ( {$sqDernierRdv} )"
					)
				)
			);
			
			// Choix du référent affecté ?
			if( isset( $search['PersonneReferent']['referent_id'] ) && ( $search['PersonneReferent']['referent_id'] != '' ) ) {
				$conditions['PersonneReferent.referent_id'] = $search['PersonneReferent']['referent_id'];
			}
			$conditions = $this->conditionsDates( $conditions, $search, 'PersonneReferent.dddesignation' );

			// Présence DSP ?
			if( isset( $search['Dsp']['exists'] ) && ( $search['Dsp']['exists'] != '' ) ) {
				if( $search['Dsp']['exists'] ) {
					$conditions[] = "( {$sqDspExists} )";
				}
				else {
					$conditions[] = "( ( {$sqDspId} ) IS NULL )";
				}
			}

			// Présence CER ?
			if( isset( $search['Contratinsertion']['exists'] ) && ( $search['Contratinsertion']['exists'] != '' ) ) {
				if( $search['Contratinsertion']['exists'] ) {
					$conditions[] = "( ( {$sqDernierContratinsertion} ) IS NOT NULL )";
				}
				else {
					$conditions[] = "( ( {$sqDernierContratinsertion} ) IS NULL )";
				}
			}

			$conditions = $this->conditionsAdresse( $conditions, $search, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $search );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $search );
			$conditions = $this->conditionsDates( $conditions, $search, 'Orientstruct.date_valid' );
			
			//Filtre sur la position du CER
			$positioncer = Set::extract( $search, 'Cer93.positioncer' );
			if( isset( $search['Cer93']['positioncer'] ) && !empty( $search['Cer93']['positioncer'] ) ) {
				$conditions[] = '( Cer93.positioncer IN ( \''.implode( '\', \'', $positioncer ).'\' ) )';
			}

			$querydata = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Calculdroitrsa->fields(),
					$Personne->Contratinsertion->fields(),
					$Personne->Contratinsertion->Cer93->fields(),
					$Personne->Orientstruct->fields(),
					$Personne->Prestation->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Rendezvous->fields(),
					$Personne->PersonneReferent->fields(),
					$Personne->Foyer->Dossier->Situationdossierrsa->fields(),
					// Présence DSP
					array(
						$Personne->sqVirtualField( 'nom_complet_court', true ),
						"( {$sqDspExists} ) AS \"Dsp__exists\"" // TODO: mettre dans le modèle
					)
				),
				'contain' => false,
				'joins' => array(
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->join(
						'Orientstruct',
						array(
							'conditions' => array(
								'OR' => array(
									'Orientstruct.id IS NULL',
									"Orientstruct.id IN ( {$sqDerniereOrientstruct} )"
								)
							),
							'type' => 'LEFT OUTER'
						)
					),
					$Personne->join( 'Contratinsertion', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'PersonneReferent', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Rendezvous', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions,
				'order' => array(
					'Orientstruct.date_valid ASC',
					'Personne.nom ASC',
					'Personne.prenom ASC',
				),
				'limit' => 10
			);

			// Lorsqu'on recherche les référents affecté, on doit ajouter des champs et une jointure
			if( $statut == 'saisie' ) {
				$querydata['fields'] = Set::merge(
					$querydata['fields'],
					array(
						'PersonneReferent.dddesignation',
						$Personne->PersonneReferent->Referent->sqVirtualField( 'nom_complet', true )
					)
				);
				$querydata['joins'][] = $Personne->PersonneReferent->join( 'Referent', array( 'type' => 'INNER' ) );
			}

			return $querydata;
		}
	}
?>