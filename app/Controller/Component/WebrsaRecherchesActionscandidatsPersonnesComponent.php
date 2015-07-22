<?php
	/**
	 * Code source de la classe WebrsaRecherchesActionscandidatsPersonnesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesActionscandidatsPersonnesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesActionscandidatsPersonnesComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Modele principal
		 * @var Model 
		 */
		public $ActioncandidatPersonne;
		
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
			
			$this->ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );
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
			
			$options['ActioncandidatPersonne']['referent_id'] = $this->ActioncandidatPersonne->Referent->find( 'list', array( 'recursive' => -1, 'order' => array( 'nom', 'prenom' ) ) );
			$options['Contactpartenaire']['partenaire_id'] = $this->ActioncandidatPersonne->Actioncandidat->Partenaire->find( 'list', array( 'fields' => array( 'libstruc' ), 'order' => array( 'Partenaire.libstruc ASC' ) ) );
			$options['ActioncandidatPersonne']['actioncandidat_id'] = $this->ActioncandidatPersonne->Actioncandidat->listActionParPartenaire();
			
			return $options;
		}
	}
?>