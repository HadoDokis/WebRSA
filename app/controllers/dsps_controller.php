<?php
	/*
		http://localhost/adullact/webrsa/trunk/dspps/view/174537
		http://localhost/adullact/webrsa/trunk/dsps/view/174537
	*/
	class DspsController extends AppController
	{
		var $name = 'Dsps';

		var $helpers = array( 'Xform', 'Xhtml', 'Dsphm' );
		
		var $uses = array('Dsp', 'DspRev');

		var $components = array( 'Jetons', 'Default' );
		
		var $paginate = array(
			'limit' => 10, 
			'order' => array('DspRev.created' => 'desc', 'DspRev.id' => 'desc')
		);

		var $specialHasMany = array(
			'Detaildifsoc' => 'difsoc',
			'Detailaccosocfam' => 'nataccosocfam',
			'Detailaccosocindi' => 'nataccosocindi',
			'Detaildifdisp' => 'difdisp',
			'Detailnatmob' => 'natmob',
			'Detaildiflog' => 'diflog'
		);
		
		var $specialHasMany58 = array(
			'Detaildifsoc' => 'difsoc',
			'Detailaccosocfam' => 'nataccosocfam',
			'Detailaccosocindi' => 'nataccosocindi',
			'Detaildifdisp' => 'difdisp',
			'Detailnatmob' => 'natmob',
			'Detaildiflog' => 'diflog',
			'Detailmoytrans' => 'moytrans',
			'Detaildifsocpro' => 'difsocpro',
			'Detailprojpro' => 'projpro',
			'Detailfreinform' => 'freinform',
			'Detailconfort' => 'confort'
		);

		// FIXME: dans les modèles ?
		var $valuesNone = array(
			'Detaildifsoc' => '0401',
			'Detailaccosocfam' => null,
			'Detailaccosocindi' => null,
			'Detaildifdisp' => '0501',
			'Detailnatmob' => '2504',
			'Detaildiflog' => '1001'
		);

		// FIXME: dans les modèles ?
		var $valuesNone58 = array(
			'Detaildifsoc' => '0401',
			'Detailaccosocfam' => null,
			'Detailaccosocindi' => null,
			'Detaildifdisp' => '0501',
			'Detailnatmob' => '2504',
			'Detaildiflog' => '1001',
			'Detailmoytrans' => null,
			'Detaildifsocpro' => null,
			'Detailprojpro' => null,
			'Detailfreinform' => null,
			'Detailfconfort' => null
		);

		var $commeDroit = array(
			'add' => 'Dsps:edit'
		);

		/**
		*
		*/

		function beforeFilter() {
			$return = parent::beforeFilter();
			
			//$cg = 'cg58';
			$cg = Configure::read('nom_form_ci_cg');
			
			if ($cg == 'cg58') {
				$this->valuesNone = $this->valuesNone58;
				$this->specialHasMany = $this->specialHasMany58;
			}

			$options = $this->Dsp->enums();

			foreach( array_keys( $this->specialHasMany ) as $model ) {
				$options = Set::merge( $options, $this->Dsp->{$model}->enums() );
			}
			
			$options = $this->Dsp->filterOptions( $cg, $options );
			$this->set( 'options', $options );
			$this->set( 'cg', $cg );

			return $return;
		}

        /**
        *
        */

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        public function view( $id = null ) {
			$dsp = $this->Dsp->findByPersonneId( $id );

			if( empty( $dsp ) ) {
                //Ajout Arnaud suite aux problemes de perf
				$dsp = $this->Dsp->Personne->find(
				    'first',
				    array(
					'conditions' => array(
					    'Personne.id' => $id
					),
					'recursive' => -1
				    )
				);
                $this->assert( !empty( $dsp ), 'invalidParameter' );
				//Fin ajout Arnaud suite aux problemes de perf
			}

			$this->set( 'personne_id', $id );
			if (isset($dsp['Dsp']['id'])) $dsp_id = $dsp['Dsp']['id'];
			else $dsp_id = 0;
			$rev = false;
			$dspRev = $this->DspRev->find('first', array('conditions'=>array('dsp_id'=>$dsp_id), 'order'=>array('id ASC, created ASC')));
			if (!empty($dspRev)) {
				$rev = true;
				foreach ($dspRev as $Model=>$values) {
					$dsp[substr($Model,0,-3)] = $dspRev[$Model];
				}
			}
			$this->set( 'dsp', $dsp );
			$this->set('rev', $rev);
        }
        
        /**
        *
        */

        public function histo( $id = null ) {
			$dsp = $this->Dsp->findByPersonneId( $id );
			if( empty( $dsp ) ) {
				$dsp = $this->Dsp->Personne->findById( $id );
			}
			$this->assert( !empty( $dsp ), 'invalidParameter' );
			$histos = $this->paginate('DspRev', array('DspRev.dsp_id'=>$dsp['Dsp']['id']));
			if (!empty($histos)) {
				$diff=array();
				for ($i=0;$i<count($histos)-1;$i++) {
					$cpt=0;
					foreach($histos[$i] as $Model=>$values) {
						if ($Model!='DspRev') {
							foreach($histos[$i][$Model] as $key1=>$value1) {
								$histos[$i][$Model][$key1]=Set::remove($histos[$i][$Model][$key1],"id");
								$histos[$i][$Model][$key1]=Set::remove($histos[$i][$Model][$key1],"dsp_rev_id");
							}
							foreach($histos[$i+1][$Model] as $key2=>$value2) {
								$histos[$i+1][$Model][$key2]=Set::remove($histos[$i+1][$Model][$key2],"id");
								$histos[$i+1][$Model][$key2]=Set::remove($histos[$i+1][$Model][$key2],"dsp_rev_id");
							}
						}
						$diff[$Model] = Set::diff($histos[$i][$Model], $histos[$i+1][$Model]);
						$diff[$Model] = Set::remove($diff[$Model],'id');
						if (isset($diff[$Model]['created'])) $diff[$Model] = Set::remove($diff[$Model],'created');
						if (isset($diff[$Model]['modified'])) $diff[$Model] = Set::remove($diff[$Model],'modified');
						if ( $Model!='DspRev' && !empty($histos[$i][$Model]) && !empty($diff[$Model]) ) {
							foreach($histos[$i][$Model] as $key1=>$value1) {
								foreach($histos[$i+1][$Model] as $key2=>$value2) {
									$compare = Set::diff($value1,$value2);
									if (empty($compare) && ($key1!=$key2)) {
										$cpt--;
									}
								}
							}
						}
					}
					foreach($diff as $Model=>$values) {
						$cpt+=count($values);
					}
					$histos[$i] = Set::insert($histos[$i], 'diff', $cpt);
				}
			}
			$histos[count($histos)-1]['diff'] = 0;
			$this->set( 'dsp', $dsp );
			$this->set( 'histos', $histos );
			$this->set( 'personne_id', $id );
        }
        
        /**
        *
        */

        public function revertTo( $id=null ) {
			$dsprevs = $this->DspRev->findById($id);
			$this->DspRev->id = $id;
			$this->DspRev->saveField('modified', date('Y-m-d'));
			$this->data = $this->Dsp->findByPersonneId( $dsprevs['DspRev']['personne_id'] );
			$dsp_id = $this->data['Dsp']['id'];
			foreach($dsprevs as $dsprev=>$values) {
				$this->data[substr($dsprev,0,-3)] = $dsprevs[$dsprev];
			}
			$this->data['Dsp']['id'] = $dsp_id;
			$this->data = Set::remove($this->data,'Dsp.created');
			$this->data = Set::remove($this->data,'Dsp.modified');
        	$this->edit( $dsprevs['DspRev']['personne_id'], $id);
        }
        
        /**
        *
        */

        public function view_revs( $id=null ) {
			$dsprevs = $this->DspRev->findById($id);
			$dsp = $this->Dsp->findByPersonneId( $dsprevs['DspRev']['personne_id'] );
			if( empty( $dsp ) ) {
				$dsp = $this->Dsp->Personne->findById( $dsprevs['DspRev']['personne_id'] );
			}
			foreach($dsprevs as $dsprev=>$values) {
				$dsp[substr($dsprev,0,-3)] = $dsprevs[$dsprev];
			}
			$this->assert( !empty( $dsp ), 'invalidParameter' );
			$this->set( 'dsp', $dsp );
			$this->set( 'personne_id', $dsprevs['DspRev']['personne_id'] );
			$this->render('view');
        }
        
        /**
        *
        */

        public function view_diff( $id = null ) {
			$dsprevact = $this->DspRev->findById( $id );
			$this->assert( !empty( $dsprevact ), 'invalidParameter' );

			$dsprevold = $this->DspRev->find(
				'first',
				array(
					'conditions'=>array(
						'created <='=>$dsprevact['DspRev']['created'],
						'id <'=>$dsprevact['DspRev']['id']
					), 
					'order' => array('created DESC', 'id DESC')
				)
			);
			$this->assert( !empty( $dsprevold ), 'invalidParameter' );
			
			foreach($dsprevact as $Model=>$values) {
				if ($Model!='DspRev') {
					foreach($dsprevact[$Model] as $key1=>$value1) {
						$dsprevact[$Model][$key1]=Set::remove($dsprevact[$Model][$key1],"id");
						$dsprevact[$Model][$key1]=Set::remove($dsprevact[$Model][$key1],"dsp_rev_id");
					}
				}
			}
			
			foreach($dsprevold as $Model=>$values) {
				if ($Model!='DspRev') {
					foreach($dsprevold[$Model] as $key2=>$value2) {
						$dsprevold[$Model][$key2]=Set::remove($dsprevold[$Model][$key2],"id");
						$dsprevold[$Model][$key2]=Set::remove($dsprevold[$Model][$key2],"dsp_rev_id");
					}
				}
			}
			
			foreach($dsprevact as $Model=>$values) {
				$diff[$Model] = Set::diff($dsprevact[$Model], $dsprevold[$Model]);
				$diff[$Model] = Set::remove($diff[$Model],'id');
				if (isset($diff[$Model]['created'])) $diff[$Model] = Set::remove($diff[$Model],'created');
				if (isset($diff[$Model]['modified'])) $diff[$Model] = Set::remove($diff[$Model],'modified');
				if ( $Model!='DspRev' && !empty($dsprevact[$Model]) && !empty($diff[$Model]) ) {
					foreach($dsprevact[$Model] as $key1=>$value1) {
						foreach($dsprevold[$Model] as $key2=>$value2) {
							$compare = Set::diff($value1,$value2);
							if (empty($compare) && ($key1!=$key2)) {
								$diff[$Model] = Set::remove($diff[$Model],$key1);
							}
						}
					}
				}
				if (isset($diff[$Model]['id'])) $diff[$Model] = Set::remove($diff[$Model],'id');
				if (isset($diff[$Model]['created'])) $diff[$Model] = Set::remove($diff[$Model],'created');
				if (isset($diff[$Model]['modified'])) $diff[$Model] = Set::remove($diff[$Model],'modified');
				if (empty($diff[$Model])) $diff = Set::remove($diff, $Model);
			}

			$this->set( 'personne', $this->findPersonne(Set::classicExtract( $dsprevact, 'DspRev.personne_id' )) );
			$this->set( 'dsprevact', $dsprevact );
			$this->set( 'dsprevold', $dsprevold );
			$this->set( 'diff', $diff );
			
			$this->set( 'personne_id', Set::classicExtract( $dsprevact, 'DspRev.personne_id' ) );
        }
        
        /**
        *
        */
        
        function findPersonne($personne_id) {
        	return $this->Dsp->Personne->find(
        		'first',
        		array(
        			'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom'
        			),
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'recursive' => -1
				)
        	);
        }

		/**
		*
		*/

		function _add_edit( $personne_id = null, $version_id = null ) {
			// Début de la transaction
			$this->Dsp->begin();

			// On cherche soit la dsp directement, soit la personne liée
			$dsp = null;
			if ( ( ( $this->action == 'edit' || $this->action == 'revertTo' ) ) && !empty( $personne_id ) ) {
				if ( empty( $version_id ) ) {
					$dsp = $this->Dsp->findByPersonneId($personne_id);
					if( empty( $dsp ) ) {
						$dsp = $this->Dsp->Personne->findById( $personne_id );
					}
				}
				else {
					$dsprevs = $this->DspRev->findById($version_id);
					$dsp = $this->Dsp->findByPersonneId( $personne_id );
					$dsp_id = $dsp['Dsp']['id'];
					foreach($dsprevs as $dsprev=>$values) {
						$dsp[substr($dsprev,0,-3)] = $dsprevs[$dsprev];
					}
					$dsp['Dsp']['id'] = $dsp_id;
				}
			}
			else if ( ( $this->action == 'add' ) && !empty( $personne_id ) ) {
				$dsp = $this->Dsp->Personne->findById( $personne_id, null, null, 1 );
			}

			// Vérification indirecte de l'id
			$this->assert( !empty( $dsp ), 'invalidParameter' );

			// Assertion: on doit pouvoir mettre un jeton sur le dossier
			$dossier_id = $this->Dsp->Personne->dossierId( Set::classicExtract( $dsp, 'Personne.id' ) );
			$hasJeton = $this->Jetons->get( $dossier_id );
			$this->assert( $hasJeton, 'lockedDossier' );

			// Tentative d'enregistrement
			if( !empty( $this->data ) ) {
				$success = true;

				// Nettoyage des Dsp
				$keys = array_keys( $this->Dsp->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );

				$this->data['Dsp'] = Set::merge( $defaults, $this->data['Dsp'] );
				foreach( $this->data['Dsp'] as $key => $value ) {
					if( strlen( trim( $value ) ) == 0 ) {
						$this->data['Dsp'][$key] = null;
					}
				}

				// Modèles liés, début hasMany spéciaux
				$deleteConditions = array();
				foreach( $this->specialHasMany as $model => $checkbox ) {
					$values = Set::classicExtract( $this->data, "{$model}" );

					if( isset( $this->valuesNone[$model] ) ) {
						$tmpValues = Set::extract( $values, "/{$this->specialHasMany['Detaildifsoc']}" );
						$cKey = array_search( $this->valuesNone[$model], $tmpValues );
						$tmpValues = $values;
						if( $cKey !== false ) {
							unset( $tmpValues[$cKey] );// FIXME
							$ids = Set::extract( $tmpValues, '/id' );
							foreach( $ids as $id ) {
								$deleteConditions[$model][] = "{$model}.id = {$id}";
							}
						}
						// FIXME: s'assurer que les autres soient à 0 ?
					}


					foreach( $values as $key => $value ) {
						$val = Set::classicExtract( $value, $checkbox );
						if( empty( $val ) ) {
							if( isset( $value['id'] ) ) {
								$deleteConditions[$model][] = "{$model}.id = {$value['id']}";
							}
							unset( $this->data[$model][$key] );
						}
					}
				}

				foreach( $deleteConditions as $model => $values ) {
					if( !empty( $values ) ) {
						$this->Dsp->{$model}->deleteAll( array( 'or' => $values ) );
					}
				}
				// fin hasMany spéciaux

				$dsp_id = Set::classicExtract( $this->data, 'Dsp.id' );
				$this->data = Set::filter( $this->data );
				
				$data2 = null;

				if( $success = $this->Dsp->saveAll( $this->data, array( 'atomic' => false, 'validate' => 'first' ) ) && $success ) {
					if( $success ) {
					
						foreach($this->data as $Model=>$values) {
							$data2[$Model."Rev"] = $this->data[$Model];
	
							if ($Model!='Dsp' && $Model!='Personne') {
								foreach($data2[$Model."Rev"] as $key=>$value) {
									if (isset($data2[$Model."Rev"][$key]['dsp_id']))
										$data2[$Model."Rev"][$key]['dsp_rev_id'] = $data2[$Model."Rev"][$key]['dsp_id'];
									$data2 = Set::remove($data2,$Model."Rev.".$key.".dsp_id");
									$data2 = Set::remove($data2,$Model."Rev.".$key.".id");
								}
							}
						}
						$data2['DspRev']['dsp_id'] = $this->Dsp->id;
						$data2 = Set::remove($data2,'DspRev.id');
						
						$this->DspRev->saveAll($data2, array( 'atomic' => false, 'validate' => 'first' ));
						
						$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
						// On enlève le jeton du dossier
						$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) ); // FIXME: if -> error
						// Fin de la transaction
						$this->Dsp->commit();
						$this->redirect( array( 'action' => 'histo', Set::classicExtract( $this->data, 'Dsp.personne_id' ) ) );
					}
					else {
						$this->Session->setFlash( __( 'Erreur lors de l\'enregistrement', true ), 'flash/error' );
						$this->Dsp->rollback();
					}
				}
			}
			// Affectation au formulaire
			else if( $this->action == 'edit' ) {
				$this->data = $dsp;
			}

			// Fin de la transaction
			$this->Dsp->commit();

			// Affectation à la vue
			$this->set( 'dsp', $dsp );
			$this->set( 'personne_id', $dsp['Dsp']['personne_id'] );
            $this->render( $this->action, null, '_add_edit' );
		}
	}
?>
