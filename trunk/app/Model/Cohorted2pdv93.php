<?php
	/**
	 * Code source de la classe Cohorted2pdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohorted2pdv93 ...
	 *
	 * @package app.Model
	 */
	class Cohorted2pdv93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cohorted2pdv93';

		/**
		 * On n'utilise pas de table de la base de données.
		 *
		 * @var mixed
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array( 'Conditionnable' );

		public function search( $mesCodesInsee, $filtre_zone_geo, $search, $lockedDossiers ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$Personne = ClassRegistry::init( 'Personne' );

			$sqDerniereRgadr01 = $Dossier->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' );

			// Un dossier possède un seul detail du droit RSA mais ce dernier possède plusieurs details de calcul
			// donc on limite au dernier detail de calcul du droit rsa
			$sqDernierDetailcalculdroitrsa = $Dossier->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->sqDernier( 'Detaildroitrsa.id' );

			$conditions = array(
				'Prestation.natprest' => 'RSA',
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				'Adressefoyer.rgadr' => '01',
				"Adressefoyer.id IN ( {$sqDerniereRgadr01} )",
				'OR' => array(
					'Detailcalculdroitrsa.id IS NULL',
					"Detailcalculdroitrsa.id IN ( {$sqDernierDetailcalculdroitrsa} )",
				)
			);

			$conditions = $this->conditionsAdresse( $conditions, $search, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $search );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $search );


			// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				if( is_array( $lockedDossiers ) ) {
					$lockedDossiers = implode( ', ', $lockedDossiers );
				}
				$conditions[] = "NOT {$lockedDossiers}";
			}

			// -----------------------------------------------------------------
			// Filtres sur le suivi
			// -----------------------------------------------------------------

			// Année de suivi
			$annee = Hash::get( $search, 'Questionnaired1pdv93.annee' );
			$conditions[] = "Rendezvous.daterdv BETWEEN '{$annee}-01-01' AND '{$annee}-12-31'";

			// PDV effectuant le suivi
			$structurereferente_id = Hash::get( $search, 'Rendezvous.structurereferente_id' );
			if( !empty( $structurereferente_id ) ) {
				$conditions['Rendezvous.structurereferente_id'] = $structurereferente_id;
			}

			// Possédant un questionnaire D2 pour l'année de suivi ?
			$questionnaired2Exists = ( Hash::get( $search, 'Questionnaired2pdv93.exists' ) ? true : false );
			if( $questionnaired2Exists ) {
				$conditions[] = 'Questionnaired2pdv93.id IS NOT NULL';
			}
			else {
				$conditions[] = 'Questionnaired2pdv93.id IS NULL';
			}

			/*$querydata = array(
				'fields' => array_merge(
					$Dossier->fields(),
					$Dossier->Detaildroitrsa->fields(),
					$Dossier->Detaildroitrsa->Detailcalculdroitrsa->fields(),
					$Dossier->Foyer->Adressefoyer->fields(),
					$Dossier->Foyer->Personne->fields(),
					$Dossier->Foyer->Adressefoyer->Adresse->fields(),
					$Dossier->Foyer->Personne->Calculdroitrsa->fields(),
					$Dossier->Foyer->Personne->Prestation->fields(),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->fields(),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->Questionnaired2pdv93->fields(),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->Rendezvous->fields(),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->Rendezvous->Structurereferente->fields()
				),
				'joins' => array(
					$Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Questionnaired1pdv93', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->join( 'Questionnaired2pdv93', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->Questionnaired1pdv93->Rendezvous->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions,
				'contain' => false,
				'order' => array( 'Questionnaired2pdv93.modified ASC', 'Rendezvous.daterdv ASC' ),
				'limit' => 10
			);*/

			$querydata = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Calculdroitrsa->fields(),
					$Personne->Prestation->fields(),
					$Personne->Questionnaired1pdv93->fields(),
					$Personne->Questionnaired1pdv93->Questionnaired2pdv93->fields(),
					$Personne->Questionnaired1pdv93->Rendezvous->fields(),
					$Personne->Questionnaired1pdv93->Rendezvous->Structurereferente->fields(),
					$Personne->Foyer->Adressefoyer->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Foyer->Dossier->Detaildroitrsa->fields(),
					$Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->fields()
				),
				'joins' => array(
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Questionnaired1pdv93', array( 'type' => 'INNER' ) ),
					$Personne->Questionnaired1pdv93->join( 'Questionnaired2pdv93', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
					$Personne->Questionnaired1pdv93->Rendezvous->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions,
				'contain' => false,
				'order' => array(
					'Questionnaired2pdv93.modified ASC',
					'Rendezvous.daterdv ASC',
					'Questionnaired1pdv93.id ASC',
				),
				'limit' => 10
			);

			return $querydata;
		}
	}
?>