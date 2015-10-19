<?php
	/**
	 * Code source de la classe WebrsaRecherchesPropospdosPossiblesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesPropospdosPossiblesComponent ...
	 *
	 * @deprecated since 3.0.00
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesPropospdosPossiblesComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * Surcharge de la méthode options() pour n'obtenir que les
		 * Situationdossierrsa.etatdosrsa en attente.
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options($params);
			$Controller = $this->_Collection->getController();

			$enAttente = $Controller->Situationdossierrsa->etatAttente();

			$Controller->loadModel( 'Situationdossierrsa' );
			foreach( $options['Situationdossierrsa']['etatdosrsa'] as $value => $label ) {
				if( !in_array( $value, $enAttente ) ) {
					unset($options['Situationdossierrsa']['etatdosrsa'][$value]);
				}
			}

			return $options;
		}
	}
?>