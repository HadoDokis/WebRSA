<?php
	/**
	 * Code source de la classe AbstractSearch.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Abstractclass
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Interface pour les classes de moteurs de recherche.
	 *
	 * @package app.Model.Abstractclass
	 */
	interface ISearch
	{
		/**
		 * Retourne le querydata pour le moteur de recherche.
		 *
		 * @param array $types Le nom du modèle => le type de jointure
		 * @return array
		 */
		public function searchQuery( array $types = array() );

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search );

		/**
		 * Moteur de recherche de base.
		 *
		 * @return array
		 */
		public function search( array $search = array() );

		/**
		 * Retourne les options nécessaires au formulaire de recherche, aux
		 * impressions, ...
		 *
		 * @param array $params
		 * @return array
		 */
//		 public function options( array $params = array() );
	}

	/**
	 * La classe AbstractSearch ...
	 *
	 * @package app.Model.Abstractclass
	 */
	abstract class AbstractSearch extends AppModel implements ISearch
	{
		/**
		 * Moteur de recherche de base.
		 *
		 * @return array
		 */
		public function search( array $search = array() ) {
			$query = $this->searchQuery();

			$query = $this->searchConditions( $query, $search );

			return $query;
		}
	}
?>