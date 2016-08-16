<?php

	/**
	 * Code source de la classe ValidationErrorsUtility.
	 *
	 * @package app.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ValidationErrorsUtility Permet de trouver facilement la totalité des erreurs de validation
	 *
	 * @package app.Utility
	 */
	abstract class ValidationErrors {
		/**
		 * Permet de trouver facilement la totalité des erreurs de validation
		 */
		public static function all() {
			$results = array();
			foreach (App::objects('Model') as $model) {
				if (class_exists($model)) {
					$r = new ReflectionClass($model);
					if (!$r->isAbstract()) {
						$errors = ClassRegistry::init($model)->validationErrors;
					}
					if (!empty($errors)) {
						$results[$model] = $errors;
					}
				}
			}
			return $results;
		}
	}