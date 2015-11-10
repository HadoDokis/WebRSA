<?php
	/**
	 * Code source de la classe WebrsaRecherchesActionscandidatsPersonnesNewComponent.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesActionscandidatsPersonnesNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesActionscandidatsPersonnesNewComponent extends WebrsaAbstractRecherchesNewComponent
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
		 * Retourne les options stockées liées à des enregistrements en base de
		 * données, ne dépendant pas de l'utilisateur connecté.
		 *
		 * @return array
		 */
		protected function _optionsRecords( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			if( !isset( $Controller->{'ActioncandidatPersonne'} ) ) {
				$Controller->loadModel( 'ActioncandidatPersonne' );
			}
			
			$options = parent::_optionsRecords( $params );
			
			$options['ActioncandidatPersonne']['referent_id'] = $Controller->ActioncandidatPersonne->Referent->find( 'list', array( 'recursive' => -1, 'order' => array( 'nom', 'prenom' ) ) );
			$options['Contactpartenaire']['partenaire_id'] = $Controller->ActioncandidatPersonne->Actioncandidat->Partenaire->find( 'list', array( 'fields' => array( 'libstruc' ), 'order' => array( 'Partenaire.libstruc ASC' ) ) );
			$options['ActioncandidatPersonne']['actioncandidat_id'] = $Controller->ActioncandidatPersonne->Actioncandidat->listActionParPartenaire();

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
					'Referent',
					'Partenaire',
					'Actioncandidat',
				)
			);

			return $result;
		}
	}
?>
