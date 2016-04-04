<?php
	/**
	 * Code source de la classe WebrsaRecherchesOrientsstructsComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesOrientsstructsComponent ...
	 *
	 * @deprecated since 3.0.00
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesOrientsstructsComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * Options pour le moteur de recherche
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
					'Orientstruct' => array(
						'typeorient_id' => $Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ) ) ),
						'structureorientante_id' => $Controller->Orientstruct->Structurereferente->listOptions( array( 'orientation' => 'O' ) ),
						'referentorientant_id' => $Controller->Orientstruct->Structurereferente->Referent->listOptions(),
						'structurereferente_id' => $Controller->InsertionsBeneficiaires->structuresreferentes( array( 'type' => 'list', 'conditions' => array( 'Structurereferente.orientation' => 'O' ) + $Controller->InsertionsBeneficiaires->conditions['structuresreferentes'] ) ),
						'statut_orient' => $Controller->Orientstruct->enum( 'statut_orient' )
					),
					'Personne' => array(
						'has_contratinsertion' => $exists,
						'has_personne_referent' => $exists,
						'is_inscritpe' => $exists,
					),
					'Serviceinstructeur' => array(
						'id' => $Controller->Orientstruct->Personne->Foyer->Dossier->Suiviinstruction->Serviceinstructeur->listOptions()
					)
				)
			);

			if( $departement === 58 ) {
				$Controller->loadModel( 'Option' );
				$options['Activite']['act'] = $Controller->Option->act();
			}
			else if( $departement === 93 ) {
				$options['Orientstruct']['propo_algo'] = $options['Orientstruct']['typeorient_id'];
			}

			return $options;
		}
	}
?>