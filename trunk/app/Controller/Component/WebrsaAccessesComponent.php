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
		 * @return void
		 */
		public function initialize(Controller $controller) {
			$MainModelName = self::controllerNameToModelName($controller->name);
			$WebrsaModelClassName = 'Webrsa'.$MainModelName;
			$WebrsaAccessClassName = 'WebrsaAccess'.$controller->name;
			
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
		 * Assure l'initialisation du component
		 * 
		 * @return void
		 */
		public function init() {
			return $this->_initialized ?: $this->initialize($this->_Collection->getController());
		}

		/**
		 * Fait appel à WebrsaAccess<i>Nomducontroller</i> pour vérifier les droits 
		 * d'accès à une action en fonction d'un enregistrement
		 * 
		 * @param integer $id			- Id de l'enregistrement si il existe
		 *								  Sera envoyé à Webrsa<i>Nomdumodel</i>::getDataForAccess
		 * 
		 * @param integer $personne_id	- Id de la personne si disponnible (nécéssaire si $id = null)
		 *								  Sera envoyé à Webrsa<i>Nomdumodel</i>::getParamsForAccess
		 * 
		 * @param String $alias			- Par défaut, Nom du controller au singulier
		 * 
		 * @return void
		 * @throws Error403Exception
		 * @throws Error404Exception
		 */
		public function check($id = null, $personne_id = null, $alias = null) {
			if (($id !== null && !self::_validId($id)) || ($personne_id !== null && !self::_validId($personne_id))) {
				throw new Error404Exception();
			}
			
			$this->init();
			$this->alias = $alias ?: $this->MainModel->alias;
			
			$record = $this->_getRecord($id);
			$actionsParams = call_user_func(array($this->WebrsaAccessClassName, 'getActionParamsList'), $this->Controller->action);
			$paramsAccess = $this->WebrsaModel->getParamsForAccess(
				$this->_personneId($id, $record, $personne_id), $actionsParams
			);
			
			if ($this->_haveAccess($record, $paramsAccess) === false) {
				throw new Error403Exception(
					__("Exception::access_denied", __CLASS__, __FUNCTION__, $this->Controller->Session->read('Auth.User.username'))
				);
			}
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
					array($this->alias.'.'.$this->Controller->{$this->alias}->primaryKey => $id)
				);
				$record = end($records);
			}
			
			return $record;
		}
		
		/**
		 * Permet d'obtenir un personne_id à partir de différentes sources
		 * 
		 * @param integer $id
		 * @param array $record
		 * @param integer $personne_id
		 * @return integer
		 */
		protected function _personneId($id, array $record, $personne_id = null) {
			$result = $personne_id ?: (Hash::get($record, $this->alias.'.personne_id') ?: Hash::get($record, 'Personne.id'));
			
			if ($result === null) {
				if ($this->MainModel->Behaviors->attached('Allocatairelie') || method_exists($this->MainModel, 'personneId')) {
					$result = $this->MainModel->personneId($id);
				} else {
					trigger_error(sprintf("Field: Personne.id n'existe pas dans %s::getDataForAccess", $this->WebrsaModel->name));
					exit;
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