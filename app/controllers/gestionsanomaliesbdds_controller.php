<?php
	ini_set( 'max_execution_time', 0 );
	ini_set( 'memory_limit', '2.5G' );

	/**
	*
	*/
	class GestionsanomaliesbddsController extends AppController
	{
		public $uses = array( 'Gestionanomaliebdd', /*'Adressefoyer', 'Foyer', */'Dossier', 'Option' );

		public $components = array( 'Gestionanomaliesbdd', 'Gestionzonesgeos', 'Prg' => array( 'actions' => array( 'index' ) ) );

		public $helpers = array( 'Default2', 'Gestionanomaliebdd' );

		/**
		* Méthodes de comparaison disponibles
		*
		* @return array
		*/
		protected function _methodes() {
			$methodes = array( /*'stricte' => 'Stricte',*/ 'normale' => 'Normale' );
			$this->Dossier->Behaviors->attach( 'Pgsqlcake.Schema' );
			$pg_functions = $this->Dossier->pgFunctions( 'difference' );
			if( !empty( $pg_functions ) ) {
				$methodes['approchante'] = 'Approchante';
			}

			return $methodes;
		}

		/**
		* FIXME: à mettre dans le modèle Gestionanomalie, fonction prechargement()
		*/
		protected function _foreignKeysTo( &$model ) {
			// @todo: + tables spéciales (fichiersmodules/personnes)
			$cacheKey = Inflector::underscore( Inflector::camelize( implode( '_', array( __CLASS__, __FUNCTION__, $model->useDbConfig, $model->alias ) ) ) );

			$foreignKeysTo = Cache::read( $cacheKey );
			if( $foreignKeysTo === false ) {
				$model->Behaviors->attach( 'Pgsqlcake.Schema' );
				$foreignKeysTo = $model->foreignKeysTo();
				Cache::write( $cacheKey, $foreignKeysTo );
			}

			return Set::extract( '/From/table', $foreignKeysTo );
		}

		/**
		* Liste des dossiers ayant des problèmes de personnes en leur sein.
		*/
		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$search = $this->data;

			if( !empty( $search ) ) {
				$this->paginate = $this->Gestionanomaliebdd->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$search,
					$this->Jetons->sqIds()
				);

				// Restrictions 58
				$this->paginate = $this->_qdAddFilters( $this->paginate );

				$results = $this->paginate( $this->Dossier );

				$methode = Set::classicExtract( $search, 'Gestionanomaliebdd.methode' );
				$sansprestation = Set::classicExtract( $search, 'Gestionanomaliebdd.sansprestation' );
				$doublons = Set::classicExtract( $search, 'Gestionanomaliebdd.doublons' );

				// Quelles sont les personnes du foyer (pour les tooltips)
				if( !empty( $results ) ) {
					foreach( $results as $i => $result ) {
						$personnes = $this->Dossier->Foyer->Personne->find(
							'all',
							array(
								'fields' => array(
									'Personne.qual',
									'Personne.nom',
									'Personne.prenom',
									'Personne.nomnai',
									'Personne.dtnai',
									'Personne.nir',
									'Prestation.rolepers',
								),
								'conditions' => array(
									'Personne.foyer_id' => $result['Foyer']['id']
								),
								'contain' => array(
									'Prestation'
								),
								'order' => array(
									'Personne.dtnai ASC',
									'Personne.nom ASC',
									'Personne.prenom ASC',
									'Personne.id DESC',
								),
							)
						);
						$results[$i]['Doublons'] = $personnes;
					}
				}

				$this->set( compact( 'results', 'methode', 'prestationObligatoire' ) );
			}

			$methodes = $this->_methodes();

			// Options du formulaire de recherche
			$options = array(
				'Foyer' => array( 'sitfam' => $this->Option->sitfam() ),
// 				'Prestation' => array( 'rolepers' => $this->Option->rolepers() ),
				'Situationdossierrsa' => array( 'etatdosrsa' => $this->Option->etatdosrsa() ),
				'Adresse' => array( 'numcomptt' => $this->Gestionzonesgeos->listeCodesInsee() ),
				'Gestionanomaliebdd' => array(
					'methode' => $methodes,
					'enerreur' => array( '1' => 'Oui', '0' => 'Non' ),
					'sansprestation' => array( '1' => 'Oui', '0' => 'Non' ),
					'doublons' => array( '1' => 'Oui', '0' => 'Non' ),
				),
				'Personne' => array( 'qual' => $this->Option->qual() ),
			);
			$this->set( compact( 'options' ) );

			// Valeurs par défaut des options
			if( empty( $this->data ) ) {
				$this->data['Gestionanomaliebdd']['touteerreur'] = true;
				$this->data['Prestation']['obligatoire'] = true;
				$this->data['Prestation']['doublons'] = null;
				$this->data['Prestation']['enerreur'] = null;
				$this->data['Prestation']['sansprestation'] = null;
				$this->data['Gestionanomaliebdd']['methode'] = 'normale';
				$this->data['Situationdossierrsa']['etatdosrsa'] = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
			}
		}

		/**
		*
		*/
		protected function _informationsFoyer( $foyer_id ) {
			$named = Xset::bump( $this->params['named'], '__' );

			$methode = Set::classicExtract( $named, 'Gestionanomaliebdd.methode' );
			$methode = ( empty( $methode ) ? 'approchante' : $methode );

			$qdPersonnesEnDoublons = $this->Gestionanomaliebdd->qdPersonnesEnDoublons(
				$methode,
				null,
				'Foyer.id'
			);
			$qdPersonnesEnDoublons['fields'] = array( 'p1.foyer_id' );
			$sqPersonnesEnDoublons = $this->Dossier->Foyer->Personne->sq( $qdPersonnesEnDoublons );

			$querydata = array(
				'fields' => array_merge(
					$this->Dossier->fields(),
					$this->Dossier->Foyer->fields(),
					$this->Gestionanomaliebdd->vfsInformationsFoyer(
						$this->Dossier->Foyer,
						$sqPersonnesEnDoublons,
						$this->Jetons->sqIds()
					)
				),
				'contain' => array(
					'Dossier'
				),
				'conditions' => array(
					'Foyer.id' => $foyer_id
				)
			);

			return $this->Dossier->Foyer->find( 'first', $querydata );
		}

		/**
		* Liste des personnes en doublon au sein d'un même dossier.
		* FIXME: la méthode utilisée (en paramètre ?)
		* FIXME: au 58: /personnes/view/17028 (3 CJT)
		*/
		public function foyer( $foyer_id ) {
			$this->assert( is_numeric( $foyer_id ), 'error404' );

			// Personnes posant problème au sein du foyer
			$named = Xset::bump( $this->params['named'], '__' );
			/*$touteerreur = Set::classicExtract( $params, 'Gestionanomaliebdd.touteerreur' );
			$enerreur = Set::classicExtract( $named, 'Gestionanomaliebdd.enerreur' );
			$sansprestation = Set::classicExtract( $named, 'Gestionanomaliebdd.sansprestation' );
			$doublons = Set::classicExtract( $named, 'Gestionanomaliebdd.doublons' );*/
			$methode = Set::classicExtract( $named, 'Gestionanomaliebdd.methode' );
			$methode = ( empty( $methode ) ? 'normale' : $methode );

			$options = array( 'Prestation' => array( 'rolepers' => ClassRegistry::init( 'Option' )->rolepers() ) );
			$methodes = $this->_methodes();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			// Recherche d'informations sur le foyer
			/*$qdPersonnesEnDoublons = $this->Gestionanomaliebdd->qdPersonnesEnDoublons(
				'approchante', // FIXME
				null,
				'Foyer.id'
			);
			$qdPersonnesEnDoublons['fields'] = array( 'p1.foyer_id' );
			$sqPersonnesEnDoublons = $this->Dossier->Foyer->Personne->sq( $qdPersonnesEnDoublons );

			$querydata = array(
				'fields' => array_merge(
					$this->Dossier->fields(),
					$this->Dossier->Foyer->fields(),
					$this->Gestionanomaliebdd->vfsInformationsFoyer(
						$this->Dossier->Foyer,
						$sqPersonnesEnDoublons,
						$this->Jetons->sqIds()
					)
				),
				'contain' => array(
					'Dossier'
				),
				'conditions' => array(
					'Foyer.id' => $foyer_id
				)
			);
			$foyer = $this->Dossier->Foyer->find( 'first', $querydata );*/
			$foyer = $this->_informationsFoyer( $foyer_id );

			// <-- Vous êtes ici
			/*$querydataDossier = $this->Gestionanomaliebdd->search(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				array(
					'Gestionanomaliebdd' => array(
						'touteerreur' => true,
						'methode' => $methode
					)
				),
				null
			);
			debug( $querydataDossier );
			debug( $this->Dossier->find( 'first', $querydataDossier ) );*/

			// Recherche de l'ensemble des  personnes du foyer
			$querydata = array(
				'fields' => Set::merge(
					$this->Dossier->Foyer->Personne->fields(),
					$this->Dossier->Foyer->Personne->Prestation->fields()
				),
				'conditions' => array(
					'Personne.foyer_id' => $foyer_id
				),
				'contain' => array(
					'Prestation'
				),
				'order' => array(
					'Personne.dtnai ASC',
					'Personne.nom ASC',
					'Personne.prenom ASC',
					'Personne.id DESC',
				),
			);
			$personnes = $this->Dossier->Foyer->Personne->find( 'all', $querydata );

			$conditions = array( 'Personne.foyer_id' => $foyer_id, 'OR' => array() );

			// Personne sans prestation RSA
			$conditions['OR'][] = 'Prestation.rolepers IS NULL';

			// Nombre incorrect de DEM ou de CJT
			$conditions['OR'][] = 'Prestation.rolepers IN ( CASE WHEN ( SELECT COUNT("personnes"."id") FROM "personnes" AS "personnes" INNER JOIN "prestations" AS "prestations" ON ("prestations"."personne_id" = "personnes"."id" AND "prestations"."natprest" = \'RSA\')  WHERE "personnes"."foyer_id" = "Personne"."foyer_id" AND "prestations"."rolepers" = \'DEM\' ) <> 1 THEN \'DEM\' ELSE NULL END )';
			$conditions['OR'][] = 'Prestation.rolepers IN ( CASE WHEN ( SELECT COUNT("personnes"."id") FROM "personnes" AS "personnes" INNER JOIN "prestations" AS "prestations" ON ("prestations"."personne_id" = "personnes"."id" AND "prestations"."natprest" = \'RSA\')  WHERE "personnes"."foyer_id" = "Personne"."foyer_id" AND "prestations"."rolepers" = \'CJT\' ) > 1 THEN \'CJT\' ELSE NULL END )';

			// Doublons de personnes
			$qd = $this->Gestionanomaliebdd->qdPersonnesEnDoublons(
				$methode,
				null,
				'Personne.foyer_id'
			);

			$sq = $this->Dossier->Foyer->Personne->sq( $qd );
			$conditions['OR'][] = "Personne.id IN ( {$sq} )";

			$querydata['conditions'] = $conditions;
			$problemes = $this->Dossier->Foyer->Personne->find( 'all', $querydata );

			$this->set( compact( 'personnes', 'options', 'methodes', 'problemes', 'foyer' ) );

			/*$named = Xset::bump( $this->params['named'], '__' );
			$enerreur = Set::classicExtract( $named, 'Gestionanomaliebdd.enerreur' );
			$sansprestation = Set::classicExtract( $named, 'Gestionanomaliebdd.sansprestation' );
			$doublons = Set::classicExtract( $named, 'Gestionanomaliebdd.doublons' );
			$methode = Set::classicExtract( $named, 'Gestionanomaliebdd.methode' );
			$methode = ( empty( $methode ) ? 'normale' : $methode );

			$conditions = array( 'Personne.foyer_id' => $foyer_id, 'OR' => array() );

			if( $enerreur ) {
				$sq = $this->Dossier->Foyer->Personne->Prestation->sq(
					array(
						'fields' => array( 'prestations.personne_id' ),
						'alias' => 'prestations',
						'contain' => false,
						'conditions' => array(
							'prestations.personne_id = Personne.id',
							'prestations.natprest' => 'RSA',
							'prestations.rolepers' => array( 'DEM', 'CJT' ),
						)
					)
				);
				$conditions['OR'][] = "Personne.id IN ( {$sq} )";
			}

			if( $sansprestation ) {
				$sq = $this->Dossier->Foyer->Personne->Prestation->sq(
					array(
						'fields' => array( 'prestations.personne_id' ),
						'alias' => 'prestations',
						'contain' => false,
						'conditions' => array(
							'prestations.personne_id = Personne.id',
							'prestations.natprest' => 'RSA'
						)
					)
				);
				$conditions['OR'][] = "Personne.id NOT IN ( {$sq} )";
			}

			if( $doublons ) {
				$sq = $this->Gestionanomaliebdd->qdPersonnesEnDoublons(
					$methode,
					null,
					'Personne.foyer_id'
				);
				$conditions['OR'][] = "Personne.id IN ( {$sq} )";
			}

			if( empty( $conditions['OR'] ) ) {
				unset( $conditions['OR'] );
			}

			$querydata = array(
				'fields' => Set::merge(
					$this->Dossier->Foyer->Personne->fields(),
					$this->Dossier->Foyer->Personne->Prestation->fields()
				),
				'conditions' => $conditions,
				'contain' => array(
					'Prestation'
				),
				'order' => array(
					'Personne.dtnai ASC',
					'Personne.nir ASC',
					'Personne.nom ASC',
					'Personne.prenom ASC',
					'Personne.id DESC',
				),
			);

			$personnes = $this->Dossier->Foyer->Personne->find( 'all', $querydata );

			$this->set( compact( 'personnes', 'options', 'methode', 'methodes', 'prestationObligatoire', 'referer' ) );*/
		}

		// FIXME: UPDATE ou DELETE sur la table « contratsinsertion » viole la contrainte de clé étrangère « actionsinsertion_contratinsertion_id_fkey » de la table « actionsinsertion »

		/**
		* TODO: tests + à mettre ailleurs
		*
		* OK (CG 66):
		*	- /gestionsanomaliesbdds/personnes/24/43957
		*	- /gestionsanomaliesbdds/personnes/20/22
		* (CG 93):
		*	- /gestionsanomaliesbdds/personnes/150937/386919
		*	- /gestionsanomaliesbdds/personnes/171911/441466
		*/
		protected function _personnesOrientstructMerge( $modelName, $personneAgarderId, $itemsIds ) {
			$modelClass = ClassRegistry::init( $modelName );
			$success = true;

			$items = $modelClass->find(
				'all',
				array(
					'fields' => array(
						'id',
						'personne_id',
						'typeorient_id',
						'structurereferente_id',
						'date_valid',
						'date_propo',
						'statut_orient',
						'rgorient',
						'origine'
					),
					'conditions' => array(
						"{$modelName}.id" => $itemsIds
					),
					'contain' => false,
					'order' => array(
						"{$modelName}.statut_orient ASC", // En attente, Non orienté, Orienté
						"{$modelName}.date_valid DESC",
						"{$modelName}.date_propo DESC",
					)
				)
			);

			$nbTotal = count( $items );

			if( $nbTotal == 0 ) {
				return $success;
			}

			$enAttente = array();
			$nonOriente = array();
			$oriente = array();

			foreach( $items as $i => $item ) {
				switch( $item['Orientstruct']['statut_orient'] ) {
					case 'En attente':
						$enAttente[] = $item;
						break;
					case 'Non orienté':
						$nonOriente[] = $item;
						break;
					case 'Orienté':
						$oriente[] = $item;
						break;
				}
			}

			// 1°) On a des orientations effectives
			if( !empty( $oriente ) ) {
				// a°) Suppression des orientsstructs 'Non orienté' et 'En attente'
				$ids = array_merge(
					Set::extract( '/Orientstruct/id', $enAttente ),
					Set::extract( '/Orientstruct/id', $nonOriente )
				);

				$ids = Set::filter( $ids );

				if( !empty( $ids ) ) {
					$success = $modelClass->deleteAll(
						array( "{$modelName}.id" => $ids )
					) && $success;
				}

				// b°) Enregistrement orientsstructs 'Orienté'
				$rgorientMax = count( Set::extract( '/Orientstruct[statut_orient=Orienté]/id', $items ) ) + 1;
				foreach( $items as $i => $item ) {
					if( $i == ( $rgorientMax - 2 ) ) { /// La dernière
						$item['Orientstruct']['rgorient'] = 1;
						if( $item['Orientstruct']['origine'] == 'reorientation' ) {
							if( !empty( $item['Orientstruct']['date_propo'] ) ) {
								$item['Orientstruct']['origine'] = 'cohorte';
							}
							else {
								$item['Orientstruct']['origine'] = 'manuelle';
							}
						}
					}
					else {
						$item['Orientstruct']['rgorient'] = ( ( $rgorientMax - $i ) * 100 );
						$item['Orientstruct']['origine'] = 'reorientation';
					}

					$item['Orientstruct']['personne_id'] = $personneAgarderId;

					$modelClass->create( $item );
					$success = $modelClass->save( null, array( 'validate' => false, 'callbacks' => false ) ) && $success;
				}

				// c°) Remise en ordre des rangs
				// FIXME: à mettre dans le modèle orientsstructs
				$sql = "UPDATE orientsstructs
							SET rgorient = (
								SELECT ( COUNT(orientsstructspcd.id) + 1 )
									FROM orientsstructs AS orientsstructspcd
									WHERE orientsstructspcd.personne_id = orientsstructs.personne_id
										AND orientsstructspcd.id <> orientsstructs.id
										AND orientsstructs.date_valid IS NOT NULL
										AND orientsstructspcd.date_valid IS NOT NULL
										AND (
											orientsstructspcd.date_valid < orientsstructs.date_valid
											OR (
												orientsstructspcd.date_valid = orientsstructs.date_valid
												AND orientsstructspcd.id < orientsstructs.id
											)
										)
										AND orientsstructs.statut_orient = 'Orienté'
										AND orientsstructspcd.statut_orient = 'Orienté'
							)
							WHERE
								orientsstructs.date_valid IS NOT NULL
								AND orientsstructs.personne_id = '{$personneAgarderId}'
								AND orientsstructs.statut_orient = 'Orienté';";

				$success = ( $modelClass->query( $sql ) !== false ) && $success;
			}
			// 2°) On a au moins une orientation en attente ou non orientée
			else {
				$ids = array_merge(
					Set::extract( '/Orientstruct/id', $enAttente ),
					Set::extract( '/Orientstruct/id', $nonOriente )
				);

				if( !empty( $ids ) ) {
					$idAGarder = $ids[0];
					unset( $ids[0] );

					// a°) Suppression des entrées en attente ou non orientée surnuméraires
					if( !empty( $ids ) ) {
						$success = $modelClass->deleteAll(
							array( "{$modelName}.id" => $ids )
						) && $success;
					}

					// b°) On garde la dernière entrée
					$success = $modelClass->updateAll(
						array( "{$modelName}.personne_id" => $personneAgarderId ),
						array( "{$modelName}.id" => $idAGarder )
					) && $success;
				}
			}

			return $success;
		}

		/**
		*
		*/
		protected function _personnesContratinsertionMerge( $modelName, $personneAgarderId, $itemsIds ) {
			$modelClass = ClassRegistry::init( $modelName );
			$success = true;

			if( empty( $itemsIds ) ) {
				return $success;
			}

			$success = $modelClass->updateAll(
				array( "{$modelName}.personne_id" => $personneAgarderId ),
				array( "{$modelName}.id" => $itemsIds )
			) && $success;

			$success = $modelClass->updateRangsContratsPersonne( $personneAgarderId ) && $success;

			$success = $modelClass->updateAll(
				array( "{$modelName}.num_contrat" => "'PRE'" ),
				array( "{$modelName}.personne_id" => $personneAgarderId, "{$modelName}.rg_ci" => 1 )
			) && $success;

			if( count( $itemsIds ) > 1 ) {
				$success = $modelClass->updateAll(
					array( "{$modelName}.num_contrat" => "'REN'" ),
					array( "{$modelName}.personne_id" => $personneAgarderId, "{$modelName}.rg_ci >" => 1 )
				) && $success;
			}

			return $success;
		}

		/**
		* TODO
		* FIXME: cmspath + en cas de rollback! -> bouger à la fin ?
		*/
		protected function _personnesFichiermoduleMerge( $modelName, $personneAgarderId, $itemsIds ) {
			$modelClass = ClassRegistry::init( $modelName );
			$success = true;

			$items = $modelClass->find(
				'all',
				array(
					'conditions' => array(
						"{$modelName}.id" => $itemsIds
					),
					'contain' => false,
					'order' => array(
						"{$modelName}.created ASC",
					)
				)
			);
// debug( $items );
			$nbTotal = count( $items );

			if( $nbTotal == 0 ) {
				return $success;
			}

			if( !empty( $items ) ) {
				$names = array();

				$success = $modelClass->deleteAll(
					array( "{$modelName}.id" => $itemsIds ),
					false,
					false
				) && $success;

				foreach( $items as $item ) {
					$count = 1;
					unset( $item[$modelName]['id'] );

					while( in_array( $item[$modelName]['name'], $names ) ) {
						$item[$modelName]['name'] = "{$count}_{$item[$modelName]['name']}";
						$count++;
					}
					$item[$modelName]['fk_value'] = $personneAgarderId;
					$item[$modelName]['cmspath'] = null;
					$names[] = $item[$modelName]['name'];
// debug( $item );
					// Enregistrement
					$modelClass->create( $item );
					$success = $modelClass->save() && $success;
				}
			}

			return $success;
		}

		/**
		*
		*/
		protected function _assocData( &$mainModel, $assocConditions, $personnes_id ) {
			$datas = array();

			if( !empty( $assocConditions ) ) {
				foreach( $assocConditions as $linkedModelName => $conditions ) {
					$conditions = (array)$conditions;

					$fields = array( 'Personne.id' );
					foreach( $mainModel->{$linkedModelName}->schema() as $field => $infos ) {
						if( $infos['type'] != 'binary' ) {
							$fields[] = "{$linkedModelName}.{$field}";
						}
					}

					// Sous-requêtes enregistrements liés
					$linkedModelAssocConditions = $this->Gestionanomaliesbdd->assocConditions( $mainModel->{$linkedModelName} );
					if( !empty( $linkedModelAssocConditions ) ) {
						$linkedTableName = Inflector::tableize( $linkedModelName );
						foreach( $linkedModelAssocConditions as $linkedModelAssoc => $linkedModelConditions ) {
							if( $this->Gestionanomaliesbdd->tablePourCg( $linkedModelAssoc ) ) {
								$autreTable = Inflector::tableize( $linkedModelAssoc );
								$sq = $mainModel->{$linkedModelName}->{$linkedModelAssoc}->sq(
									array(
										'alias' => '',
										'fields' => array( 'COUNT(*)' ),
										'contain' => false,
										'conditions' => $linkedModelConditions
									)
								);
								$countField = "{$mainModel->$linkedModelName->alias}__nb_{$autreTable}";
								$sq = preg_replace( "/(?<!\.)(?<!\w)({$linkedModelAssoc})(?!\w)/", $autreTable, $sq );

								$fields[] = "( {$sq} ) AS \"{$countField}\"";
							}
						}
					}

					$querydata = array(
						'fields' => $fields,
						'conditions' => array(
							'Personne.id' => $personnes_id
						),
						'joins' => array(
							// FIXME
				// 			$this->Dossier->Foyer->Personne->{$linkedModelName}->join( 'Personne', array( 'type' => 'INNER' ) )
							array(
								'table' => 'personnes',
								'alias' => 'Personne',
								'type' => 'INNER',
								'foreignKey' => false,
								'conditions' => $conditions
							)
						),
						'limit' => 10,
						'contain' => false,
						'order' => array( 'Personne.id DESC' )

					);
					$datas[$linkedModelName] = $mainModel->{$linkedModelName}->find( 'all', $querydata );
				}
			}

			return $datas;
		}

		/**
		* Validation
		*/
		protected function _validationErrors( $data, $donnees, $dependencies ) {
			$errors = array();

			// 1°) On doit impérativement garder une des personnes
			if( !isset( $data['Personne']['garder'] ) || empty( $data['Personne']['garder'] ) ) {
				$errors[] = __d( 'gestionanomaliebdd', 'Validation::notEmpty(Personne)', true );
			}

			// 2°) Les enregistrements doivent être uniques pour une personne donnée
			$uniqueRecordsModels = array_keys( $data );
			foreach( $uniqueRecordsModels as $uniqueRecordsModel ) {
				if( $uniqueRecordsModel != 'Personne' ) {
					$pathKeep = "/{$uniqueRecordsModel}/id";
					$keep = Set::extract( $pathKeep, $data );
					$keep = Set::filter( $keep );

					if( count( $keep ) > 1 ) {
						$pathOriginals = "/{$uniqueRecordsModel}/{$uniqueRecordsModel}";
						$originals = Set::extract( $pathOriginals, $donnees );

						$foo = array();
						foreach( $keep as $id ) {
							$pathRecord = "/{$uniqueRecordsModel}[id={$id}]";
							$record = Set::extract( $pathRecord, $originals );
							$record = $record[0][$uniqueRecordsModel];
							unset( $record['id'], $record['personne_id'], $record['fk_value'] );
							foreach( $record as $key => $value ) {
								if( is_string( $value ) && trim( $value ) == '' ) {
									$record[$key] = null;
								}
							}
							$foo[] = serialize( $record );
						}

						$uniqueFoo = array_unique( $foo );
						if( count( $foo ) != count( $uniqueFoo ) ) {
							$errors[] = sprintf( __d( 'gestionanomaliebdd', "Validation::isUnique(%s)", true ), $uniqueRecordsModel );
						}
					}
				}
			}

			// 3°) Au niveau des orientations
			$orientsstructsIdsAGarder = Set::extract( '/Orientstruct/id', $data );
			if( count( $orientsstructsIdsAGarder ) > 1 ) {
				$orientsstructsAGarder = array();
				foreach( $orientsstructsIdsAGarder as $orientsstructsIdAGarder ) {
					$pathOrientstruct = "/Orientstruct/Orientstruct[id={$orientsstructsIdAGarder}]/statut_orient";
					$orientstruct = Set::extract( $pathOrientstruct, $donnees );
					if( !isset( $orientsstructsAGarder[$orientstruct[0]] ) ) {
						$orientsstructsAGarder[$orientstruct[0]] = 0;
					}
					$orientsstructsAGarder[$orientstruct[0]]++;
				}
				$orientees = ( ( isset( $orientsstructsAGarder['Orienté'] ) ? $orientsstructsAGarder['Orienté'] : 0 ) );
				$nonorientees = array_sum( $orientsstructsAGarder ) - $orientees;

				// 3.1°) au maximum, une seule peut ne pas être orientée
				if( $nonorientees > 1 ) {
					$errors[] = __d( 'gestionanomaliebdd', "Validation::nonOrientees>1", true );
				}

				// 3.2°) s'il en existe au moins une orientée, on ne peut en avoir de non orientées
				if( ( $orientees > 0 ) && ( $nonorientees > 0 ) ) {
					$errors[] = __d( 'gestionanomaliebdd', "Validation::orientees>1::nonOrientees>1", true );
				}
			}

			// Les enregistrements dépendants entre eux doivent être conservés
			if( !empty( $dependencies ) ) {
				foreach( $dependencies as $dependency ) {
					if( isset( $data[$dependency['From']['model']] ) ) {
						if( !isset( $data[$dependency['To']['model']] ) ) {
							$errors[] = sprintf( __d( 'gestionanomaliebdd', "Validation::dependency(%s->%s)", true ), $dependency['From']['model'], $dependency['To']['model'] );
						}
						else {
							$valuesFrom = $data[$dependency['From']['model']]['id'];
							if( !empty( $valuesFrom ) ) {
								foreach( $valuesFrom as $valueFrom ) {
									$pathFrom = "/{$dependency['From']['model']}/{$dependency['From']['model']}[id={$valueFrom}]/{$dependency['From']['column']}";
									$to = Set::extract( $pathFrom, $donnees );

									// INFO: ne devrait jamais arriver -> Si, avec les contrats (on n'a pas toujours un avenant) -> le champ est-il not null ?
									if( ( empty( $to ) || empty( $to[0] ) ) ) {
										if( !$dependency['From']['nullable'] ) {
											$errors[] = __d( 'gestionanomaliebdd', 'Error 500', true );
										}
									}
									else if( !in_array( $to[0], $data[$dependency['To']['model']][$dependency['To']['column']] ) ) {
										$errors[] = sprintf( __d( 'gestionanomaliebdd', "Validation::dependency(%s->%s)", true ), $dependency['From']['model'], $dependency['To']['model'] );
									}
								}
							}
						}
					}
				}
			}

			return $errors;
		}

		/**
		* FIXME: orientstructs/contratsinsertion à supprimer/mettre à jour dans la table pdfs / sur Alfresco ?
		*/
		protected function _mergePersonnes( $data, $assocConditions, $donnees, $personnes_id, $dependencies ) {
			$validationErrors = $this->_validationErrors( $data, $donnees, $dependencies );
			if( !empty( $validationErrors ) ) {
				$this->set( 'validationErrors', $validationErrors );
				return false;
			}

			$success = true;

			// Suppression des enregistrements liés aux personnes à supprimer
			foreach( $assocConditions as $linkedModel => $linkedConditions ) {
				$avant = Set::extract( "/{$linkedModel}/{$linkedModel}/id", $donnees );
				$apres = Set::extract( "/{$linkedModel}/id", $data );
				$diff = array_diff( $avant, $apres );

				if( !empty( $avant ) && !empty( $diff ) ) {
					$success = ClassRegistry::init( $linkedModel )->deleteAll(
						array( "{$linkedModel}.id" => $diff )
					) && $success;
				}
			}

			// Fusion des enregistrements liés à la personne sélectionnée
			$models = array_keys( Set::filter( $data ) );
			foreach( $models as $model ) {
				if( $model != 'Personne' && !empty( $data[$model]['id'] ) ) {
					$methodName = "_personnes{$model}Merge";
					// A-t'on un traitement particulier ?
					if( method_exists( $this, $methodName ) ) {
						$success = call_user_func_array(
							array( $this, $methodName ),
							array( $model, $data['Personne']['garder'], $data[$model]['id'] )
						) && $success;
					}
					// Traitement générique
					else {
						$success = ClassRegistry::init( $model )->updateAll(
							array( "{$model}.personne_id" => $data['Personne']['garder'] ),
							array( "{$model}.id" => $data[$model]['id'] )
						) && $success;
					}
				}
			}


			// Personnes à supprimer
			$personnesASupprimer = array();
			foreach( $personnes_id as $personne_id ) {
				if( $personne_id != $data['Personne']['garder'] ) {
					$personnesASupprimer[] = $personne_id;
				}
			}

			$success = $this->Dossier->Foyer->Personne->deleteAll(
				array( 'Personne.id' => $personnesASupprimer ),
				false,
				false // FIXME
			) && $success;

			return $success;
		}

		/**
		* FIXME: cache, fonction prechargement()
		*/
		protected function _linkedModelsDependencies( &$mainModel ) {
			$cacheKey = Inflector::underscore( Inflector::camelize( implode( '_', array( __CLASS__, __FUNCTION__, $mainModel->useDbConfig, $mainModel->alias ) ) ) );

			$dependencies = Cache::read( $cacheKey );
			if( $dependencies === false ) {
				$dependencies = array();
				$linkedModels = array_keys( $this->Gestionanomaliesbdd->associations( $mainModel ) );
				sort( $linkedModels );

				foreach( $linkedModels as $linkedModel ) {
					$mainModel->{$linkedModel}->Behaviors->attach( 'Pgsqlcake.Schema' );
					$foreignKeysTo = $mainModel->{$linkedModel}->foreignKeysTo();
					if( !empty( $foreignKeysTo ) ){
						foreach( $foreignKeysTo as $foreignKeyTo ) {
							$modelName = Inflector::classify( $foreignKeyTo['From']['table'] );
							if( in_array( $modelName, $linkedModels ) ) {
								$dependencies[] = array(
									'From' => array(
										'model' => $modelName,
										'column' => $foreignKeyTo['From']['column'],
										'nullable' => $foreignKeyTo['From']['nullable'],
									),
									'To' => array(
										'model' => Inflector::classify( $foreignKeyTo['To']['table'] ),
										'column' => $foreignKeyTo['To']['column'],
									),
								);
							}
						}
					}
					$mainModel->{$linkedModel}->Behaviors->detach( 'Pgsqlcake.Schema' );
				}
				Cache::write( $cacheKey, $dependencies );
			}

			return $dependencies;
		}

		/**
		* Existe-t'il des fichiers modules liés aux enregistrements que nous voulons fusionner ?
		*/
		protected function _fichiersModuleLies( $donnees ) {
			$conditions = array();
			$models = array_keys( $donnees );
			if( !empty( $models ) ) {
				foreach( $models as $model ) {
					if( $model != 'Fichiermodule' ) {
						$values = Set::extract( $donnees, "/{$model}/{$model}/id" );
						$conditions[] = array( 'Fichiermodule.modele' => $model, 'Fichiermodule.fk_value' => $values );
					}
				}
			}

			$querydata = array(
				'fields' => array(
					'Fichiermodule.modele',
					'Fichiermodule.fk_value',
				),
				'conditions' => array(
					'OR' => $conditions
				),
				'contain' => false,
				'order' => array(
					'Fichiermodule.modele ASC',
					'Fichiermodule.fk_value DESC',
				)
			);
			$results = ClassRegistry::init( 'Fichiermodule' )->find( 'all', $querydata );

			return $results;
		}

		/**
		* FIXME: la méthode utilisée (en paramètre ?)
		*
		* Tables ayant un champ personne_id:
		*	SELECT
		*				DISTINCT table_name AS name
		*		FROM information_schema.columns
		*		WHERE
		*			column_name = 'personne_id'
		*			AND table_schema = 'public'
		*		ORDER BY table_name ASC;
		*
		* Tables ayant un champ personne_id et une clé étrangère vers une de ces mêmes tables:
		*/
		public function personnes( $foyer_id, $personne_id ) {
			$this->assert( is_numeric( $personne_id ), 'error404' );

			// Acquisition du lock ?
			$this->Dossier->begin();
			$dossier_id = $this->Dossier->Foyer->dossierId( $foyer_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Dossier->begin();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );


			$named = Xset::bump( $this->params['named'], '__' );
			$methode = Set::classicExtract( $named, 'Gestionanomaliebdd.methode' );
			$methode = ( empty( $methode ) ? 'normale' : $methode );
			$sansprestation = Set::classicExtract( $named, 'Gestionanomaliebdd.sansprestation' );

			$dependencies = $this->_linkedModelsDependencies( $this->Dossier->Foyer->Personne );

			// 1°) Lecture des données
			$qd = $this->Gestionanomaliebdd->qdPersonnesEnDoublons(
				$methode,
				$sansprestation,
				'Personne.foyer_id'
			);
			$qd['conditions'][] = '( p1.id = '.$personne_id.' OR p2.id = '.$personne_id.' )';

			$querydata = array(
				'fields' => Set::merge(
					$this->Dossier->Foyer->Personne->fields(),
					$this->Dossier->Foyer->Personne->Prestation->fields()
				),
				'conditions' => array(
					'Personne.foyer_id' => $foyer_id,
					'Personne.id IN ('
						.$this->Dossier->Foyer->Personne->sq( $qd )
					.')',
				),
				'contain' => array(
					'Prestation'
				),
				'order' => array(
					'Personne.id DESC'
				),
			);

			$personnes = $this->Dossier->Foyer->Personne->find( 'all', $querydata );
			$personnes_id = Set::extract( '/Personne/id', $personnes );

			if( !empty( $personnes_id ) ) {
				$assocConditions = $this->Gestionanomaliesbdd->assocConditions( $this->Dossier->Foyer->Personne );
				$donnees = $this->_assocData( $this->Dossier->Foyer->Personne, $assocConditions, $personnes_id );
				$donnees = Set::filter( $donnees );

				$fichiersModuleLies = array_merge(
					$this->_fichiersModuleLies( $donnees ),
					$this->_fichiersModuleLies( array( 'Personne' => array( 'Personne' => array( 'id' => $personnes_id ) ) ) )
				);
				$this->set( 'fichiersModuleLies', $fichiersModuleLies );

			}
			else {
				$donnees =  array();
			}

			// 2°) Tentative de fusion des personnes et de leurs enregistrements liés
			if( !empty( $this->data ) ) {
				unset( $this->data['Form'] );

				$success = $this->_mergePersonnes( $this->data, $assocConditions, $donnees, $personnes_id, $dependencies );
				if( $success ) {
					$this->Jetons->release( $dossier_id );
					$this->Dossier->Foyer->Personne->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'foyer', $this->params['pass'][0] ) );
				}
				else {
					$this->Dossier->Foyer->Personne->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
				}
			}
			else {
				$this->Dossier->Foyer->Personne->commit(); // Pour le jeton
			}

			$foyer = $this->_informationsFoyer( $foyer_id );

			$associations = $this->Gestionanomaliesbdd->associations( $this->Dossier->Foyer->Personne );
			$methodes = $this->_methodes();
			$this->set( compact( 'personnes', 'donnees', 'associations', 'methode', 'methodes', 'dependencies', 'foyer' ) );
		}
	}
?>