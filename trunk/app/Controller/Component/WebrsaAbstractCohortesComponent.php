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
		public function _params( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			
			$params += array(
				'modelRechercheName' => 'WebrsaCohorte'.$Controller->modelClass,
			);

			$params += parent::_params( $params );
			
			$params += array(
				'cohorteKey' => 'Cohorte',
				'dossierIdPath' => '{n}.Foyer.dossier_id',
				'modelSave' => $params['modelName']
			);

			return $params;
		}

		/**
		 * Utilise WebrsaAbstractRecherchesComponent et ajoute le traitement du formulaire d'une cohorte
		 * 
		 * @param array $paramsComponent equivalent de $params de WebrsaRecherches::search( $params )
		 * @param array $paramsSave Utilisé pour la fonction $this->saveCohorte()
		 */
		public function cohorte( array $paramsComponent = array(), array $paramsSave = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $paramsComponent );
			$results = array();
			$options = $this->options( $params );
			$Controller->loadModel( $params['modelRechercheName'] );
			
			// On isole le contenu du formulaire de cohorte du formulaire de recherche
			$cohorteRequestData = isset($Controller->request->data[$params['cohorteKey']]) 
				? $Controller->request->data[$params['cohorteKey']] 
				: array()
			;
			$cohorte = $cohorteRequestData;
			
			// On s'occupe de lancer la recherche ou de préparer le remplissage des filtres
			if( !empty( $Controller->request->data ) ) {
				$query = $this->_getQuery( $params );

				$Controller->{$params['modelName']}->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query, $params['modelName'] );
				
				$Controller->request->data += $Controller->{$params['modelRechercheName']}->prepareFormDataCohorte( $results, $params );
				
				// Jetons
				ClassRegistry::init('Jeton')->deleteAll( array( 'Jeton.user_id' => $Controller->Session->read( 'Auth.User.id' ) ), false ); // FIXME Trouver un autre moyen ???
				$this->Cohortes->Controller = $Controller;
				$this->Cohortes->get(Hash::extract($results, $params['dossierIdPath']));
				
				$Controller->set( compact('results') );
			}
			else {
				$this->_prepareFilter($params);
			}
			
			// Si un formulaire de cohorte est renvoyé, on tente la sauvegarde
			if ( !empty($cohorte) ) {
				$saved = $this->saveCohorte( $cohorte, $params, $paramsSave );
				
				if ( $saved ) {
					$this->Cohortes->Controller = $Controller;
					$this->Cohortes->release(array_unique(Set::extract($results, $params['dossierIdPath'])));
					$this->WebrsaRecherches->search($params);
					unset($Controller->request->data[$params['cohorteKey']]);
				}
				else{
					$Controller->request->data[$params['cohorteKey']] = $cohorte;
				}
			}
			
			$cohorteFields = $this->_formatFieldsForInsert( $Controller->{$params['modelRechercheName']}->cohorteFields, $params );
			
			$Controller->set( compact('cohorteFields', 'options') );
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