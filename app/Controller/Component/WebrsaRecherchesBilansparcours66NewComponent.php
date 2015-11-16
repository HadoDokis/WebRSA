<?php
	/**
	 * Code source de la classe WebrsaRecherchesBilansparcours66NewComponent.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesBilansparcours66NewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesBilansparcours66NewComponent extends WebrsaAbstractRecherchesNewComponent
	{
		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Gestionzonesgeos'
		);
		
		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * @return array
		 */
		protected function _optionsEnums( array $params = array() ) {
			$options = parent::_optionsEnums( $params );
			$options['Dossierep']['themeep'] = array(
				'saisinesbilansparcourseps66' => 'Oui',
				'defautsinsertionseps66' => 'Oui',
			);
			$options['Bilanparcours66']['hasmanifestation'] = array('Non', 'Oui');

			return $options;
		}

		/**
		 * Retourne les options stockées liées à des enregistrements en base de
		 * données, ne dépendant pas de l'utilisateur connecté.
		 *
		 * @return array
		 */
		protected function _optionsRecords( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			
			if( !isset( $Controller->Bilanparcours66 ) ) {
				$Controller->loadModel( 'Bilanparcours66' );
			}
			
			$options = parent::_optionsRecords($params);
			$options['Bilanparcours66']['structurereferente_id'] = $Controller->Bilanparcours66->Structurereferente->listOptions( array( 'orientation' => 'O' ) );
			$options['Bilanparcours66']['referent_id'] = $Controller->Bilanparcours66->Structurereferente->Referent->listOptions();
			
			return $options;
		}

		/**
		 * Retourne les noms des modèles dont des enregistrements seront mis en
		 * cache après l'appel à la méthode _optionsRecords() afin que la clé de
		 * cache générée par la méthode _options() se trouve associée dans
		 * ModelCache.
		 *
		 * @see _optionsRecords(), _options()
		 *
		 * @return array
		 */
		protected function _optionsRecordsModels( array $params ) {
			$result = array_merge(
				parent::_optionsRecordsModels( $params ),
				array(
					'Structurereferente',
					'Referent',
				)
			);

			return $result;
		}
	}
?>
