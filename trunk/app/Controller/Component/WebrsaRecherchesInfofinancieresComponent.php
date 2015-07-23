<?php
	/**
	 * Code source de la classe WebrsaRecherchesInfofinancieresComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesInfofinancieresComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesInfofinancieresComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model 
		 */
		public $Infofinanciere;
		
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
			
			$this->Infofinanciere = ClassRegistry::init( 'Infofinanciere' );
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
			
			$options['Infofinanciere']['natpfcre'] = $this->Option->natpfcre( 'autreannulation' );
			$options['Dossier']['typeparte'] = $this->Option->typeparte();
			$options['Infofinanciere']['compare'] = array('<' => '<','>' => '>','<=' => '<=','>=' => '>=');
						
			return $options;
		}
	}
?>