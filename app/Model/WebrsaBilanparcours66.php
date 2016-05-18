<?php
	/**
	 * Code source de la classe WebrsaBilanparcours66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaAbstractLogic', 'Model');
	App::uses('WebrsaLogicAccessInterface', 'Model/Interface');
	App::uses('WebrsaModelUtility', 'Utility');

	/**
	 * La classe WebrsaBilanparcours66 possède la logique métier web-rsa
	 *
	 * @todo WebrsaLogicBilanparcours66 ?
	 *
	 * @package app.Model
	 */
	class WebrsaBilanparcours66 extends WebrsaAbstractLogic implements WebrsaLogicAccessInterface
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaBilanparcours66';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Bilanparcours66');
		
		/**
		 * Mémorise le résultat d'une fonction en cas d'appels succéssifs de celles-ci
		 * 
		 * @var array - array(__FUNCTION__.'.'.md5(json_encode(array($param1, $param2, ...))) => $results)
		 */
		private $_mem = array();
		
		/**
		 * Permet d'obtenir la clef pour le stockage du résultat de fonction en fonction des paramètres
		 * 
		 * @param String $functionName
		 * @param mixed $params
		 * @return String
		 */
		private function _getKeyMem($functionName, $params = 'empty') {
			return $functionName.'.'.md5(json_encode($params));
		}

		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array()) {
			$fields = array(
				'positionbilan' => 'Bilanparcours66.positionbilan',
				'proposition' => 'Bilanparcours66.proposition',
				'dateimpressionconvoc' => 'Defautinsertionep66.dateimpressionconvoc',
			);
			
			if (!WebrsaModelUtility::findJoinKey('Defautinsertionep66', $query)) {
				$query['joins'][] = $this->Bilanparcours66->join('Defautinsertionep66');
			}
			
			return Hash::merge($query, array('fields' => array_values($fields)));
		}
		
		/**
		 * Permet d'obtenir le nécéssaire pour calculer les droits d'accès métier à une action
		 * 
		 * @param array $conditions
		 * @return array
		 */
		public function getDataForAccess(array $conditions) {
			$query = array(
				'fields' => array(
					'Bilanparcours66.id',
					'Bilanparcours66.personne_id',
				),
				'conditions' => $conditions,
				'joins' => array(
					$this->Bilanparcours66->join('Personne')
				),
				'contain' => false,
				'order' => array(
					'Bilanparcours66.datebilan' => 'DESC',
					'Bilanparcours66.id' => 'DESC',
				)
			);
			
			$results = $this->Bilanparcours66->find('all', $this->completeVirtualFieldsForAccess($query));
			return $results;
		}
		
		/**
		 * Permet d'obtenir les paramètres à envoyer à WebrsaAccess pour une personne en particulier
		 * 
		 * @see WebrsaAccess::getParamsList
		 * @param integer $personne_id
		 * @param array $params - Liste des paramètres actifs
		 */
		public function getParamsForAccess($personne_id, array $params = array()) {
			$results = array();
			
			if (in_array('ajoutPossible', $params)) {
				$results['ajoutPossible'] = $this->ajoutPossible($personne_id);
			}
			
			return $results;
		}
		
		/**
		 * Permet de savoir si il est possible d'ajouter un enregistrement
		 * 
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function ajoutPossible($personne_id) {
			return true;
		}
	}