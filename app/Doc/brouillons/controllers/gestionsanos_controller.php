<?php
	class GestionsanosController extends AppController
	{
		/**
		*
		*/

		public $name = 'Gestionsanos';

		/**
		*
		*/

		public $uses = array( 'Gestionano', 'Dossier', 'Webrsa' );

		/**
		*
		*/

		public $helpers = array( 'Type2' );

		/**
		*
		*/

		public function index() {
			$contraintes = $this->Gestionano->constraints();

			$this->set( compact( 'contraintes' ) );

			/*$Foyer = ClassRegistry::init( 'Foyer' );
			$results = $Foyer->find(
				'all',
				array(
					'conditions' => array(
						'Foyer.id' => array( 216915, 216916 )
					),
					'recursive' => 2
				)
			);

			debug( Set::flatten( $results ) );*/
// 			debug( Set::diff( $results[0], $results[1] ) );
		}

		/**
		* CG 93
		*/

		public function foyers() {
			$results = $this->Dossier->Foyer->find(
				'all',
				array(
					'conditions' => array(
						'Foyer.id' => array( 216915, 216916 )
					),
					'recursive' => 2
				)
			);

			debug( $results );
		}

		/**
		* CG 66:
		*	Si on a:
		*		- un DEM/CJT + 1 ENF qui sont en doublon dans le même foyer
		*		- un DEM/CJT correspondant dans un autre foyer (avec le bon nombre de DEM/CJT)
		*		- le DEM/CJT du premier foyer n'a rien dans les tables métier (voire une orientstruct non orientée)
		*/

		public function prestations( $foyer_id = null ) {
			$querydata = array(
				'conditions' => array(
					'Foyer.id IN (
						SELECT personnes.foyer_id
							FROM personnes
							WHERE personnes.id IN (
								SELECT p1.personne_id
									FROM prestations p1,
										prestations p2
									WHERE p1.id <> p2.id
										AND p1.personne_id = p2.personne_id
										AND p1.natprest = p2.natprest
										AND p1.rolepers <> p2.rolepers
						)
					)'
				),
			);

			$bindPrestation = $this->Dossier->Foyer->Personne->hasOne['Prestation'];
			$this->Dossier->Foyer->Personne->unbindModel( array( 'hasOne' => array( 'Prestation' ) ), false );
			$this->Dossier->Foyer->Personne->bindModel( array( 'hasMany' => array( 'Prestation' => $bindPrestation ) ), false );

			if( is_null( $foyer_id ) ) {
				$querydata['contain'] = array( 'Dossier' );

				$this->paginate = $querydata;
				$foyers = $this->paginate( $this->Dossier->Foyer );

				$this->set( compact( 'foyers' ) );
			}
			else {
				if( !empty( $this->data ) ) {
					$this->Dossier->Foyer->Personne->Prestation->begin();
					$success = true;
					$validationErrors = array();
					foreach( $this->data['Prestation'] as $i => $prestation ) {
						$validate = array();

						/// FIXME: on pourrait se retrouver avec des foyers sans DEM ou DEM/CJT multiples
						if( empty( $prestation['rolepers'] ) || in_array( $prestation['rolepers'], array( 'DEM', 'CJT' ) ) ) {
							$this->Dossier->Foyer->Personne->Prestation->invalidate( 'rolepers', "{$prestation['rolepers']} multiples" );
							$validationErrors[] = $this->Dossier->Foyer->Personne->Prestation->validationErrors;
						}

						$success = $this->Dossier->Foyer->Personne->Prestation->deleteAll(
							array(
								'Prestation.personne_id' => $prestation['personne_id'],
								'Prestation.rolepers <>' => $prestation['rolepers']
							)
						) && $success;
					}

					$success = empty( $validationErrors ) && $success;
					$this->Dossier->Foyer->Personne->Prestation->validationErrors = $validationErrors;

					if( $success ) {	
						$this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
						$this->Dossier->Foyer->Personne->Prestation->commit();
						$this->redirect( array( 'controller' => $this->params['controller'], 'action' => $this->action ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
						$this->Dossier->Foyer->Personne->Prestation->rollback();
					}
				}

				$this->Dossier->Foyer->Personne->Behaviors->attach( 'Pgsqlcake.Schema' );
				$linkedTables = Set::merge(
// 					Set::extract( '/To/table', $this->Dossier->Foyer->Personne->foreignKeysFrom() ),
					Set::extract( '/From/table', $this->Dossier->Foyer->Personne->foreignKeysTo() )
				);
				$tablesCaf = $this->Webrsa->tables( 'caf' );

				$fields = array_keys( Set::flatten( array( 'Personne' => Set::normalize( array_keys( $this->Dossier->Foyer->Personne->schema() ) ) ) ) );

				$querydataNombre = array();
				foreach( $linkedTables as $i => $linkedTable ) {
					if( !in_array( $linkedTable, $tablesCaf ) ) {
						$querydataNombre[] = "( SELECT COUNT(*) FROM \"{$linkedTable}\" WHERE \"{$linkedTable}\".\"personne_id\" = \"Personne\".\"id\" )";
					}
				}
				$fields[] = "( ".implode( ' + ', $querydataNombre )." ) AS \"Personne__nbrliens\"";

				$querydata['conditions']['Foyer.id'] = $foyer_id;
				$querydata['contain'] = array(
					'Dossier',
					'Personne' => array(
						'order' => array(
							'Personne.dtnai DESC',
							'Personne.nom',
							'Personne.prenom',
						),
						'fields' => $fields,
						'Prestation'
					)
				);

				$foyer = $this->Dossier->Foyer->find( 'first', $querydata );
				$this->set( compact( 'foyer' ) );
			}

			$this->Dossier->Foyer->Personne->unbindModel( array( 'hasMany' => array( 'Prestation' ) ), false );
			$this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Prestation' => $bindPrestation ) ), false );
		}
	}
?>