<?php
	/**
	 * Code source de la classe WebrsaRecherchesEntretiensComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesEntretiensComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesEntretiensComponent extends WebrsaRecherchesComponent
	{
		/**
		 * @todo: faire la distinction search(index)/exportcsv (notamment dans Allocataires)
		 * @todo: mise en cache ?
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );

			return parent::options( $params );
		}
	}
?>