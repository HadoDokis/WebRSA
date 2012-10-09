<?php
	/**
	 * Code source de la classe Cohortereferent.
	 *
	 * PHP 5.3
	 *
	 * @package app.models
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortereferent permet de rechercher le allocataires ne possédant pas de référent de parcours
	 * en cours.
	 *
	 * @package app.models
	 */
	class Cohortereferent93 extends AppModel
	{
		/**
		 * @var string
		 */
		public $name = 'Cohortereferent';

		/**
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * @var array
		 */
		public $actsAs = array( 'Conditionnable' );

		/**
		 * Règles de validation pour la cohorte d'affectation d'un référent.
		 *
		 * @var array
		 */
		public $validatePersonneReferent = array(
			'action' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => 'Champ obligatoire',
					'required' => true
				)
			),
			'referent_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'action', true, array( 'Valider' ) ),
					'message' => 'Champ obligatoire'
				)
			),
		);

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
			$sqDernierReferent = $Personne->PersonneReferent->sqDerniere( 'Personne.id' );
			$sqDernierContratinsertion = $Personne->sqLatest( 'Contratinsertion', 'rg_ci', array( 'Contratinsertion.decision_ci' => 'V' ) );
			$sqDspId = 'SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1';
			$sqDspExists = "( {$sqDspId} ) IS NOT NULL";

			$conditions = array(
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				"Adressefoyer.id IN ( {$sqDerniereRgadr01} )",
				"Orientstruct.id IN ( {$sqDerniereOrientstruct} )",
				$sqDernierContratinsertion
			);

			// Formulaire de cohorte ou formulaire de visualisation ?
			if( $statut == 'affecter' ) {
				$conditions[] = array(
					'OR' => array(
						'PersonneReferent.id IS NULL',
						array(
							"PersonneReferent.id IN ( {$sqDernierReferent} )",
							'PersonneReferent.dfdesignation IS NOT NULL'
						)
					)
				);
			}
			else {
				$conditions[] = array(
					"PersonneReferent.id IN ( {$sqDernierReferent} )",
					'PersonneReferent.dfdesignation IS NULL'
				);

				// Choix du référent affecté ?
				if( isset( $search['PersonneReferent']['referent_id'] ) && ( $search['PersonneReferent']['referent_id'] != '' ) ) {
					$conditions['PersonneReferent.referent_id'] = $search['PersonneReferent']['referent_id'];
				}

				$conditions = $this->conditionsDates( $conditions, $search, 'PersonneReferent.dddesignation' );
			}

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

			$querydata = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Calculdroitrsa->fields(),
					$Personne->Contratinsertion->fields(),
					$Personne->Orientstruct->fields(),
					$Personne->Prestation->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					// Présence DSP
					array(
						$Personne->sqVirtualField( 'nom_complet_court', true ),
						"( {$sqDspExists} ) AS \"Dsp__exists\"" // TODO: mettre dans le modèle
					)
				),
				'contain' => false,
				'joins' => array(
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'Contratinsertion', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Orientstruct', array( 'type' => 'INNER' ) ),
					$Personne->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
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
			if( $statut == 'affectes' ) {
				$querydata['fields'] = Set::merge(
					$querydata['fields'],
					array(
						'PersonneReferent.dddesignation',
						$Personne->PersonneReferent->Referent->sqVirtualField( 'nom_complet', true )
					)
				);
				$querydata['joins'][] = $Personne->PersonneReferent->join( 'Referent' );
			}

			return $querydata;
		}
	}
?>