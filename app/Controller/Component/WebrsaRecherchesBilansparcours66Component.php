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
		 * Modèles utilisé par le component
		 * 
		 * @var array
		 */
		public $uses = array(
			'Bilanparcours66'
		);
		
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$this->Bilanparcours66 = ClassRegistry::init( 'Bilanparcours66' );
			
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