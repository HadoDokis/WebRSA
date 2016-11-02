<?php

/**
 * Code source de la classe CheckDroitsControllerShell.
 *
 * @package app.Console.Command
 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Shell.php.
 */

/**
 * La classe CheckDroitsControllerShell ...
 *
 * @package app.Console.Command
 */
class CheckDroitsControllerShell extends AppShell
{
	/**
	 * Nombre de messages envoyés (erreurs detectés)
	 * @var integer
	 */
	public $messageCount = 0;
	
	/**
	 * Méthode principale.
	 */
	public function main() {
		$controllers = App::objects('controllers');
		
		foreach ($controllers as $controller) {
			App::uses($controller, 'Controller');
			$reflect = new ReflectionClass($controller);
			
			if ($reflect->isAbstract()) {
				continue;
			}
			$Controller = new $controller;
			
			$actions = array();
			$reflectMethods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);
			foreach ($reflectMethods as $reflectMethod) {
				$actions[] = $reflectMethod->name;
			}
			
			// Vérifi que les attributs existent et qu'ils sont public
			$missings = array();
			foreach (array('aucunDroit', 'commeDroit', 'crudMap') as $property) {
				if ($reflect->hasProperty($property)) {
					if (!$reflect->getProperty($property)->isPublic()) {
						$this->_message('propertyIsNotPublic', $controller, $property);
					}

				} else {
					$this->_message('missingProperty', $controller, $property);
					$missings[] = $property;
				}
			}
			
			// Aucun droit
			if (!in_array('aucunDroit', $missings)) {
				foreach ($Controller->aucunDroit as $action) {
					if (!in_array($action, $actions)) {
						$this->_message('missingAction_aucunDroit', $controller, $action);
					}
				}
			}
			
			// Comme droit
			if (!in_array('commeDroit', $missings)) {
				foreach ($Controller->commeDroit as $action => $redirect) {
					if (!in_array($action, $actions)) {
						$this->_message('missingAction_commeDroit_key', $controller, $action);
					}
					
					if (!preg_match('/^([A-Z][\w]+):([\w]+)$/', $redirect, $matches)) {
						$this->_message('bad_syntax', $controller, $redirect);
						continue;
					}
					
					if (!in_array($matches[1].'Controller', $controllers)) {
						$this->_message('controller_not_found', $controller, $redirect);
						continue;
					}
					
					App::uses($matches[1].'Controller', 'Controller');
					$subReflect = new ReflectionClass($matches[1].'Controller');
					$subActions = array();
					foreach ($a = $subReflect->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectMethod) {
						$subActions[] = $reflectMethod->name;
					}
					
					if (!in_array($matches[2], $subActions)) {
						$this->_message('missingAction_commeDroit_value', $controller, $redirect);
					}
				}
			}
			
			// Crud map
			if (!in_array('crudMap', $missings)) {
				foreach (array_keys($Controller->crudMap) as $action) {
					if (!in_array($action, $actions)) {
						$this->_message('missingAction_crudMap', $controller, $action);
					}
				}
			}
		}
		
		$this->out('', 2);
		$this->out('Total de '.$this->messageCount.' erreurs detectés');
	}
	
	protected function _message($message, $controllerName, $propertyName) {
		switch ($message) {
			case 'missingProperty':
				$out = 'Propriété manquante dans le controlleur "%s" (%s)';
				break;
			case 'propertyIsNotPublic':
				$out = 'Propriété manquante dans le controlleur "%s" (%s)';
				break;
			case 'missingAction_commeDroit_key':
				$out = 'Une action n\'existe pas dans le controlleur "%s" (%s) sur la propriété commeDroit (clef)';
				break;
			case 'missingAction_commeDroit_value':
				$out = 'Une action n\'existe pas dans le controlleur "%s" (%s) sur la propriété commeDroit (valeur)';
				break;
			case 'bad_syntax' :
				$out = 'Mauvaise syntaxe sur la propriété commeDroit du controlleur "%s" (%s)';
				break;
			case 'controller_not_found' :
				$out = 'Erreur sur la propriété commeDroit du controlleur "%s", '
				. 'le controlleur n\'a pas été trouvé avec la valeur : %s';
				break;
			case 'missingAction_aucunDroit':
				$out = 'Une action n\'existe pas dans le controlleur "%s" (%s) sur la propriété aucunDroit';
				break;
			case 'missingAction_crudMap':
				$out = 'Une action n\'existe pas dans le controlleur "%s" (%s) sur la propriété crudMap';
				break;
			default:
				$out = '';
		}
		
		$this->out(sprintf($out, $controllerName, $propertyName));
		$this->messageCount++;
	}
}