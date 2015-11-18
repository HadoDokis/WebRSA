<?php
	/**
	 * Code source de la classe WebrsaCohortesTagsNewComponent.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Component.php.
	 */
	App::uses( 'WebrsaAbstractCohortesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesTagsNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesTagsNewComponent extends WebrsaAbstractCohortesNewComponent
	{
		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * La mise en cache se fera dans ma méthode _options().
		 *
		 * @return array
		 */
		protected function _optionsEnums( array $params ) {
			$Controller = $this->_Collection->getController();
			
			$options = parent::_optionsEnums( $params );
			$options['Personne']['trancheage'] = array(
				'0_24' => '< 25',
				'25_30' => '25 - 30',
				'31_55' => '31 - 55',
				'56_65' => '56 - 65',
				'66_999' => '> 65',
			);
			
			$options['Foyer']['composition'] = array(
				'cpl_sans_enf' => 'Couple sans enfant',
				'cpl_avec_enf' => 'Couple avec enfant(s)',
				'iso_sans_enf' => 'Isolé sans enfant',
				'iso_avec_enf' => 'Isolé avec enfant(s)'
			);
			
			$options['Adresse']['heberge'] = array(
				0 => 'Non',
				1 => 'Oui',
			);
			
			$options = array_merge(
				$options,
				$Controller->Tag->Personne->Prestation->enums()
			);
			
			$accepted = array( 'DEM', 'CJT' );
			foreach( array_keys($options['Prestation']['rolepers']) as $value ) {
				if( !in_array( $value, $accepted ) ) {
					unset( $options['Prestation']['rolepers'][$value] );
				}
			}
			
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
			
			if( !isset( $Controller->Tag ) ) {
				$Controller->loadModel( 'Tag' );
			}
			
			if( !isset( $Controller->Zonegeographique ) ) {
				$Controller->loadModel( 'Zonegeographique' );
			}
			
			if( !isset( $Controller->Requestmanager ) ) {
				$Controller->loadModel( 'Requestmanager' );
			}
			
			$options = parent::_optionsRecords($params);
			
			$modeles = $Controller->Tag->find('list', array('fields' => 'modele', 'order' => 'modele'));
			foreach ( $modeles as $value ) {
				$options['Tag']['modele'][$value] = $value;
			}
			
			// Valeur tag / catégorie
			$results = $Controller->Tag->Valeurtag->find('all', array(
				'fields' => array(
					'Categorietag.name',
					'Valeurtag.id',
					'Valeurtag.name'
				),
				'joins' => array(
					$Controller->Tag->Valeurtag->join('Categorietag')
				),
			));
			
			foreach ($results as $value) {
				$categorie = Hash::get($value, 'Categorietag.name') ? Hash::get($value, 'Categorietag.name') : 'Sans catégorie';
				$valeur = Hash::get($value, 'Valeurtag.name');
				$valeurtag_id = Hash::get($value, 'Valeurtag.id');
				$options['Tag']['valeurtag_id'][$categorie][$valeurtag_id] = $valeur;
			}
			
			$options['Zonegeographique']['id'] = $Controller->Zonegeographique->find( 'list' );
			
			$options['Requestgroup']['name'] = $Controller->Requestmanager->Requestgroup->find('list', array('order' => 'name'));
			$requestManager = $Controller->Requestmanager->find('all', array('conditions' => array( 'actif' => '1' )));
			
			foreach ($options['Requestgroup']['name'] as $group_id => $group) {
				foreach ($requestManager as $value) {
					if ( $value['Requestmanager']['requestgroup_id'] === $group_id
						&& $Controller->Requestmanager->checkModelPresence($value, 'Foyer')
						&& $Controller->Requestmanager->checkModelPresence($value, 'Personne') 
						&& $Controller->Requestmanager->checkModelPresence($value, 'Dossier') 
					) {
						$options['Requestmanager']['name'][$group][$value['Requestmanager']['id']] = $value['Requestmanager']['name'];
					}
				}
			}
			
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
					'Valeurtag',
					'Categorietag',
					'Zonegeographique',
					'Requestmanager',
				)
			);

			return $result;
		}
		
		/**
		 * Permet de filtrer les options envoyées à la vue au moyen de la clé
		 * 'filters.accepted' dans le fichier de configuration.
		 *
		 * @param array $options
		 * @param array $params
		 * @return array
		 */
		protected function _optionsAccepted( array $options, array $params ) {
			$Controller = $this->_Collection->getController();
			$options = parent::_optionsAccepted($options, $params);
			
			$options['filter']['Tag']['valeurtag_id'] = $options['Tag']['valeurtag_id'];
			
			foreach( $options['Tag']['valeurtag_id'] as $title => $values ) {
				foreach (array_keys($values) as $id) {
					if (!in_array((string)$id, (array)Hash::get($Controller->request->data, 'Search.Tag.valeurtag_id'))) {
						unset($options['Tag']['valeurtag_id'][$title][$id]);
						if ( empty($options['Tag']['valeurtag_id'][$title]) ) {
							unset($options['Tag']['valeurtag_id'][$title]);
						}
					}
				}
			}
			
			foreach( $options['Requestgroup']['name'] as $key => $value ) {
				if ( !in_array($key, Configure::read('Tags.cohorte.allowed.Requestgroup.id')) ) {
					unset($options['Requestmanager']['name'][$value]);
				}
			}

			return $options;
		}
		
		/**
		 * Retourne un array avec clés de paramètres suivantes complétées en
		 * fonction du contrôleur:
		 *	- modelName: le nom du modèle sur lequel se fera la pagination
		 *	- modelRechercheName: le nom du modèle de moteur de recherche
		 *	- searchKey: le préfixe des filtres renvoyés par le moteur de recherche
		 *	- searchKeyPrefix: le préfixe des champs configurés
		 *	- configurableQueryFieldsKey: les clés de configuration contenant les
		 *    champs à sélectionner dans la base de données.
		 *  - auto: la recherche doit-elle être lancée (avec les valeurs par défaut
		 *    des filtres de recherche) automatiquement au premier accès à la page,
		 *    lors de l'appel à une méthode search() ou cohorte(). Configurable
		 *    avec Configure::write( 'ConfigurableQuery.<Controller>.<action>.query.auto' )
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _params( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			if ( Hash::get($Controller->request->data, 'Search.Requestmanager.name') ) {
				$result = ClassRegistry::init('Requestmanager')->find('first', 
					array( 
						'fields' => 'Requestmanager.model', 
						'conditions' => array(
							'Requestmanager.id' => Hash::get($Controller->request->data, 'Search.Requestmanager.name') 
						)
					)
				);
				$params['modelName'] = Hash::get($result, 'Requestmanager.model');
			}
			
			return parent::_params($params);
		}
		
		/**
		 * Surcharge de _queryConditions permettant de modifier la configuration dans le cas d'une utilisation du Requestmanager
		 * 
		 * @param array $query
		 * @param array $filters
		 * @param array $params
		 * @return type
		 */
		protected function _queryConditions( array $query, array $filters, array $params ) {
			$Controller = $this->_Collection->getController();
			$query = parent::_queryConditions($query, $filters, $params);
			
			if ( Hash::get($Controller->request->data, 'Search.Requestmanager.name') ) {
				$config = Configure::read('ConfigurableQuery.'.$params['configurableQueryFieldsKey']);
				$actions = array();
				
				foreach ( $config['results']['fields'] as $key => $value ) {
					if (strpos((string)$key, '/') === 0 || (is_string($value) && strpos($value, '/') === 0)) {
						$actions[$key] = $value;
					}
				}debug($actions);
				
				$config['results']['fields'] = array_merge( $query['fields'], $actions );
				$config['query']['order'] = $query['order'];
				unset($config['results']['fields']['Dossier.locked']);
				
				Configure::write('ConfigurableQuery.'.$params['configurableQueryFieldsKey'], $config);
				
				// Force l'ajout de Foyer.id, Dossier.id et Personne.id si ils ne sont pas présent
				$fields = Hash::get($query, 'fields');
				$flippedFields = array_flip($fields);
				if ( !isset($fields['Foyer.id']) && !isset($flippedFields['Foyer.id']) ) {
					$query['fields'][] = 'Foyer.id';
				}
				if ( !isset($fields['Dossier.id']) && !isset($flippedFields['Dossier.id']) ) {
					$query['fields'][] = 'Dossier.id';
				}
				if ( !isset($fields['Personne.id']) && !isset($flippedFields['Personne.id']) ) {
					$query['fields'][] = 'Personne.id';
				}
			}
			
			return $query;
		}
	}
?>