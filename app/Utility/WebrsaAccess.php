<?php
	/**
	 * Code source de la classe WebrsaAccess.
	 *
	 * PHP 5.3
	 *
	 * @package app.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaPermissions', 'Utility');

	/**
	 * La classe WebrsaAccess utilise les rêgles métier afin de griser ou pas un ou plusieurs liens
	 *
	 * @package app.Utility
	 */
	class WebrsaAccess
	{	
		/**
		 * Renseignez le dossier menu si besoin
		 * 
		 * @var array
		 */
		public static $dossierMenu;
		
		/**
		 * Permet de définir en une fois les variables utiles
		 * 
		 * @param array $dossierMenu
		 */
		public static function init($dossierMenu = null) {
			self::$dossierMenu = $dossierMenu;
		}
		
		/**
		 * Permet de produire un lien aux standards du plugin Defaut3 avec import
		 * de la vérification acl et métier (standard WebrsaAccess)
		 * 
		 * Fonction avec ou sans dossierMenu/modèle
		 * @see WebrsaAccess::init
		 * 
		 * @param String $url - ex: '/controller/action/params'
		 * @param array $params - array('condition' => true, 'msgid' => 'foo', ...)
		 *						- Spécial <b>(boolean) 'regles_metier'</b> Prise en compte ou non des règles métier
		 *						- Spécial <b>(String) 'controller'</b> Permet de spécifier l'action d'un autre controller
		 * @return array - array('/controller/action/id' => array('disable' => true))
		 */
		public static function link($url, $params = array()) {
			$matches = null;
			if (!preg_match('/^\/([\w]+)(?:\/([\w]+)){0,1}/', $url, $matches)) {
				trigger_error("URL mal définie");
				exit;
			}
			
			$useModel = Hash::get((array)$params, 'regles_metier');
			unset($params['regles_metier']);
			
			$controller = strtolower($matches[1]);
			$action = count($matches) === 3 ? $matches[2] : 'index';
			
			$aclAccess = self::$dossierMenu !== null
				? WebrsaPermissions::checkDossier($controller, $action, self::$dossierMenu)
				: WebrsaPermissions::check($controller, $action)
			;
			
			$toEval = "!'#/".ucfirst($controller)."/$action#'";
			
			$disabled = $useModel !== false
				? ($aclAccess ? $toEval : true)
				: !$aclAccess
			;
			
			return array($url => array('disabled' => $disabled) + (array)$params);
		}
		
		/**
		 * Permet de traiter en une seule fois, une liste d'actions, utile pour Default3::index
		 * 
		 * @param array $urls - Liste des urls ex: array('/controller/action/params', ...)
		 * @param array $allParams - Paramètres à appliquer à tous les liens
		 * @return array
		 */
		public static function links(array $urls, array $allParams = array()) {
			$results = array();
			foreach (Hash::normalize($urls) as $url => $params) {
				$link = self::link($url, (array)$params + $allParams);
				$results[$url] = $link[$url];
			}
			return $results;
		}
		
		/**
		 * Utile pour l'action add, permet d'ajouter un boolean $ajoutPossible à la fonction link
		 * Désactive également tout seul les rêgles métier (déjà traité par $ajoutPossible)
		 * 
		 * @param String $url - ex: '/controller/action/params'
		 * @param boolean $ajoutPossible
		 * @param array $params
		 * @return array
		 */
		public static function actionAdd($url, $ajoutPossible = true, $params = array()) {
			$params['regles_metier'] = false;
			$link = self::link($url, $params);
			
			if ($link[$url]['disabled'] === false) {
				$link[$url]['disabled'] = !$ajoutPossible;
			}
			
			return $link;
		}
	}
?>