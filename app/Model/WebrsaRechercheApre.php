<?php
	/**
	 * Code source de la classe WebrsaRechercheApre.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheApre ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheApre extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheApre';

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Allocataire', 'Canton' );

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Apres.search.fields',
			'Apres.search.innerTable',
			'Apres.exportcsv'
		);

		/**
		 * Surcharge du constructeur pour utiliser le bon modèle d'APRE suivant
		 * le département.
		 *
		 * @param mixed $id Set this ID for this model on startup, can also be an array of options, see above.
		 * @param string $table Name of database table to use.
		 * @param string $ds DataSource connection name.
		 */
		public function __construct( $id = false, $table = null, $ds = null ) {
			parent::__construct( $id, $table, $ds );

			$departement = (int)Configure::read( 'Cg.departement' );
			if( $departement === 66 ) {
				$this->Apre = ClassRegistry::init( 'Apre66' );
				$this->Apre->alias = 'Apre';
			}
			else {
				$this->Apre = ClassRegistry::init( 'Apre' );
			}
		}

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$departement = (int)Configure::read( 'Cg.departement' );
			$types += array(
				'Calculdroitrsa' => ( $departement === 93 ? 'LEFT OUTER' : 'INNER' ),
				'Foyer' => 'INNER',
				'Prestation' => 'LEFT OUTER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'LEFT OUTER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'INNER',
				'Structurereferente' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
				$this->Apre->name => 'LEFT OUTER',
				'Aideapre66' => 'LEFT OUTER',
				'Typeaideapre66' => 'LEFT OUTER',
				'Themeapre66' => 'LEFT OUTER',
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Apre' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Apre,
							$this->Apre->Personne->PersonneReferent,
							$this->Apre->Structurereferente,
							$this->Apre->Referent,
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Apre.id',
						'Apre.personne_id',
						'Apre.datedemandeapre',
					)
				);

				// 2. Jointure
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Apre->join('Structurereferente', array('type' => $types['Structurereferente'])),
						$this->Apre->join('Referent', array('type' => $types['Referent']))
					)
				);

				// 3. Ajout de champs et de jointures spécifiques au CG 66
				if( $departement === 66 ) {
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields(
							array(
								$this->Apre->Aideapre66,
								$this->Apre->Aideapre66->Themeapre66,
								$this->Apre->Aideapre66->Themeapre66->Typeaideapre66
							)
						)
					);

					$query['joins'] = array_merge(
						$query['joins'],
						array(
							$this->Apre->join( 'Aideapre66', array( 'type' => $types['Aideapre66'] ) ),
							$this->Apre->Aideapre66->join( 'Themeapre66', array( 'type' => $types['Themeapre66'] ) ),
							$this->Apre->Aideapre66->Themeapre66->join( 'Typeaideapre66', array( 'type' => $types['Typeaideapre66'] ) )
						)
					);
				}

				// 3. Si on utilise les cantons, on ajoute une jointure
				if( Configure::read( 'CG.cantons' ) ) {
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $this->Canton->joinAdresse();
				}

				$this->Apre->deepAfterFind = false;

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$query = $this->Allocataire->searchConditions( $query, $search );

			/**
			 * Generateur de conditions
			 */
			$paths = array(
				'Apre.structurereferente_id',
				'Apre.activitebeneficiaire',
				'Aideapre66.themeapre66_id',
				'Apre.etatdossierapre',
				'Apre.isdecision',
			);

			// Fils de dependantSelect
			$pathsToExplode = array(
				'Apre.referent_id',
				'Aideapre66.typeaideapre66_id',
			);

			$pathsDate = array(
				'Apre.datedemandeapre'
			);

			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			foreach( $pathsToExplode as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' && strpos($value, '_') > 0 ) {
					list(,$value) = explode('_', $value);
					$query['conditions'][$path] = $value;
				}
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $pathsDate );

			// CG 93: filtre "Statut de l'APRE"
			$statutapre = Hash::get( $search, 'Apre.statutapre' );
			if( !empty( $statutapre ) ) {
				$query['conditions']['Apre.statutapre'] = $statutapre;
			}

			// CG 93: filtre sur le tiers prestataire lié à une formation APRE
			$tiersprestataire_id = Hash::get( $search, 'Tiersprestataireapre.id' );
			if( !empty( $tiersprestataire_id ) ) {
				$this->loadModel( 'Tiersprestataireapre' );

				$aliases = array( 'Tiersprestataireapre' => 'tiersprestatairesapres' );
				$qd = array(
					'fields' => array( 'Tiersprestataireapre.id' ),
					'joins' => array(),
					'conditions' => array(
						'Tiersprestataireapre.id' => $tiersprestataire_id
					)
				);

				foreach( $this->Apre->modelsFormation as $modelName ) {
					$aliases[$modelName] = Inflector::tableize( $modelName );
					$join = $this->Tiersprestataireapre->join(
						$modelName,
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array( "{$modelName}.apre_id = Apre.id" )
						)
					);
					$qd['joins'][] = $join;
				}

				$qd = array_words_replace( $qd, $aliases );
				$sql = $this->Tiersprestataireapre->sq( $qd );
				$sql = str_replace( 'AS "Tiersprestataireapre"', 'AS "tiersprestatairesapres"', $sql );

				$query['conditions'][] = "EXISTS( {$sql} )";
			}

			// CG 93, Toute les demandes APRE, bloc Recherche par demande APRE
			$paths = array( 'Apre.typedemandeapre', 'Apre.activitebeneficiaire', 'Apre.natureaide' );
			foreach( $paths as $path ) {
				$value = (string)Hash::get( $search, $path );
				if( $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			return $query;
		}
	}
?>