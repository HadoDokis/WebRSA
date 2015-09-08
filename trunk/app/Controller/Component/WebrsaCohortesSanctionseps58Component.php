<?php
	/**
	 * Code source de la classe WebrsaCohortesSanctionseps58Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractCohortesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesSanctionseps58Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesSanctionseps58Component extends WebrsaAbstractCohortesComponent
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

			if( isset( $Controller->Personne ) === false ) {
				$Controller->loadModel( 'Personne' );
			}

			if( isset( $Controller->Informationpe ) === false ) {
				$Controller->loadModel( 'Informationpe' );
			}

			$options = array_merge(
				parent::options($params),
				$Controller->Informationpe->enums(),
				$Controller->Informationpe->Historiqueetatpe->enums(),
				$Controller->Personne->Dossierep->enums(),
				$Controller->Personne->Dossierep->Sanctionep58->enums(),
				$Controller->Personne->Foyer->Dossier->Suiviinstruction->Serviceinstructeur->enums(),
				$Controller->Personne->Orientstruct->enums(),
				$Controller->Personne->Orientstruct->Referentorientant->enums(),
				$Controller->Personne->Orientstruct->Structureorientante->enums(),
				$Controller->Personne->Orientstruct->Structurereferente->enums(),
				$Controller->Personne->Orientstruct->Typeorient->enums()
			);

			return $options;
		}
	}
?>