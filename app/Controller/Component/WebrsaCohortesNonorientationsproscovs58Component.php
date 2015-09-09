<?php
	/**
	 * Code source de la classe WebrsaCohortesNonorientationsproscovs58Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractCohortesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesNonorientationsproscovs58Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesNonorientationsproscovs58Component extends WebrsaAbstractCohortesComponent
	{
		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Cohortes'
		);

		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$options = array_merge(
				parent::options($params),
				$Controller->Orientstruct->enums(),
				$Controller->Orientstruct->Personne->Contratinsertion->enums()
			);

			return $options;
		}
	}
?>