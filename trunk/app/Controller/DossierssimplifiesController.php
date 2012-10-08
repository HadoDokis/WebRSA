<?php
	class DossierssimplifiesController extends AppController
	{

		public $name = 'Dossierssimplifies';
		public $uses = array( 'Dossier', 'Foyer', 'Personne', 'Option', 'Structurereferente', 'Zonegeographique', 'Typeorient', 'Orientstruct', 'Typocontrat' );
		public $components = array( 'Gedooo.Gedooo' );
		public $commeDroit = array(
			'add' => 'Dossierssimplifies:edit'
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$this->set( 'pays', $this->Option->pays() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'fonorg', array( 'CAF' => 'CAF', 'MSA' => 'MSA' ) );
			$this->set( 'rolepers', array_filter_keys( $this->Option->rolepers(), array( 'DEM', 'CJT' ) ) );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
			$this->set( 'statut_orient', $this->Option->statut_orient() );
			$this->set( 'options', $this->Typeorient->listOptions() );
			$this->set( 'structsReferentes', $this->Structurereferente->list1Options() );
			$this->set( 'refsorientants', $this->Structurereferente->Referent->listOptions() );
		}

		/**
		 *
		 */
		public function view( $id = null ) {
			$details = array( );

			$typeorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) );
			$this->set( 'typeorient', $typeorient );

			$qd_tDossier = array(
				'conditions' => array(
					'Dossier.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tDossier = $this->Dossier->find( 'first', $qd_tDossier );


			$details = Set::merge( $details, $tDossier );

			$qd_tFoyer = array(
				'conditions' => array(
					'Foyer.dossier_id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tFoyer = $this->Dossier->Foyer->find( 'first', $qd_tFoyer );

			$details = Set::merge( $details, $tFoyer );

			$bindPrestation = $this->Personne->hasOne['Prestation'];
			$this->Personne->unbindModelAll();
			$this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Prestation' => $bindPrestation ) ) );
			$personnesFoyer = $this->Personne->find(
				'all',
				array(
					'conditions' => array(
						'Personne.foyer_id' => $tFoyer['Foyer']['id'],
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'contain' => array(
						'Prestation'
					)
// 					'recursive' => -1
				)
			);

			$roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
			foreach( $roles as $index => $role ) {
				///Orientations
				$orient = $this->Orientstruct->find(
						'first', array(
					'conditions' => array( 'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
					'recursive' => -1,
					'order' => 'Orientstruct.date_propo DESC',
						)
				);
				$personnesFoyer[$index]['Orientstruct'] = $orient['Orientstruct'];

				///Structures référentes
				$struct = $this->Structurereferente->find(
						'first', array(
					'conditions' => array( 'Structurereferente.id' => $personnesFoyer[$index]['Orientstruct']['structurereferente_id'] ),
					'recursive' => -1
						)
				);
				$personnesFoyer[$index]['Structurereferente'] = $struct['Structurereferente'];

				$details[$role] = $personnesFoyer[$index];
			}

			$this->set( 'personnes', $personnesFoyer );

			$this->_setOptions();
			$this->set( 'details', $details );
		}

		/**
		 *
		 */
		public function add() {
			$this->set( 'typesOrient', $this->Typeorient->listOptions() );
			$this->set( 'structures', $this->Structurereferente->list1Options() );

			$typesOrient = $this->Typeorient->find(
					'list', array(
				'fields' => array(
					'Typeorient.id',
					'Typeorient.lib_type_orient'
				),
				'conditions' => array(
					'Typeorient.parentid' => null
				)
					)
			);
			$this->set( 'typesOrient', $typesOrient );

			$typesStruct = $this->Typeorient->find(
					'list', array(
				'fields' => array(
					'Typeorient.id',
					'Typeorient.lib_type_orient'
				),
				'conditions' => array(
					'Typeorient.parentid NOT' => null
				)
					)
			);
			$this->set( 'typesStruct', $typesStruct );


			if( !empty( $this->request->data ) ) {
				if( !empty( $this->request->data['Dossier']['numdemrsatemp'] ) ) {
					$this->request->data['Dossier']['numdemrsa'] = $this->Dossier->generationNumdemrsaTemporaire();
				}

				$this->Dossier->set( $this->request->data );
				$this->Foyer->set( $this->request->data );
				$this->Orientstruct->set( $this->request->data );
				$this->Structurereferente->set( $this->request->data );

				$validates = $this->Dossier->validates();
				$validates = $this->Foyer->validates() && $validates;

				$tPers1 = $this->request->data['Personne'][1];
				unset( $tPers1['rolepers'] );
				unset( $tPers1['dtnai'] ); // FIXME ... créer array_filter_deep
				$t = array_filter( $tPers1 );
				if( empty( $t ) ) {
					unset( $this->request->data['Personne'][1] );
				}
				$validates = $this->Personne->saveAll( $this->request->data['Personne'], array( 'validate' => 'only' ) ) & $validates;

				$validates = $this->Orientstruct->validates() && $validates;
				$validates = $this->Structurereferente->validates() && $validates;

				if( $validates ) {
					$this->Dossier->begin();
					$saved = $this->Dossier->save( $this->request->data );
					// Foyer
					$this->request->data['Foyer']['dossier_id'] = $this->Dossier->id;
					$saved = $this->Foyer->save( $this->request->data ) && $saved;
					// Situation dossier RSA
					$situationdossierrsa = array( 'Situationdossierrsa' => array( 'dossier_id' => $this->Dossier->id, 'etatdosrsa' => 'Z' ) );
					$this->Dossier->Situationdossierrsa->validate = array( );
					$saved = $this->Dossier->Situationdossierrsa->save( $situationdossierrsa ) && $saved;

					$orientstruct_validate = $this->Orientstruct->validate;

					foreach( $this->request->data['Personne'] as $key => $pData ) {
						if( !empty( $pData ) ) {
							$this->Orientstruct->validate = $orientstruct_validate;
							// Personne
							$this->Personne->create();
							$pData['foyer_id'] = $this->Foyer->id;
							$this->Personne->set( $pData );
							$saved = $this->Personne->save() && $saved;
							$personneId = $this->Personne->id;

							// Prestation, Calculdroitrsa
							foreach( array( 'Prestation', 'Calculdroitrsa' ) as $tmpModel ) {
								$this->Personne->{$tmpModel}->create();
								$this->request->data[$tmpModel][$key]['personne_id'] = $personneId;
								$this->Personne->{$tmpModel}->set( $this->request->data[$tmpModel][$key] );
								$saved = $this->Personne->{$tmpModel}->save( $this->request->data['Prestation'][$key] ) && $saved;
							}

							// Orientation
							$tOrientstruct = Set::extract( $this->request->data, 'Orientstruct.'.$key );
							if( !empty( $tOrientstruct ) ) {
								$tOrientstruct = Set::filter( $tOrientstruct );
							}

							if( !empty( $tOrientstruct ) ) {
								$this->Orientstruct->create();
								$this->request->data['Orientstruct'][$key]['personne_id'] = $this->Personne->id;
								$this->request->data['Orientstruct'][$key]['valid_cg'] = true;
								$this->request->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
								$this->request->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
								$this->request->data['Orientstruct'][$key]['user_id'] = $this->Session->read( 'Auth.User.id' );
								$saved = $this->Orientstruct->save( $this->request->data['Orientstruct'][$key] ) && $saved;
							}
							else {
								$this->Orientstruct->create();
								$this->Orientstruct->validate = array( );
								$this->request->data['Orientstruct'][$key]['personne_id'] = $this->Personne->id;
								$this->request->data['Orientstruct'][$key]['user_id'] = $this->Session->read( 'Auth.User.id' );
								$saved = $this->Orientstruct->save( $this->request->data['Orientstruct'][$key] ) && $saved;
							}
						}
					}

					if( $saved ) {
						$this->Dossier->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'view', $this->Dossier->id ) );
					}
					else {
						$this->Dossier->rollback();
					}
				}
			}
			$this->_setOptions();
		}

		/**
		 *
		 *
		 *
		 */
		public function edit( $personne_id = null, $orient_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'contain' => array(
					'Foyer',
					'Orientstruct',
					'Prestation',
					'Calculdroitrsa'
				)
			);
			$personne = $this->Personne->find( 'first', $qd_personne );
// debug($personne);
			$orientstruct = $this->Orientstruct->find(
				'first',
				array(
					'conditions' => array( 'Orientstruct.personne_id' => $personne_id ),
					'contain' => false,
					'recursive' => -1,
					'order' => 'Orientstruct.date_propo DESC'
				)
			);
			$personne = Set::merge( $personne, array( 'Orientstruct' => array( $orientstruct['Orientstruct'] ) ) );

			$dossier_id = $personne['Foyer']['dossier_id'];
			$dossimple = $this->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $dossier_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);

			$this->set( 'personne_id', $personne_id );
			$this->set( 'dossiersimple_id', $dossier_id );
			$this->set( 'foyer_id', $personne['Foyer']['id'] );
			$this->set( 'typesOrient', $this->Typeorient->listOptions() );
			$this->set( 'structures', $this->Structurereferente->list1Options() );
			$this->set( 'structuresorientantes', $this->Structurereferente->listOptions() );
			$this->set( 'numdossierrsa', $dossimple['Dossier']['numdemrsa'] );
			$this->set( 'datdemdossrsa', $dossimple['Dossier']['dtdemrsa'] );
			$this->set( 'matricule', $dossimple['Dossier']['matricule'] );
			$this->set( 'orient_id', $personne['Orientstruct'][0]['typeorient_id'] );
			$this->set( 'structure_id', $personne['Orientstruct'][0]['structurereferente_id'] );
			$this->set( 'structureorientante_id', $personne['Orientstruct'][0]['structureorientante_id'] );

			$this->_setOptions();
			if( !empty( $this->request->data ) ) {
				if( isset( $personne['Orientstruct'][0]['id'] ) ) {
					$this->request->data['Orientstruct'][0]['id'] = $personne['Orientstruct'][0]['id'];
				}

				$this->request->data['Orientstruct'][0]['user_id'] = $this->Session->read( 'Auth.User.id' );

				if( $this->Personne->saveAll( $this->request->data, array( 'validate' => 'only' ) ) && isset( $this->request->data['Orientstruct'][0]['typeorient_id'] ) && isset( $this->request->data['Orientstruct'][0]['structurereferente_id'] ) ) {
					$this->request->data['Orientstruct'][0]['statut_orient'] = 'Orienté';
					$this->request->data['Orientstruct'][0]['date_propo'] = strftime( '%Y-%m-%d', time() ); // FIXME
					$this->request->data['Orientstruct'][0]['date_valid'] = strftime( '%Y-%m-%d', time() ); // FIXME
				}

				$this->Dossier->begin();
				$saved = $this->Personne->saveAll( $this->request->data, array( 'atomic' => false ) );

				if( $saved ) {
					$this->Dossier->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'view', $this->Dossier->id ) );
				}
				else {
					$this->Dossier->rollback();
				}
			}
			else {
				$this->request->data = $personne;
			}
			$this->_setOptions();
			$this->set( 'personne', $personne );
		}

	}
?>