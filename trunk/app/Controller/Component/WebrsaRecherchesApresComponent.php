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
		 * Contructeur de class, assigne le controller et le modele principal
		 *
		 * @param \ComponentCollection $collection
		 * @param array $settings
		 */
		public function __construct(\ComponentCollection $collection, $settings = array()) {
			parent::__construct($collection, $settings);

			$this->Apre = ClassRegistry::init( 'Apre'.Configure::read( 'Apre.suffixe' ) );
		}

		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$departement = (int)Configure::read( 'Cg.departement' );
			$Controller = $this->_Collection->getController();

			$options['Apre']['structurereferente_id'] = $this->Apre->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Apre']['referent_id'] = $this->Apre->Structurereferente->Referent->listOptions();

			if( $departement === 66 ) {
				$options = Hash::merge(
					$this->Apre->Aideapre66->enums(),
					array(
						'Aideapre66' => array(
							'themeapre66_id' => $this->Apre->Aideapre66->Themeapre66->find('list'),
							'typeaideapre66_id' => $this->Apre->Aideapre66->Typeaideapre66->listOptions()
						)
					)
				);
			}
			else if( $departement === 93 ) {
				$Controller->loadModel( 'Option' );
				$options['Apre']['natureaide'] = $Controller->Option->natureAidesApres();

				$Controller->loadModel( 'Tiersprestataireapre' );
				$options['Tiersprestataireapre']['id'] = $Controller->Tiersprestataireapre->find( 'list' );
			}

			return $options;
		}
	}
?>