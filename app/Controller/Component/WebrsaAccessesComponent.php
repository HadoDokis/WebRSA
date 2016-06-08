<?php
	/**
	 * Code source de la classe WebrsaAccessesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe WebrsaAccessesComponent fournit des méthodes de contrôle d'accès métier
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaAccessesComponent extends Component
	{
		/**
		 * Nom du component
		 *
		 * @var string
		 */
		public $name = 'WebrsaAccesses';
		
		/**
		 * Controller lié au component
		 * 
		 * @var Object
		 */
		public $Controller = null;
		
		/**
		 * Modèle principal
		 * 
		 * @var Object
		 */
		public $MainModel = null;
		
		/**
		 * Modèle de logique
		 * 
		 * @var Object
		 */
		public $WebrsaModel = null;
		
		/**
		 * Modèle supérieur au modèle principal
		 * ex: Personne ou Foyer
		 * 
		 * @var Object
		 */
		public $parentModelName = null;
		
		/**
		 * Nom de la classe de logique d'accès métier à une action
		 * 
		 * @var String - WebrsaAccess<i>Nomducontroller</i>
		 */
		public $WebrsaAccessClassName = '';
		
		/**
		 * Funcion init() appelé ?
		 * 
		 * @var boolean
		 */
		protected $_initialized = false;
		
		/**
		 * Alias du modèle lié au Controller
		 * 
		 * @var String
		 */
		protected $_alias = '';
		
		/**
		 * Assure le chargement des modèles et Utilitaires liés
		 * 
		 * @param Controller $controller
		 * @param array $modelNames
		 * @return void
		 */
		public function initialize(Controller $controller, array $modelNames = array()) {
			$modelNames += array(
				'mainModelName' => null,
				'webrsaModelName' => null,
				'webrsaAccessName' => null,
				'parentModelName' => null,
			);
			extract($modelNames);
			extract($this->settings);
			
			$MainModelName = $mainModelName ?: self::controllerNameToModelName($controller->name);
			$WebrsaModelClassName = $webrsaModelName ?: 'Webrsa'.$MainModelName;
			$WebrsaAccessClassName = $webrsaAccessName ?: 'WebrsaAccess'.$controller->name;
			
			// Si le modèle principal n'est pas chargé
			if (!isset($controller->{$MainModelName})) {
				$controller->{$MainModelName} = ClassRegistry::init($MainModelName);
			}
			
			// Si le modèle de logique n'est pas chargé
			if (!isset($controller->{$WebrsaModelClassName})) {
				$controller->{$WebrsaModelClassName} = ClassRegistry::init($WebrsaModelClassName);
			}
			
			// Si l'utilitaire n'est pas chargé...
			if (!class_exists($WebrsaAccessClassName)) {
				App::uses($WebrsaAccessClassName, 'Utility');
			}
			
			$this->Controller = $controller;
			$this->MainModel =& $this->Controller->{$MainModelName};
			$this->WebrsaModel =& $this->Controller->{$WebrsaModelClassName};
			$this->WebrsaAccessClassName = $WebrsaAccessClassName;
			$this->parentModelName = $parentModelName ?: (
				!isset($this->MainModel->belongsTo['Personne']) && isset($this->MainModel->belongsTo['Foyer']) ? 'Foyer' : 'Personne'
			);
			
			// Vérifications
			$interfaces = class_implements($WebrsaModelClassName);
			if (!in_array('WebrsaLogicAccessInterface', $interfaces)) {
				trigger_error(
					sprintf("La classe %s doit impl&eacute;menter l'interface %s", $WebrsaModelClassName, 'WebrsaLogicAccessInterface')
				);
			}
			
			$this->_initialized = true;
			
			return parent::initialize($controller);
		}
		
		/**
		 * Permet de modifier les modèles liés au component
		 * 
		 * @param String $attr - Nom de l'attribut
		 * @param mixed $name - Model ou String
		 * @return \WebrsaAccessesComponent
		 */
		protected function _setAttr($attr, $name) {
			if ($name instanceof Model) {
				$this->{$attr} = $name;
			} elseif (isset($this->Controller->{$name})) {
				$this->{$attr} =& $this->Controller->{$name};
			} else {
				$this->Controller->{$name} = ClassRegistry::init($name);
				$this->{$attr} =& $this->Controller->{$name};
			}
			return $this;
		}
		
		/**
		 * Permet le changement du modèle principal (singulier du nom du controller)
		 * 
		 * @param mixed $modelName
		 * @return \WebrsaAccessesComponent
		 */
		public function setWebrsaModel($modelName) {
			return $this->_setAttr('WebrsaModel', $modelName);
		}
		
		/**
		 * Permet le changement du modèle principal (singulier du nom du controller)
		 * 
		 * @param mixed $modelName
		 * @return \WebrsaAccessesComponent
		 */
		public function setMainModel($modelName) {
			return $this->_setAttr('MainModel', $modelName);
		}
		
		/**
		 * Assure l'initialisation du component
		 * 
		 * @param array $modelNames
		 * @return void
		 */
		public function init(array $modelNames = array()) {
			return $this->_initialized ?: $this->initialize($this->_Collection->getController(), $modelNames);
		}

		/**
		 * Fait appel à WebrsaAccess<i>Nomducontroller</i> pour vérifier les droits 
		 * d'accès à une action en fonction d'un enregistrement
		 * 
		 * @param integer $id			- Id de l'enregistrement si il existe
		 *								  Sera envoyé à Webrsa<i>Nomdumodel</i>::getDataForAccess
		 * 
		 * @param integer $parent_id		- Le plus souvent : personne_id ou foyer_id	si disponnible (nécéssaire si $id = null)
		 *								  Sera envoyé à Webrsa<i>Nomdumodel</i>::getParamsForAccess
		 * 
		 * @param array $params			- Paramètres à envoyer à WebrsaAccess<i>Nomducontroller</i>
		 * 
		 * @return void
		 * @throws Error403Exception
		 * @throws Error404Exception
		 */
		public function check($id = null, $parent_id = null, array $params = array()) {
			if (($id !== null && !self::_validId($id)) || ($parent_id !== null && !self::_validId($parent_id))) {
				throw new Error404Exception();
			}
			
			$this->init();
			
			if (!isset($this->Controller->{$this->MainModel->alias})) {
				trigger_error(sprintf("Le controller '%s' doit avoir la valeur '%s' dans l'attribut 'uses'", 
					$this->Controller->name, $this->MainModel->alias)
				);
			}
			
			$record = $this->_getRecord($id);
			$actionsParams = call_user_func(
				array($this->WebrsaAccessClassName, 'getActionParamsList'), 
				$this->Controller->action, 
				$params
			);
			$paramsAccess = $this->WebrsaModel->getParamsForAccess(
				$this->_parentId($id, $record, $parent_id), $actionsParams
			);
			
			if ($this->_haveAccess($record, $paramsAccess) === false) {
				throw new Error403Exception(
					__("Exception::access_denied", 
						$this->Controller->name, 
						$this->Controller->action, 
						$this->Controller->Session->read('Auth.User.username')
					)
				);
			}
		}
		
		/**
		 * Permet d'obtenir le nécéssaire pour l'index d'un module (données + accès)
		 * <strong>Attention</strong> : fait un set de la variable <i>ajoutPossible</i>
		 * 
		 * @param type $parent_id - personne_id ou foyer_id selon le cas
		 * @param array $query - Query utilisé pour obtenir l'index
		 * @return array - Liste des enregistrements pour un index avec règles d'accès métier
		 * @throws Error404Exception
		 */
		public function getIndexRecords($parent_id, array $query = array()) {
			if (!self::_validId($parent_id)) {
				throw new Error404Exception();
			}
			
			$queryCompleted = $this->WebrsaModel->completeVirtualFieldsForAccess($query);
			$paramsActions = call_user_func(array($this->WebrsaAccessClassName, 'getParamsList'));
			$paramsAccess = $this->WebrsaModel->getParamsForAccess($parent_id, $paramsActions);
			$this->Controller->set('ajoutPossible', Hash::get($paramsAccess, 'ajoutPossible') !== false);

			return call_user_func(
				array($this->WebrsaAccessClassName, 'accesses'),
				$this->MainModel->find('all', $queryCompleted),
				$paramsAccess
			);
		}
		
		/**
		 * Vérifi qu'un ID est un entier positif de base 10
		 * 
		 * @param integer $id
		 * @return boolean
		 */
		protected static function _validId($id) {
			return is_numeric($id) && (integer)$id > 0 && preg_match('/^[\d]+$/', (string)$id);
		}
		
		/**
		 * Appel de la fonction check sur l'utilitaire de logique d'accès métier lié au Controller
		 * 
		 * @param array $record
		 * @param array $paramsAccess
		 * @return boolean
		 */
		protected function _haveAccess(array $record, array $paramsAccess) {
			return call_user_func(
				array($this->WebrsaAccessClassName, 'check'), 
				$this->Controller->name, 
				$this->Controller->action, 
				$record, 
				$paramsAccess
			);
		}
		
		/**
		 * Permet d'obtenir l'enregistrement lié à l'id donné
		 * 
		 * @param integer $id
		 * @return array
		 */
		protected function _getRecord($id) {
			$record = array();
			if ($id !== null) {
				$records = $this->WebrsaModel->getDataForAccess(
					array($this->MainModel->alias.'.'.$this->Controller->{$this->MainModel->alias}->primaryKey => $id),
					array('controller' => $this->Controller->name, 'action' => $this->Controller->action)
				);
				$record = end($records);
			}
			
			return (array)$record;
		}
		
		/**
		 * Permet d'obtenir un id à partir de différentes sources
		 * 
		 * @param integer $id
		 * @param array $record
		 * @param integer $parent_id - Le plus souvent : personne_id ou foyer_id
		 * @return integer
		 */
		protected function _parentId($id, array $record, $parent_id = null) {
			if ($parent_id !== null) {
				$result = $parent_id;
			} else {
				$isLinkedToParent = property_exists($this->MainModel, 'belongsTo') 
					&& isset($this->MainModel->belongsTo[$this->parentModelName]);
				$foreignKey = $isLinkedToParent 
					? $this->MainModel->belongsTo[$this->parentModelName]['foreignKey'] 
					: Inflector::underscore($this->parentModelName).'_id'
				;
				$parentPrimarykey = $isLinkedToParent
					? $this->MainModel->{$this->parentModelName}->primaryKey
					: 'id'
				;
				$methodName = Inflector::underscore($this->parentModelName).'Id';

				$result = Hash::get($record, $this->MainModel->alias.'.'.$foreignKey)
					?: Hash::get($record, $this->parentModelName.'.'.$parentPrimarykey
				);

				if ($result === null) {
					if ($this->MainModel->Behaviors->attached('Allocatairelie') || method_exists($this->MainModel, $methodName)) {
						$result = $this->MainModel->{$methodName}($id);
					} else {
						trigger_error(sprintf("Field: %s.%s n'existe pas dans %s::getDataForAccess",
							$this->parentModelName, $parentPrimarykey, $this->WebrsaModel->name));
						exit;
					}
				}
			}
			
			return $result;
		}
		
		/**
		 * Renvoi une chaine en Camelcase pluriel en Camelcase singulier
		 * 
		 * @param String $controllerName
		 * @return String
		 */
		protected static function controllerNameToModelName($controllerName) {
			return Inflector::camelize(Inflector::singularize(Inflector::underscore($controllerName)));
		}
	}
?>