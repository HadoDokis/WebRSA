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
				array(
					'Orientstruct' => array(
						'propo_algo' => $propo_algo,
						'typeorient_id' => $Controller->Personne->Orientstruct->Typeorient->listOptionsCohortes93(),
						'structurereferente_id' => $Controller->Personne->Orientstruct->Structurereferente->list1Options(),
						'statut_orient' => array( 'Orienté' => 'A valider', 'En attente' => 'En attente' )
					),
					'Suiviinstruction' => array(
						'typeserins' => $Controller->Option->typeserins()
					)
				)
			);

			// TODO: mettre dans une autre classe
			$Controller->loadModel( 'Cohorte' );
			$options['structuresAutomatiques'] = $Controller->Cohorte->structuresAutomatiques();

			// Limitation de certaines options possibles
			// TODO: factoriser dans le (modèle) Component, etc...
			// TODO: voir la classe WebrsaCohorteNonorientationprocov58::searchConditions()
			// où l'on ajoute des conditions et où l'on en restreint d'autres
			$accepted = array(
				'Detailcalculdroitrsa.natpf' => array( 'RSD', 'RSI', 'RSU', 'RSJ' ),
				'Situationdossierrsa.etatdosrsa' => array( 'Z', 2, 3, 4 )
			);
			foreach( $accepted as $path => $acceptedValues ) {
				foreach( (array)Hash::get( $options, $path ) as $value => $label ) {
					if( in_array( $value, $acceptedValues ) === false ) {
						$options = Hash::remove( $options, "{$path}.{$value}" );
					}
				}
			}

			return $options;
		}
	}
?>