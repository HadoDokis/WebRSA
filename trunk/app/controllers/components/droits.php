<?php
/*
 * Gestion dynamique des droits des utilisateurs sur les actions (m�thodes) des controleurs
 *
 * 	Utilisation des variables suivantes � placer dans les contr�leurs :
 *  $demandeDroit = array('actionName') -> liste des actions qui sont concern�es par le contr�le des droits
 *  $demandePost = array('actionName') -> liste des actions qui demandent un post pour �tre ex�cut� (utilis�e avec le composant security)
 *  $aucunDroit = array('actionName')|Null -> listes des actions qui ne sont pas soumises au contr�le des droits (si vide, aucune actions n'est soumises au contr�le)
 *  $ajouteDroit = array('newActionName') -> listes des actions qui sont ajout�s pour le contr�le des droits alors que la m�thode n'existe pas'
 *  $commeDroit = array('actionName'=>'controllerAction'|array('controllerAction')) -> liste des actions soumises aux m�mes droits que d'autres actions (r�alise un OU dans le cas d'un array)
 *  $libelleControleurDroit = string -> permet de d�finir un nom m�tier pour le controleur
 *  $libellesActionsDroit = array(string=>string) -> permet de d�finir un nom pour les m�thodes du controleur
 *
 * Fonctionnement de la m�thode check
 *  la m�thode check v�rifie � l'aide du composant Acl, que l'utilisateur dont l'id est pass� en param�tre
 *  � les droits suffisants pour ex�cuter une action d'un controleur pass�e �galement
 *  en param�tre sous la forme NomControleur:nomMethode (ex : User:add).
 *  Pour chaque controleur, la liste des actions soumises aux droits est d�termin�e de la fa�on suivante
 *		si $demandeDroit est d�finie et est non vide
 *			alors -> liste des actions soumises aux droits = $demandeDroit
 *		si $aucunDroit est d�finie et est vide
 *			alors -> aucune des actions n'est soumises au contr�le des droits
 *		si $demandeDroit n'est pas d�finie ou est vide
 *			alors -> liste des actions soumises aux droits =
 *				liste des actions non priv�es du controleurs
 *				- liste des actions d�finies par $demandePost
 *				- liste des actions d�finies par $aucunDroit
 *				- liste des actions d�finies par $commeDroit
 *				+ liste des actions d�finies par $ajouteDroit
 *
 *	Si la variables $commeDroit est d�finie, le contr�le des droits se fait sur l'action d�finie dans cette variable
 *  Par d�faut, si aucune des variables pr�c�dentes n'est d�finie dans un controleur, alors
 *  toutes les m�thodes non priv�es seront soumises au contr�le des droits.
 *
 *  Attention : la m�thode check autorise toutes les actions qui ne sont pas soumises au droits.
*/
class DroitsComponent extends Object
{
	var $components = array('Acl');


/* v�rifie si l'utilisateur $userAlias est autoris�e � ex�cuter l'action $controllerAction */
/* v�rifie les droits si l'action est dans la liste des actions soumises aux droits */
	function check($userAlias, $controllerAction) {
		// Initialisations
		$listeActions = array();
		$listeActionsComme = array();
		$controller = substr($controllerAction, 0, strpos($controllerAction,':'));
		$action = substr($controllerAction, strpos($controllerAction,':')+1);

		// Pas de contr�le si controller = App -> Retourne true
		if ($controller == 'App') return true;

		// Initialisation de la liste des actions soumises aux droits
		if ($controller != 'Pages'){
			$listeActions = $this->listeActionsControleur($controller);
			$listeActionsComme = $this->_listeActionsCommeControleur($controller);
		}

		// V�rifie les droits si controller = 'Pages' ou si l'action est dans la liste des actions soumises aux droits
		if ($controller == 'Pages' or in_array($action, $listeActions) or array_key_exists($action, $listeActionsComme)) {
			if (array_key_exists($action, $listeActionsComme)) {
				// Traite les droits de commeDroit
				if (is_array($listeActionsComme[$action])) {
					foreach($listeActionsComme[$action] as $ctrlActionComme) {
						if ($this->Acl->check($userAlias, $ctrlActionComme)) return true;
					}
					return false;
				}
				else
					return $this->Acl->check($userAlias, $listeActionsComme[$action]);
			}
			else
				return $this->Acl->check($userAlias, $controllerAction);
		}
		else
			return true;
	}

/* d�termine la liste des actions (m�thodes) qui sont soumises aux droits d'un controleur */
/* en fonction des variables $demandeDroit, $aucunDroit, $demandePost, $commeDroit, $ajouteDroit */
	function listeActionsControleur($controllerName) {

		// chargement du controleur
		$file = APP."controllers".DS.Inflector::underscore($controllerName)."_controller.php";
		require_once($file);
		$subClassVars = get_class_vars($controllerName.'Controller');

		// Si $demandeDroit est d�finie et non vide alors retourne cette liste
		if(array_key_exists('demandeDroit', $subClassVars) and !empty($subClassVars['demandeDroit']))
		{
			return $subClassVars['demandeDroit'];
		}

		// Si $aucunDroit est d�finie et vide alors retourne liste vide
		if(array_key_exists('aucunDroit', $subClassVars) and empty($subClassVars['aucunDroit']))
		{
			return array();
		}

		// Cr�ation de la liste des actions du controleur
		$parentClassMethods = get_class_methods('AppController');
		$subClassMethods = get_class_methods($controllerName.'Controller');
		$classMethods = array_diff($subClassMethods, $parentClassMethods);

		// Suppression des actions priv�es et prot�g�es (commencent par '_')
		$classMethods = array_filter($classMethods, array($this, '_nonPrivee'));

		// Ajout des actions du scaffold
		if(array_key_exists('scaffold', $subClassVars) && !empty($subClassVars['scaffold']))
		{
			if (!in_array('index', $classMethods)) $classMethods[] = 'index';
			if (!in_array('view', $classMethods)) $classMethods[] = 'view';
			if (!in_array('add', $classMethods)) $classMethods[] = 'add';
			if (!in_array('edit', $classMethods)) $classMethods[] = 'edit';
			if (!in_array('delete', $classMethods)) $classMethods[] = 'delete';
		}

		// Suppression des actions soumises � un post $demandePost
		if(array_key_exists('demandePost', $subClassVars) and !empty($subClassVars['demandePost']))
		{
			$classMethods = array_diff($classMethods, $subClassVars['demandePost']);
		}

		// Suppression des actions qui ne sont soumises � aucun droit $aucunDroit
		if(array_key_exists('aucunDroit', $subClassVars) and !empty($subClassVars['aucunDroit']))
		{
			$classMethods = array_diff($classMethods, $subClassVars['aucunDroit']);
		}

		// Suppression des actions qui sont soumises � d'autre droits $commeDroit
		if(array_key_exists('commeDroit', $subClassVars) and !empty($subClassVars['commeDroit']))
		{
			$classMethods = array_diff($classMethods, array_keys($subClassVars['commeDroit']));
		}

		// Ajout des actions suppl�mentaires $ajouteDroit
		if(array_key_exists('ajouteDroit', $subClassVars) and !empty($subClassVars['ajouteDroit']))
		{
			$classMethods = array_merge($classMethods, $subClassVars['ajouteDroit']);
		}

		return $classMethods;

	}

/* Retourne les libell�s d�finits dans $libellesActionsDroit correspondant � $ListeActions */
	function libellesActionsControleur($controllerName, $listeActions) {
		// Initialisations
		$listeActionsLibelles = array();

		// chargement du controleur
		$file = APP."controllers".DS.Inflector::underscore($controllerName)."_controller.php";
		require_once($file);
		$subClassVars = get_class_vars($controllerName.'Controller');

		// initialisation du tableau des libelles
		$i=0;
		foreach($listeActions as $action) {
			$listeActionsLibelles[$i] = $action;
			$i++;
		}

		// teste si $libellesDroit est d�fini et non vide
		if(array_key_exists('libellesActionsDroit', $subClassVars) and !empty($subClassVars['libellesActionsDroit']))
		{
			$i=0;
			foreach($listeActions as $action) {
				if (array_key_exists($action,$subClassVars['libellesActionsDroit']))
					$listeActionsLibelles[$i] = $subClassVars['libellesActionsDroit'][$action];
				$i++;
			}
		}

		return $listeActionsLibelles;
	}

/* Retourne le libell� d�fini dans $libelleControleurDroit */
	function libelleControleur($controllerName) {
		// chargement du controleur
		$file = APP."controllers".DS.Inflector::underscore($controllerName)."_controller.php";
		require_once($file);
		$subClassVars = get_class_vars($controllerName.'Controller');

		// teste si $libelleControleurDroit est d�fini et non vide
		if(array_key_exists('libelleControleurDroit', $subClassVars) and !empty($subClassVars['libelleControleurDroit']))
		{
			return $subClassVars['libelleControleurDroit'];
		}

		return $controllerName;
	}

/* retourne true si $nomMethode ne commence pas pas '_' */
	function _nonPrivee($nomMethode) {
		return $nomMethode[0] != '_';
	}

/* retourne la liste $commeDroit si elle est d�finie et non vide */
	function _listeActionsCommeControleur($controllerName) {

		// chargement du controleur
		$file = APP."controllers".DS.Inflector::underscore($controllerName)."_controller.php";
		require_once($file);
		$subClassVars = get_class_vars($controllerName.'Controller');

		if(array_key_exists('commeDroit', $subClassVars) and !empty($subClassVars['commeDroit']))
		{
			return $subClassVars['commeDroit'];
		} else return array();

	}


}
?>
