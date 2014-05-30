<?php
	/**
	 * Code source de la classe ImportcsvCataloguespdisfps93Shell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'CsvAbstractImporterShell', 'Csv.Console/Command/Abstract' );

	/**
	 * La classe ImportcsvCataloguespdisfps93Shell permet d'importer le catalogue PDI
	 * pour le module fiches de rpescriptions du CG 93.
	 *
	 * @package app.Console.Command
	 */
	class ImportcsvCataloguespdisfps93Shell extends CsvAbstractImporterShell
	{
		/**
		 * Les modèles utilisés par ce shell.
		 *
		 * @var array
		 */
		public $uses = array( 'Thematiquefp93', 'Categoriefp93', 'Filierefp93', 'Prestatairefp93', 'Adresseprestatairefp93', 'Actionfp93' );

		/**
		 * Les tâches utilisées par ce shell.
		 *
		 * @var array
		 */
		public $tasks = array( 'XProgressBar' );

		/**
		 * Les en-têtes par défaut tels qu'ils sont attendus.
		 *
		 * @var array
		 */
		protected $_defaultHeaders = array(
			'Thematique',
			'Categorie Action',
			'Numero Convention Action',
			'Prestataire',
			'Intitulé d\'Action',
			'Filiere',
			'Tel_Action',
			'Adresse Action',
			'CP Action',
			'Commune Action',
			'Duree Action',
			'Annee'
		);

		/**
		 * Tableau de correspondances entre les en-têtes et des chemins de
		 * modèles CakePHP.
		 *
		 * @var array
		 */
		protected $_correspondances = array(
			'Thematiquefp93.name',
			'Categoriefp93.name',
			'Actionfp93.numconvention',
			'Prestatairefp93.name',
			'Actionfp93.name',
			'Filierefp93.name',
			'Adresseprestatairefp93.tel',
			'Adresseprestatairefp93.adresse',
			'Adresseprestatairefp93.codepos',
			'Adresseprestatairefp93.localite',
			'Actionfp93.duree',
			'Actionfp93.annee'
		);

		/**
		 * Nettoyage et normalisation de la ligne d'en-tête.
		 *
		 * @param array $headers
		 * @return array
		 */
		public function processHeaders( array $headers ) {
			foreach( $headers as $key => $value ) {
				$headers[$key] = preg_replace( '/[\W_ ]+/', ' ', noaccents_upper( trim( $value ) ) );
			}

			return $headers;
		}

		/**
		 * Nettoyage des valeurs des champs (suppression des espaces excédentaires)
		 * et transformation des clés via les correspondances.
		 *
		 * @param array $row
		 * @return array
		 */
		public function normalizeRow( array $row ) {
			$new = array();

			foreach( $row as $key => $value ) {
				if( isset( $this->_correspondances[$key] ) ) {
					$new = Hash::insert(
						$new,
						$this->_correspondances[$key],
						trim( preg_replace( '/[ ]+/', ' ', $value ) )
					);
				}
			}

			return $new;
		}

		/**
		 * Recherche de la thématique, insertion si besoin
		 *
		 * @param array $row
		 * @return integer
		 */
		public function processThematiquefp93( array $row ) {
			$query = array(
				'fields' => array( 'Thematiquefp93.id' ),
				'conditions' => array(
					'Thematiquefp93.type' => 'pdi',
					'Thematiquefp93.name' => Hash::get( $row, 'Thematiquefp93.name' ) // TODO: normaliser
				)
			);

			$thematiquefp93 = $this->Thematiquefp93->find( 'first', $query );

			if( !empty( $thematiquefp93 ) ) {
				$thematiquefp93_id = Hash::get( $thematiquefp93, 'Thematiquefp93.id' );
			}
			else {
				$data = array(
					'Thematiquefp93' => array(
						'type' => 'pdi',
						'name' => Hash::get( $row, 'Thematiquefp93.name' )
					)
				);
				$this->Thematiquefp93->create( $data );
				if( $this->Thematiquefp93->save() ) {
					$thematiquefp93_id = $this->Thematiquefp93->id;
				}
				else {
					$thematiquefp93_id = null;
				}
			}

			return $thematiquefp93_id;
		}

		/**
		 * Recherche de la thématique, insertion si besoin
		 *
		 * @param array $row
		 * @return integer
		 */
		public function processCategoriefp93( array $row ) {
			$query = array(
				'fields' => array( 'Categoriefp93.id' ),
				'conditions' => array(
					'Categoriefp93.thematiquefp93_id' => Hash::get( $row, 'Thematiquefp93.id' ),
					'Categoriefp93.name' => Hash::get( $row, 'Categoriefp93.name' ) // TODO: normaliser
				)
			);

			$categoriefp93 = $this->Categoriefp93->find( 'first', $query );

			if( !empty( $categoriefp93 ) ) {
				$categoriefp93_id = Hash::get( $categoriefp93, 'Categoriefp93.id' );
			}
			else {
				$data = array(
					'Categoriefp93' => array(
						'thematiquefp93_id' => Hash::get( $row, 'Thematiquefp93.id' ),
						'name' => Hash::get( $row, 'Categoriefp93.name' )
					)
				);
				$this->Categoriefp93->create( $data );
				if( $this->Categoriefp93->save() ) {
					$categoriefp93_id = $this->Categoriefp93->id;
				}
				else {
					$categoriefp93_id = null;
				}
			}

			return $categoriefp93_id;
		}

		/**
		 * Recherche de la filière, insertion si besoin
		 *
		 * @param array $row
		 * @return integer
		 */
		public function processFilierefp93( array $row ) {
			$query = array(
				'fields' => array( 'Filierefp93.id' ),
				'conditions' => array(
					'Filierefp93.categoriefp93_id' => Hash::get( $row, 'Categoriefp93.id' ),
					'Filierefp93.name' => Hash::get( $row, 'Filierefp93.name' ) // TODO: normaliser
				)
			);

			$filierefp93 = $this->Filierefp93->find( 'first', $query );

			if( !empty( $filierefp93 ) ) {
				$filierefp93_id = Hash::get( $filierefp93, 'Filierefp93.id' );
			}
			else {
				$data = array(
					'Filierefp93' => array(
						'categoriefp93_id' => Hash::get( $row, 'Categoriefp93.id' ),
						'name' => Hash::get( $row, 'Filierefp93.name' )
					)
				);
				$this->Filierefp93->create( $data );
				if( $this->Filierefp93->save() ) {
					$filierefp93_id = $this->Filierefp93->id;
				}
				else {
					$filierefp93_id = null;
				}
			}

			return $filierefp93_id;
		}

		/**
		 * Recherche du prestataire, insertion si besoin
		 *
		 * @param array $row
		 * @return integer
		 */
		public function processPrestatairefp93( array $row ) {
			$query = array(
				'fields' => array( 'Prestatairefp93.id' ),
				'conditions' => array(
					'Prestatairefp93.name' => Hash::get( $row, 'Prestatairefp93.name' ) // TODO: normaliser
				)
			);

			$prestatairefp93 = $this->Prestatairefp93->find( 'first', $query );

			if( !empty( $prestatairefp93 ) ) {
				$prestatairefp93_id = Hash::get( $prestatairefp93, 'Prestatairefp93.id' );
			}
			else {
				$data = array(
					'Prestatairefp93' => array(
						'name' => Hash::get( $row, 'Prestatairefp93.name' )
					)
				);
				$this->Prestatairefp93->create( $data );
				if( $this->Prestatairefp93->save() ) {
					$prestatairefp93_id = $this->Prestatairefp93->id;
				}
				else {
					$prestatairefp93_id = null;
				}
			}

			return $prestatairefp93_id;
		}

		/**
		 * Recherche de l'adresse du prestataire, insertion si besoin
		 *
		 * @param array $row
		 * @return integer
		 */
		public function processAdresseprestatairefp93( array $row ) {
			$query = array(
				'fields' => array( 'Adresseprestatairefp93.id' ),
				'conditions' => array(
					// TODO: normaliser, etc...
					'Adresseprestatairefp93.prestatairefp93_id' => Hash::get( $row, 'Prestatairefp93.id' ),
					'Adresseprestatairefp93.adresse' => Hash::get( $row, 'Adresseprestatairefp93.adresse' ),
					'Adresseprestatairefp93.codepos' => Hash::get( $row, 'Adresseprestatairefp93.codepos' ),
					'Adresseprestatairefp93.localite' => Hash::get( $row, 'Adresseprestatairefp93.localite' ),
					'Adresseprestatairefp93.tel' => Hash::get( $row, 'Adresseprestatairefp93.tel' )
				)
			);

			$adresseadresseprestatairefp93 = $this->Adresseprestatairefp93->find( 'first', $query );

			if( !empty( $adresseprestatairefp93 ) ) {
				$adresseprestatairefp93_id = Hash::get( $adresseprestatairefp93, 'Adresseprestatairefp93.id' );
			}
			else {
				$data = array(
					'Adresseprestatairefp93' => array(
						'prestatairefp93_id' => Hash::get( $row, 'Prestatairefp93.id' ),
						'adresse' => Hash::get( $row, 'Adresseprestatairefp93.adresse' ),
						'codepos' => Hash::get( $row, 'Adresseprestatairefp93.codepos' ),
						'localite' => Hash::get( $row, 'Adresseprestatairefp93.localite' ),
						'tel' => Hash::get( $row, 'Adresseprestatairefp93.tel' )
					)
				);
				$this->Adresseprestatairefp93->create( $data );
				if( $this->Adresseprestatairefp93->save() ) {
					$adresseprestatairefp93_id = $this->Adresseprestatairefp93->id;
				}
				else {
					$adresseprestatairefp93_id = null;
				}
			}

			return $adresseprestatairefp93_id;
		}

		/**
		 * Recherche de l'action, insertion si besoin
		 *
		 * @param array $row
		 * @return integer
		 */
		public function processActionfp93( array $row ) {
			$query = array(
				'fields' => array( 'Actionfp93.id' ),
				'conditions' => array(
					 // TODO: normaliser
					'Actionfp93.filierefp93_id' => Hash::get( $row, 'Filierefp93.id' ),
					'Actionfp93.prestatairefp93_id' => Hash::get( $row, 'Prestatairefp93.id' ),
					'Actionfp93.numconvention' => Hash::get( $row, 'Actionfp93.numconvention' ),
					'Actionfp93.name' => Hash::get( $row, 'Actionfp93.name' ),
					'Actionfp93.duree' => Hash::get( $row, 'Actionfp93.duree' ),
					'Actionfp93.annee' => Hash::get( $row, 'Actionfp93.annee' )
				)
			);

			$actionfp93 = $this->Actionfp93->find( 'first', $query );

			if( !empty( $actionfp93 ) ) {
				$actionfp93_id = Hash::get( $actionfp93, 'Actionfp93.id' );
			}
			else {
				$data = array(
					'Actionfp93' => array(
						'filierefp93_id' => Hash::get( $row, 'Filierefp93.id' ),
						'prestatairefp93_id' => Hash::get( $row, 'Prestatairefp93.id' ),
						'numconvention' => Hash::get( $row, 'Actionfp93.numconvention' ),
						'name' => Hash::get( $row, 'Actionfp93.name' ),
						'duree' => Hash::get( $row, 'Actionfp93.duree' ),
						'annee' => Hash::get( $row, 'Actionfp93.annee' ),
						'actif' => '1'
					)
				);
				$this->Actionfp93->create( $data );
				if( $this->Actionfp93->save() ) {
					$actionfp93_id = $this->Actionfp93->id;
				}
				else {
					$actionfp93_id = null;
				}
			}

			return $actionfp93_id;
		}

		/**
		 * Traitement d'une ligne de données du fichier CSV.
		 *
		 * @param array $row
		 * @return boolean
		 */
		public function processRow( array $row ) {
			if( empty( $row ) ) {
				$this->empty[] = $row;
				return false;
			}

			$data = $this->normalizeRow( $row );

			// Formatage des données de la ligne
			$path = 'Actionfp93.numconvention';
			$data = Hash::insert( $data, $path, strtoupper( Hash::get( $data, $path ) ) );

			$query = array(
				'fields' => array( 'Actionfp93.id' ),
				'conditions' => array(
					'Actionfp93.numconvention' => Hash::get( $data, 'Actionfp93.numconvention' )
				),
			);

			$found = $this->Actionfp93->find( 'first', $query );
			if( !empty( $found ) ) {
				$this->rejectRow( $row, $this->Actionfp93, 'N° de convention d\'action déjà présent' );
				return false;
			}

			// Traitement de la thématique
			$thematiquefp93_id = $this->processThematiquefp93( $data );
			if( $thematiquefp93_id === null ) {
				$this->rejectRow( $row, $this->Thematiquefp93 );
				return false;
			}
			$data = Hash::insert( $data, 'Thematiquefp93.id', $thematiquefp93_id );

			// Traitement de la catégorie
			$categoriefp93_id = $this->processCategoriefp93( $data );
			if( $categoriefp93_id === null ) {
				$this->rejectRow( $row, $this->Categoriefp93 );
				return false;
			}
			$data = Hash::insert( $data, 'Categoriefp93.id', $categoriefp93_id );

			// Traitement de la filière
			$filierefp93_id = $this->processFilierefp93( $data );
			if( $filierefp93_id === null ) {
				$this->rejectRow( $row, $this->Filierefp93 );
				return false;
			}
			$data = Hash::insert( $data, 'Filierefp93.id', $filierefp93_id );

			// Traitement du prestataire
			$prestatairefp93_id = $this->processPrestatairefp93( $data );
			if( $prestatairefp93_id === null ) {
				$this->rejectRow( $row, $this->Prestatairefp93 );
				return false;
			}
			$data = Hash::insert( $data, 'Prestatairefp93.id', $prestatairefp93_id );

			// Traitement de l'adresse du prestataire
			$adresseprestatairefp93_id = $this->processAdresseprestatairefp93( $data );
			if( $adresseprestatairefp93_id === null ) {
				$this->rejectRow( $row, $this->Adresseprestatairefp93 );
				return false;
			}
			$data = Hash::insert( $data, 'Adresseprestatairefp93.id', $adresseprestatairefp93_id );

			// Traitement de l'action
			$actionfp93_id = $this->processActionfp93( $data );
			if( $actionfp93_id === null ) {
				$this->rejectRow( $row, $this->Actionfp93 );
				return false;
			}
			$data = Hash::insert( $data, 'Actionfp93.id', $actionfp93_id );

			return true;
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
		 * Méthode principale, traitement du fichier CSV.
		 */
		public function main() {
			$success = true;
			$count = $this->_Csv->count();

			$this->XProgressBar->start( $count );

			foreach( $this->_Csv as $row ) {
				if( !empty( $row ) ) {
					$this->Actionfp93->begin();

					if( $this->processRow( $row ) === false ) {
						$this->Actionfp93->rollback();
					}
					else {
						$this->Actionfp93->commit();
					}
				}
				else {
					$this->empty[] = $row;
				}
				$this->XProgressBar->next();
			}

			$this->epilog();
		}
	}
?>