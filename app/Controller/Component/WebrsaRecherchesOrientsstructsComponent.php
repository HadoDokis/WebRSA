<?php
	/**
	 * Code source de la classe WebrsaRecherchesOrientsstructsComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesOrientsstructsComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesOrientsstructsComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->params( $params );
			$departement = (int)Configure::read( 'Cg.departement' );

			$exists = array( '1' => 'Oui', '0' => 'Non' );

			$options = Hash::merge(
				parent::options( $params ),
				array(
					'Orientstruct' => array(
						'typeorient_id' => $Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ) ) ),
						'structureorientante_id' => $Controller->Orientstruct->Structurereferente->listOptions( array( 'orientation' => 'O' ) ),
						'referentorientant_id' => $Controller->Orientstruct->Structurereferente->Referent->listOptions(),
						'structurereferente_id' => $Controller->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => false, 'conditions' => array( 'orientation' => 'O' ) ) ),
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
				$options['Activite']['act'] = $Controller->Option->act();
			}

			return $options;
		}
	}
?>