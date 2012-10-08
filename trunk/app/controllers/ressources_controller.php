<?php
	/**
	 * Code source de la classe RessourcesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe RessourcesController permet de gérer les ressources d'un allocataire.
	 *
	 * @package app.controllers
	 */
	class RessourcesController extends AppController
	{
		public $name = 'Ressources';

		public $uses = array( 'Ressource', 'Option', 'Personne', 'Ressourcemensuelle', 'Detailressourcemensuelle' );

		public $components = array( 'Jetons2' );

		public $commeDroit = array(
			'view' => 'Ressources:index',
			'add' => 'Ressources:edit'
		);

		/**
		 *
		 */
		public function beforeFilter() {
			$return = parent::beforeFilter();
			$this->set( 'natress', $this->Option->natress() );
			$this->set( 'abaneu', $this->Option->abaneu() );
			return $return;
		}

		/**
		 *
		 */
		public function index( $personne_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );
			$ressources = $this->Ressource->find(
					'all', array(
				'conditions' => array(
					'Ressource.personne_id' => $personne_id
				),
				'contain' => array(
					'Ressourcemensuelle' => array(
						'Detailressourcemensuelle'
					)
				)
					)
			);

			foreach( $ressources as $i => $ressource ) {
				$ressources[$i]['Ressource']['avg'] = $this->Ressource->moyenne( $ressource );
			}
			$this->set( 'ressources', $ressources );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 *
		 */
		public function view( $ressource_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $ressource_id ), 'invalidParameter' );

			$ressource = $this->Ressource->find(
					'first', array(
				'conditions' => array(
					'Ressource.id' => $ressource_id
				),
				'contain' => array(
					'Ressourcemensuelle' => array(
						'Detailressourcemensuelle'
					)
				)
					)
			);
			$this->assert( !empty( $ressource ), 'invalidParameter' );

			$ressource['Ressource']['avg'] = $this->Ressource->moyenne( $ressource );

			$this->set( 'ressource', $ressource );
			$this->set( 'personne_id', $ressource['Ressource']['personne_id'] );
			$this->set( 'urlmenu', '/ressources/index/'.$ressource['Ressource']['personne_id'] );
		}

		/**
		 *
		 */
		public function add( $personne_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			$qd_personne = array(
				'conditions' => array(
					'Personne.id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$personne = $this->Personne->find( 'first', $qd_personne );
			$this->assert( !empty( $personne ), 'invalidParameter' );

			$dossier_id = $this->Personne->dossierId( $personne_id );

			$this->Jetons2->get( $dossier_id );

			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				$this->Ressource->begin();

				$this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];
				$this->Ressource->set( $this->data['Ressource'] );

				$validates = $this->Ressource->validates();
				if( isset( $this->data['Ressourcemensuelle'] ) && isset( $this->data['Detailressourcemensuelle'] ) ) {
					$validates = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
					$validates = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
				}

				if( $validates ) {
					$saved = $this->Ressource->save( $this->data );
					if( isset( $this->data['Ressourcemensuelle'] ) ) {
						foreach( $this->data['Ressourcemensuelle'] as $index => $dataRm ) {
							$dataRm['ressource_id'] = $this->Ressource->id;
							$this->Ressourcemensuelle->create();
							$saved = $this->Ressourcemensuelle->save( $dataRm ) && $saved;
							if( isset( $this->data['Detailressourcemensuelle'] ) ) {
								$dataDrm = $this->data['Detailressourcemensuelle'][$index];
								$dataDrm['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
								$this->Detailressourcemensuelle->create();
								$saved = $this->Detailressourcemensuelle->save( $dataDrm ) && $saved;
							}
						}
					}
					if( $saved ) {
						$this->Ressource->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Ressource->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Ressource->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$qd_ressource = array(
				'conditions' => array(
					'Ressource.personne_id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$ressource = $this->Ressource->find( 'first', $qd_ressource );

			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		 *
		 *
		 *
		 */
		public function edit( $ressource_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $ressource_id ), 'invalidParameter' );

			$qd_ressource = array(
				'conditions' => array(
					'Ressource.id' => $ressource_id
				),
				'fields' => null,
				'order' => null,
				'contain' => array(
					'Ressourcemensuelle' => array(
						'Detailressourcemensuelle'
					)
				)
			);
			$ressource = $this->Ressource->find( 'first', $qd_ressource );

			$this->assert( !empty( $ressource ), 'invalidParameter' );

			$dossier_id = $this->Ressource->dossierId( $ressource_id );

			$this->Jetons2->get( $dossier_id );

			if( !empty( $this->data ) ) {
				$this->Ressource->begin();

				$this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];

				$this->Ressource->set( $this->data );

				$validates = $this->Ressource->validates();
				if( array_key_exists( 'Ressourcemensuelle', $this->data ) ) {
					$validates = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
					if( array_key_exists( 'Detailressourcemensuelle', $this->data ) ) {
						$validates = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
					}
				}

				if( $validates ) {
					$saved = $this->Ressource->save( $this->data );
					if( !$this->data['Ressource']['topressnul'] ) {
						if( array_key_exists( 'Ressourcemensuelle', $this->data ) ) {
							foreach( $this->data['Ressourcemensuelle'] as $index => $dataRm ) {
								$this->Ressourcemensuelle->create();
								$dataRm['ressource_id'] = $this->Ressource->id;
								$saved = $this->Ressourcemensuelle->save( $dataRm ) && $saved;

								if( array_key_exists( 'Detailressourcemensuelle', $this->data ) ) {
									$dataDrm = $this->data['Detailressourcemensuelle'][$index];
									$dataDrm['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
									$this->Detailressourcemensuelle->create();
									$saved = $this->Detailressourcemensuelle->save( $dataDrm ) && $saved;
								}
							}
						}
					}
					else {
						$rm = $this->Ressourcemensuelle->find(
								'list', array(
							'fields' => array( 'Ressourcemensuelle.id' ),
							'conditions' => array( 'Ressourcemensuelle.ressource_id' => $this->Ressource->id )
								)
						);
						if( !empty( $rm ) ) {
							$saved = $this->Detailressourcemensuelle->deleteAll(
											array(
												'Detailressourcemensuelle.ressourcemensuelle_id' => $rm
											)
									) && $saved;

							$saved = $this->Ressourcemensuelle->deleteAll(
											array(
												'Ressourcemensuelle.id' => $rm
											)
									) && $saved;
						}
					}

					$saved = $this->Ressource->refresh( $ressource['Ressource']['personne_id'] ) && $saved;

					if( $saved ) {
						$this->Ressource->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $ressource['Ressource']['personne_id'] ) );
					}
					else {
						$this->Ressource->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Ressource->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				//INFO !!!! ça marche, mais c'est un hack
				$ressource['Detailressourcemensuelle'] = array( );
				foreach( $ressource['Ressourcemensuelle'] as $kRm => $rm ) {
					if( isset( $rm['Detailressourcemensuelle'][0] ) ) {
						$ressource['Detailressourcemensuelle'][$kRm] = $rm['Detailressourcemensuelle'][0];
					}
					unset( $ressource['Ressourcemensuelle'][$kRm]['Detailressourcemensuelle'] );
				}

				$this->data = $ressource;
			}

			$this->Ressource->commit();
			$this->set( 'personne_id', $ressource['Ressource']['personne_id'] );
			$this->set( 'urlmenu', '/ressources/index/'.$ressource['Ressource']['personne_id'] );
			$this->render( $this->action, null, 'add_edit' );
		}

	}
?>