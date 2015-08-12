<?php
	/**
	 * Code source de la classe WebrsaRecherchesOrientsstructsComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesOrientsstructsComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesOrientsstructsComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model
		 */
		public $Orientstruct;

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

			$this->Orientstruct = ClassRegistry::init( 'Orientstruct' );
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

			$options['Orientstruct']['typeorient_id'] = $this->Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ) ) );
			$options['Orientstruct']['structureorientante_id'] = $this->Orientstruct->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Orientstruct']['referentorientant_id'] = $this->Orientstruct->Structurereferente->Referent->listOptions();
			$options['Orientstruct']['structurereferente_id'] = $this->Controller->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => false, 'conditions' => array( 'orientation' => 'O' ) ) );
			$options['Orientstruct']['statut_orient'] = $this->Orientstruct->enum( 'statut_orient' );
			// $options['Orientstruct']['serviceinstructeur_id'] = $this->Orientstruct->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) ); // Inutile ???

			return $options;
		}
	}
?>