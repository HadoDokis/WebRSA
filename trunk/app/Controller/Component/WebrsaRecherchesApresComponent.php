<?php
	/**
	 * Code source de la classe WebrsaRecherchesApresComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesApresComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesApresComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model 
		 */
		public $Apre;
		
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
			
			$this->Apre = ClassRegistry::init( 'Apre'.Configure::read( 'Apre.suffixe' ) );
			$this->Controller = $this->_Collection->getController();
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
			
			$options['Apre']['structurereferente_id'] = $this->Apre->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Apre']['referent_id'] = $this->Apre->Structurereferente->Referent->listOptions();
			
			if ( isset($this->Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}) ) {
				$enumsAideapre = $this->Apre->{'Aideapre'.$cgDepartement}->enums();
				$options['Aideapre'.$cgDepartement] = $enumsAideapre['Aideapre'.$cgDepartement];
				$options['Aideapre'.$cgDepartement]['themeapre'.$cgDepartement.'_id'] = $this->Apre->{'Aideapre'.$cgDepartement}->{'Themeapre'.$cgDepartement}->find('list');
			}
			if ( isset($this->Apre->{'Aideapre'.$cgDepartement}->{'Typeaideapre'.$cgDepartement}) ) {
				$options['Aideapre'.$cgDepartement]['typeaideapre'.$cgDepartement.'_id'] = $this->Apre->{'Aideapre'.$cgDepartement}->{'Typeaideapre'.$cgDepartement}->listOptions();
			}
			
			return $options;
		}
	}
?>