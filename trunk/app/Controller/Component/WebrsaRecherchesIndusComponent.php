<?php
	/**
	 * Code source de la classe WebrsaRecherchesIndusComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesIndusComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesIndusComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$this->Option = ClassRegistry::init( 'Option' );

			$options['Infofinanciere']['natpfcre'] = $this->Option->natpfcre( 'autreannulation' );
			$options['Dossier']['typeparte'] = $this->Option->typeparte();
			$options['Infofinanciere']['compare'] = array('<' => '<','>' => '>','<=' => '<=','>=' => '>=');

			return $options;
		}
	}
?>