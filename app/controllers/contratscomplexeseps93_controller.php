<?php
	class Contratscomplexeseps93Controller extends AppController
	{

		/**
		*
		*/

		public $helpers = array( 'Default2' );

		/**
		*
		*/

		public function selection() {
			if( !empty( $this->data ) ) {
				$success = true;
				if( !empty( $this->data['Contratinsertion'] ) ) {
					$this->Contratcomplexeep93->Dossierep->begin();
					foreach( $this->data['Contratinsertion'] as $i => $contratinsertion ) {
						if( $contratinsertion['chosen'] ) {
							// Sauvegarde du dossier d'EP
							$dossierep = array(
								'Dossierep' => array(
									'themeep' => 'contratscomplexeseps93',
									'personne_id' => $this->data['Personne'][$i]['id']
								)
							);

							$this->Contratcomplexeep93->Dossierep->create( $dossierep );
							$tmpSuccess = $this->Contratcomplexeep93->Dossierep->save();

							// Sauvegarde des données de la thématique
							if( $tmpSuccess ) {
								$contratcomplexeep93 = array(
									'Contratcomplexeep93' => array(
										'dossierep_id' => $this->Contratcomplexeep93->Dossierep->id,
										'contratinsertion_id' => $contratinsertion['id']
									)
								);

								$this->Contratcomplexeep93->create( $contratcomplexeep93 );
								$success = $this->Contratcomplexeep93->save() && $success;
							}
							$success = $success && $tmpSuccess;
						}
					}

					$this->_setFlashResult( 'Save', $success );
					if( $success ) {
						$this->data = array();
						$this->Contratcomplexeep93->Dossierep->commit();
					}
					else {
						$this->Contratcomplexeep93->Dossierep->rollback();
					}
				}
			}

			$this->paginate = array(
				'Contratinsertion' => array(
					'conditions' => array(
						'Contratinsertion.decision_ci' => 'E',
						'Contratinsertion.forme_ci' => 'C',
						'Contratinsertion.id NOT IN ( '.$this->Contratcomplexeep93->sq(
							array(
								'alias' => 'contratscomplexeseps93',
								'fields' => array( 'contratscomplexeseps93.contratinsertion_id' ),
							)
						).' )',
					),
					'contain' => array(
						'Personne'
					),
					'limit' => 10
				)
			);

			$contratsinsertion = $this->paginate( $this->Contratcomplexeep93->Contratinsertion );

			$this->set( compact( 'contratsinsertion' ) );
		}
	}
?>