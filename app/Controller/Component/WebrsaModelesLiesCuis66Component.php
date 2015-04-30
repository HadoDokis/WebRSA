<?php
	/**
	 * Code source de la classe WebrsaModelesLiesCuis66Component.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Component.php.
	 */
	App::uses( 'DefaultUrl', 'Default.Utility' );
	App::uses( 'DefaultUtility', 'Default.Utility' );

	/**
	 * La classe WebrsaModelesLiesCuis66Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaModelesLiesCuis66Component extends Component
	{
		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'DossiersMenus',
			'Gedooo.Gedooo',
			'Jetons2',
			'Session'			
		);

		public function index( $cui_id, $params = array() ){
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelClass' => $Controller->modelClass,
				'redirect' => "/{$Controller->name}/index/#personne_id#",
				'view' => 'index',
				'urlmenu' => "/{$Controller->name}/index/#personne_id#"
			);
			$Model = $Controller->{$params['modelClass']};
			
			$personne_id = $Model->Cui66->Cui->personneId( $cui_id );
			
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			
			$Model->_setEntriesAncienDossier( $personne_id, 'Cui' );

			$query = array(
				'fields' => array_merge(
					$Model->fields(),
					array(
						'Cui.id',
						'Cui.personne_id',
						'Cui66.id',
						'Cui66.etatdossiercui66',
						$params['modelClass'] . '.id'
					)
				),
				'conditions' => array(
					'Cui.id' => $cui_id,
				),
				'joins' => array(
					$Model->Cui66->join( 'Cui', array( 'conditions' => array( 'Cui.id' => $cui_id, ), 'type' => 'INNER' ) ),
					$Model->Cui66->join( $params['modelClass'], array( 'type' => 'INNER' ) ),
				),
				'order' => array( $params['modelClass'] . '.created DESC' )
			);
			$results = $Model->Cui66->find( 'all', $query );
			
			$messages = $Model->messages( $personne_id );
			$addEnabled = $Model->addEnabled( $messages );
			
			$query = array(
				'fields' => array( 'Cui66.etatdossiercui66' ),
				'conditions' => array( 'Cui66.cui_id' => $cui_id )
			);
			$etatdossiercui66 = $Model->Cui66->find( 'first', $query );
			
			$params['redirect'] = DefaultUrl::toArray( DefaultUtility::evaluate( $results, $params['redirect'] ) );
			$params['urlmenu'] = Inflector::underscore( DefaultUtility::evaluate( $results, $params['urlmenu'] ) );

			// Options
			$options = $Model->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );
			
			$urlmenu = $params['urlmenu'];

			$Controller->set( compact( 'results', 'dossierMenu', 'messages', 'addEnabled', 'personne_id', 'options', 'cui_id', 'urlmenu', 'etatdossiercui66' ) );
			$Controller->view = $params['view'];
		}
		
		public function view( $id = null, $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelClass' => $Controller->modelClass,
				'view' => 'view',
				'urlmenu' => "/{$Controller->name}/index/#personne_id#"
			);
			$Model = $Controller->{$params['modelClass']};

			$data = $Model->find( 'first', 
				array(
					'fields' => array( 'Cui.personne_id', 'Cui66.id', 'Cui.id', 'Cui.personne_id' ),
					'conditions' => array( "{$params['modelClass']}.id" => $id ),
					'joins' => array(
						$Model->join( 'Cui66' ),
						$Model->Cui66->join( 'Cui' )
					)
				)
			);
			$personne_id = Hash::get( $data, 'Cui.personne_id' );
			$cui66_id = Hash::get( $data, 'Cui66.id' );
			$cui_id = Hash::get( $data, 'Cui.id' );

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$params['urlmenu'] = Inflector::underscore( DefaultUtility::evaluate( $data, $params['urlmenu'] ) );

			$query = $Model->queryView( $id );
			$result = $Model->find( 'first', $query );
			if ( isset($result['Entreeromev3']) ){
				foreach( array_keys( $Model->Immersioncui66->Entreeromev3->belongsTo ) as $romev3Alias ) {
					$result['Entreeromev3'][$romev3Alias] = $result[$romev3Alias];
					unset( $result[$romev3Alias] );
				}
			}
			$Controller->request->data = $result;

			$options = $Model->options( array( 'allocataire' => true, 'find' => false, 'autre' => true ) );

			$urlmenu = $params['urlmenu'];

			$Controller->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'cui_id' ) );
			$Controller->view = $params['view'];
		}
		
		public function addEdit( $id = null, $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelClass' => $Controller->modelClass,
				'redirect' => "/{$Controller->name}/index/#personne_id#",
				'view' => 'edit',
				'urlmenu' => "/{$Controller->name}/index/#personne_id#"
			);
			$Model = $Controller->{$params['modelClass']};

			if( $Controller->action == 'add' ) {
				$cui_id = $id;
				$id = null;
				$data = $Model->Cui66->find(
					'first', 
					array(
						'fields' => array( 'Cui.personne_id', 'Cui66.id', 'Cui.id' ),
						'conditions' => array( 'Cui.id' => $cui_id ),
						'joins' => array(
							$Model->Cui66->join( 'Cui' )
						)
					)
				);
				$personne_id = Hash::get( $data, 'Cui.personne_id' );
				$cui66_id = Hash::get( $data, 'Cui66.id' );
			}
			else {
				$data = $Model->find( 'first', 
					array(
						'fields' => array( 'Cui.personne_id', 'Cui66.id', 'Cui.id' ),
						'conditions' => array( "{$params['modelClass']}.id" => $id ),
						'joins' => array(
							$Model->join( 'Cui66' ),
							$Model->Cui66->join( 'Cui' )
						)
					)
				);
				$personne_id = Hash::get( $data, 'Cui.personne_id' );
				$cui66_id = Hash::get( $data, 'Cui66.id' );
				$cui_id = Hash::get( $data, 'Cui.id' );
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );

			$params['redirect'] = DefaultUrl::toArray( DefaultUtility::evaluate( $data, $params['redirect'] ) );
			$params['urlmenu'] = Inflector::underscore( DefaultUtility::evaluate( $data, $params['urlmenu'] ) );

			// Retour à l'index en cas d'annulation
			if( isset( $Controller->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$Controller->redirect( $params['redirect'] );
			}

			if( !empty( $Controller->request->data ) ) {
				$Model->begin();
				if( $Model->saveAddEditFormData( $Controller->request->data, $this->Session->read( 'Auth.User.id' ) ) ) {
					$Model->commit();
					$Model->Cui66->updatePositionsCuisById( $cui_id );
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$Controller->redirect( $params['redirect'] );
				}
				else {
					$Model->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$Controller->request->data = $Model->prepareAddEditFormData( $cui66_id, $id, $this->Session->read( 'Auth.User.id' ) );
			}

			$options = $Model->options( array( 'allocataire' => true, 'find' => true, 'autre' => true ) );

			$urlmenu = $params['urlmenu'];

			$Controller->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu' ) );
			$Controller->view = $params['view'];
		}
		
		public function delete( $id = null, $params = array() ) {
			$Controller = $this->_Collection->getController();

			$params += array(
				'modelClass' => $Controller->modelClass,
			);
			$Model = $Controller->{$params['modelClass']};
			
			$data = $Model->find( 'first',
				array(
					'fields' => array( 'Cui.personne_id', 'Cui.id' ),
					'recursive' => -1,
					'conditions' => array( $params['modelClass'] . '.id' => $id ),
					'joins' => array( 
						$Model->join( 'Cui66' ),
						$Model->Cui66->join( 'Cui' ),
					)
				)
			);
			
			$personne_id = Hash::get( $data, 'Cui.personne_id' );
			$cui_id = Hash::get( $data, 'Cui.id' );
			
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );

			$Model->begin();
			$success = $Model->delete($id);
			$Model->_setFlashResult('Delete', $success);

			if ($success) {
				$Model->commit();
				$Model->Cui66->updatePositionsCuisById( $cui_id );
			} else {
				$Model->rollback();
			}
			$this->Jetons2->release($dossierMenu['Dossier']['id']);
			$Controller->redirect($Controller->referer());
		}
	}
?>