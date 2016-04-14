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
		public static function actions(array $params = array()) {debug(2);
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
			$params = self::params($params);
			$actions = self::actions($params);

			foreach ($actions as $action) {
				$record[$params['alias']]['action_'.$action] = self::check($action, $record, $params);
			}

			return $record;
		}

		/**
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
		 *
		 * @param string $action
		 * @param array $record
		 * @param array $params
		 * @return boolean
		 */
		public final static function check($action, array $record, array $params = array()) {
			$method = "_{$action}";

			return method_exists(__CLASS__, $method)
				&& in_array($action, self::actions($params))
				&& call_user_func_array(array(__CLASS__, $method),
					array($record, self::params($params )));
		}
	}
?>
