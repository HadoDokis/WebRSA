<?php
	/**
	 * Code source de la classe WebrsaModelUtility.
	 *
	 * @package app.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe WebrsaModelUtility fourni une palette d'outils pour travailler sur les modeles et les requetes
	 * 
	 * @package app.Utility
	 */
	class WebrsaModelUtility {
		/**
		 * Permet d'obtenir la clef dans les jointures de la query en fonction d'un nom de modele (alias)
		 * ex : 
		 *	$query['joins'][223]['alias'] = 'Personne';
		 *	findJoinKey( 'Personne', $query ); // Renvoi (int) 223
		 * 
		 * @param string $modelName
		 * @param array $query
		 * @return integer
		 */
		public static function findJoinKey( $modelName, array $query ) {
			foreach ((array)Hash::get($query, 'joins') as $key => $jointure) {
				if (Hash::get($jointure, 'alias') === $modelName) {
					return $key;
				}
			}
			return false;
		}
	}
