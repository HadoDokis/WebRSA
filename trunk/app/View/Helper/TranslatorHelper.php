<?php
	/**
	 * Fichier source de la classe TranslatorHelper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe TranslatorHelper
	 *
	 * @package Translator
	 * @subpackage app.View.Helper
	 */
	class TranslatorHelper extends AppHelper
	{
		/**
		 * Normalize et ajoute les traductions à l'array donné
		 * 
		 * @param array $fields
		 * @return array
		 */
		public function normalize(array $fields) {
			$results = array();
			foreach (Hash::normalize($fields) as $key => $field) {
				$paramName = strpos($key, '/') === 0 ? 'msgid' : (strpos($key, 'data[') === 0 ? '' : 'label');
				if ($paramName === '') {
					$results[$key] = (array)$field;
					continue;
				}
				$results[$key] = Hash::get((array)$field, $paramName) || Hash::get((array)$field, 'type') === 'hidden'
					? $field
					: (array)$field + array($paramName => __m($key))
				;
			}
			return $results;
		}
	}