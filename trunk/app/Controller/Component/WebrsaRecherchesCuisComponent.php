<?php
	/**
	 * Code source de la classe WebrsaRecherchesCuisComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesCuisComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesCuisComponent extends WebrsaRecherchesComponent
	{
		/**
		 * Modèles utilisé par le component
		 * 
		 * @var array
		 */
		public $uses = array(
			'Cui'
		);
		
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$params = $this->params( $params );
			$this->Cui = ClassRegistry::init( 'Cui' );

			$options = $this->Cui->Emailcui->options();
			
			$cgDepartement = Configure::read( 'Cg.departement' );
			$modelCuiDpt = 'Cui' . $cgDepartement;
			if( isset( $this->Cui->{$modelCuiDpt} ) ) {
				$options = Hash::merge( $options, $this->Cui->{$modelCuiDpt}->options() );
				$optionCui = $options['Cui'];
				
				// Liste de modeles potentiel pour un CG donné
				$modelPotentiel = array(
					'Accompagnementcui' . $cgDepartement,
					'Decisioncui' . $cgDepartement,
					'Propositioncui' . $cgDepartement,
					'Rupturecui' . $cgDepartement,
					'Suspensioncui' . $cgDepartement,
				);
				
				foreach ( $modelPotentiel as $modelName ){
					if ( isset( $this->Cui->{$modelCuiDpt}->{$modelName} ) ){
						$options = Hash::merge( $options, $this->Cui->{$modelCuiDpt}->{$modelName}->enums() );
					}
				}
			}
			
			unset( $options['Situationdossierrsa'], $options['Personne'], $options['Calculdroitrsa'], $options['Cui'] );
			
			$result = Hash::merge( $options, parent::options( $params ) );
			
			$result['Cui'] = $optionCui;
			
			return $result;
		}
	}
?>