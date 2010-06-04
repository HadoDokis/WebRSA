<?php
/*
 */

class MenuComponent extends Object
{
	var $components = array('Acl', 'Droits');

    function load($varNameMenu, $aro=null)
    {
		// lecture du menu du fichier menu.ini.php
    	include(CONFIGS . 'menu.ini.php');
    	$menuRet = ${$varNameMenu};

    	// chargement des droits
		if (!empty($aro)) $this->_filtreMenuDroits($menuRet, $aro);

    	return $menuRet;
    }

    function _filtreMenuDroits(&$menu, $aro) {

		$items = $menu['items'];
		foreach($items as $title => $menuItem)
		{
			// Calcul du aco en fonction du lien
			$aco = $this->_calcAction($menuItem['link']);

			// V�rifie les droits
			if ($this->Acl->Check($aro, $aco, "*")) {
				// sous-menu
				if (array_key_exists('subMenu', $menuItem) and
					is_array($menuItem['subMenu']) and count($menuItem['subMenu'])>0) {
					$this->_filtreMenuDroits($menu['items'][$title]['subMenu'], $aro);
				};
			} else unset($menu['items'][$title]);

		};

    }

/* retourne le couple Controler:action pour le lien pass� en param�tre */
	function _calcAction($lien) {

		// Traitement du lien
		if (empty($lien) or $lien == '/') return 'Pages:home';
		else {
			if ($lien[0] == '/') $lien = substr($lien, 1);
			else $lien = 'pages/' . $lien;
			$tabAction = explode('/', $lien);
			$tabAction[0] = ucwords($tabAction[0]);
			if (count($tabAction) == 1) $tabAction[] = 'index';
			return $tabAction[0] . ':' . $tabAction[1];
		};
	}


/**
 * Retourne la valeur $key du menu $menu si elle existe et si elle et non null,
 * et retourne $default dans le cas contraire.
 *
 * @return
 *
 * @param array &$menu Donn�es du menu
 * @param str $key Nom de la valeur � traiter
 * @param str $default Valeur par d�faut
 * @access private
 */
	function _getArrayValue($menu, $key, $default=null) {
		if (!array_key_exists($key, $menu)) return $default;

		if (is_array($menu[$key])) {
			if(count($menu[$key])<1) return $default;
			return $menu[$key][0] ? $menu[$key][0] : $default;
		};

		return $menu[$key] ? $menu[$key] : $default;
	}
	
function listeAliasMenuControlleur() {
	// liste des alias du menu
	$listeAliasMenu = $this->_listeAliasMenu($this->load('menu'));

	// Ajout des actions de tous les controllers du projet qui ne sont pas r�f�renc�es par le menu
	$listeAliasCtrl = array();
	$controllerList = $this->_listClasses(APP."/controllers/", '_controller.php');
	foreach($controllerList as $controllerFile) {
		$controllerName = Inflector::camelize(str_replace('_controller.php','',$controllerFile));
		$listeActions = $this->Droits->listeActionsControleur($controllerName);
		// Liste des actions de ce controlleur qui ne sont pas dans le menu
		$actionPlus = array();
		$ctrl_alias = 'Module:'.$controllerName;
		foreach($listeActions as $action) {
			$trouve = false;
			$aliasAction = $controllerName.':'.$action;
			foreach ($listeAliasMenu as $aliasMenu)
				if ($aliasAction === $aliasMenu['alias']) {
					$trouve = true;
					break;
				}
			if (!$trouve) $actionPlus[]=array('alias'=>$aliasAction, 'parent_alias'=>$ctrl_alias);
		}
		if (!empty($actionPlus)) {
			$listeAliasCtrl[] = array('alias'=>$ctrl_alias, 'parent_alias'=>'');
			$listeAliasCtrl = array_merge($listeAliasCtrl, $actionPlus);
		}
	}
	return array_merge($listeAliasMenu, $listeAliasCtrl);
}

/**
 * fonction r�cursive du parcours des entr�es du menu
 */
function _listeAliasMenu($menu, $parentAlias='') {
	// Initialisation
	$ret = array();

	$items = $menu['items'];
	foreach($items as $title => $menuItem) {
		// Calcul de l'alias aco en fonction du lien
		$alias = $this->_calcAction($menuItem['link']);
		$ret[] = array('alias'=>$alias, 'parent_alias'=>$parentAlias);
		// sous-menu
		if (	array_key_exists('subMenu', $menuItem) and
				is_array($menuItem['subMenu']) and
				count($menuItem['subMenu'])>0)
			$ret = array_merge($ret, $this->_listeAliasMenu($menu['items'][$title]['subMenu'], $alias));
	}
	return $ret;
}

/**
 * Returns an array of filenames of PHP files in given directory.
 *
 * @param  string $path Path to scan for files
 * @return array  List of files in directory
 */
function _listClasses($path, $filtre='') {
	$dir = opendir($path);
	$classes=array();
	while (false !== ($file = readdir($dir))) {
		if ((substr($file, -3, 3) == 'php') && substr($file, 0, 1) != '.') {
			if (!empty($filtre)) {
				if (strpos($file, $filtre)>0)
					$classes[] = $file;
			} else $classes[] = $file;
		}
	}
	closedir($dir);
	sort($classes);
	return $classes;
}

/**
 * Retourne la liste du menu principal et des controleurs pour l'affichage de l'onglet des droits
 * Initialise la valeur 'modifiable' en fonction des droits de la collectivit�-r�le-utilisateur
 * Intitialise � True le champ 'modifiable' si $cru est vide
 * @param array $cru ('model'=>string, 'foreign_key'=>integer)
 * @return array(
 * 	[title] => affich� de vant la case � cocher
 *	[acosAlias] => alias de la table acos
 *	[niveau] => 0:menu principal, 1:sous-menu, 2:sous-sous-menu, ...
 *	[nbSousElements] => nb de sous �l�ments du menu
 *	[modifiable] => false|true indique si la case � cocher est accessible ou pas.
 */
function menuCtrlActionAffichage($cru=null) {
	$ret = array();
	
	// Chargement de l'arborescence du menu
	$this->_chargeMenuControllers($ret, $this->load('menu'), $cru);

	// Chargement des controleurs/Action qui ne sont pas dans le menu
	$this->_chargeControllersActions($ret, $cru);

	return $ret;
}

/**
 * construit l'arborescence du menu et des controleurs dans le tableau $menuCtrlTree
 * retourne le nombre d'�l�ments du menu
 *
 * @param  string $path Path to scan for files
 * @return array  Liste des action du menu
 */
function _chargeMenuControllers(&$menuCtrlTree, $menu, $cru=null, $niveau=0) {
	// Initialisations
	$nbTotElement = 0;

	// Parcours des items du menu
	$items = $menu['items'];
	foreach($items as $title => $menuItem) {
		$menuCtrlTree[] = array();
		$key = count($menuCtrlTree) - 1;
		$acosAlias = $this->_calcAction($menuItem['link']);
		$menuCtrlTree[$key]['title'] = ($niveau == 0 ? 'Menu:' : '') . $title;
		$menuCtrlTree[$key]['acosAlias'] = $acosAlias;
		$menuCtrlTree[$key]['niveau'] = $niveau;
		$menuCtrlTree[$key]['nbSousElements'] = 0;
		$menuCtrlTree[$key]['modifiable'] = empty($cru) ? true : $this->Acl->Check($cru, $acosAlias);

		// Traitement des sous-menus
		if (array_key_exists('subMenu', $menuItem) and
			is_array($menuItem['subMenu']) and count($menuItem['subMenu'])>0) {
			$menuCtrlTree[$key]['nbSousElements'] += $this->_chargemenuControllers($menuCtrlTree, $menuItem['subMenu'], $cru, $niveau+1);
		}

		$nbTotElement += $menuCtrlTree[$key]['nbSousElements'] + 1;
	}
	return $nbTotElement;
}

/**
 * ajoute les actions des controller qui ne sont pas li�s au menu $menuCtrlTree
 * retourne le nombre d'�l�ments ajout�s
 *
 */
function _chargeControllersActions(&$menuCtrlTree, $cru=null) {

	// Initialisation
	$nbElements = 0;

	// Parcours des controleurs
	$controllerList = $this->_listClasses(APP."/controllers/");
	foreach($controllerList as $controllerFile) {
		if (strpos($controllerFile, '_controller.php')>0) {
			$controllerName = Inflector::camelize(str_replace('_controller.php','',$controllerFile));
			$listeActions = $this->Droits->listeActionsControleur($controllerName);
			// Supprime les actions d�j� li�es au menu
			foreach($listeActions as $key => $action) {
				if ($this->_trouveAction($controllerName . ':' . $action, $menuCtrlTree)) unset($listeActions[$key]);
			}
			// Ajout � la liste des menus-controleurs
			if (!empty($listeActions)) {
				$menuCtrlTree[] = array();
				$key = count($menuCtrlTree) - 1;
				$acosAlias = 'Module:'.$controllerName;
				$menuCtrlTree[$key]['title'] = 'Module:'.$this->Droits->libelleControleur($controllerName);
				$menuCtrlTree[$key]['acosAlias'] = $acosAlias;
				$menuCtrlTree[$key]['niveau'] = 0;
				$menuCtrlTree[$key]['nbSousElements'] = count($listeActions);
				$menuCtrlTree[$key]['modifiable'] = empty($cru) ? true : $this->Acl->Check($cru, $acosAlias);

				// Ajoute les libell�s des actions
				$listeLibelles = $this->Droits->libellesActionsControleur($controllerName, $listeActions);
				$i = 0;
				foreach($listeActions as $action) {
					$key++;
					$acosAlias = $controllerName . ':' . $action;
					$menuCtrlTree[$key]['title'] = $listeLibelles[$i];
					$menuCtrlTree[$key]['acosAlias'] = $acosAlias;
					$menuCtrlTree[$key]['niveau'] = 1;
					$menuCtrlTree[$key]['nbSousElements'] = 0;
					$menuCtrlTree[$key]['modifiable'] = empty($cru) ? true : $this->Acl->Check($cru, $acosAlias);
					$i++;
				}

				$nbElements += 1 + count($listeActions);
			}
		}
	}
	return $nbElements;
}

/**
 * retourne True si l'action $actionName est dans $menuCtrlTree
 */
function _trouveAction($actionName, $menuCtrlTree) {
	foreach($menuCtrlTree as $menuCtrl) {
		if ($menuCtrl['acosAlias'] == $actionName) return true;
	}
	return false;
}

}
?>
