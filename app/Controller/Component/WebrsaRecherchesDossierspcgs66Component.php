<?php
	/**
	 * Code source de la classe WebrsaRecherchesDossierspcgs66Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesDossierspcgs66Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesDossierspcgs66Component extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model 
		 */
		public $Dossierpcg66;
		
		/**
		 * Controller executant le component
		 * @var Controller 
		 */
		public $Controller;
		
		/**
		 * Contructeur de class, assigne le controller et le modele principal
		 * 
		 * @param \ComponentCollection $collection
		 * @param array $settings
		 */
		public function __construct(\ComponentCollection $collection, $settings = array()) {
			parent::__construct($collection, $settings);
			
			$this->Dossierpcg66 = ClassRegistry::init( 'Dossierpcg66' );
			$this->Controller = $this->_Collection->getController();
			$this->Option = ClassRegistry::init('Option');
		}
		
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			
			$catalogueromev3 = ClassRegistry::init( 'Catalogueromev3' )->dependantSelects();
			$options['Categorieromev3'] = $catalogueromev3['Catalogueromev3'];
			$options['Dossierpcg66']['originepdo_id'] = $this->Dossierpcg66->Originepdo->find('list');
			$options['Dossierpcg66']['typepdo_id'] = $this->Dossierpcg66->Typepdo->find('list');
			$options['Dossierpcg66']['poledossierpcg66_id'] = $this->Dossierpcg66->User->Poledossierpcg66->find(
				'list', 
				array(
                    'conditions' => array('Poledossierpcg66.isactif' => '1'),
                    'order' => array('Poledossierpcg66.name ASC', 'Poledossierpcg66.id ASC')
				)
			);
			$options['Dossierpcg66']['user_id'] = $this->Dossierpcg66->User->find(
				'list', 
				array(
                    'fields' => array('User.nom_complet'),
                    'conditions' => array('User.isgestionnaire' => 'O'),
                    'order' => array('User.nom ASC', 'User.prenom ASC')
				)
			);
			$options['Decisiondossierpcg66']['org_id'] = $this->Dossierpcg66->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
				'list', 
				array(
					'conditions' => array('Orgtransmisdossierpcg66.isactif' => '1'),
					'order' => array('Orgtransmisdossierpcg66.name ASC')
				)
			);
			$options['Traitementpcg66']['situationpdo_id'] = $this->Dossierpcg66->Personnepcg66->Situationpdo->find(
				'list', 
				array(
					'order' => array('Situationpdo.libelle ASC'), 
					'conditions' => array('Situationpdo.isactif' => '1')
				)
			);
			$options['Traitementpcg66']['statutpdo_id'] = $this->Dossierpcg66->Personnepcg66->Statutpdo->find(
				'list', 
				array(
					'order' => array('Statutpdo.libelle ASC'), 
					'conditions' => array('Statutpdo.isactif' => '1')
				)
			);
			$options['Decisiondossierpcg66']['decisionpdo_id'] = $this->Dossierpcg66->Decisiondossierpcg66->Decisionpdo->find(
				'list', 
				array(
					'conditions' => array('Decisionpdo.isactif' => '1')
				)
			);
			
			// FIXME N'aparait pas dans $this->Dossierpcg66->enums()
			$query = array(
				'fields' => 'Dossierpcg66.etatdossierpcg',
				'group' => 'Dossierpcg66.etatdossierpcg'
			);
			foreach($this->Dossierpcg66->find( 'all', $query ) as $etatdossier) {
				$etat = $etatdossier['Dossierpcg66']['etatdossierpcg'];
				if ($etat !== null) {
					$options['Dossierpcg66']['etatdossierpcg'][$etat] = __d('dossierpcg66', 'ENUM::ETATDOSSIERPCG::'.$etat);
				}
			}
			
			return $options;
		}
	}
?>