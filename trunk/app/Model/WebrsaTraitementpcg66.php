<?php
	/**
	 * Code source de la classe WebrsaTraitementpcg66.
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
	 * La classe WebrsaTraitementpcg66 possède la logique métier web-rsa
	 *
	 * @todo WebrsaLogicTraitementpcg66 ?
	 *
	 * @package app.Model
	 */
	class WebrsaTraitementpcg66 extends WebrsaAbstractLogic implements WebrsaLogicAccessInterface
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaTraitementpcg66';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Traitementpcg66');

		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array()) {
			$fields = array(
				'Traitementpcg66.annule',
				'Traitementpcg66.typetraitement',
				'Traitementpcg66.dateenvoicourrier',
				'Traitementpcg66.reversedo',
			);
			
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
					'Traitementpcg66.id',
					'Traitementpcg66.personnepcg66_id',
				),
				'conditions' => $conditions,
				'joins' => array(
					$this->Traitementpcg66->join('Personnepcg66')
				),
				'contain' => false,
				'order' => array(
					'Traitementpcg66.created' => 'DESC',
					'Traitementpcg66.id' => 'DESC',
				)
			);
			
			$results = $this->Traitementpcg66->find('all', $this->completeVirtualFieldsForAccess($query));
			return $results;
		}
		
		/**
		 * Permet d'obtenir les paramètres à envoyer à WebrsaAccess pour une personne en particulier
		 * 
		 * @see WebrsaAccess::getParamsList
		 * @param integer $dossierpcg66_id
		 * @param array $params - Liste des paramètres actifs
		 */
		public function getParamsForAccess($dossierpcg66_id, array $params = array()) {
			$results = array();
			
			if (in_array('ajoutPossible', $params)) {
				$results['ajoutPossible'] = $this->ajoutPossible($dossierpcg66_id);
			}
			
			return $results;
		}
		
		/**
		 * Permet de savoir si il est possible d'ajouter un enregistrement
		 * 
		 * @param integer $dossierpcg66_id
		 * @return boolean
		 */
		public function ajoutPossible($dossierpcg66_id) {
			return true;
		}
	}