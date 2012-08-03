<?php
	/*
		http://localhost/adullact/webrsa/trunk/dspps/view/174537
		http://localhost/adullact/webrsa/trunk/dsps/view/174537
	*/
	class DspsController extends AppController
	{
		public $name = 'Dsps';

		public $helpers = array( 'Xform', 'Xhtml', 'Dsphm', 'Default2', 'Fileuploader', 'Search', 'Csv' );
		public $uses = array('Dsp', 'DspRev');
		public $components = array( 'Jetons', 'Default', 'Fileuploader' );


		public $paginate = array(
			'limit' => 10,
			'order' => array('DspRev.created' => 'desc', 'DspRev.id' => 'desc')
		);

		public $specialHasMany = array(
			'Detaildifsoc' => 'difsoc',
			'Detailaccosocfam' => 'nataccosocfam',
			'Detailaccosocindi' => 'nataccosocindi',
			'Detaildifdisp' => 'difdisp',
			'Detailnatmob' => 'natmob',
			'Detaildiflog' => 'diflog'
		);

		public $specialHasMany58 = array(
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
		public $valuesNone = array(
			'Detaildifsoc' => '0401',
			'Detailaccosocfam' => null,
			'Detailaccosocindi' => null,
			'Detaildifdisp' => '0501',
			'Detailnatmob' => '2504',
			'Detaildiflog' => '1001'
		);

		// FIXME: dans les modèles ?
		public $valuesNone58 = array(
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

		public $commeDroit = array(
			'findPersonne' => 'Dsps:view'
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		
		public function __construct() {
			$this->components = Set::merge( $this->components, array( 'Gestionzonesgeos', 'Prg' => array( 'actions' => array( 'index' ) ) ) );
			parent::__construct();
		}		
		
		
		
		
		/**
		*
		*/

		function beforeFilter() {
			$return = parent::beforeFilter();

			$cg = Configure::read('nom_form_ci_cg');

			if ($cg == 'cg58') {
				$this->valuesNone = $this->valuesNone58;
				$this->specialHasMany = $this->specialHasMany58;
			}

			$options = $this->Dsp->enums();

			foreach( array_keys( $this->specialHasMany ) as $model ) {
				$options = Set::merge( $options, $this->Dsp->{$model}->enums() );
			}

			$options['Coderomesecteurdsp66'] = array();
			$options['Coderomemetierdsp66'] = array();
			if ( Configure::read( 'Cg.departement' ) == 66 ) {
				$options['Coderomesecteurdsp66'] = $this->Dsp->Libsecactderact66Secteur->find(
					'list',
					array(
						'contain' => false,
						'order' => array( 'Libsecactderact66Secteur.code' )
					)
				);
// 				foreach( $codesromesecteursdsps66 as $coderomesecteurdsp66 ) {
// 					$options['Coderomesecteurdsp66'][$coderomesecteurdsp66['Libsecactderact66Secteur']['id']] = $coderomesecteurdsp66['Libsecactderact66Secteur']['code'].'. '.$coderomesecteurdsp66['Libsecactderact66Secteur']['name'];
// 				}

				$codesromemetiersdsps66 = $this->Dsp->Libderact66Metier->find(
					'all',
					array(
						'contain' => false,
						'order' => array( 'Libderact66Metier.code' )
					)
				);
				foreach( $codesromemetiersdsps66 as $coderomemetierdsp66 ) {
					$options['Coderomemetierdsp66'][$coderomemetierdsp66['Libderact66Metier']['coderomesecteurdsp66_id'].'_'.$coderomemetierdsp66['Libderact66Metier']['id']] = $coderomemetierdsp66['Libderact66Metier']['code'].'. '.$coderomemetierdsp66['Libderact66Metier']['name'];
				}
			}

			$options = $this->Dsp->filterOptions( $cg, $options );
// 			debug( $options );
			$this->set( 'options', $options );
			$this->set( 'cg', $cg );

			return $return;
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		*/

		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		* FIXME: traiter les valeurs de retour
		*/

		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		*   Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
		*/

		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		*   Téléchargement des fichiers préalablement associés à un traitement donné
		*/

		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		*   Fonction permettant d'accéder à la page pour lier les fichiers à l'Orientation
		*/

		public function filelink( $id ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array();

			$dsprev = $this->DspRev->find(
				'first',
				array(
					'conditions' => array(
						'DspRev.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$optionsrevs = $this->DspRev->allEnumlists();

			$personne_id = $dsprev['DspRev']['personne_id'];
			$dsp_id = $dsprev['DspRev']['dsp_id'];

			$dossier_id = $this->Dsp->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Dsp->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Dsp->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'histo', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$saved = $this->DspRev->updateAll(
					array( 'DspRev.haspiecejointe' => '\''.$this->data['DspRev']['haspiecejointe'].'\'' ),
					array(
						'"DspRev"."personne_id"' => $personne_id,
						'"DspRev"."dsp_id"' => $dsp_id,
						'"DspRev"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "DspRev.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Dsp->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'dsps','action' => 'histo', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Dsp->commit();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'optionsrevs', 'dsprev' ) );
			$this->set( 'urlmenu', '/dsps/histo/'.$personne_id );
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
			$this->Dsp->forceVirtualFields = true;
			$dsp = $this->Dsp->find(
				'first',
				array(
					'conditions' => array(
						'Dsp.personne_id' => $id
					),
					'contain' => array(
						'Personne',
						'Libderact66Metier',
						'Libsecactderact66Secteur',
						'Libactdomi66Metier',
						'Libsecactdomi66Secteur',
						'Libemploirech66Metier',
						'Libsecactrech66Secteur',
						'Detaildifsoc',
						'Detailaccosocfam',
						'Detailaccosocindi',
						'Detaildifdisp',
						'Detailnatmob',
						'Detaildiflog',
						'Detailmoytrans',
						'Detaildifsocpro',
						'Detailprojpro',
						'Detailfreinform',
						'Detailconfort'
					)
				)
			);
			$personne = $this->Dsp->Personne->findById( $id, null, null, -1 );
			$this->set( 'personne', $personne );
			$this->set( 'personne_id', $id );
			if ( isset( $dsp['Dsp']['id'] ) ) {
				$dsp_id = $dsp['Dsp']['id'];
			}
			else {
				$dsp_id = 0;
			}
			$rev = false;

			$dspRev = $this->DspRev->find(
				'first',
				array(
					'conditions' => array(
						'dsp_id' => $dsp_id
					),
					'order' => array(
						'DspRev.id ASC',
						'DspRev.created ASC'
					)
				)
			);
			if ( !empty( $dspRev ) ) {
				$rev = true;
			}
			$this->set( 'dsp', $dsp );
			$this->set( 'rev', $rev );
		}

		/**
		*
		*/

		public function histo( $id = null ) {

			$dsp = $this->Dsp->Personne->find(
				'first',
				array(
					'conditions' => array(
						'Personne.id' => $id
					),
					'contain' => array(
						'Dsp' => array(
							'Detaildifsoc',
							'Detailaccosocfam',
							'Detailaccosocindi',
							'Detaildifdisp',
							'Detailnatmob',
							'Detaildiflog',
							'Detailmoytrans',
							'Detaildifsocpro',
							'Detailprojpro',
							'Detailfreinform',
							'Detailconfort'
						)
					)
				)
			);
			$this->assert( !empty( $dsp ), 'invalidParameter' );

			$this->DspRev->forceVirtualFields = true;
			$this->paginate['contain'] = array(
				'Personne',
				'DetaildifsocRev',
				'DetailaccosocfamRev',
				'DetailaccosocindiRev',
				'DetaildifdispRev',
				'DetailnatmobRev',
				'DetaildiflogRev',
				'DetailmoytransRev',
				'DetaildifsocproRev',
				'DetailprojproRev',
				'DetailfreinformRev',
				'DetailconfortRev',
				'Fichiermodule'
			);
			
			$histos = array();
			if( isset( $dsp['Dsp']['id'] ) && !empty( $dsp['Dsp']['id'] ) ) {
				$histos = $this->paginate(
					'DspRev',
					array(
						'DspRev.dsp_id' => $dsp['Dsp']['id']
					)
				);
			}

			if ( !empty( $histos ) ) {
				$diff=array();
				for ($i=0;$i<count($histos)-1;$i++) {
					$cpt=0;
					foreach($histos[$i] as $Model=>$values) {
						if ( $Model != 'Fichiermodule' ) {
							if ( $Model != 'DspRev' && preg_match( '/Rev$/', $Model ) ) {
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
							if ( isset( $diff[$Model]['created'] ) ) {
								$diff[$Model] = Set::remove($diff[$Model],'created');
							}
							if ( isset( $diff[$Model]['modified'] ) ) {
								$diff[$Model] = Set::remove($diff[$Model],'modified');
							}
							if ( isset( $diff[$Model]['haspiecejointe'] ) ) {
								$diff[$Model] = Set::remove($diff[$Model],'haspiecejointe');
							}
							if ( $Model != 'DspRev' && !empty( $histos[$i][$Model] ) && !empty( $diff[$Model] ) && preg_match( '/Rev$/', $Model ) ) {
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
// debug( $histos );
					}
					foreach($diff as $Model=>$values) {
						$cpt+=count($values);
					}
					$histos[$i] = Set::insert($histos[$i], 'diff', $cpt);
// debug($diff);
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
				$this->data[preg_replace( '/Rev$/', '', $dsprev )] = $dsprevs[$dsprev];
			}
			$this->data['Dsp']['id'] = $dsp_id;
			$this->data = Set::remove($this->data,'Dsp.created');
			$this->data = Set::remove($this->data,'Dsp.modified');
			$this->edit( $dsprevs['DspRev']['personne_id'], $id);
		}

		/**
		*
		*/

		public function view_revs( $id = null ) {
			$this->DspRev->forceVirtualFields = true;
			$dsprevs = $this->DspRev->find(
				'first',
				array(
					'conditions' => array(
						'DspRev.id' => $id
					),
					'contain' => array(
						'Personne',
						'Libderact66Metier',
						'Libsecactderact66Secteur',
						'Libactdomi66Metier',
						'Libsecactdomi66Secteur',
						'Libemploirech66Metier',
						'Libsecactrech66Secteur',
						'DetaildifsocRev',
						'DetailaccosocfamRev',
						'DetailaccosocindiRev',
						'DetaildifdispRev',
						'DetailnatmobRev',
						'DetaildiflogRev',
						'DetailmoytransRev',
						'DetaildifsocproRev',
						'DetailprojproRev',
						'DetailfreinformRev',
						'DetailconfortRev',
						'Fichiermodule'
					)
				)
			);

			// Retour à la liste en cas d'annulation
			if(  isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'histo', $dsprevs['DspRev']['personne_id'] ) );
			}

			$dsp = array();
			// Suppression du suffixe Rev pour utiliser la même vue que les Dsp
			foreach( $dsprevs as $key => $value ) {
				$key = preg_replace( '/Rev$/', '', $key );
				$dsp[$key] = $value;
			}

			$this->assert( !empty( $dsp ), 'invalidParameter' );

			$this->set( 'dsp', $dsp );

			$this->set( 'personne_id', $dsprevs['DspRev']['personne_id'] );
			$this->set( 'urlmenu', '/dsps/histo/'.$dsprevs['DspRev']['personne_id'] );
			$this->render('view');
		}

		/**
		*
		*/

		public function view_diff( $id = null ) {
			$this->DspRev->forceVirtualFields = true;
// 			$dsprevact = $this->DspRev->findById( $id );
			$dsprevact = $this->DspRev->find(
				'first',
				array(
					'conditions' => array(
						'DspRev.id' => $id
					),
					'contain' => array(
						'Personne',
						'Libderact66Metier',
						'Libsecactderact66Secteur',
						'Libactdomi66Metier',
						'Libsecactdomi66Secteur',
						'Libemploirech66Metier',
						'Libsecactrech66Secteur',
						'DetaildifsocRev',
						'DetailaccosocfamRev',
						'DetailaccosocindiRev',
						'DetaildifdispRev',
						'DetailnatmobRev',
						'DetaildiflogRev',
						'DetailmoytransRev',
						'DetaildifsocproRev',
						'DetailprojproRev',
						'DetailfreinformRev',
						'DetailconfortRev',
						'Fichiermodule'
					)
				)
			);
			$this->assert( !empty( $dsprevact ), 'invalidParameter' );

			$dsprevold = $this->DspRev->find(
				'first',
				array(
					'conditions'=>array(
						'DspRev.personne_id'=>$dsprevact['DspRev']['personne_id'],
						'DspRev.created <='=>$dsprevact['DspRev']['created'],
						'DspRev.id <'=>$dsprevact['DspRev']['id']
					),
					'contain' => array(
						'Personne',
						'Libderact66Metier',
						'Libsecactderact66Secteur',
						'Libactdomi66Metier',
						'Libsecactdomi66Secteur',
						'Libemploirech66Metier',
						'Libsecactrech66Secteur',
						'DetaildifsocRev',
						'DetailaccosocfamRev',
						'DetailaccosocindiRev',
						'DetaildifdispRev',
						'DetailnatmobRev',
						'DetaildiflogRev',
						'DetailmoytransRev',
						'DetaildifsocproRev',
						'DetailprojproRev',
						'DetailfreinformRev',
						'DetailconfortRev',
						'Fichiermodule'
					),
					'order' => array('DspRev.created DESC', 'DspRev.id DESC')
				)
			);
			$this->assert( !empty( $dsprevold ), 'invalidParameter' );

			// Suppression des champs de clés primaires et étrangères des résultats des Dsps actuelles
			foreach($dsprevact as $Model=>$values) {
				if ( $Model!='DspRev' && preg_match( '/Rev$/', $Model ) ) {
					foreach($dsprevact[$Model] as $key1=>$value1) {
						$dsprevact[$Model][$key1]=Set::remove($dsprevact[$Model][$key1],"id");
						$dsprevact[$Model][$key1]=Set::remove($dsprevact[$Model][$key1],"dsp_rev_id");
					}
				}
			}

			// Suppression des champs de clés primaires et étrangères des résultats des Dsps précédentes
			foreach($dsprevold as $Model=>$values) {
				if ( $Model!='DspRev' && preg_match( '/Rev$/', $Model ) ) {
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
				if ( $Model!='DspRev' && !empty($dsprevact[$Model]) && !empty($diff[$Model]) && preg_match( '/Rev$/', $Model ) ) {
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
			$this->set( 'urlmenu', '/dsps/histo/'.Set::classicExtract( $dsprevact, 'DspRev.personne_id' ) );
		}

		/**
		*
		*/

		public function findPersonne($personne_id) {
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

		protected function _add_edit( $personne_id = null, $version_id = null ) {
			// Début de la transaction
			$this->Dsp->begin();

			// Retour à la liste en cas d'annulation
			if(  isset( $this->params['form']['Cancel'] ) ) {
				if( empty( $version_id ) ){
					$this->redirect( array( 'action' => 'view', $personne_id ) );
				}
				else{
					$this->redirect( array( 'action' => 'histo', $personne_id ) );
				}
			}

			// On cherche soit la dsp directement, soit la personne liée
			$dsp = null;
			if ( ( ( $this->action == 'edit' || $this->action == 'revertTo' ) ) && !empty( $personne_id ) ) {
				if ( empty( $version_id ) ) {
					$this->Dsp->forceVirtualFields = true;
					$dsp = $this->Dsp->findByPersonneId($personne_id);
					if( empty( $dsp ) ) {
						$dsp = $this->Dsp->Personne->findById( $personne_id );
					}
				}
				else {
					$this->DspRev->forceVirtualFields = true;
					$dsprevs = $this->DspRev->find(
						'first',
						array(
							'conditions' => array(
								'DspRev.id' => $version_id
							),
							'contain' => array(
								'Personne',
								'Libderact66Metier',
								'Libsecactderact66Secteur',
								'Libactdomi66Metier',
								'Libsecactdomi66Secteur',
								'Libemploirech66Metier',
								'Libsecactrech66Secteur',
								'DetaildifsocRev',
								'DetailaccosocfamRev',
								'DetailaccosocindiRev',
								'DetaildifdispRev',
								'DetailnatmobRev',
								'DetaildiflogRev',
								'DetailmoytransRev',
								'DetaildifsocproRev',
								'DetailprojproRev',
								'DetailfreinformRev',
								'DetailconfortRev',
								'Fichiermodule'
							)
						)
					);
					$dsp_id = $dsprevs['DspRev']['dsp_id'];
					foreach( $dsprevs as $key => $value ) {
						$key = preg_replace( '/Rev$/', '', $key );
						$dsp[$key] = $value;
					}
					$dsp['Dsp']['id'] = $dsp_id;
				}
			}
			else if ( ( $this->action == 'add' ) && !empty( $personne_id ) ) {
				$this->Dsp->forceVirtualFields = true;
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

				unset( $this->data['Dsp']['haspiecejointe'] );
				if( $success = $this->Dsp->saveAll( $this->data, array( 'atomic' => false, 'validate' => 'only' ) ) && $success ) {
					if( $this->action == 'add' ){
						$success = $this->Dsp->saveAll( $this->data, array( 'atomic' => false, 'validate' => 'first' ) ) && $success;
					}
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
			// Affectation au formulaire
			else if( $this->action == 'edit' ) {
				$dsp['Dsp']['libderact66_metier_id'] = $dsp['Dsp']['libsecactderact66_secteur_id'].'_'.$dsp['Dsp']['libderact66_metier_id'];
				$dsp['Dsp']['libactdomi66_metier_id'] = $dsp['Dsp']['libsecactdomi66_secteur_id'].'_'.$dsp['Dsp']['libactdomi66_metier_id'];
				$dsp['Dsp']['libemploirech66_metier_id'] = $dsp['Dsp']['libsecactrech66_secteur_id'].'_'.$dsp['Dsp']['libemploirech66_metier_id'];
				$this->data = $dsp;
			}

			// Fin de la transaction
			$this->Dsp->commit();

			// Affectation à la vue
			$this->set( 'dsp', $dsp );
			$this->set( 'personne_id', $dsp['Dsp']['personne_id'] );
			$this->set( 'urlmenu', '/dsps/histo/'.$dsp['Dsp']['personne_id'] );
			$this->render( $this->action, null, '_add_edit' );
		}
		
		
		/**
		 * Recherche par DSPs
		 */
		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
			
			$params = $this->data;
			if( !empty( $params ) ) {
                            $wildcardKeys = array(
                                'Personne.nom',
                                'Personne.prenom',
                                'Personne.nir',
                                'Dossier.matricule',
                                'Dsp.libsecactdomi',
                                'Dsp.libactdomi',
                                'Dsp.libsecactrech',
                                'Dsp.libemploirech',
                                'Dsp.libsecactderact',
                                'Dsp.libderact'
                            );

                            $this->paginate = $this->Dsp->search(
                                $mesCodesInsee,
                                $this->Session->read( 'Auth.User.filtre_zone_geo' ),
                                $this->_wildcardKeys( $this->data, $wildcardKeys )
                            );

                            $this->paginate = $this->_qdAddFilters( $this->paginate );
                            $this->paginate['limit'] = 10;

                            $dsps = $this->paginate( 'Personne' );

                            // Les dsps que l'on a obtenus sont-ils lockés ?
                            $lockedList = $this->Jetons->lockedList( Set::extract( $dsps, '/Dossier/id' ) );

                            foreach( $dsps as $key => $dsp ) {
                                    $dsps[$key]['Dossier']['locked'] = in_array( $dsp['Dossier']['id'], $lockedList );
                            }

                            $this->set( 'dsps', $dsps );
			}
			
		}
		
		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

                        $wildcardKeys = array(
                            'Personne.nom',
                            'Personne.prenom',
                            'Personne.nir',
                            'Dossier.matricule',
                            'Dsp.libsecactdomi',
                            'Dsp.libactdomi',
                            'Dsp.libsecactrech',
                            'Dsp.libemploirech',
                            'Dsp.libsecactderact',
                            'Dsp.libderact'
                        );
 
			$querydata = $this->Dsp->search(
                            $mesCodesInsee,
                            $this->Session->read( 'Auth.User.filtre_zone_geo' ),
                            $this->_wildcardKeys( Xset::bump( $this->params['named'], '__' ), $wildcardKeys )
                        );
			unset( $querydata['limit'] );

                        $dsps = $this->Dsp->Personne->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'dsps' ) );
		}
		
		
	}
?>