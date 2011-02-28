<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class RadiespoleemploiepsController extends AppController
	{
		public $helpers = array( 'Default2' );
		
		public $uses = array( 'Radiepoleemploiep58', 'Radiepoleemploiep93' );
		
		public function beforeFilter() {
			$this->modelClass = 'Radiepoleemploiep'.Configure::read( 'Cg.departement' );
			parent::beforeFilter();
		}

		/**
		*
		*/

		protected function _selectionPassageRadiepoleemploiep( $qdName ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->{$this->modelClass}->begin();
				foreach( $this->data['Historiqueetatpe'] as $key => $item ) {
					if( $item['chosen'] == 1 ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => Inflector::tableize( $this->modelClass ),
								'personne_id' => $this->data['Personne'][$key]['id']
							)
						);
						$this->{$this->modelClass}->Dossierep->create( $dossierep );
						$success = $this->{$this->modelClass}->Dossierep->save() && $success;

						$radiepoleemploiep = array(
							$this->modelClass => array(
								'dossierep_id' => $this->{$this->modelClass}->Dossierep->id,
								'historiqueetatpe_id' => $item['id']
							)
						);

						$this->{$this->modelClass}->create( $radiepoleemploiep );
						$success = $this->{$this->modelClass}->save() && $success;
					}
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->{$this->modelClass}->commit();
					$this->data = null;
				}
				else {
					$this->{$this->modelClass}->rollback();
				}
			}

			$queryData = $this->{$this->modelClass}->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->{$this->modelClass}->Dossierep->Personne );

			$this->set( compact( 'personnes' ) );
            $this->render( $this->action, null, 'selectionnoninscrits' ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageRadiepoleemploiep( 'qdRadies' );
		}
	}
?>