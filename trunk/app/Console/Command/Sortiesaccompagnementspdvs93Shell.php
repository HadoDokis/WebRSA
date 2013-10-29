<?php
	/**
	 * Code source de la classe Sortiesaccompagnementspdvs93Shell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Sortiesaccompagnementspdvs93Shell enregistre automatiquement
	 * des formualaires D2 pour les allocataires quittant le département lorsque
	 * ceux-ci ont un formulaire D1 sans formulaire D2 lié pour l'année en cours.
	 *
	 * @package app.Console.Command
	 */
	class Sortiesaccompagnementspdvs93Shell extends AppShell
	{
		/**
		 * La constante à utiliser dans la méthode _stop() en cas de succès.
		 */
		const SUCCESS = 0;

		/**
		 * La constante à utiliser dans la méthode _stop() en cas d'erreur.
		 */
		const ERROR = 1;

		/**
		 * Les modèles utilisés par ce shell.
		 *
		 * @var array
		 */
		public $uses = array( 'Dossier', 'Cohortetransfertpdv93' );

		/**
		 * Démarrage du shell
		 */
		public function startup() {
			parent::startup();
		}

		/**
		 * Lignes de bienvenue.
		 */
		protected function _welcome() {
			parent::_welcome();
		}

		/**
		 * Méthode principale.
		 */
		public function main() {
			// Dernière adresse de rang 01
			$sqDerniereRgadr01 = $this->Dossier->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' );

			// Allocataires ayant un D2 sans D1
			$sq = $this->Dossier->Foyer->Personne->Questionnaired2pdv93->sqQuestionnaired2Necessaire( 'Personne.id', date( 'Y' ) );

			$querydata = array(
				'fields' => array(
					'Personne.id',
					'Questionnaired1pdv93.id',
					'Rendezvous.structurereferente_id',
				),
				'joins' => array(
					$this->Dossier->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Dossier->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$this->Dossier->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Dossier->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$this->Dossier->Foyer->Personne->join( 'Questionnaired1pdv93', array( 'type' => 'INNER' ) ),
					$this->Dossier->Foyer->Personne->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
				),
				'contain' => false,
				'conditions' => array(
					// Déménagement hors du département
					'Adresse.numcomptt NOT LIKE' => Configure::read( 'Cg.departement' ).'%',
					'Adressefoyer.rgadr' => '01',
					"Adressefoyer.id IN ( {$sqDerniereRgadr01} )",
					"Personne.id IN ( {$sq} )",
				)
			);

			$success = true;
			$this->Dossier->begin();

			$results = $this->Dossier->find( 'all', $querydata );

			// Enregistrement de sorties du département pour les résultats trouvés
			if( !empty( $results ) ) {
				foreach( $results as $result ) {
					$success = $this->Dossier->Foyer->Personne->Questionnaired2pdv93->saveAuto(
						$data['Transfertpdv93']['personne_id'],
						'changement_situation',
						'modif_departement'
					) && $success;
				}
			}

			if( $success ) {
				$this->Dossier->commit();
				$this->out( sprintf( "%d sorties du département enregistrées", count( $results ) )/*, $newlines, $level*/ );
				$this->_stop( self::SUCCESS );
			}
			else {
				$this->Dossier->rollback();
				$this->out( sprintf( "Impossible d'enregistrer les %d sorties du département", count( $results ) )/*, $newlines, $level*/ );
				$this->_stop( self::ERROR );
			}


		}

		/**
		 * Paramétrages et aides du shell.
		 */
		public function getOptionParser() {
			$Parser = parent::getOptionParser();
			return $Parser;
		}
	}
?>