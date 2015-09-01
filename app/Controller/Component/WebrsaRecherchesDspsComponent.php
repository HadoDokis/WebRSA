<?php
	/**
	 * Code source de la classe WebrsaRecherchesDspsComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesDspsComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesDspsComponent extends WebrsaAbstractRecherchesComponent
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
			$params = $this->_params( $params );

			return Hash::merge(
				parent::options( $params ),
				$Controller->Dsp->options( array( 'alias' => 'Donnees', 'allocataire' => false ) )
			);
		}
	}
?>