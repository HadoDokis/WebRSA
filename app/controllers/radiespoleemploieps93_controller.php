<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Radiespoleemploieps93Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		/**
		*
		*/

		protected function _selectionPassageRadiepoleemploiep93( $qdName ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Radiepoleemploiep93->begin();
				foreach( $this->data['Historiqueetatpe'] as $key => $item ) {
					if( $item['chosen'] == 1 ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => 'radiespoleemploieps93',
								'personne_id' => $this->data['Personne'][$key]['id']
							)
						);
						$this->Radiepoleemploiep93->Dossierep->create( $dossierep );
						$success = $this->Radiepoleemploiep93->Dossierep->save() && $success;

						$radiepoleemploiep93 = array(
							'Radiepoleemploiep93' => array(
								'dossierep_id' => $this->Radiepoleemploiep93->Dossierep->id,
								'historiqueetatpe_id' => $item['id']
							)
						);

						$this->Radiepoleemploiep93->create( $radiepoleemploiep93 );
						$success = $this->Radiepoleemploiep93->save() && $success;
					}
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Radiepoleemploiep93->commit();
					$this->data = null;
				}
				else {
					$this->Radiepoleemploiep93->rollback();
				}
			}

			$queryData = $this->Radiepoleemploiep93->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->Radiepoleemploiep93->Dossierep->Personne );

			$this->set( compact( 'personnes' ) );
            $this->render( $this->action, null, 'selectionnoninscrits' ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageRadiepoleemploiep93( 'qdRadies' );
		}
	}
?>