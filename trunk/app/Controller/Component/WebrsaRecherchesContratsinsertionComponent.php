<?php
	/**
	 * Code source de la classe WebrsaRecherchesContratsinsertionComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesContratsinsertionComponent ...
	 *
	 * @deprecated since 3.0.00
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesContratsinsertionComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$departement = (int)Configure::read( 'Cg.departement' );

			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Option' );

			$options['Contratinsertion']['structurereferente_id'] = $Controller->Contratinsertion->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Contratinsertion']['referent_id'] = $Controller->Contratinsertion->Structurereferente->Referent->listOptions();
			$options['Contratinsertion']['decision_ci'] = $Controller->Option->decision_ci();
			$options['Contratinsertion']['forme_ci'] = $Controller->Option->forme_ci();
			$options['Contratinsertion']['duree_engag'] = $Controller->Option->duree_engag();

			$options['Orientstruct']['typeorient_id'] = $Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ), 'empty' => ( $departement !== 58 ) ) );

			$options['Personne']['trancheage'] = array(
				'0_24' => '- 25 ans',
				'25_34' => '25 - 34 ans',
				'35_44' => '35 - 44 ans',
				'45_54' => '45 - 54 ans',
				'55_999' => '+ 55 ans'
			);

			if( $departement === 58 ) {
				$options['Personne']['etat_dossier_orientation'] = $Controller->Contratinsertion->Personne->enum( 'etat_dossier_orientation' );
			}
			else if( $departement === 66 ) {
				$options['Orientstruct']['not_typeorient_id'] = $Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O', 'Typeorient.parentid IS NULL' ) ) );
			}
			else if( $departement === 93 ) {
				$Controller->loadModel( 'Catalogueromev3' );

				$options = Hash::merge(
					$options,
					$Controller->Contratinsertion->Cer93->enums(),
					$Controller->Contratinsertion->Cer93->options( array( 'autre' => true, 'find' => true ) ),
					$Controller->Catalogueromev3->dependantSelects()
				);
				$options['Expprocer93']['metierexerce_id'] = $Controller->Contratinsertion->Cer93->Expprocer93->Metierexerce->find( 'list' );
				$options['Expprocer93']['secteuracti_id'] = $Controller->Contratinsertion->Cer93->Expprocer93->Secteuracti->find( 'list' );
			}

			return $options;
		}
	}
?>