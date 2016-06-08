<?php
	/**
	 * Code source de la classe WebrsaPersonne.
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
	 * La classe WebrsaPersonne possède la logique métier web-rsa
	 * 
	 * @package app.Model
	 */
	class WebrsaPersonne extends WebrsaAbstractLogic implements WebrsaLogicAccessInterface
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaPersonne';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Personne');

		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array(), array $params = array()) {
			$fields = array('Personne.nom');
			return Hash::merge($query, array('fields' => array_values($fields)));
		}
		
		/**
		 * Permet d'obtenir le nécéssaire pour calculer les droits d'accès métier à une action
		 * 
		 * @param array $conditions
		 * @return array
		 */
		public function getDataForAccess(array $conditions, array $params = array()) {
			$query = array(
				'fields' => array(
					'Foyer.id',
					'Personne.id',
				),
				'conditions' => $conditions,
				'joins' => array(
					$this->Personne->Foyer->join('Personne'),
					$this->Personne->join('Prestation'),
				),
				'contain' => false,
				'order' => array(
					'(CASE WHEN "Prestation"."rolepers" = \'DEM\' THEN 3 '
					. 'WHEN "Prestation"."rolepers" = \'CJT\' THEN 2 '
					. 'WHEN "Prestation"."rolepers" = \'ENF\' THEN 1 '
					. 'ELSE 0 END)' => 'DESC',
					'Personne.nom' => 'ASC',
					'Personne.prenom' => 'ASC',
				)
			);
			
			$results = $this->Personne->Foyer->find('all', $this->completeVirtualFieldsForAccess($query));
			return $results;
		}
		
		/**
		 * Permet d'obtenir les paramètres à envoyer à WebrsaAccess pour une personne en particulier
		 * 
		 * @see WebrsaAccess::getParamsList
		 * @param integer $foyer_id
		 * @param array $params - Liste des paramètres actifs
		 */
		public function getParamsForAccess($foyer_id, array $params = array()) {
			$results = array();
			
			if (in_array('ajoutPossible', $params)) {
				$results['ajoutPossible'] = $this->ajoutPossible($foyer_id);
			}
			
			return $results;
		}
		
		/**
		 * Permet de savoir si il est possible d'ajouter un enregistrement
		 * 
		 * @param integer $foyer_id
		 * @return boolean
		 */
		public function ajoutPossible($foyer_id) {
			return true;
		}
	}