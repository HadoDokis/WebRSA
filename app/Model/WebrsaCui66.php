<?php
	/**
	 * Code source de la classe WebrsaCui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaCui', 'Model');
	App::uses('WebrsaModelUtility', 'Utility');

	/**
	 * La classe WebrsaCui66 possède la logique métier web-rsa
	 *
	 * @package app.Model
	 */
	class WebrsaCui66 extends WebrsaCui
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaCui66';
		
		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array(), array $params = array()) {
			$query = parent::completeVirtualFieldsForAccess($query);
			
			if (!isset($query['joins'])) {
				$query['joins'] = array();
			}
			
			$modelName = Inflector::camelize(Inflector::singularize(Inflector::underscore($params['controller'])));
			if ($modelName !== 'Cui66') {
				$query = WebrsaModelUtility::addJoins($this->Cui->Cui66, $modelName, $query);
			}
			
			return $query;
		}
		
		/**
		 * Permet de savoir si il est possible d'ajouter un enregistrement
		 * 
		 * @param integer $cui_id
		 * @param array $params
		 * @return boolean
		 */
		public function ajoutPossible($cui_id, array $params = array()) {
			$ajoutPossible = true;
			if ($ajoutPossible && in_array('isModuleDecision', $params)) {
				$query = array(
					'fields' => 'Decisioncui66.id',
					'joins' => array(
						$this->Cui->join('Cui66', array('type' => 'INNER')),
						$this->Cui->Cui66->join('Decisioncui66', array('type' => 'INNER'))
					),
					'conditions' => array('Cui.id' => $cui_id)
				);
				$exist = $this->Cui->find('first', $query);
				$ajoutPossible = empty($exist);
			}
			if ($ajoutPossible && in_array('isModuleRupture', $params)) {
				$query = array(
					'fields' => 'Rupturecui66.id',
					'joins' => array(
						$this->Cui->join('Cui66', array('type' => 'INNER')),
						$this->Cui->Cui66->join('Rupturecui66', array('type' => 'INNER'))
					),
					'conditions' => array('Cui.id' => $cui_id)
				);
				$exist = $this->Cui->find('first', $query);
				$ajoutPossible = empty($exist);
			}
			return $ajoutPossible;
		}
	}