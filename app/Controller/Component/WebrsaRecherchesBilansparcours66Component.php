<?php
	/**
	 * Code source de la classe WebrsaRecherchesBilansparcours66Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesBilansparcours66Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesBilansparcours66Component extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model 
		 */
		public $Bilanparcours66;
		
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
			
			$this->Bilanparcours66 = ClassRegistry::init( 'Bilanparcours66' );
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
			
			$options['Bilanparcours66']['structurereferente_id'] = $this->Bilanparcours66->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Bilanparcours66']['referent_id'] = $this->Bilanparcours66->Structurereferente->Referent->listOptions();
			$options['Dossierep']['themeep'] = array(
				'saisinesbilansparcourseps66' => 'Oui',
				'defautsinsertionseps66' => 'Oui',
			);
			$options['Bilanparcours66']['hasmanifestation'] = array('Non', 'Oui');
			
			return $options;
		}
	}
?>