<?php
	/**
	 * Code source de la classe AbstractWebrsaCohorteContratinsertion.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaCohorte', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe AbstractWebrsaCohorteContratinsertion ...
	 *
	 * @package app.Model
	 */
	class AbstractWebrsaCohorteContratinsertion extends AbstractWebrsaCohorte
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'AbstractWebrsaCohorteContratinsertion';

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Allocataire',
			'Contratinsertion',
			'Canton',
		);

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types += array(
				'Personne' => 'INNER',
				'Referent' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Dossier' => 'INNER',
				'Adressefoyer' => 'LEFT OUTER',
				'Adresse' => 'LEFT OUTER',
				'Typeorient' => 'LEFT OUTER',
				'Structurereferente' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'Orientstruct' => 'LEFT OUTER'
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Contratinsertion' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Contratinsertion,
							$this->Contratinsertion->Referent,
							$this->Contratinsertion->Structurereferente,
							$this->Contratinsertion->Personne->Orientstruct,
							$this->Contratinsertion->Personne->Orientstruct->Typeorient
						)
					),
					array(
						'Personne.id',
						'Dossier.id',
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
						'Contratinsertion.datevalidation_ci',
						'Contratinsertion.observ_ci',
					)
				);

				// 2. Jointures
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Contratinsertion->join( 'Referent', array( 'type' => $types['Referent'] ) ),
						$this->Contratinsertion->join( 'Structurereferente', array( 'type' => $types['Structurereferente'] ) ),
						$this->Contratinsertion->Personne->join( 'Orientstruct',
							array(
								'type' => $types['Orientstruct'],
								'conditions' => array(
									'Orientstruct.statut_orient' => 'Orienté',
									'Orientstruct.id IN ( '.$this->Contratinsertion->Personne->Orientstruct->sqDerniere().' )'
								)
							)
						),
						$this->Contratinsertion->Personne->Orientstruct->join( 'Typeorient', array( 'type' => $types['Typeorient'] ) ),
					)
				);
				
				// 4. Tri par défaut
				$query['order'] = array( 'Contratinsertion.df_ci' => 'ASC' );

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
				'Contratinsertion.structurereferente_id'
			);

			// Fils de dependantSelect
			$pathsToExplode = array(
				'Contratinsertion.referent_id'
			);

			$pathsDate = array(
				'Contratinsertion.created'
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

			return $query;
		}
	}
?>