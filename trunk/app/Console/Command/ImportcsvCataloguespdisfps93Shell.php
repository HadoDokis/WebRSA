<?php
	/**
	 * Code source de la classe ImportcsvCataloguespdisfps93Shell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ImportcsvCataloguespdisfps93Shell permet d'importer le catalogue PDI
	 * pour le module fiches de rpescriptions du CG 93.
	 *
	 * @todo  Adresseprestatairefp93
	 * @todo XProgressbar
	 *
	 * @package app.Console.Command
	 */
	class ImportcsvCataloguespdisfps93Shell extends AppShell
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
		 * Nom du shell
		 *
		 * @var string
		 */
		public $name = 'ImportcsvCataloguespdisfps93';

		/**
		 * Le fichier CSV.
		 *
		 * @var File
		 */
		protected $_Csv = null;

		/**
		 * Stockage des lignes rejetées
		 *
		 * @var array
		 */
		protected $_rejects = array();

		/**
		 * Stockage des lignes du fichier.
		 *
		 * @var array
		 */
		protected $_lines = array();

		/**
		 * Ligne d'en-tête du fichier.
		 *
		 * @var array
		 */
		protected $_headers = array();

		/**
		 * Contient les erreurs de validation d'une ligne traitée.
		 *
		 * @var array
		 */
		protected $_validationErrors = array();

		/**
		 * Ligne d'en-tête par défaut.
		 *
		 * @var array
		 */
		protected $_defaultHeaders = array(
			'thematique', // Thematiquefp93.name (Thematiquefp93.type pdi implicite)
			'categorie action', // Categoriefp93.name
			'numero convention action',
			'prestataire',
			'intitulé d\'action',
			'filiere',
			'tel_action',
			'adresse action',
			'cp action',
			'commune action'
		);

		/**
		 * Modèles utilisés par ce shell.
		 *
		 * @var array
		 */
		public $uses = array( 'Thematiquefp93' );

		/**
		 * Fonction utilitaire de trim (espaces, doubles quotes) pour un chaîne
		 * ou un array, de façon récursive.
		 *
		 * @todo: dans une classe utilitaire
		 *
		 * @param array|string $mixed
		 * @return array|string
		 */
		protected function _trim( $mixed ) {
			$charlist = " \t\n\r\0\x0B\"";

			if( is_array( $mixed ) ) {
				foreach( $mixed as $key => $value ) {
					$mixed[$key] = trim( $value, $charlist );
				}
			}
			else {
				$mixed = trim( $mixed, $charlist );
			}

			return $mixed;
		}

		/**
		 * Démarrage du shell
		 */
		public function startup() {
			parent::startup();

			// 1°) Si on n'est pas le CG 93, on ne peut pas utiliser ce shell
			if( Configure::read( 'Cg.departement' ) != 93 ) {
				$this->error( "Ce shell est réservé au CG 93" );
			}

			// 2°) Vérification du format des paramètres hors fichier CSV
			if( !is_string( $this->params['separator'] ) ) {
				$this->error( "Le séparateur \"{$this->params['separator']}\" n'est pas correct, n'oubliez pas d'échapper le caractère (par exemple: \";\" plutôt que ;)" );
			}

			if( !is_numeric( $this->params['annee'] ) || (int)$this->params['annee'] != $this->params['annee'] ) {
				$this->error( "L'année \"{$this->params['annee']}\" n'est pas correcte." );
			}

			foreach( array( 'headers' ) as $bool ) {
				if( $this->params[$bool] == 'true' ) {
					$this->params[$bool] = true;
				}
				else if( $this->params[$bool] == 'false' ) {
					$this->params[$bool] = false;
				}

				if( !is_bool( $this->params[$bool] ) ) {
					$this->error( "Le paramètre {$bool} n'est pas correct \"{$this->params[$bool]}\" (valeurs possibles: true et false)" );
				}
			}

			// 3°) Lecture du fichier CSV
			$this->_Csv = new File( Hash::get( $this->args, '0' ) );

			// 3.1°) Vérifications concernant le fichier CSV.
			if( !$this->_Csv->exists() ) {
				$this->error( "Le fichier \"{$this->_Csv->pwd()}\" n'existe pas." );
			}
			else if( !$this->_Csv->readable() ) {
				$this->error( "Le fichier \"{$this->_Csv->pwd()}\" n'est pas lisible." );
			}
			else if( $this->_Csv->size() == 0 ) {
				$this->error( "Le fichier \"{$this->_Csv->pwd()}\" est vide." );
			}

			// 3.2°) Lecture en ré-encodage éventuel du fichier CSV
			mb_detect_order( array( 'UTF-8', 'ISO-8859-1', 'ASCII' ) );
			$csvLines = $this->_Csv->read();
			$encoding = mb_detect_encoding( $csvLines );
			if( $encoding != 'UTF-8' ) {
				$csvLines = mb_convert_encoding( $csvLines, 'UTF-8', $encoding );
			}
			$this->_lines = explode( "\n", $csvLines );

			// 3.3°) Traitement de la ligne d'en-tête
			if( $this->params['headers'] ) {
				$this->_headers = explode( $this->params['separator'], strtolower( trim( $this->_lines[0] ) ) );
			}
			else {
				$this->_headers = $this->_defaultHeaders;
			}
			$this->_headers = $this->_trim( $this->_headers );

			// 3.4°) Scission des lignes du catalogue et de la ligne d'en-tête
			if( $this->params['headers'] ) {
				$this->_lines = array_slice( $this->_lines, 1 );
			}

			// 4°) Vérifications
			// 4.1°) Vérification de la ligne d'en-tête
			$diff = array_diff( $this->_defaultHeaders, $this->_headers );
			if( !empty( $diff ) ) {
				$this->err( sprintf( "En-têtes de colonnes manquants: %s", implode( ',', $diff ) ), 1, Shell::QUIET );
				$this->_stop( self::ERROR );
			}

			// 4.2°) Si on n'a aucune action PDI
			if( empty( $this->_lines ) ) {
				$this->out( '<info>Aucune action PDI présente dans ce fichier</info>', 1, Shell::QUIET );
				$this->_stop( self::SUCCESS );
			}
		}

		/**
		 *
		 * @param string $line
		 * @return array
		 */
		protected function _normalizeLine( $line ) {
			$return = array();

			$return = $this->_trim( explode( $this->params['separator'], $line ) );
			foreach( $return as $i => $value ) {
				if( trim( $value ) == '' ) {
					$return[$i] = null;
				}
			}

			return $return;
		}

		/**
		 * Recherche l'enregistrement répondant aux conditions. Si celui-ci existe,
		 * sa clé primaire est renvoyée; sinon, on tente d'enregistrer les données.
		 *
		 * En cas de succès, la clé primaire du nouvel enregistrement est retournée,
		 * sinon on peuple l'attribut $_validationErrors
		 *
		 * @param AppModel $Model
		 * @param array $conditions
		 * @return integer
		 */
		protected function _createOrUpdate( AppModel $Model, array $conditions ) {
			if( $Model->Behaviors->attached( 'Cataloguepdifp93' ) ) {
				$id = $Model->createOrUpdate( $conditions );

				if( empty( $id ) ) {
					$this->_validationErrors = Hash::merge(
						$this->_validationErrors,
						Hash::flatten( array( $Model->alias => $Model->validationErrors ) )
					);

					return null;
				}

				return $id;
			}
			else {
				$conditions = Hash::flatten( $Model->doFormatting( Hash::expand( $conditions ) ) );

				$primaryKeyField = "{$Model->alias}.{$Model->primaryKey}";

				$query = array(
					'fields' => array( $primaryKeyField ),
					'conditions' => $conditions
				);

				$record = $Model->find( 'first', $query );

				if( empty( $record ) ) {
					$record = Hash::expand( $conditions );
					$Model->create( $record );

					if( !$Model->save() ) {
						$this->_validationErrors = Hash::merge(
							$this->_validationErrors,
							Hash::flatten( array( $Model->alias => $Model->validationErrors ) )
						);

						return null;
					}
					else {
						return $Model->{$Model->primaryKey};
					}
				}
				else {
					return Hash::get( $record, $primaryKeyField );
				}
			}
		}

		/**
		 * Méthode principale.
		 */
		public function main() {
			$expectedCount = count( $this->_headers );
			$nbTraitees = 0;
			$headers = array_flip( $this->_defaultHeaders );

			foreach( $this->_lines as $i => $line ) {
				$this->_validationErrors = array();

				$trimmed = trim( $line );
				if( empty( $trimmed ) ) {
					unset( $this->_lines[$i] );
				}
				else {
					$record = $this->_normalizeLine( $line );

					if( count( $record ) == $expectedCount ) {
						$this->Thematiquefp93->begin();

						// Début thématique
						$conditions = array(
							'Thematiquefp93.type' => 'pdi',
							'Thematiquefp93.name' => $record[$headers['thematique']],
						);

						$thematiquefp93_id = $this->_createOrUpdate(
							$this->Thematiquefp93,
							$conditions
						);
						// Fin thématique

						// Début catégorie
						if( empty( $this->_validationErrors ) ) {
							$conditions = array(
								'Categoriefp93.thematiquefp93_id' => $thematiquefp93_id,
								'Categoriefp93.name' => $record[$headers['categorie action']],
							);

							$categoriefp93_id = $this->_createOrUpdate(
								$this->Thematiquefp93->Categoriefp93,
								$conditions
							);
						}
						// Fin catégorie

						// Début filière
						if( empty( $this->_validationErrors ) ) {
							$conditions = array(
								'Filierefp93.categoriefp93_id' => $categoriefp93_id,
								'Filierefp93.name' => $record[$headers['filiere']],
							);

							$filierefp93_id = $this->_createOrUpdate(
								$this->Thematiquefp93->Categoriefp93->Filierefp93,
								$conditions
							);
						}
						// Fin filière

						// Début prestataire
						if( empty( $this->_validationErrors ) ) {
							$conditions = array(
								'Prestatairefp93.name' => $record[$headers['prestataire']],
							);

							$prestatairefp93_id = $this->_createOrUpdate(
								$this->Thematiquefp93->Categoriefp93->Filierefp93->Actionfp93->Prestatairefp93,
								$conditions
							);
						}
						// Fin prestataire

						// Début Adresseprestatairefp93
						if( empty( $this->_validationErrors ) ) {
							$conditions = array(
								'Adresseprestatairefp93.prestatairefp93_id' => $prestatairefp93_id,
								'Adresseprestatairefp93.adresse' => $record[$headers['adresse action']],
								'Adresseprestatairefp93.codepos' => $record[$headers['cp action']],
								'Adresseprestatairefp93.localite' => $record[$headers['commune action']],
								'Adresseprestatairefp93.tel' => $record[$headers['tel_action']],
							);

							$adresseprestatairefp93_id = $this->_createOrUpdate(
								$this->Thematiquefp93->Categoriefp93->Filierefp93->Actionfp93->Prestatairefp93->Adresseprestatairefp93,
								$conditions
							);
						}
						// Fin Adresseprestatairefp93

						// Début action
						if( empty( $this->_validationErrors ) ) {
							$conditions = array(
								'Actionfp93.filierefp93_id' => $filierefp93_id,
								'Actionfp93.prestatairefp93_id' => $prestatairefp93_id,
								'Actionfp93.numconvention' => $record[$headers['numero convention action']],
								'Actionfp93.name' => $record[$headers['intitulé d\'action']],
								'Actionfp93.annee' => $this->params['annee'],
								'Actionfp93.actif' => '1', // FIXME
							);

							$actionfp93_id = $this->_createOrUpdate(
								$this->Thematiquefp93->Categoriefp93->Filierefp93->Actionfp93,
								$conditions
							);
						}
						// Fin action

						if( !empty( $this->_validationErrors ) ) {
							$this->Thematiquefp93->rollback();
							$key = key( $this->_validationErrors );
							$value = $this->_validationErrors[$key];
							$this->_rejects[] = "{$line}{$this->params['separator']}{$this->params['delimiter']}".str_replace( $this->params['delimiter'], "\\{$this->params['delimiter']}", "{$key} => {$value}" ).$this->params['delimiter'];
						}
						else {
							$nbTraitees++;
							$this->Thematiquefp93->commit();
						}
					}
					else {
						$this->_rejects[] = "{$line}{$this->params['separator']}{$this->params['delimiter']}".sprintf( "Nombre de champs de la ligne erroné: %d au lieu des %d attendus", count( $record ), $expectedCount ).$this->params['delimiter'];
					}
				}
			}

			$this->out( sprintf( "Traitement du fichier %s: %d lignes à traiter, %d lignes traitées, %d lignes rejetées", $this->_Csv->pwd(), count( $this->_lines ), $nbTraitees, count( $this->_rejects ) ) );

			// A-t'on des lignes rejetées à exporter dans un fichier CSV ?
			if( !empty( $this->_rejects ) ) {
				$titleLine = "";
				if( $this->params['headers'] == 'true' ) {
					$headers = $this->params['delimiter'].implode( "{$this->params['delimiter']}{$this->params['separator']}{$this->params['delimiter']}", $this->_headers ).$this->params['delimiter'];
					$headers = "{$headers};\"Erreur\"";
					$titleLine = "{$headers}\n";
				}
				$output = $titleLine.implode( "\n", $this->_rejects )."\n";
				$outfile = LOGS.$this->name.'_rejets-'.date( 'Ymd-His' ).'.csv';
				file_put_contents( $outfile, $output );
				$this->out( "<info>Le fichier de rejets se trouve dans {$outfile}</info>" );
			}

			$this->_stop( self::SUCCESS );
		}

		/**
		 * Paramétrages et aides du shell.
		 */
		public function getOptionParser() {
			$Parser = parent::getOptionParser();

			$Parser->description( "Ce script permet d'importer, via des fichiers .csv, le catalogue PDI pour la fiche de prescription. (CG 93)" );

			$options = array(
				'headers' => array(
					'short' => 'H',
					'help' => 'précise si le fichier à importer commence par une colonne d\'en-tête ou s\'il commence directement par des données à intégrées',
					'choices' => array( 'true', 'false' ),
					'default' => 'true'
				),
				'separator' => array(
					'short' => 's',
					'help' => 'le caractère utilisé comme séparateur',
					'default' => ','
				),
				'delimiter' => array(
					'short' => 'd',
					'help' => 'le caractère utilisé comme délimiteur de champ',
					'default' => '"'
				),
				'annee' => array(
					'short' => 'a',
					'help' => 'l\'année de conventionnement des actions contenues dans le fichier CSV',
					'default' => date( 'Y' )
				),
			);
			$Parser->addOptions( $options );

			$args = array(
				'csv' => array(
					'help' => 'chemin et nom du fichier à importer',
					'required' => true
				)
			);
			$Parser->addArguments( $args );

			return $Parser;
		}
	}
?>