<?php
	/**
	 * Code source de la classe AbstractWebrsaCohorteDossierpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaCohorte', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe AbstractWebrsaCohorteDossierpcg66 ...
	 *
	 * @package app.Model
	 */
	abstract class AbstractWebrsaCohorteDossierpcg66 extends AbstractWebrsaCohorte
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'AbstractWebrsaCohorteDossierpcg66';

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'WebrsaRechercheDossierpcg66',
			'Dossierpcg66'
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
				'Typepdo' => 'INNER',
				'Originepdo' => 'INNER',
				'Serviceinstructeur' => 'LEFT OUTER',
			);
			
			$query = $this->WebrsaRechercheDossierpcg66->searchQuery( $types );
			
			$query['fields'] = array_merge(
				$query['fields'],
				ConfigurableQueryFields::getModelsFields(
					array(
						$this->Dossierpcg66->Typepdo,
						$this->Dossierpcg66->Originepdo,
						$this->Dossierpcg66->Serviceinstructeur,
					)
				),
				// Champs nécessaires au traitement de la cohorte
				array(
					'Dossier.id',
					'Dossierpcg66.poledossierpcg66_id',
					'Dossierpcg66.dateaffectation',
				)
			);
			
			$query['joins'] = array_merge(
				$query['joins'],
				array(
					$this->Dossierpcg66->join( 'Typepdo', array( 'type' => $types['Typepdo'] ) ),
					$this->Dossierpcg66->join( 'Originepdo', array( 'type' => $types['Originepdo'] ) ),
					$this->Dossierpcg66->join( 'Serviceinstructeur', array( 'type' => $types['Serviceinstructeur'] ) ),
				)
			);
			
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
			$query = $this->WebrsaRechercheDossierpcg66->searchConditions( $query, $search );
			
			/**
			 * Generateur de conditions
			 */
			$paths = array(
				'Dossierpcg66.serviceinstructeur_id',
			);

			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}
			
			return $query;
		}
	}
?>