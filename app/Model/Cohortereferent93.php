<?php
	/**
	 * Code source de la classe Cohortereferent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortereferent permet de rechercher le allocataires ne possédant pas de référent de parcours
	 * en cours.
	 *
	 * @package app.Model
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

		public $vfPersonneOrder = '(
			CASE
				WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NULL ) THEN 0
				WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NOT NULL ) THEN 1
				WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NULL ) THEN 2
				ELSE 3
			END
		)';

		/**
		 * Retourne un querydata résultant du traitement du formulaire de recherche des cohortes de référent
		 * du parcours.
		 *
		 * @param integer $structurereferente_id L'id technique de la structure référente pour laquelle on effectue la recherche
		 * @param array $mesCodesInsee La liste des codes INSEE à laquelle est lié l'utilisateur
		 * @param boolean $filtre_zone_geo L'utilisateur est-il limité au niveau des zones géographiques ?
		 * @param array $search Critères du formulaire de recherche
		 * @param mixed $lockedDossiers
		 * @return array
		 */
		public function search( $structurereferente_id, $mesCodesInsee, $filtre_zone_geo, $search, $lockedDossiers ) {
			$Personne = ClassRegistry::init( 'Personne' );

			$sqDerniereRgadr01 = $Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' );
			$sqDerniereOrientstruct = $Personne->Orientstruct->sqDerniere();

			$sqOrientstructpcd = $Personne->sqLatest(
				'Orientstruct',
				'date_valid',
				array(
					'Orientstruct.statut_orient' => 'Orienté',
					'Orientstruct.date_valid IS NOT NULL',
					"Orientstruct.id NOT IN ( {$sqDerniereOrientstruct} )",
				),
				false
			);

			$sqDernierReferent = $Personne->PersonneReferent->sqDerniere( 'Personne.id' );
			$sqDernierContratinsertion = $Personne->sqLatest( 'Contratinsertion', 'rg_ci', array( 'NOT' => array( 'Contratinsertion.decision_ci' => array( 'A', 'R' ) ) ), true );

			$sqDspId = 'SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1';
			$sqDspExists = "( {$sqDspId} ) IS NOT NULL";

			$conditions = array(
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				"Adressefoyer.id IN ( {$sqDerniereRgadr01} )",
				"Orientstruct.id IN ( {$sqDerniereOrientstruct} )",
				'Orientstruct.structurereferente_id' => $structurereferente_id,
				$sqDernierContratinsertion,
				array(
					'OR' => array(
						'Contratinsertion.id IS NULL',
						array(
// 							'Contratinsertion.structurereferente_id <>' => $structurereferente_id,
							'Contratinsertion.df_ci <=' => date( 'Y-m-d' ),
						),
						array(
// 							'Contratinsertion.structurereferente_id' => $structurereferente_id,
							'Cer93.positioncer' => '00enregistre',
						),
						//FIXME bug #6288
						array(
// 							'Contratinsertion.structurereferente_id' => $structurereferente_id,
							'Cer93.positioncer' => '01signe'
						),
					)
				)
			);

			if( isset( $search['Referent']['filtrer'] ) && $search['Referent']['filtrer'] == '1' ) {
				// Filtre par référent désigné / non désigné
				if( isset( $search['Referent']['designe'] ) ) {
					if( $search['Referent']['designe'] === '1' ) {
						$conditions[] = 'PersonneReferent.referent_id IS NOT NULL';
					}
					else if( $search['Referent']['designe'] === '0' ) {
						$conditions[] = 'PersonneReferent.referent_id IS NULL';
					}
				}

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
// 			if( isset( $search['Contratinsertion']['exists'] ) && ( $search['Contratinsertion']['exists'] != '' ) ) {
// 				if( $search['Contratinsertion']['exists'] ) {
// 					$conditions[] = "( ( {$sqDernierContratinsertion} ) IS NOT NULL )";
// 				}
// 				else {
// 					$conditions[] = "( ( {$sqDernierContratinsertion} ) IS NULL )";
// 				}
// 			}
			
			/// Présence ou non d'un CER
			if( isset( $search['Contratinsertion']['exists'] ) && ( $search['Contratinsertion']['exists'] != '' ) ) {
				if( $search['Contratinsertion']['exists'] ) {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) = 0';
				}
			}
			
			$conditions = $this->conditionsAdresse( $conditions, $search, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $search );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $search );
			$conditions = $this->conditionsDates( $conditions, $search, 'Orientstruct.date_valid' );

			$querydata = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->PersonneReferent->fields(),
					$Personne->Calculdroitrsa->fields(),
					$Personne->Contratinsertion->fields(),
					$Personne->Contratinsertion->Cer93->fields(),
					$Personne->Orientstruct->fields(),
					array_words_replace( $Personne->Orientstruct->Structurereferente->fields(), array( 'Orientstruct' => 'Orientstructpcd' ) ),
					$Personne->Prestation->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->Dossier->Situationdossierrsa->fields(),
					// Présence DSP
					array(
						$Personne->sqVirtualField( 'nom_complet_court', true ),
						"( {$sqDspExists} ) AS \"Dsp__exists\"", // TODO: mettre dans le modèle,
						"( \"Contratinsertion\".\"structurereferente_id\" = {$structurereferente_id} ) AS \"Contratinsertion__interne\"",
						$Personne->sqVirtualField( 'order', true ),
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
					$Personne->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					array_words_replace(
						$Personne->join(
							'Orientstruct',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'OR' => array(
										'Orientstruct.id IS NULL',
										"Orientstruct.id IN ( {$sqOrientstructpcd} )"
									)
								)
							)
						),
						array( 'Orientstruct' => 'Orientstructpcd' )
					),
					array_words_replace( $Personne->Orientstruct->join( 'Structurereferente' ), array( 'Orientstruct' => 'Orientstructpcd' ) ),
				),
				'conditions' => $conditions,
				'order' => array(
					'Personne.order' => 'ASC',
					'Orientstruct.date_valid ASC',
					'Personne.nom ASC',
					'Personne.prenom ASC',
				),
				'limit' => 10
			);

			return $querydata;
		}
	}
?>