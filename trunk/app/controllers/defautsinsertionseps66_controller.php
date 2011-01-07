<?php
	class Defautsinsertionseps66Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		/**
		*
		*/

		protected function _selectionPassageDefautinsertionep66( $qdName, $origine ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Defautinsertionep66->begin();

				foreach( $this->data['Orientstruct'] as $item ) {
					// La personne était-elle sélectionnée précédemment ?
					$alreadyChecked = $this->Defautinsertionep66->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								'Dossierep.etapedossierep' => 'cree',
								'Dossierep.themeep' => 'defautsinsertionseps66',
								'Dossierep.personne_id' => $item['personne_id'],
								'Defautinsertionep66.origine' => $origine
							),
							'contain' => array(
								'Defautinsertionep66'
							)
						)
					);

					// Personnes non cochées que l'on sélectionne
					if( empty( $alreadyChecked ) && !empty( $item['chosen'] ) ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => 'defautsinsertionseps66',
								'personne_id' => $item['personne_id']
							)
						);
						$this->Defautinsertionep66->Dossierep->create( $dossierep );
						$success = $this->Defautinsertionep66->Dossierep->save() && $success;

						$defautinsertionep66 = array(
							'Defautinsertionep66' => array(
								'dossierep_id' => $this->Defautinsertionep66->Dossierep->id,
								'orientstruct_id' => $item['id'],
								'origine' => $origine
							)
						);

						if( $origine == 'radiationpe' ) {
							$queryDataPersonne = $this->Defautinsertionep66->qdRadies();
							$queryDataPersonne['fields'][] = 'Historiqueetatpe.id';
							$queryDataPersonne['conditions']['Personne.id'] = $item['personne_id'];
							$historiqueetatpe = $this->Defautinsertionep66->Dossierep->Personne->find( 'first', $queryDataPersonne );

							$defautinsertionep66['Defautinsertionep66']['historiqueetatpe_id'] = $historiqueetatpe['Historiqueetatpe']['id'];
						}

						$this->Defautinsertionep66->create( $defautinsertionep66 );
						$success = $this->Defautinsertionep66->save() && $success;
					}
					// Personnes précédemment sélectionnées, que l'on désélectionne
					else if( !empty( $alreadyChecked ) && empty( $item['chosen'] ) ) {
						$success = $this->Defautinsertionep66->Dossierep->delete( $alreadyChecked['Dossierep']['id'], true ) && $success;
					}
					// Personnes précédemment sélectionnées, que l'on garde sélectionnées -> rien à faire
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Defautinsertionep66->commit();
				}
				else {
					$this->Defautinsertionep66->rollback();
				}
			}

			$queryData = $this->Defautinsertionep66->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->Defautinsertionep66->Dossierep->Personne );

			if( empty( $this->data ) ) {
				// Pré-remplissage des cases à cocher avec les dossiers sélectionnés,
				// qui ne sont pas encore assocés à une séance. -> FIXME permettre jusqu'à l'étape avisep ?
				$dossiers = $this->Defautinsertionep66->Dossierep->find(
					'all',
					array(
						'conditions' => array(
							'Dossierep.etapedossierep' => 'cree',
							'Dossierep.themeep' => 'defautsinsertionseps66',
							'Dossierep.personne_id' => Set::extract( '/Orientstruct/personne_id', $personnes ),
							'Defautinsertionep66.origine' => $origine
						),
						'contain' => array(
							'Defautinsertionep66'
						)
					)
				);

				if( !empty( $dossiers ) ) {
					$checked = Set::extract( '/Dossierep/personne_id', $dossiers );

					foreach( $personnes as $i => $personne ) {
						$this->data['Orientstruct'][$i]['id'] = $personne['Orientstruct']['id'];
						$this->data['Orientstruct'][$i]['personne_id'] = $personne['Orientstruct']['personne_id'];
						if( in_array( $personne['Orientstruct']['personne_id'], $checked ) ) {
							$this->data['Orientstruct'][$i]['chosen'] = '1';
						}
						else {
							$this->data['Orientstruct'][$i]['chosen'] = '0';
						}
					}
				}
			}

			$this->set( compact( 'personnes' ) );
            $this->render( $this->action, null, 'selectionnoninscrits' ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionnoninscrits() {
			$this->_selectionPassageDefautinsertionep66( 'qdNonInscrits', 'noninscriptionpe' );
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageDefautinsertionep66( 'qdRadies', 'radiationpe' );
		}
	}
?>