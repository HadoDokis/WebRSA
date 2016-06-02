<?php
	/**
	 * Code source de la classe WebrsaAccessCommissionseps.
	 *
	 * PHP 5.3
	 *
	 * @package app.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaAbstractAccess', 'Utility');

	/**
	 * La classe WebrsaAccessCommissionseps ...
	 *
	 * @package app.Utility
	 */
	class WebrsaAccessCommissionseps extends WebrsaAbstractAccess
	{
		/**
		 * Paramètres par défaut
		 * 
		 * @param array $params
		 * @return array
		 */
		public static function params(array $params = array()) {
			return $params + array(
				'alias' => 'Commissionep',
				'departement' => (int)Configure::read('Cg.departement'),
			);
		}

		/**
		 * Permission d'accès
		 * 
		 * @param array $record
		 * @param array $params
		 * @return boolean
		 */
		protected static function _decisionep(array $record, array $params) {
			return true;
		}

		/**
		 * Permission d'accès
		 * 
		 * @param array $record
		 * @param array $params
		 * @return boolean
		 */
		protected static function _decisioncg(array $record, array $params) {
			return true;
		}
		
		/**
		 * Liste les actions disponnible
		 * Si une action pointe sur un autre controler, il faut préciser son nom
		 * ex : Moncontroller.monaction
		 * 
		 * @param array $params
		 * @return array
		 */
		public static function actions(array $params = array()) {
			$params = self::params($params);
			$result = self::normalize_actions(
				array(
					
				)
			);
			
			switch ($params['departement']) {
				case 58: 
					$result = self::merge_actions(
						$result, array(
							'decisionep',
						)
					);
					break;
				default:
					$result = self::merge_actions(
						$result, array(
							'decisioncg',
						)
					);
			}
			
			return $result;
		}
	}