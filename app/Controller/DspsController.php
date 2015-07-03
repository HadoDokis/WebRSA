<?php
	/**
	 * Code source de la classe DspsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe DspsController ...
	 *
	 * @package app.Controller
	 */
	class DspsController extends AppController
	{
		public $name = 'Dsps';

		public $helpers = array(
			'Xform',
			'Xhtml',
			'Dsphm',
			'Default2',
			'Fileuploader',
			'Search',
			'Csv',
			'Romev3',
			'Default3' => array(
//				'className' => 'Default.DefaultDefault'
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			)
		);

		public $uses = array( 'Dsp', 'DspRev', 'Option', 'Familleromev3', 'Catalogueromev3' );

		public $components = array(
			'Jetons2',
			'Default',
			'Fileuploader',
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'Search.SearchPrg' => array(
				'actions' => array( 'index' )
			),
			'DossiersMenus'
		);

		public $paginate = array(
			'limit' => 10,
			'order' => array( 'DspRev.created' => 'desc', 'DspRev.id' => 'desc' )
		);

		public $commeDroit = array(
			'findPersonne' => 'Dsps:view'
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'download' => 'read',
			'edit' => 'update',
			'exportcsv' => 'read',
			'filelink' => 'read',
			'fileview' => 'read',
			'findPersonne' => 'read',
			'histo' => 'read',
			'index' => 'read',
			'revertTo' => 'update',
			'view' => 'read',
			'view_diff' => 'read',
			'view_revs' => 'read',
		);

		public $wildcardKeys = array(
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

		/**
		 * Liste des alias pouvant être triés dans la recherche par Dsps.
		 *
		 * @var array
		 */
		public $sortableModels = array(
			'Personne',
			'Foyer',
			'Dossier',
			'Prestation',
			'Adressefoyer',
			'Adresse',
			'Situationdossierrsa',
			'PersonneReferent',
			'Structurereferenteparcours'
		);

		/**
		 *
		 */
		public function beforeFilter() {
			$return = parent::beforeFilter();

			$this->set( 'cg', Configure::read( 'nom_form_ci_cg' ) ); // FIXME

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
		public function filelink( $id ) {
            $this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );

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

			$optionsrevs = (array)Hash::get( $this->DspRev->enums(), 'DspRev' );

			$personne_id = $dsprev['DspRev']['personne_id'];
			$dsp_id = $dsprev['DspRev']['dsp_id'];

			$dossier_id = $this->Dsp->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'histo', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Dsp->begin();

				$saved = $this->DspRev->updateAllUnBound(
						array( 'DspRev.haspiecejointe' => '\''.$this->request->data['DspRev']['haspiecejointe'].'\'' ), array(
					'"DspRev"."personne_id"' => $personne_id,
					'"DspRev"."dsp_id"' => $dsp_id,
					'"DspRev"."id"' => $id
						)
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "DspRev.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Dsp->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
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
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $id ) ) );

			$this->_setEntriesAncienDossier( $id, 'Dsp' );

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

			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $id
				),
				'fields' => array(
                    $this->Dsp->Personne->sqVirtualField( 'nom_complet' )
                ),
				'order' => null,
				'recursive' => -1
			);
			$personne = $this->Dsp->Personne->find( 'first', $qd_personne );

			$this->set( 'personne', $personne );
			$this->set( 'personne_id', $id );
			if( isset( $dsp['Dsp']['id'] ) ) {
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
					),
					'recursive' => -1
				)
			);

			if( !empty( $dspRev ) ) {
				$rev = true;
			}
			$this->set( 'dsp', $dsp );
			$this->set( 'rev', $rev );
			$this->set( 'options', $this->Dsp->options( array( 'find' => false ) ) );
		}

		/**
		 * Permet de visualiser les différentes versions des DSP d'un allocataire,
		 * ainsi que le nombre de différences entre avec la version précédente.
		 */
		public function histo( $id = null ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $id ) ) );

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

			$query = $this->DspRev->getViewQuery();
			$query['conditions'] = array( 'DspRev.personne_id' => $id );
			$query['order'] = array( 'DspRev.created DESC', 'DspRev.id DESC' );

			$histos = $this->DspRev->find( 'all', $query );

			$count = count( $histos );
			$prev = array();
			for( $i = $count - 1 ; $i >= 0 ; $i-- ) {
				$diff = 0;

				if( $i !== $count - 1 ) {
					$delta = $this->DspRev->getDiffs( $prev, $histos[$i] );
					$diff = count( Hash::flatten( $delta ) );
				}

				$prev = $histos[$i];
				$histos[$i]['diff'] = $diff;
			}

			$this->set( array( 'dsp' => $dsp, 'histos' => $histos, 'personne_id' => $id ) );
		}

		/**
		 * Permet d'ajouter une nouvelle version des DspRev à partir d'une copie
		 * plus ancienne.
		 *
		 * @param integer $id L'id de l'entrée des DspRev qu'il faut copier.
		 * @throws NotFoundException
		 */
		public function revertTo( $id = null ) {
			$belongsToRomev3 = $this->DspRev->belongsTo;
			foreach( $belongsToRomev3 as $alias => $params ) {
				if( strpos( $alias, 'romev3Rev' ) === false ) {
					unset( $belongsToRomev3[$alias] );
				}
			}

			$query = array(
				'conditions' => array(
					'DspRev.id' => $id
				),
				'contain' => array_merge(
					array_keys( $this->DspRev->hasMany ),
					array_keys( $this->DspRev->hasOne ),
					array_keys( $belongsToRomev3 )
				)
			);

			$record = $this->DspRev->find( 'first', $query );

			if( empty( $record ) ) {
				throw new NotFoundException();
			}

			$personne_id = Hash::get( $record, 'DspRev.personne_id' );
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$dossier_id = Hash::get( $dossierMenu, 'Dossier.id' );

			// INFO: on obtient un jeton qui sera traité dans la méthode edit()
			$this->Jetons2->get( $dossier_id );

			// Nettoyage des champs
			$fieldNames = array( 'id', 'created', 'modified' );
			foreach( $fieldNames as $fieldName ) {
				unset( $record['DspRev'][$fieldName] );
			}
			foreach( array_keys( $this->DspRev->hasMany ) as $alias ) {
				foreach( array_keys( $record[$alias] ) as $key ) {
					foreach( array_merge( $fieldNames, array( 'dsp_rev_id' ) ) as $fieldName ) {
						unset( $record[$alias][$key][$fieldName] );
					}
				}
			}

			// Remplacement des alias XxxRev en Xxx
			foreach( $record as $alias => $values ) {
				$newAlias = preg_replace( '/Rev$/', '', $alias );
				if( $alias !== $newAlias ) {
					$record[$newAlias] = $record[$alias];
					unset( $record[$alias] );
				}
			}

			$record['Dsp']['id'] = $record['Dsp']['dsp_id'];
			unset( $record['Dsp']['dsp_id'] );

			// INFO: on ne copie pas Fichiermodule
			unset( $record['Fichiermodule'] );

			// Enregistrements ROME V3 dans la table entreesromesv3, à copier
			foreach( $belongsToRomev3 as $alias => $params ) {
				$alias = preg_replace( '/Rev$/', '', $alias );
				$record['Dsp'][$params['foreignKey']] = null;
				foreach( $fieldNames as $fieldName ) {
					unset( $record[$alias][$fieldName] );
				}
			}

			$this->request->data = $record;
			$this->edit( $personne_id, $id );
		}

		/**
		 * Visualisation d'une version particulière des DspRev.
		 */
		public function view_revs( $id = null ) {
			$query = $this->DspRev->getViewQuery();
			$query['conditions'] = array( 'DspRev.id' => $id );

			$dsprevs = $this->DspRev->find( 'first', $query );

            $personne = Hash::get( $dsprevs, 'Personne.nom_complet' );
            $this->set( compact( 'personne' ) );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $dsprevs['DspRev']['personne_id'] ) ) );

			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'histo', $dsprevs['DspRev']['personne_id'] ) );
			}

			$this->_setEntriesAncienDossier( $dsprevs['DspRev']['personne_id'], 'DspRev' );

			$dsp = array( );
			// Suppression du suffixe Rev pour utiliser la même vue que les Dsp
			foreach( $dsprevs as $key => $value ) {
				$key = preg_replace( '/Rev$/', '', $key );
				$dsp[$key] = $value;
			}

			$this->assert( !empty( $dsp ), 'invalidParameter' );

			$this->set( 'dsp', $dsp );

			$this->set( 'personne_id', $dsprevs['DspRev']['personne_id'] );
			$personne = $dsprevs; // Pour récupérer les informations de la personne
			$this->set( 'personne', $personne );
			$this->set( 'urlmenu', '/dsps/histo/'.$dsprevs['DspRev']['personne_id'] );
			$this->set( 'options', $this->Dsp->options( array( 'find' => false ) ) );

			$this->render( 'view' );
		}

		/**
		 *
		 */
		public function view_diff( $id = null ) {
			$base = $this->DspRev->getViewQuery();

			$query = $base;
			$query['conditions'] = array( 'DspRev.id' => $id );

			$dsprevact = $this->DspRev->find( 'first', $query );
			$this->assert( !empty( $dsprevact ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $dsprevact['Personne']['id'] ) ) );

			// -----------------------------------------------------------------

			$query = $base;
			$query['conditions'] = array(
				'DspRev.personne_id' => $dsprevact['DspRev']['personne_id'],
				'DspRev.created <=' => $dsprevact['DspRev']['created'],
				'DspRev.id <' => $dsprevact['DspRev']['id']
			);
			$query['order'] = array( 'DspRev.created DESC', 'DspRev.id DESC' );

			$dsprevold = $this->DspRev->find( 'first', $query );
			$this->assert( !empty( $dsprevold ), 'invalidParameter' );

			$diff = $this->DspRev->getDiffs( $dsprevold, $dsprevact );

			$this->set( 'personne', $this->findPersonne( Set::classicExtract( $dsprevact, 'DspRev.personne_id' ) ) );
			$this->set( 'dsprevact', $dsprevact );
			$this->set( 'dsprevold', $dsprevold );
			$this->set( 'diff', $diff );

			if( Configure::read( 'Romev3.enabled' ) ) {
				$prefixes = $this->Dsp->prefixesRomev3;
				$suffixes = $this->Dsp->suffixesRomev3;
				$this->set( compact( 'prefixes', 'suffixes' ) );
			}

			$this->set( 'personne_id', Set::classicExtract( $dsprevact, 'DspRev.personne_id' ) );
			$this->set( 'urlmenu', '/dsps/histo/'.Set::classicExtract( $dsprevact, 'DspRev.personne_id' ) );
			$this->set( 'options', $this->Dsp->options( array( 'find' => false ) ) );
		}

		/**
		 *
		 */
		public function findPersonne( $personne_id ) {
			return $this->Dsp->Personne->find(
				'first',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
                        $this->Dsp->Personne->sqVirtualField( 'nom_complet' )
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
			$dossier_id = $this->Dsp->Personne->dossierId( $personne_id );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );

				if( empty( $version_id ) ) {
					$this->redirect( array( 'action' => 'view', $personne_id ) );
				}
				else {
					$this->redirect( array( 'action' => 'histo', $personne_id ) );
				}
			}

			// On cherche soit la dsp directement, soit la personne liée
			$dsp = null;
			if( ( ( $this->action == 'edit' || $this->action == 'revertTo' ) ) && !empty( $personne_id ) ) {
				if( empty( $version_id ) ) {

					$qd_dsp = array(
						'conditions' => array(
							'Dsp.personne_id' => $personne_id
						)
					);
					$dsp = $this->Dsp->find( 'first', $qd_dsp );
					if( empty( $dsp ) ) {
						$qd_dsp = array(
							'conditions' => array(
								'Personne.id' => $personne_id
							)
						);
						$dsp = $this->Dsp->Personne->find( 'first', $qd_dsp );
					}
				}
				else {
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
								'Fichiermodule',
								'Deractromev3Rev',
								'Deractdomiromev3Rev',
								'Actrechromev3Rev'
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
			else if( ( $this->action == 'add' ) && !empty( $personne_id ) ) {

				$qd_dsp = array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'fields' => null,
					'order' => null,
					'contain' => array(
						'Dsp'
					)
				);
				$dsp = $this->Dsp->Personne->find( 'first', $qd_dsp );

			}

			// Vérification indirecte de l'id
			$this->assert( !empty( $dsp ), 'invalidParameter' );

			// Tentative d'enregistrement
			if( !empty( $this->request->data ) ) {
				$this->Dsp->begin();

				$success = true;

				// Nettoyage des Dsp
				$keys = array_keys( $this->Dsp->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );

				$this->request->data['Dsp'] = Set::merge( $defaults, $this->request->data['Dsp'] );
				foreach( $this->request->data['Dsp'] as $key => $value ) {
					if( strlen( trim( $value ) ) == 0 ) {
						$this->request->data['Dsp'][$key] = null;
					}
				}

				// Modèles liés, début hasMany spéciaux
				$deleteConditions = array( );
				$valuesNone = $this->Dsp->getCheckboxesValuesNone();

				foreach( $this->Dsp->getCheckboxesVirtualFields() as $fieldName ) {
					list( $model, $checkbox ) = model_field( $fieldName );
					$values = Set::classicExtract( $this->request->data, "{$model}" );

					if( isset( $valuesNone[$model] ) && $valuesNone[$model] !== null ) {
						$tmpValues = Set::extract( $values, "/{$checkbox}" );
						$cKey = array_search( $valuesNone[$model], $tmpValues );
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
							unset( $this->request->data[$model][$key] );
						}
					}
				}

				foreach( $deleteConditions as $model => $values ) {
					if( !empty( $values ) ) {
						$this->Dsp->{$model}->deleteAll( array( 'or' => $values ) );
					}
				}
				// fin hasMany spéciaux

				$dsp_id = Set::classicExtract( $this->request->data, 'Dsp.id' );
				$this->request->data = Hash::filter( (array)$this->request->data );

				$data2 = null;

				unset( $this->request->data['Dsp']['haspiecejointe'] );
				if( $success = $this->Dsp->saveAll( $this->request->data, array( 'atomic' => false, 'validate' => 'only' ) ) && $success ) {
					if( $this->action == 'add' ) {
						$success = $this->Dsp->saveAll( $this->request->data, array( 'atomic' => false, 'validate' => 'first' ) ) && $success;
					}
					foreach( $this->request->data as $Model => $values ) {
						$data2[$Model."Rev"] = $this->request->data[$Model];

						if( in_array( $Model, array( 'Deractromev3', 'Deractdomiromev3', 'Actrechromev3' ) ) ) {
							$data2 = Hash::remove( $data2, $Model."Rev.id" );
						}
						else if( !in_array( $Model, array( 'Dsp', 'Personne' ) ) ) {
							foreach( $data2[$Model."Rev"] as $key => $value ) {
								if( isset( $data2[$Model."Rev"][$key]['dsp_id'] ) )
									$data2[$Model."Rev"][$key]['dsp_rev_id'] = $data2[$Model."Rev"][$key]['dsp_id'];
								$data2 = Hash::remove( $data2, $Model."Rev.".$key.".dsp_id" );
								$data2 = Hash::remove( $data2, $Model."Rev.".$key.".id" );
							}
						}
					}
					$data2['DspRev']['dsp_id'] = $this->Dsp->id;
					$data2 = Hash::remove( $data2, 'DspRev.id' );

					$this->DspRev->saveAll( $data2, array( 'atomic' => false, 'validate' => 'first' ) );

					$this->Session->setFlash( __( 'Enregistrement effectué' ), 'flash/success' );
					// Fin de la transaction
					$this->Dsp->commit();
					$this->Jetons2->release( $dossier_id );
					$this->redirect( array( 'action' => 'histo', Set::classicExtract( $this->request->data, 'Dsp.personne_id' ) ) );
				}
				else {
					$this->Session->setFlash( __( 'Erreur lors de l\'enregistrement' ), 'flash/error' );
					$this->Dsp->rollback();
				}
			}
			// Affectation au formulaire
			else if( $this->action == 'edit' ) {
				$libderact66 = $dsp['Dsp']['libsecactderact66_secteur_id'].'_'.$dsp['Dsp']['libderact66_metier_id'];
				$libactdomi66 = $dsp['Dsp']['libsecactdomi66_secteur_id'].'_'.$dsp['Dsp']['libactdomi66_metier_id'];
				$libemploirech66 = $dsp['Dsp']['libsecactrech66_secteur_id'].'_'.$dsp['Dsp']['libemploirech66_metier_id'];
				$dsp['Dsp']['libderact66_metier_id'] = preg_match('/_$/', $libderact66) ? '' : $libderact66;
				$dsp['Dsp']['libactdomi66_metier_id'] = preg_match('/_$/', $libactdomi66) ? '' : $libactdomi66;
				$dsp['Dsp']['libemploirech66_metier_id'] = preg_match('/_$/', $libemploirech66) ? '' : $libemploirech66;

				// Début ROME V3
				foreach( array( 'Deractromev3', 'Deractdomiromev3', 'Actrechromev3' ) as $alias ) {
					$dsp = $this->Dsp->{$alias}->prepareFormDataAddEdit( $dsp );
				}
				// Fin ROME V3
				$this->request->data = $dsp;
			}

			// Affectation à la vue
			$this->set( 'dsp', $dsp );
			$this->set( 'personne_id', $dsp['Dsp']['personne_id'] );
			$this->set( 'urlmenu', ( $this->action === 'edit' ? "/dsps/edit/{$dsp['Dsp']['personne_id']}" : "/dsps/histo/{$dsp['Dsp']['personne_id']}" ) );

			// Options
			$options = $this->Dsp->options();
			$this->set( compact( 'options' ) );

			// Valeurs spéciales "Aucun(e)"
			$valuesNone = $this->Dsp->getCheckboxesValuesNone();
			$checkboxes = $this->Dsp->getCheckboxes();
			$this->set( compact( 'checkboxes', 'valuesNone' ) );

			$this->render( '_add_edit' );
		}

		/**
		 * Moteur de recherche par DSPs.
		 */
		public function index() {
			$params = $this->request->data;
			if( !empty( $params ) ) {
				$query = $this->Dsp->search(
					$this->_wildcardKeys( $this->request->data, $this->wildcardKeys )
				);

				$query = $this->Gestionzonesgeos->qdConditions( $query );
				$query['conditions'][] = WebrsaPermissions::conditionsDossier();
				$query = $this->_qdAddFilters( $query );

				$query['limit'] = 10;

				$key = "{$this->name}.{$this->request->params['action']}";
				$query = ConfigurableQueryFields::getFieldsByKeys( array( "{$key}.fields", "{$key}.innerTable" ), $query );

				$this->Dsp->Personne->forceVirtualFields = true;
				$this->paginate = $query;
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
				$results = $this->paginate( $this->Dsp->Personne, array(), array(), $progressivePaginate );

				$checkboxesVirtualFields = $this->Dsp->getCheckboxesVirtualFields();
				$this->set( compact( 'results', 'checkboxesVirtualFields' ) );
			}

			$this->Gestionzonesgeos->setCantonsIfConfigured();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );

			$options = $this->Dsp->options( array( 'alias' => 'Donnees', 'allocataire' => true ) );

			$this->set( 'sortableModels', $this->sortableModels );
			$this->set( compact( 'options', 'prefixes', 'suffixes' ) );
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			$query = $this->Dsp->search(
				$this->_wildcardKeys( Hash::expand( $this->request->params['named'], '__' ), $this->wildcardKeys )
			);

			$query = $this->Gestionzonesgeos->qdConditions( $query );
			$query['conditions'][] = WebrsaPermissions::conditionsDossier();
			$query = $this->_qdAddFilters( $query );
			unset( $query['limit'] );

			$key = "{$this->name}.{$this->request->params['action']}";
			$query = ConfigurableQueryFields::getFieldsByKeys( $key, $query );

			$this->Dsp->Personne->forceVirtualFields = true;
			$dsps = $this->Dsp->Personne->find( 'all', $query );

			$qual = $this->Option->qual();
			$romev3Aliases = $this->Dsp->romev3LinkedModels;
			$romev3Fields = $this->Dsp->romev3Fields;

			$options = $this->Dsp->options( array( 'alias' => 'Donnees', 'allocataire' => true ) );
			$this->set( compact( 'options' ) );

			$checkboxesVirtualFields = $this->Dsp->getCheckboxesVirtualFields();
			$this->set( compact( 'dsps', 'qual', 'romev3Aliases', 'romev3Fields', 'options', 'checkboxesVirtualFields' ) );
			$this->layout = '';
		}

		/**
		 * Ajoute les caractères '*' devant et derrière les valeurs non
		 * vides pour les clés qui ont été définies.
		 *
		 * @param array $data Un array de profondeur quelconque venant
		 *  d'un formulaire de recherche.
		 * @param mixed $wildcardKeys Soit une liste de clés, soit la
		 *  valeur true pour appliquer sur toutes les clés.
		 * @return array
		 */
		protected function _wildcardKeys( $data, $wildcardKeys ) {
			$search = array( );
			foreach( Hash::flatten( $data ) as $key => $value ) {
				$keyNeedsWildcard = (
						$wildcardKeys === true
						|| ( is_array( $wildcardKeys ) && in_array( $key, $wildcardKeys ) )
						);
				if( $keyNeedsWildcard && (!is_null( $value ) && trim( $value ) != '' ) ) {
					$search[$key] = "*{$value}*";
				}
				else {
					$search[$key] = $value;
				}
			}
			return Hash::expand( $search );
		}
	}
?>