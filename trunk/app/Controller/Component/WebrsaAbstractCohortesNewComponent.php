<?php
	/**
	 * Code source de la classe WebrsaAbstractCohortesNewComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractMoteursNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaAbstractCohortesNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaAbstractCohortesNewComponent extends WebrsaAbstractMoteursNewComponent
	{
		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Cohortes',
			'WebrsaRecherches'
		);

		/**
		 * Surcharge des paramètres:
		 *  - cohorteKey: le préfixe des inputs de la cohorte
		 *  - dossierIdPath: chemin vers dossier_id revoyé en champs obligatoire ex: {n}.Foyer.dossier_id ou {n}.Dossier.id
		 *  - modelSave: modèle utilisé par $this->saveCohorte() pour sauvegarder les données
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _params( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$defaults = array( 'modelRechercheName' => 'WebrsaCohorte'.$Controller->modelClass );
			$params = parent::_params( $params + $defaults );

			$params += array(
				'cohorteKey' => 'Cohorte',
				'dossierIdPath' => '{n}.Dossier.id',
				'modelSave' => $params['modelName']
			);

			return $params;
		}

		protected function _queryConditions( array $query, array $filters, array $params ) {
			$Controller = $this->_Collection->getController();

			$query = parent::_queryConditions( $query, $filters, $params );
			$query = $Controller->Cohortes->qdConditions( $query );

			return $query;
		}

		protected function _traitementCohorte( array $params, array $paramsSave ) {
			$Controller = $this->_Collection->getController();
			$success = null;

			$Controller->request->params['named'] += array(
				'page' => Hash::get($Controller->request->data, 'page' )
			);

			// INFO: on s'assure de ne pas ajouter de clés sort ou direction vides
			foreach( array( 'sort', 'direction' ) as $key ) {
				$value = (string)Hash::get($Controller->request->data, $key );
				if( $value !== '' ) {
					$Controller->request->params['named'][$key] = $value;
				}
			}

			// On retire la Cohorte en cas de changement de page
			$sessionKey = 'Page Check: '.$Controller->name.'_'.$Controller->action;
			$page = (int)Hash::get( $Controller->request->data, 'page' );
			$page = $page === 0 ? 1 : $page;
			
			if ( (int)$Controller->Session->read( $sessionKey ) !== $page ) {
				unset($Controller->request->data[$params['cohorteKey']]);
				$Controller->Session->write( $sessionKey, $page );
			}
			$Controller->request->data['page'] = $page;

			// Si un formulaire de cohorte est renvoyé, on le traite
			if( isset( $Controller->request->data[$params['cohorteKey']] ) ) {
				$dossiersIds = (array)Hash::extract(
					$Controller->request->data[$params['cohorteKey']], $params['dossierIdPath']
				);
				$Controller->Cohortes->get($dossiersIds);

				$success = $this->saveCohorte( $Controller->request->data[$params['cohorteKey']], $params, $paramsSave );

				if ( $success ) {
					$Controller->Cohortes->release($dossiersIds);
				}
			}

			return $success;
		}

		/**
		 * Utilise WebrsaAbstractRecherchesComponent et ajoute le traitement du formulaire d'une cohorte
		 *
		 * @param array $params
		 * @param array $paramsSave Utilisé pour la fonction $this->saveCohorte()
		 * @return type
		 */
		final public function cohorte( array $params = array(), array $paramsSave = array() ) { // FIXME: doubles paramètres ($paramsSave)
			$Controller = $this->_Collection->getController();
			$defaults = array( 'keys' => array( 'results.fields', 'results.innerTable' ) );
			$params = $this->_params( $params + $defaults );

			// Récupération des options
			$options = $this->_options( $params );

			// Suppression des jetons en cas de changement de page
			// TODO: factoriser
			$Controller->Cohortes->clean();

			// Si la recherche doit être effectuée
			if( $this->_needsSearch( $params ) ) {
				// Initialisation de la recherche
				$this->_initializeSearch( $params );

				// Traitement des données renvoyées par la cohorte ?
				$success = $this->_traitementCohorte( $params, $paramsSave );

				// Récupération des valeurs du formulaire de recherche
				$filters = $this->_filters( $params );

				// Récupération du query
				$query = $this->_query( $filters, $params );

				// Exécution du query et assignation des résultats
				$Controller->{$params['modelName']}->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query, $params['modelName'] );

				//--------------------------------------------------------------
				// TODO: début factoriser
				// On pré-remplit le formulaire de cohorte
				if( $success !== false ) {
					$data = $Controller->{$params['modelRechercheName']}->prepareFormDataCohorte( $results, $params );
					$Controller->request->data[$params['cohorteKey']] = $data;
				}

				// Jetons
				$dossiersIds = (array)Hash::extract($results, $params['dossierIdPath']);
				$Controller->Cohortes->get($dossiersIds);

				// On insert les élements du formulaire de cohorte dans le tableau de résultats
				$cohorteFields = $this->_formatFieldsForInsert( $Controller->{$params['modelRechercheName']}->cohorteFields, $params );

				// On conserve les filtres de recherche en élements cachés dans le formulaire de cohorte
				$filterData =& $Controller->request->data[$params['searchKey']];
				$extraHiddenFields = array(
					$params['searchKey'] => $filterData,
					'page' => Hash::get($Controller->request->data, 'page' ),
					'sort' => Hash::get($Controller->request->data, 'sort' ),
					'direction' => Hash::get($Controller->request->data, 'direction' ),
				);

				$configuredCohorteParams = array(
					'format' => SearchProgressivePagination::format(
						!Hash::get( $Controller->request->data, 'Search.Pagination.nombre_total' )
					),
					'options' => $options,
					'extraHiddenFields' => $extraHiddenFields,
					'entityErrorPrefix' => 'Cohorte',
					'cohorteFields' => $cohorteFields,
					'view' => Configure::read($params['searchKeyPrefix'].'.'.$params['configurableQueryFieldsKey'].'.view')
				);

				$Controller->set( compact('cohorteFields', 'results', 'extraHiddenFields', 'configuredCohorteParams') );
				// TODO: fin factoriser
			}
			// Sinon
			else {
				// Récupération des valeurs par défaut des filtres
				$defaults = $this->_defaults( $params );

				// Assignation au formulaire
				$Controller->request->data = $defaults;

				// Si on doit automatiquement lancer la recherche, on met les filtres ar défaut dans l'URL
				if( $params['auto'] === true ) {
					return $this->_auto( $defaults, $params );
				}
			}

			// Assignation à la vue
			$Controller->set( 'options', $options );
		}

		/**
		 * Sauvegarde des données de cohorte générique (à surcharger en cas de fonctionnement spécial)
		 * En lien avec modelSave->saveCohorte()
		 *
		 * @param array $datas
		 * @param array $paramsComponent
		 * @param array $paramsSave Params à passer a la sauvegarde
		 * @return boolean
		 */
		public function saveCohorte( array $datas, array $paramsComponent = array(), array $paramsSave = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $paramsComponent );
			$user_id = $Controller->Session->read( 'Auth.User.id' );

			$Controller->loadModel( $params['modelSave'] ); // TODO: dans l'initialisation

			$Controller->{$params['modelSave']}->begin();
			$saved = (boolean)$Controller->{$params['modelRechercheName']}->saveCohorte( $datas, $paramsSave, $user_id );

			if ( $saved ) {
				$Controller->{$params['modelSave']}->commit();
				$Controller->Session->setFlash('Enregistrement effectué.', 'flash/success');
			}
			else {
				$Controller->{$params['modelName']}->rollback();
				$Controller->Session->setFlash('Erreur lors de l\'enregistrement.', 'flash/error');
			}

			return $saved;
		}

		/**
		 * Transforme des champs de type < Model.field >, en < data[Cohorte][][Model][field] >
		 * Destiné à être insérer avec ConfigurableQuery
		 *
		 * @param array $fields
		 * @param array $paramsComponent
		 * @return array
		 */
		protected function _formatFieldsForInsert( array $fields, array $paramsComponent = array() ) {
			$params = $this->_params( $paramsComponent );
			$formatedFields = array();
			$options = $this->_options($params);

			foreach ( Hash::normalize($fields) as $path => $value ) {
				if ( strpos($path, '.') ) {
					$model_field = model_field($path);
					$formatedKey = 'data[' . $params['cohorteKey'] . '][][' . $model_field[0] . '][' . $model_field[1] . ']';
					$value += array( 'options' => Hash::get( $options, $path ) );

					$formatedFields[$formatedKey] = $value;
				}
			}

			return $formatedFields;
		}
	}
?>