<?php
	/**
	 * Code source de la classe WebrsaRecherchesRendezvousComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesRendezvousComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesRendezvousComponent extends WebrsaAbstractRecherchesComponent
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
			$Controller->loadModel( 'Option' );
			$params = $this->_params( $params );

			$options = Hash::merge(
				parent::options( $params ),
				array(
					'Personne' => array(
						'trancheage' => array(
							'0_24' => '< 25',
							'25_30' => '25 - 30',
							'31_55' => '31 - 55',
							'56_65' => '56 - 65',
							'66_999' => '> 65',
						),
					),
					// TODO
					'Prestation' => array(
						'exists' => array(
							'0' => 'Sans prestation',
							'1' => 'Demandeur ou Conjoint du RSA'
						)
					),
					'Structurereferente' => array(
						'type_voie' => $Controller->Option->typevoie()
					)
				)
			);

			// Pas dans l'export CSV
			if( in_array( $Controller->action, array( 'index', 'search' ) ) ) {
				$options['Serviceinstructeur']['id'] = $Controller->Rendezvous->Personne->Orientstruct->Serviceinstructeur->listOptions();

				$options['Rendezvous']['statutrdv_id'] = $Controller->Rendezvous->Statutrdv->find( 'list' );
				$options['Rendezvous']['typerdv_id'] = $Controller->Rendezvous->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
				$options['Rendezvous']['permanence_id'] = $Controller->Rendezvous->Permanence->find( 'list' );

				if( Configure::read( 'Rendezvous.useThematique' ) ) {
					$options['Rendezvous']['thematiquerdv_id'] = $Controller->Rendezvous->Thematiquerdv->find( 'list', array( 'fields' => array( 'Thematiquerdv.id', 'Thematiquerdv.name', 'Thematiquerdv.typerdv_id' ) ) );
				}
			}

			return $options;
		}
	}
?>