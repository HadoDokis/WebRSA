<?php
	/**
	 * Code source de la classe WebrsaRecherchesDossiersComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesDossiersComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesDossiersComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * @todo: faire la distinction search(index)/exportcsv (notamment dans Allocataires)
		 * @todo: mise en cache ?
		 * @todo: -> params static/dynamic (ex find)
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );
			$departement = (int)Configure::read( 'Cg.departement' );

			$exists = array( '1' => 'Oui', '0' => 'Non' );

			$options = Hash::merge(
				parent::options( $params ),
				array(
					'Dsp' => array(
						'natlog' => $Controller->Dossier->Foyer->Personne->Dsp->enum( 'natlog', true )
					),
					'Personne' => array(
						'has_contratinsertion' => $exists,
						'has_cui' => $exists,
						'has_dsp' => $exists,
						'has_orientstruct' => $exists,
						'trancheage' => array(
							'0_24' => '< 25',
							'25_30' => '25 - 30',
							'31_55' => '31 - 55',
							'56_65' => '56 - 65',
							'66_999' => '> 65',
						),
					),
					'Prestation' => array(
						'exists' => array(
							'0' => 'Sans prestation',
							'1' => 'Demandeur ou Conjoint du RSA'
						)
					)
				)
			);

			if( $departement === 58 ) {
				$options['Activite']['act'] = $Controller->Option->act();
				$options['Personne']['etat_dossier_orientation'] = $Controller->Dossier->Foyer->Personne->enum( 'etat_dossier_orientation' );
			}

			if( in_array( $Controller->action, array( 'index', 'search' ) ) ) {
				$options['Serviceinstructeur']['id'] = $Controller->Dossier->Suiviinstruction->Serviceinstructeur->listOptions();

				if( $departement === 58 ) {
					$options['Propoorientationcov58']['referentorientant_id'] = $Controller->Dossier->Foyer->Personne->PersonneReferent->Referent->find( 'list', array( 'order' => array( 'Referent.nom' ) ) );
				}
			}

			return $options;
		}
	}
?>