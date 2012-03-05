<?php
	App::import('Sanitize');

	class EtatsliquidatifsController extends AppController
	{
		public $name = 'Etatsliquidatifs';

		public $uses = array( 'Etatliquidatif', 'Parametrefinancier', 'Option' );
		public $components = array( 'Gedooo.Gedooo' );
		public $helpers = array( 'Xform', 'Locale', 'Paginator', 'Apreversement' );

		public $commeDroit = array(
			'add' => 'Etatsliquidatifs:edit'
		);

		public $aucunDroit = array( 'ajaxmontant' );

		/**
		*
		*/

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
			ini_set('default_socket_timeout', 3660);
			parent::beforeFilter();
		}

		/**
		*
		*/

		public function index() {
			$conditions = array();

			$budgetapre_id = Set::classicExtract( $this->params, 'named.budgetapre_id' );
			if( !empty( $budgetapre_id ) ) {
				$conditions["Etatliquidatif.budgetapre_id"] = $budgetapre_id;
			}

			$this->paginate[$this->modelClass] = array(
				'limit' => 10,
				'conditions' => $conditions,
				'recursive' => 0,
				'order' => array(
					'Etatliquidatif.datecloture DESC',
					'Etatliquidatif.id DESC'
				)
			);

			$etatsliquidatifs = $this->paginate( $this->modelClass );

			if( !empty( $etatsliquidatifs ) ){
				$apres_etatsliquidatifs = $this->Etatliquidatif->ApreEtatliquidatif->find(
					'all',
					array(
						'conditions' => array(
							'ApreEtatliquidatif.etatliquidatif_id' => Set::extract( $etatsliquidatifs, '/Etatliquidatif/id' )
						),
						'recursive' => -1
					)
				);
				$this->set( 'apres_etatsliquidatifs', $apres_etatsliquidatifs);
			}

			$this->set( compact( 'etatsliquidatifs' ) );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			$parametrefinancier = $this->Parametrefinancier->find( 'first' );
			if( empty( $parametrefinancier ) ) {
				$this->Session->setFlash( __( 'Impossible de créer ou de modifier un état liquidatif si les paramètres financiers ne sont pas enregistrés.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
			}

			$budgetsapres = $this->Etatliquidatif->Budgetapre->find( 'list' );
			if( empty( $budgetsapres ) ) {
				$this->Session->setFlash( __( 'Impossible de créer ou de modifier un état liquidatif s\'il n\'existe pas de budget APRE.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
			}

			if( $this->action == 'edit' ) {
				$etatliquidatif = $this->Etatliquidatif->findById( $id, null, null, -1 );
				$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );
			}
			else {
				// Aucun autre état liquidatif ouvert
				$nEtatsliquidatifs = $this->Etatliquidatif->find( 'count', array( 'conditions' => array( 'Etatliquidatif.datecloture IS NULL' ) ) );
				if( $nEtatsliquidatifs > 0 ) {
					$this->Session->setFlash( __( 'Impossible de créer un état liquidatif lorsqu\'il existe un autre état liquidatif non validé.', true ), 'flash/error' );
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
				}
			}

			if( !empty( $this->data ) ) {
				$parametrefinancier = $this->Parametrefinancier->find( 'first' );

				// Copie
				$etatliquidatifFields = array_keys( $this->Etatliquidatif->schema() );
				foreach( $parametrefinancier['Parametrefinancier'] as $field => $value ) {
					if( ( $field != 'id' ) && in_array( $field, $etatliquidatifFields ) ) {
						$this->data[$this->modelClass][$field] = $value;
					}
				}
				$this->data[$this->modelClass]['operation'] = ( ( $this->data[$this->modelClass]['typeapre'] == 'forfaitaire' ) ? $this->data[$this->modelClass]['apreforfait'] : $this->data[$this->modelClass]['aprecomplem'] );

				$this->Etatliquidatif->create( $this->data );
				if( $this->Etatliquidatif->save() ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $etatliquidatif;
			}

			$this->set( 'typesapres', array( 'forfaitaire' => 'APREs forfaitaires', 'complementaire' => 'APREs complémentaires' ) ); // TODO: enum
			$this->set( 'budgetsapres', $budgetsapres );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function selectionapres( $id = null ) {
			$etatliquidatif = $this->Etatliquidatif->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			// État liquidatif pas encore validé
			if( !empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de sélectionner des APREs pour un état liquidatif validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}


			if( !empty( $this->data ) ) {
				foreach( $this->data['Apre']['Apre'] as $i => $value ) {
					if( empty( $value ) ) {
						unset( $this->data['Apre']['Apre'][$i] );
					}
				}

				if( $this->Etatliquidatif->saveAll( $this->data ) ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );

				}
			}

			$typeapre = ( ( Set::classicExtract( $etatliquidatif, 'Etatliquidatif.typeapre' ) == 'forfaitaire' ) ? 'F' : 'C' );

			$queryData = $this->Etatliquidatif->listeApresPourEtatLiquidatif( $id, array( 'Apre.statutapre' => $typeapre ) );

			$etatliquidatifLimit = Configure::read( 'Etatliquidatif.limit' );
			if( !empty( $etatliquidatifLimit ) ) {
				$queryData['limit'] = $etatliquidatifLimit;
			}

			$deepAfterFind = $this->Etatliquidatif->Apre->deepAfterFind;
			$this->Etatliquidatif->Apre->deepAfterFind = false;
// 			$this->Etatliquidatif->Apre->unbindModelAll();
// http://localhost/adullact/webrsa/trunk/etatsliquidatifs/selectionapres/17
$querydata = array(
	'fields' => $queryData['fields'],
	'joins' => array(
		$this->Etatliquidatif->Apre->join( 'Personne' ),
		$this->Etatliquidatif->Apre->Personne->join( 'Foyer' ),
		$this->Etatliquidatif->Apre->Personne->Foyer->join( 'Dossier' ),
		$this->Etatliquidatif->Apre->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
		$this->Etatliquidatif->Apre->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
		$this->Etatliquidatif->Apre->join( 'ApreComiteapre', array( 'type' => 'LEFT OUTER' ) )
	),
	'conditions' => array(
		'Adressefoyer.id IS NULL OR Adressefoyer.id IN ('
			.$this->Etatliquidatif->Apre->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' )
		.')',
		'Apre.eligibiliteapre' => 'O',
		'AND' => array(
			'(Apre.statutapre = \'F\') OR Apre.montantaverser IS NOT NULL',// FIXME: Apre.statutapre F -> pas de montantaverser ?
			'OR' => array(
				'Apre.montantdejaverse IS NULL',
				'Apre.montantaverser > Apre.montantdejaverse'
			),
			// Nb. paiements ?
		),
		'Apre.statutapre' => $typeapre,
		'OR' => array(
			'Apre.statutapre' => 'F',
			'AND' => array(
				'Apre.statutapre' => 'C',
				'ApreComiteapre.id IN ('
					.$this->Etatliquidatif->Apre->ApreComiteapre->sqDernierComiteApre()
				.')',
				'ApreComiteapre.decisioncomite' => 'ACC'
			)
		),
		array(
			'OR' => array(
				// L'APRE n'est pas dans un etatliquidatif non clôturé
				'Apre.id NOT IN ('
					.$this->Etatliquidatif->ApreEtatliquidatif->sq(
						array(
							'alias' => 'apres_etatsliquidatifs',
							'fields' => 'apres_etatsliquidatifs.apre_id',
							'joins' => array(
								array(
									'table' => '"etatsliquidatifs"', // FIXME
									'alias' => 'etatsliquidatifs',
									'type' => 'INNER',
									'conditions' => array(
										'apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id'
									)
								)
							),
							'conditions' => array(
								'etatsliquidatifs.datecloture IS NOT NULL'
							),
							'contain' => false
						)
					)
				.')',
				// L'APRE doit encore recevoir des paiement
				// FIXME: à présent, on prend tant que la totalité n'a pas été payée OU
				//        tant que le montant déjà versé est inférieur au montant à verser
				// FIXME: on a une partie de ces conditions en haut, ligne 207: Apre.montantaverser > Apre.montantdejaverse
				'Apre.id IN ('
					.$this->Etatliquidatif->ApreEtatliquidatif->sq(
						array(
							'alias' => 'apres_etatsliquidatifs',
							'fields' => 'apres_etatsliquidatifs.apre_id',
							'joins' => array(
								array(
									'table' => '"etatsliquidatifs"', // FIXME
									'alias' => 'etatsliquidatifs',
									'type' => 'INNER',
									'conditions' => array(
										'apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id'
									)
								)
							),
							'conditions' => array(
								'OR' => array(
									$this->Etatliquidatif->sousRequeteApreNbpaiementeff.' <> Apre.nbpaiementsouhait',
									'Apre.montantdejaverse < Apre.montantaverser'
								)
							),
							'contain' => false
						)
					)
				.')'
			)
		)
	),
	'contain' => false,
// 	'limit' => 1000
);

$queryData = $querydata;

			$apres = $this->Etatliquidatif->Apre->find( 'all', $queryData );
//
			$this->Etatliquidatif->Apre->deepAfterFind = $deepAfterFind;

			$apres_etatsliquidatifs = $this->Etatliquidatif->ApreEtatliquidatif->find(
				'all',
				array(
					'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $id ),
					'recursive' => -1
				)
			);
			$this->data['Apre']['Apre'] = Set::extract( $apres_etatsliquidatifs, '/ApreEtatliquidatif/apre_id' );

			$this->set( compact( 'apres', 'typeapre' ) );
		}

		/**
		*
		*/

		public function visualisationapres( $id = null ) {
			$typeapre = $this->Etatliquidatif->getTypeapre( $id );
			$this->assert( !empty( $typeapre ), 'invalidParameter' );

			$method = 'qdDonneesApre'.Inflector::camelize( $typeapre );
			$querydata = $this->Etatliquidatif->{$method}();
			$querydata = Set::merge(
				$querydata,
				array(
					'conditions' => array(
						'Etatliquidatif.id' => $id
					),
					'limit' => 100
				)
			);

			$this->paginate = $querydata;
			$deepAfterFind = $this->Etatliquidatif->Apre->deepAfterFind;
			$this->Etatliquidatif->Apre->deepAfterFind = false;
			$apres = $this->paginate( $this->Etatliquidatif );
			$this->Etatliquidatif->Apre->deepAfterFind = $deepAfterFind;

			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
			$this->set( 'typeapre', ( $typeapre == 'forfaitaire' ? 'F' : 'C' ) );
			$this->set( compact( 'apres' ) );
		}

		/**
		*
		*/

		public function impressiongedoooapres( $apre_id, $etatliquidatif_id ) {
			$typeapre = $this->Etatliquidatif->getTypeapre( $etatliquidatif_id );
			$this->assert( !empty( $typeapre ), 'invalidParameter' );

			$method = 'qdDonneesApre'.Inflector::camelize( $typeapre ).'Gedooo';

			$querydata = $this->Etatliquidatif->{$method}();
			$querydata = Set::merge(
				$querydata,
				array(
					'conditions' => array(
						'Etatliquidatif.id' => $etatliquidatif_id,
						'Apre.id' => $apre_id
					)
				)
			);

			$deepAfterFind = $this->Etatliquidatif->Apre->deepAfterFind;
			$this->Etatliquidatif->Apre->deepAfterFind = false;
			$apre = $this->Etatliquidatif->find( 'first', $querydata );
			$this->Etatliquidatif->Apre->deepAfterFind = $deepAfterFind;

			$this->assert( !empty( $apre ), 'invalidParameter' );

			/// Récupération de l'utilisateur
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $this->Session->read( 'Auth.User.id' )
					),
					'contain' => false
				)
			);
			$apre['User'] = $user['User'];

			$typeapre = Set::classicExtract( $apre, 'Apre.statutapre' );
			$dest = Set::classicExtract( $this->params, 'named.dest' );

			if( !empty( $apre['Apre']['nomaide'] ) && in_array( $apre['Apre']['nomaide'], $this->Etatliquidatif->Apre->modelsFormation ) ) {
				$typeformation = 'formation';
			}
			else {
				$typeformation = 'horsformation';
			}

			$options = array(
				'Adresse' => array(
					'typevoie' => $this->Option->typevoie()
				),
				'Apre' => array(
					'natureaide' => $this->Option->natureAidesApres()
				),
				'Personne' => array(
					'qual' => $this->Option->qual()
				),
				'Tiersprestataireapre' => array(
					'typevoie' => $this->Option->typevoie()
				)
			);

			if( $typeapre == 'F' ) {
				$modeleodt = 'APRE/apreforfaitaire.odt';
				$nomfichierpdf = sprintf( 'apreforfaitaire-%s.pdf', date( 'Y-m-d' ) );
			}
			else if( $typeapre == 'C' && $dest == 'tiersprestataire' ) {
				$modeleodt = 'APRE/Paiement/paiement_'.$dest.'.odt';
				$nomfichierpdf = sprintf( 'paiement_'.$dest.'-%s.pdf', date( 'Y-m-d' ) );
			}
			else if( $typeapre == 'C' && $dest == 'beneficiaire' ) {
				$modeleodt = 'APRE/Paiement/paiement_'.$typeformation.'_'.$dest.'.odt';
				$nomfichierpdf = sprintf( 'paiement_'.$typeformation.'_'.$dest.'-%s.pdf', date( 'Y-m-d' ) );
			}

			$pdf = $this->Etatliquidatif->Apre->ged( $apre, $modeleodt, false, $options );
			$this->assert( !empty( $pdf ), 'error500' );

			$this->Gedooo->sendPdfContentToClient( $pdf, $nomfichierpdf );
		}

		/**
		*
		*/

		public function impressioncohorte( $id ) {
			$typeapre = $this->Etatliquidatif->getTypeapre( $id );
			$this->assert( !empty( $typeapre ), 'invalidParameter' );

			$dest = 'beneficiaire';//FIXME

			$options = array(
				'Adresse' => array(
					'typevoie' => $this->Option->typevoie()
				),
				'Apre' => array(
					'natureaide' => $this->Option->natureAidesApres()
				),
				'Personne' => array(
					'qual' => $this->Option->qual()
				),
				'Tiersprestataireapre' => array(
					'typevoie' => $this->Option->typevoie()
				)
			);

			$method = 'qdDonneesApre'.Inflector::camelize( $typeapre ).'Gedooo';
			$querydata = $this->Etatliquidatif->{$method}();
			$querydata = Set::merge(
				$querydata,
				array(
					'conditions' => array(
						'Etatliquidatif.id' => $id,
					),
					'limit' => 100
				)
			);

			Configure::write( "Optimisations.{$this->name}_{$this->action}.progressivePaginate", true );
			$this->paginate = $querydata;
			$deepAfterFind = $this->Etatliquidatif->Apre->deepAfterFind;
			$this->Etatliquidatif->Apre->deepAfterFind = false;
			$apres = $this->paginate( $this->Etatliquidatif );
			$this->Etatliquidatif->Apre->deepAfterFind = $deepAfterFind;

			$typeapre = ( $typeapre == 'forfaitaire' ? 'F' : 'C' );
			if( $typeapre == 'F' ) {
				$key = 'forfaitaire';
				$modeleodt = 'APRE/apreforfaitaire.odt';
				$nomfichierpdf = sprintf( 'apreforfaitaire-%s.pdf', date( 'Y-m-d' ) );
			}
			else if( $typeapre == 'C' && $dest == 'tiersprestataire' ) {
				$key = 'etatliquidatif_tiers';
				$modeleodt = 'APRE/Paiement/paiement_'.$dest.'.odt';
				$nomfichierpdf = sprintf( 'paiement_'.$dest.'-%s.pdf', date( 'Y-m-d' ) );
			}
			else if( $typeapre == 'C' && $dest == 'beneficiaire' ) {
				$key = 'apreforfaitaire';
				$modeleodt = 'APRE/Paiement/paiement_'.$typeformation.'_'.$dest.'.odt';
				$nomfichierpdf = sprintf( 'paiement_'.$typeformation.'_'.$dest.'-%s.pdf', date( 'Y-m-d' ) );
			}

			$pdf = $this->Etatliquidatif->Apre->ged( array( $key => $apres ), $modeleodt, true, $options );
			$this->assert( !empty( $pdf ), 'error500' );

			$this->Gedooo->sendPdfContentToClient( $pdf, $nomfichierpdf );
		}

		/**
		*
		*/

		public function validation( $id = null ) {
			$etatliquidatif = $this->Etatliquidatif->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// État liquidatif pas encore validé
			if( !empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de valider un état liquidatif déjà validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

			// État liquidatif sans APRE ?
			// FIXME: doit-il y avoir obligatoirement des apres dans un état liquidatif
			$nApres = $this->Etatliquidatif->ApreEtatliquidatif->find( 'count', array( 'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $id ) ) );
			if( $nApres == 0 ) {
				$this->Session->setFlash( __( 'Impossible de valider un état liquidatif n\'étant associé à aucune APRE.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

			// TODO -> dans le modèle
			$this->Etatliquidatif->Apre->unbindModelAll();
			$montanttotalapre = $this->Etatliquidatif->Apre->find(
				'all',
				array(
					'fields' => array(
						'Apre.mtforfait',
						'ApreEtatliquidatif.montantattribue',
					),
					'joins' => array(
						array(
							'table'      => 'apres_etatsliquidatifs',
							'alias'      => 'ApreEtatliquidatif',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Apre.id = ApreEtatliquidatif.apre_id' )
						),
					),
					'recursive' => 1,
					'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => $id ),
				)
			);

			$etatliquidatif['Etatliquidatif']['datecloture'] = date( 'Y-m-d' );

			if( $etatliquidatif['Etatliquidatif']['typeapre'] == 'forfaitaire' ) {
				$etatliquidatif['Etatliquidatif']['montanttotalapre'] = array_sum( Set::extract( $montanttotalapre, '/Apre/mtforfait' ) );
			}
			else if( $etatliquidatif['Etatliquidatif']['typeapre'] == 'complementaire' ) {
				$etatliquidatif['Etatliquidatif']['montanttotalapre'] = array_sum( Set::extract( $montanttotalapre, '/ApreEtatliquidatif/montantattribue' ) );
			}
			else {
				$this->cakeError( 'error500' );
			}

			$this->Etatliquidatif->create( $etatliquidatif );
			if( $this->Etatliquidatif->save() ) {
				$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
				$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
			}
		}

		/**
		*
		*/

		public function hopeyra( $id = null ) {
			$etatliquidatif = $this->Etatliquidatif->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// État liquidatif pas encore validé
			if( empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de générer le fichier HOPEYRA pour un état liquidatif pas encore validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

			$apres = $this->Etatliquidatif->hopeyra( $id, $etatliquidatif['Etatliquidatif']['typeapre'] );

			$this->set( compact( 'apres' ) );

			$this->render( null, 'ajax' ); // FIXME: pas ajax
		}

		/**
		*   PDF pour les APREs Forfaitaires
		*/

		public function pdf( $id = null ) {
			$etatliquidatif = $this->Etatliquidatif->findById( $id, null, null, 0 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			// État liquidatif pas encore validé
			if( empty( $etatliquidatif['Etatliquidatif']['datecloture'] ) ) {
				$this->Session->setFlash( __( 'Impossible de générer le fichier PDF pour un état liquidatif pas encore validé.', true ), 'flash/error' );
				$this->redirect( array( 'action' => 'index' ) );
			}

			$elements = $this->Etatliquidatif->pdf( $id, $etatliquidatif['Etatliquidatif']['typeapre'], true );

			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();

			$this->set( compact( 'elements', 'etatliquidatif', 'qual', 'typevoie' ) );

			Configure::write( 'debug', 0 );
		}

		/**
		*
		*/

		public function ajaxmontant( $etatliquidatif_id, $apre_id, $index ) { // FIXME
			Configure::write( 'debug', 0 );
			$nbpaiementsouhait = $this->data['Apre'][$index]['nbpaiementsouhait'];

			$queryData = $this->Etatliquidatif->listeApresEtatLiquidatifNonTermine( array( 'Apre.statutapre' => 'C', 'Apre.id' => $apre_id ), $etatliquidatif_id );
			$queryData['recursive'] = -1;

			$apre = $this->Etatliquidatif->Apre->find( 'first', $queryData );

			// Calcul -> FIXME: dans le modèle
			$montanttotal = Set::classicExtract( $apre, 'Apre.montantaverser' );
			if( $nbpaiementsouhait == 1 ) {
				$montantattribue = $montanttotal;
			}
			else if( $nbpaiementsouhait == 2 ) {
				// INFO: remplacement du pourcentage de 40 -> 60 % pour les versements en 2 fois (du coup ajout d'un paramétrage)
				$montantattribue = Configure::read( 'Apre.pourcentage.montantversement' ) * ( Set::classicExtract( $apre, 'Apre.montantaverser' ) ) / 100;
			}

			$this->set( 'json', array( 'montantattribue' => $montantattribue ) );

			$this->layout = 'ajax';
			$this->render( '/elements/json' );
		}

		/**
		*
		*/

		public function versementapres( $id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			$etatliquidatif = $this->Etatliquidatif->findById( $id, null, null, -1 );
			$this->assert( !empty( $etatliquidatif ), 'invalidParameter' );

			$nbpaiementsouhait = array( '1' => 1, '2' => 2 );
			$this->set( 'nbpaiementsouhait', $nbpaiementsouhait );


			$queryData = $this->Etatliquidatif->listeApresEtatLiquidatifNonTerminePourVersement( array( 'Apre.statutapre' => 'C' ), $id );
			$queryData['limit'] = 100;

			$this->Etatliquidatif->Apre->unbindModelAll( false );
			$this->paginate['Apre'] = $queryData;
			$apres = $this->paginate( 'Apre' );
			$this->set( compact( 'apres', 'queryData' ) );


			if( !empty( $this->data ) ) {
				$this->Etatliquidatif->ApreEtatliquidatif->begin();

				$apre_ids = Set::extract( $this->data, '/ApreEtatliquidatif/apre_id' );
				$apres = Set::extract( $this->data, '/Apre' );
				$apres_etatsliquidatifs = Set::extract( $this->data, '/ApreEtatliquidatif' );

				// INFO: il faut d'abord sauver les APREs pour connaître le nombre de montants désirés
				$return = $this->Etatliquidatif->Apre->saveAll( $apres, array( 'atomic' => false ) );
				$return = $this->Etatliquidatif->ApreEtatliquidatif->saveAll( $apres_etatsliquidatifs, array( 'atomic' => false ) ) && $return;
				if( $return ) {
					$this->Etatliquidatif->Apre->calculMontantsDejaVerses( $apre_ids );
					$this->Etatliquidatif->ApreEtatliquidatif->commit();
					$this->redirect( array( 'action' => 'index', max( 1, Set::classicExtract( $this->params, 'named.page' ) ) ) );
				}
				else {
					$this->Etatliquidatif->ApreEtatliquidatif->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
		}
	}
?>
