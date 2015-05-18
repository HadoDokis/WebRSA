<?php
	/**
	 * Fichier source de la classe Tableauxsuivispdvs93Shell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );

	/**
	 * La classe Tableauxsuivispdvs93Shell ...
	 *
	 * @package app.Console.Command
	 */
	class Tableauxsuivispdvs93Shell extends XShell
	{
		/**
		 * Modèles utilisés par ce shell
		 *
		 * @var array
		 */
		public $uses = array( 'Tableausuivipdv93' );

		/**
		 * Paramètres par défaut pour ce shell
		 *
		 * @var array
		 */
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
		);

		/**
		 * Affiche l'en-tête du shell
		 */
		public function _welcome() {
			$this->out();
			$this->out( 'Shell de photographie des tableaux de suivi PDV' );
			$this->out();
			$this->hr();
		}

		/**
		 * Surcharge de la méthode startup pour vérifier que le département soit
		 * uniquement le 93.
		 */
		public function startup() {
			parent::startup();

			$this->checkDepartement( 93 );
		}

		/**
		 * Méthode principale.
		 */
		public function main() {
			$pdvs = $this->Tableausuivipdv93->listePdvs();
			$referents = $this->Tableausuivipdv93->listeReferentsPdvs();
			$search = array( 'Search' => array( 'annee' => date( 'Y' ), 'rdv_structurereferente' => false ) );
			$success = true;
			$tableaux = array_keys( $this->Tableausuivipdv93->tableaux );

			$this->Tableausuivipdv93->begin();

			// Sauvegarde pour le CG
			$this->out( "Enregistrement des tableaux de suivi CG pour l'année {$search['Search']['annee']}" );
			foreach( $tableaux as $tableau ) {
				$success = $success && $this->Tableausuivipdv93->historiser( $tableau, $search );
			}

			// Sauvegarde par PDV
			foreach( $pdvs as $pdv_id => $label ) {
				$search['Search']['structurereferente_id'] = $pdv_id;
				$this->out( "Enregistrement des tableaux de suivi du PDV {$label} pour l'année {$search['Search']['annee']}" );
				foreach( $tableaux as $tableau ) {
					$success = $success && $this->Tableausuivipdv93->historiser( $tableau, $search );
				}
			}

			// Sauvegarde par référent de PDV
			foreach( $referents as $referent_id => $label ) {
				$search['Search']['structurereferente_id'] = prefix( $referent_id );
				$search['Search']['referent_id'] = suffix( $referent_id );
				$this->out( "Enregistrement des tableaux de suivi du référent {$label} pour l'année {$search['Search']['annee']}" );
				foreach( $tableaux as $tableau ) {
					$success = $success && $this->Tableausuivipdv93->historiser( $tableau, $search );
				}
			}

			if( $success ) {
				$this->Tableausuivipdv93->commit();
				$this->out( 'Succès' );
			}
			else {
				$this->Tableausuivipdv93->rollback();
				$this->err( 'Erreur' );
			}
		}
	}
?>