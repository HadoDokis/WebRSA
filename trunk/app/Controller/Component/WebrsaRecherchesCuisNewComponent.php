<?php
	/**
	 * Code source de la classe WebrsaRecherchesCuisNewComponent.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesCuisNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesCuisNewComponent extends WebrsaAbstractRecherchesNewComponent
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
			$Controller = $this->_Collection->getController();
			$cgDepartement = Configure::read( 'Cg.departement' );
			$modelCuiDpt = 'Cui' . $cgDepartement;
			$options = array();
			
			if( isset( $Controller->Cui->{$modelCuiDpt} ) ) {
				$options = $Controller->Cui->{$modelCuiDpt}->enums();
				
				// Liste de modeles potentiel pour un CG donné
				$modelPotentiel = array(
					'Accompagnementcui' . $cgDepartement,
					'Decisioncui' . $cgDepartement,
					'Personnecui' . $cgDepartement,
					'Partenairecui' . $cgDepartement,
					'Propositioncui' . $cgDepartement,
					'Rupturecui' . $cgDepartement,
					'Suspensioncui' . $cgDepartement,
				);
				
				foreach ( $modelPotentiel as $modelName ){
					if ( isset( $Controller->Cui->{$modelCuiDpt}->{$modelName} ) ){
						$options = Hash::merge( $options, $Controller->Cui->{$modelCuiDpt}->{$modelName}->enums() );
					}
				}
			}
			
			$options['Adressecui']['canton'] = $this->Gestionzonesgeos->listeCantons();
			
			foreach( $Controller->Cui->beneficiairede as $key => $value ){
				$options['Cui']['beneficiairede'][] = $value;
			}

			$options = Hash::merge(
				$options,
				parent::_optionsEnums( $params ),
				$Controller->Cui->enums(),
				$Controller->Cui->Partenairecui->enums(),
				$Controller->Cui->Personnecui->enums()
			);

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
			$cgDepartement = Configure::read( 'Cg.departement' );
			$modelCuiDpt = 'Cui' . $cgDepartement;
			$typeContrat = 'Typecontratcui' . $cgDepartement;
			$libsec = 'Libsecactderact' . $cgDepartement . 'Secteur';

			$options = parent::_optionsRecords( $params );
			
			// On vérifi que les tables existent avant de charger les modeles
			$modelList = Hash::normalize(App::objects( 'model' ));
						
			if( isset($modelList[$typeContrat]) && !isset( $Controller->{$typeContrat} ) ) {
				$Controller->loadModel( $typeContrat );
			}
			
			if( isset($modelList[$libsec]) && !isset( $Controller->{$libsec} ) ) {
				$Controller->loadModel( $libsec );
			}
			
			if ( isset($modelList[$modelCuiDpt]) && isset( $Controller->Cui->{$modelCuiDpt}) ) {
				$options[$modelCuiDpt]['datebutoir_select'] = array(
					1 => __d( $modelCuiDpt, 'ENUM::DATEBUTOIR_SELECT::1' ),
					2 => __d( $modelCuiDpt, 'ENUM::DATEBUTOIR_SELECT::2' ),
					3 => __d( $modelCuiDpt, 'ENUM::DATEBUTOIR_SELECT::3' ),
				);
				
				if ( isset($modelList[$typeContrat]) && isset($Controller->{$typeContrat}) ) {
					$options[$modelCuiDpt]['typecontrat'] = $Controller->{$typeContrat}->find( 'list', 
						array( 'order' => 'name' )
					);
					$options[$modelCuiDpt]['typecontrat_actif'] = $Controller->{$typeContrat}->find( 'list', 
						array( 'conditions' => array( 'actif' => true ), 'order' => 'name' )
					);
				}
			}
			
			if ( isset($Controller->{$libsec}) && is_object($Controller->{$libsec}) ) {
				$options['Partenairecui'] = array(
					'naf' => $Controller->{$libsec}->find(
						'list',	
						array( 'contain' => false, 'order' => array( 'code' ) )
					),
				);
			}
			
			$communes = $Controller->Cui->Partenairecui->Adressecui->query('SELECT commune AS "Adressecui__commune" FROM adressescuis GROUP BY commune');
			foreach ( $communes as $value ) {
				$commune = Hash::get($value, 'Adressecui.commune');
				$options['Adressecui']['commune'][$commune] = $commune;
			}
			
			$options['Cui']['partenaire_id'] = $Controller->Cui->Partenaire->find( 'list', array( 'order' => array( 'Partenaire.libstruc' ) ) );
			
			$options = array_merge(
				$options,
				$Controller->Cui->Entreeromev3->options()
			);

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
			$cgDepartement = Configure::read( 'Cg.departement' );
			$modelCuiDpt = 'Cui' . $cgDepartement;
			$typeContrat = 'Typecontratcui' . $cgDepartement;
			$libsec = 'Libsecactderact' . $cgDepartement . 'Secteur';
			
			$result = array_merge(
				parent::_optionsRecordsModels( $params ),
				array(
					'Partenaire',
					'Adressecui',
					'Familleromev3',
					'Domaineromev3',
					'Metierromev3',
					'Appellationromev3',
					$modelCuiDpt,
					$typeContrat,
					$libsec
				)
			);

			return $result;
		}
	}
?>
