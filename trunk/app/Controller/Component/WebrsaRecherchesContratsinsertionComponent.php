<?php
	/**
	 * Code source de la classe WebrsaRecherchesContratsinsertionComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesContratsinsertionComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesContratsinsertionComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model 
		 */
		public $Contratinsertion;
		
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
			
			$this->Contratinsertion = ClassRegistry::init( 'Contratinsertion'.Configure::read( 'Contratinsertion.suffixe' ) );
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
			$cgDepartement = Configure::read( 'Cg.departement' );
			
			$options['Contratinsertion']['structurereferente_id'] = $this->Contratinsertion->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Contratinsertion']['referent_id'] = $this->Contratinsertion->Structurereferente->Referent->listOptions();
			$options['Contratinsertion']['decision_ci'] = $this->Option->decision_ci();
			$options['Contratinsertion']['forme_ci'] = $this->Option->forme_ci();
			$options['Contratinsertion']['duree_engag'] = $this->Option->duree_engag();
			$options['Orientstruct']['typeorient_id'] = $this->Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O', 'Typeorient.parentid IS NOT NULL' ) ) );
			
			return $options;
		}
	}
?>