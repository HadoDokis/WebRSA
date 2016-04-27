<?php
	/**
	 * Fichier source de la classe TestsTableauxsuivispdvs93Shell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );

	/**
	 * La classe TestsTableauxsuivispdvs93Shell permet de tester l'ensemble des
	 * filtres de recherches pour tous les tableaux de suivi PDV pour le moteur
	 * de recherche ainsi que l'historisation.
	 *
	 * @package app.Console.Command
	 */
	class TestsTableauxsuivispdvs93Shell extends XShell
	{
		/**
		 * Modèles utilisés par ce shell
		 *
		 * @var array
		 */
		public $uses = array( 'Tableausuivipdv93', 'WebrsaTableausuivipdv93' );

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
			$this->out( 'Shell de tests des moteurs de recherche des tableaux de suivi PDV et des historisations de leurs résultats' );
			$this->out();
			$this->hr();
		}

		/**
		 * Liste des clés renvoyées par les différents formulaires de recherche.
		 *
		 * @var array
		 */
		public $masks = array(
			'tableaud1' => array(
				'annee',
				'structurereferente_id',
				'referent_id',
				'soumis_dd_dans_annee'
			),
			'tableaud2' => array(
				'annee',
				'structurereferente_id',
				'referent_id',
				'soumis_dd_dans_annee'
			),
			'tableau1b3' => array(
				'annee',
				'structurereferente_id',
				'referent_id',
				'dsps_maj_dans_annee'
			),
			'tableau1b4' => array(
				'annee',
				'structurereferente_id',
				'referent_id',
				'typethematiquefp93_id',
				'rdv_structurereferente'
			),
			'tableau1b5' => array(
				'annee',
				'structurereferente_id',
				'referent_id',
				'typethematiquefp93_id',
				'rdv_structurereferente'
			),
			'tableau1b6' => array(
				'annee',
				'structurereferente_id',
				'referent_id',
				'rdv_structurereferente'
			),
		);

		/**
		 * Surcharge de la méthode startup pour vérifier que le département soit
		 * uniquement le 93
		 */
		public function startup() {
			parent::startup();

			$this->stdout->styles( 'important', array( 'text' => 'yellow', 'bold' => true ) );

			$this->checkDepartement( 93 );

			// Chargement du fichier de configuration lié, s'il existe
			$path = APP.'Config'.DS.'Cg'.Configure::read( 'Cg.departement' ).DS.$this->name.'.php';
			if( file_exists( $path ) ) {
				include_once $path;
			}
		}

		/**
		 * Méthode principale, qui effectue
		 */
		public function main() {
			$annee = date( 'Y' ) + 1;
			$referents = $this->WebrsaTableausuivipdv93->listeReferentsPdvs();
			$referent_id = Hash::get( array_keys( $referents ), 0 );

			$criteres = array(
				'structurereferente_id' => array( '' => null, prefix( $referent_id ) => 'Test' ),
				'referent_id' => array( '' => null, $referent_id => 'Test' ),
				'soumis_dd_dans_annee' => array( '' => null, '0' => 'Non', '1' => 'Oui' ),
				'dsps_maj_dans_annee' => array( '' => null, '0' => 'Non', '1' => 'Oui' ),
				'typethematiquefp93_id' => array( '' => null, 'pdi' => 'PDI', 'horspdi' => 'Hors PDI' ),
				'rdv_structurereferente' => array( '' => null, '0' => 'Non', '1' => 'Oui' )
			);

			$tableaux = array_keys( $this->Tableausuivipdv93->tableaux );

			$success = true;
			$this->Tableausuivipdv93->begin();

			$searches = array();
			foreach( array_keys( $criteres['structurereferente_id'] ) as $structurereferente_id ) {
				foreach( array_keys( $criteres['referent_id'] ) as $referent_id ) {
					if( !empty( $referent_id ) ) {
						$structurereferente_id = prefix( $referent_id );
					}
					foreach( array_keys( $criteres['soumis_dd_dans_annee'] ) as $soumis_dd_dans_annee ) {
						foreach( array_keys( $criteres['dsps_maj_dans_annee'] ) as $dsps_maj_dans_annee ) {
							foreach( array_keys( $criteres['typethematiquefp93_id'] ) as $typethematiquefp93_id ) {
								foreach( array_keys( $criteres['rdv_structurereferente'] ) as $rdv_structurereferente ) {
									$search = array(
										'annee' => $annee,
										'structurereferente_id' => $structurereferente_id,
										'referent_id' => $referent_id,
										'soumis_dd_dans_annee' => $soumis_dd_dans_annee,
										'dsps_maj_dans_annee' => $dsps_maj_dans_annee,
										'typethematiquefp93_id' => $typethematiquefp93_id,
										'rdv_structurereferente' => $rdv_structurereferente
									);
									$key = serialize( $search );
									$searches[$key] = $search;
								}
							}
						}
					}
				}
			}

			$errors = array();
			$done = array();
			foreach( $tableaux as $tableau ) {
				$this->out( sprintf( "<important>Tableau %s</important>", $tableau ) );
				foreach( $searches as $search ) {
					$search = array_filter_keys($search, $this->masks[$tableau]);
					ksort( $search );
					$searchKey = preg_replace( '/\s+/', ' ', var_export( $search, true ) );
					$key = $tableau. '_'. $searchKey;
					if( !isset( $done[$key] ) ) {
						$this->out( sprintf( "\t%s", $searchKey ) );

						// Recherche
						try {
							$this->Tableausuivipdv93->{$tableau}( array( 'Search' => $search ) );
							$successSearch = true;
						} catch( Exception $e ) {
							$successSearch = false;
							$errors[$tableau][$key]['search'] = $e->getMessage();
						}

						// Historisation
						try {
							$this->Tableausuivipdv93->historiser( $tableau, array( 'Search' => $search ) );
							$successHisoriser = true;
						} catch( Exception $e ) {
							$successHisoriser = false;
							$errors[$tableau][$key]['historiser'] = $e->getMessage();
						}

						$done[$key] = $successSearch && $successHisoriser;
					}
				}
			}

			$this->out( "\n" );

			if( empty( $errors ) ) {
				$this->out( 'Succès' );
			}
			else {
				$this->out( 'Erreurs rencontrées' );
				foreach( $errors as $tableau => $errorsTableau ) {
					$this->out( "\ttableau {$tableau}" );
					foreach( $errorsTableau as $criteres => $errorsCritere ) {
						$this->out( sprintf( "\t\tcritères %s", $criteres ) );
						foreach( $errorsCritere as $method => $errorMethod ) {
							$this->err( sprintf( "\t\t\tméthode %s: %s", $method, $errorMethod ) );
						}
					}
				}
				$this->err( 'Erreur' );
			}

			$this->Tableausuivipdv93->rollback();
		}
	}
?>