<?php
	/**
	 * Code source de la classe WebrsaCohortesOrientsstructsNouvellesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractCohortesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesOrientsstructsNouvellesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesOrientsstructsNouvellesComponent extends WebrsaAbstractCohortesComponent
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
			$Controller->loadModel( 'Option' );
			$departement = (int)Configure::read( 'Cg.departement' );

			// Pré-orientation
			$propo_algo = $Controller->Orientstruct->Typeorient->listOptionsPreorientationCohortes93();
			if( $departement === 93 ) {
				$propo_algo['NOTNULL'] = 'Renseigné';
				$propo_algo['NULL'] = 'Non renseigné';
			}

			$options = array_merge(
				parent::options($params),
				$Controller->Orientstruct->enums(),
				$Controller->Orientstruct->Personne->Foyer->Dossier->Suiviinstruction->enums(),
				array(
					'Orientstruct' => array(
						'propo_algo' => $propo_algo,
						'typeorient_id' => $Controller->Personne->Orientstruct->Typeorient->listOptionsCohortes93(),
						'structurereferente_id' => $Controller->Personne->Orientstruct->Structurereferente->list1Options(),
						'statut_orient' => array( 'Orienté' => 'A valider', 'En attente' => 'En attente' )
					),
					'Personne' => array(
						'has_dsp' => array(
							'1' => 'Oui',
							'0' => 'Non'
						)
					)
				)
			);

			if( !isset( $Controller->{$params['modelRechercheName']} ) ) {
				$Controller->loadModel( $params['modelRechercheName'] );
			}

			$options['structuresAutomatiques'] = $Controller->{$params['modelRechercheName']}->structuresAutomatiques();

			return $options;
		}
	}
?>