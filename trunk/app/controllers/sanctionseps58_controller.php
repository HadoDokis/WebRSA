<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Sanctionseps58Controller extends AppController
	{
		public $helpers = array( 'Default2' );
		
		public function beforeFilter() {
			parent::beforeFilter();
		}

		/**
		*
		*/

		protected function _selectionPassageSanctionep58( $qdName, $origine ) {
			if( !empty( $this->data ) ) {
// debug($this->data);
				$success = true;
				$this->Sanctionep58->begin();

				foreach( $this->data['Historiqueetatpe'] as $key => $item ) {
					// La personne était-elle sélectionnée précédemment ?
					$alreadyChecked = $this->Sanctionep58->Dossierep->find(
						'first',
						array(
							'conditions' => array(
								'Dossierep.id NOT IN ( '.$this->Sanctionep58->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'reporte' )
										)
									)
								).' )',
								'Dossierep.themeep' => 'sanctionseps58',
								'Dossierep.personne_id' => $this->data['Personne'][$key]['id'],
								'Sanctionep58.origine' => $origine
							),
							'contain' => array(
								'Sanctionep58'
							)
						)
					);

					// Personnes non cochées que l'on sélectionne
					if( empty( $alreadyChecked ) && !empty( $item['chosen'] ) ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => 'sanctionseps58',
								'personne_id' => $this->data['Personne'][$key]['id']
							)
						);
						$this->Sanctionep58->Dossierep->create( $dossierep );
						$success = $this->Sanctionep58->Dossierep->save() && $success;

						$rgsanction = $this->Sanctionep58->find(
							'count',
							array(
								'conditions' => array(
									'Sanctionep58.origine' => $origine
								),
								'joins' => array(
									array(
										'table' => 'dossierseps',
										'alias' => 'Dossierep',
										'type' => 'INNER',
										'conditions' => array(
											'Dossierep.id = Sanctionep58.dossierep_id'
										)
									),
									array(
										'table' => 'passagescommissionseps',
										'alias' => 'Passagecommissionep',
										'type' => 'INNER',
										'conditions' => array(
											'Dossierep.id = Passagecommissionep.dossierep_id'
										)
									),
									array(
										'table' => 'decisionssanctionseps58',
										'alias' => 'Decisionsanctionep58',
										'type' => 'INNER',
										'conditions' => array(
											'Passagecommissionep.id = Decisionsanctionep58.passagecommissionep_id',
											'Decisionsanctionep58.decision' => 'sanction'
										)
									)
								),
								'contain' => false
							)
						);

						$listesanctionep58 = $this->Sanctionep58->Listesanctionep58->find(
							'first',
							array(
								'conditions' => array(
									'Listesanctionep58.rang' => $rgsanction+1
								),
								'contain' => false
							)
						);
						
						$sanctionep58 = array(
							'Sanctionep58' => array(
								'dossierep_id' => $this->Sanctionep58->Dossierep->id,
								'origine' => $origine,
								'listesanctionep58_id' => $listesanctionep58['Listesanctionep58']['id']
							)
						);

						$this->Sanctionep58->create( $sanctionep58 );
						$success = $this->Sanctionep58->save() && $success;
					}
					// Personnes précédemment sélectionnées, que l'on désélectionne
					else if( !empty( $alreadyChecked ) && empty( $item['chosen'] ) ) {
						$success = $this->Sanctionep58->Dossierep->delete( $alreadyChecked['Dossierep']['id'], true ) && $success;
					}
					// Personnes précédemment sélectionnées, que l'on garde sélectionnées -> rien à faire
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Sanctionep58->commit();
				}
				else {
					$this->Sanctionep58->rollback();
				}
			}

			$queryData = $this->Sanctionep58->{$qdName}();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->Sanctionep58->Dossierep->Personne );
			
			$this->data = null;

			$this->set( compact( 'personnes' ) );
            $this->render( $origine ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionnoninscrits() {
			$this->_selectionPassageSanctionep58( 'qdNonInscrits', 'noninscritpe' );
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageSanctionep58( 'qdRadies', 'radiepe' );
		}
	}
?>