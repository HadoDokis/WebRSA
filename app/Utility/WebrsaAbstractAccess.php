<?php
    /**
     * Code source de la classe WebrsaAbstractAccess.
     *
     * PHP 5.3
     *
     * @package app.Utility
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
	 * La classe WebrsaAbstractAccess 
     *
     * @package app.Utility
     */
	abstract class WebrsaAbstractAccess
	{
		/**
		 * Renvoi la liste des actions disponibles
		 * 
		 * @param array $params
		 * @return array - array('action1', 'action2', ...)
		 */
		public static function actions(array $params = array()) {
			return array();
		}
		
		/**
		 * Permet d'obtenir | de compléter les paramètres par défaut
		 * 
		 * @param array $params
		 * @return array
		 */
		public static function params(array $params = array()) {
			return $params;
		}

		/**
		 * Complète $record avec 
		 * 
		 * @param array $record
		 * @param array $params
		 * @return array
		 */
		public final static function access(array $record, array $params = array()) {
			$className = get_called_class();
			$params = call_user_func(array($className, 'params'), $params);
			$actions = call_user_func(array($className, 'actions'), $params);

			foreach ($actions as $action) {
				$record[$params['alias']]['action_'.$action] = self::check($action, $record, $params);
			}

			return $record;
		}

		/**
		 * Permet d'obtenir les accès pour un index
		 *
		 * @param array $records
		 * @param array $params
		 * @return array
		 */
		public final static function accesses(array $records, array $params = array()) {
			foreach (array_keys($records) as $key) {
				$records[$key] = self::access($records[$key], $params);
			}

			return $records;
		}

		/**
		 * Permet de vérifier les droits d'accès à une action sur un enregistrement
		 *
		 * @param string $action
		 * @param array $record
		 * @param array $params
		 * @return boolean
		 */
		public final static function check($action, array $record, array $params = array()) {
			$className = get_called_class();
			$method = "_{$action}";

			return method_exists($className, $method)
				&& in_array($action, call_user_func(array($className, 'actions'), $params))
				&& call_user_func(array($className, $method), $record, call_user_func(array($className, 'params'), $params))
			;
		}
	}
?>
