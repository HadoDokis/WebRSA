<?php
	/**
	 * Code source de la classe WebrsaAbtractCohortesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );
	App::uses( 'WebrsaAbstractMoteursComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaAbtractCohortesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	abstract class WebrsaAbstractCohortesComponent extends WebrsaAbstractMoteursComponent
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
		 * Utilise WebrsaAbstractRecherchesComponent et ajoute le traitement du formulaire d'une cohorte
		 *
		 * @param array $paramsComponent equivalent de $params de WebrsaRecherches::search( $params )
		 * @param array $paramsSave Utilisé pour la fonction $this->saveCohorte()
		 */
		public function cohorte( array $paramsComponent = array(), array $paramsSave = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $paramsComponent );
			$options = $this->options( $params );
			$Controller->loadModel( $params['modelRechercheName'] );

			// Suppression des jetons en cas de changement de page
			$Controller->Cohortes->clean();

			if( !empty( $Controller->request->data ) || $this->_needsAutoSearch( $params ) ) {
				$prepareForm = true;

				$Controller->request->params['named'] += array(
					'page' => Hash::get($Controller->request->data, 'page' ),
					'sort' => Hash::get($Controller->request->data, 'sort' ),
					'direction' => Hash::get($Controller->request->data, 'direction' ),
				);

				// On retire la Cohorte en cas de changement de page
				$sessionKey = 'Page Check: '.$Controller->name.'_'.$Controller->action;
				$page = (int)Hash::get( $Controller->request->data, 'page' );

				if ( (int)$Controller->Session->read( $sessionKey ) !== $page ) {
					unset($Controller->request->data[$params['cohorteKey']]);
					$Controller->Session->write( $sessionKey, ($page ? $page : 1) );
				}
				$Controller->request->data['page'] = $page ? $page : 1;

				// Si un formulaire de cohorte est renvoyé, on le traite
				if( isset( $Controller->request->data[$params['cohorteKey']] ) ) {
					$dossiersIds = (array)Hash::extract(
						$Controller->request->data[$params['cohorteKey']], $params['dossierIdPath']
					);
					$Controller->Cohortes->get($dossiersIds);

					$saved = $this->saveCohorte( $Controller->request->data[$params['cohorteKey']], $params, $paramsSave );

					if ( $saved ) {
						$Controller->Cohortes->release($dossiersIds);
					}
					else{
						$prepareForm = false;
					}
				}

				if( $this->_needsAutoSearch( $params ) ) {
					$this->_autoSearch( $params );
				}

				// Recherche
				$query = $this->_getQuery( $params );
				$Controller->{$params['modelName']}->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query, $params['modelName'] );

				// On prérempli le formulaire de cohorte
				if( $prepareForm ) {
					$cohorteFormData = $Controller->{$params['modelRechercheName']}->prepareFormDataCohorte( $results, $params );
					$Controller->request->data[$params['cohorteKey']] = $cohorteFormData;
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
					'cohorteFields' => $cohorteFields
				);

				$Controller->set( compact('cohorteFields', 'results', 'extraHiddenFields', 'configuredCohorteParams') );
			}
			else {
				$this->_prepareFilter($params);
			}

			$Controller->set( compact('options') );
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

			$Controller->loadModel( $params['modelSave'] );

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
		 * Retourne un array avec clés de paramètres suivantes complétées en
		 * fonction du contrôleur:
		 *	- modelName: le nom du modèle sur lequel se fera la pagination
		 *	- modelRechercheName: le nom du modèle de moteur de recherche
		 *	- searchKey: le préfixe des filtres renvoyés par le moteur de recherche
		 *	- searchKeyPrefix: le préfixe des champs configurés
		 *	- configurableQueryFieldsKey: les clés de configuration contenant les
		 *    champs à sélectionner dans la base de données.
		 *
		 *  - cohorteKey: le préfixe des inputs de la cohorte
		 *  - dossierIdPath: chemin vers dossier_id revoyé en champs obligatoire ex: {n}.Foyer.dossier_id ou {n}.Dossier.id
		 *  - modelSave: modèle utilisé par $this->saveCohorte() pour sauvegarder les données
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _params( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelRechercheName' => 'WebrsaCohorte'.$Controller->modelClass,
			);

			$params += parent::_params( $params );

			$params += array(
				'cohorteKey' => 'Cohorte',
				'dossierIdPath' => '{n}.Dossier.id',
				'modelSave' => $params['modelName']
			);

			return $params;
		}

		/**
		 * Récupère la query complète pour la recherche en fonction des clefs du params
		 *
		 * @param array $params
		 * @return array
		 */
		protected function _getQuery( array $params = array() ) {
			// On ne veux pas les dossiers avec un jeton d'un autre utilisateur
			return $this->_Collection->getController()->Cohortes->qdConditions( parent::_getQuery($params) );
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
			$options = $this->options($params);

			foreach ( Hash::normalize($fields) as $path => $value ) {
				if ( strpos($path, '.') ) {
					$model_field = model_field($path);
					$formatedKey = 'data[' . $params['cohorteKey'] . '][][' . $model_field[0] . '][' . $model_field[1] . ']';
					$value += array(
						'options' => Hash::get($options, $path)
					);

					$formatedFields[$formatedKey] = $value;
				}
			}

			return $formatedFields;
		}
	}
?>