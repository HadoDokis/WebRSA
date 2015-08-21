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
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 
			'Allocataire',
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
			$cgDepartement = Configure::read( 'Cg.departement' );
			$modelApreDpt = 'Apre'.Configure::read( 'Apre.suffixe' );
			
			$types += array(
				'Calculdroitrsa' => 'INNER',
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
				$modelApreDpt => 'LEFT OUTER',
				'Aideapre' . $cgDepartement => 'LEFT OUTER',
				'Typeaideapre' . $cgDepartement => 'LEFT OUTER',
				'Themeapre' . $cgDepartement => 'LEFT OUTER',
			);
			
			$Apre = ClassRegistry::init( $modelApreDpt );
			$Apre->alias = 'Apre';

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Apre' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Apre,
							$Apre->Personne->PersonneReferent,
							$Apre->Structurereferente,
							$Apre->Referent,
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
						$Apre->join('Structurereferente', array('type' => $types['Structurereferente'])),
						$Apre->join('Referent', array('type' => $types['Referent']))
					)
				);
				if ( isset($Apre->{'Aideapre'.$cgDepartement}) ) {
					$query['joins'][] = $Apre->join( 'Aideapre'.$cgDepartement, array( 'type' => $types['Aideapre'.$cgDepartement] ) );
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields( array($Apre->{'Aideapre'.$cgDepartement}) )
					);
				}
				if ( isset($Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}) ) {
					$query['joins'][] = $Apre->{'Aideapre'.$cgDepartement}->join( 'Themeapre'.$cgDepartement, array( 'type' => $types['Themeapre'.$cgDepartement] ) );
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields( array($Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}) )
					);
				}
				if ( isset($Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}->{'Typeaideapre'.$cgDepartement}) ) {
					$query['joins'][] = $Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}->join( 'Typeaideapre'.$cgDepartement, array( 'type' => $types['Typeaideapre'.$cgDepartement] ) );
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields( array($Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}->{'Typeaideapre'.$cgDepartement}) )
					);
				}
				
				// 3. Si on utilise les cantons, on ajoute une jointure
				if( Configure::read( 'CG.cantons' ) ) {
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $this->Canton->joinAdresse();
				}

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

			return $query;
		}
	}
?>