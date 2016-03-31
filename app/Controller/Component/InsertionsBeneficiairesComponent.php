<?php
	/**
	 * Code source de la classe InsertionsBeneficiairesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe InsertionsBeneficiairesComponent fournit des méthodes permettant
	 * d'obtenir les listes de types d'orientation, structures référentes et
	 * référents sous différentes formes, en fonction de l'utilisateur connecté.
	 *
	 * Elle fournit également une méthode permettant de compléter des options
	 * de structures référentes et de référents avec les entrées d'un enregistrement
	 * métier (afin que ces valeurs apparaissent quoi qu'il arrive lors d'une
	 * modification).
	 *
	 * @fixme
	 *	- remplace la classe InsertionAllocatairesComponent qui sera dépréciée depuis
	 *		la version 3.1.0 et avec laquelle elle présente quelques différences
	 *		notables.
	 *	- (OK) Cohérence des conditions initiales (actif) pour les différentes méthodes
	 *	- voir où ça influe pour les options (array() + array() vs. Set::merge)
	 *	- ajouter des tests (structuresreferentes) pour actif / inactif
	 *	- ATTENTION: à présent, les conditions par défaut de typesorients sont
	 *		'Typeorient.actif' => 'O' (ce n'était pas le cas avant)
	 *
	 * @package app.Controller.Component
	 */
	class InsertionsBeneficiairesComponent extends Component
	{
		/**
		 * Type de liste "ids" retourné par les méthodes structuresreferentes et
		 * referents
		 */
		const TYPE_IDS = 'ids';

		/**
		 * Type de liste "list" retourné par les méthodes structuresreferentes et
		 * referents
		 */
		const TYPE_LIST = 'list';

		/**
		 * Type de liste "optgroup" retourné par les méthodes structuresreferentes
		 * et referents
		 */
		const TYPE_OPTGROUP = 'optgroup';

		/**
		 * Nom du component
		 *
		 * @var string
		 */
		public $name = 'InsertionsBeneficiaires';

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Session'
		);

		/**
		 * Conditions par défaut pour les méthodes typesorients, structuresreferentes
		 * et referents.
		 *
		 * @var array
		 */
		public $conditions = array(
			'typesorients' => array(
				'Typeorient.actif' => 'O'
			),
			'structuresreferentes' => array(
				'Typeorient.actif' => 'O',
				'Structurereferente.actif' => 'O'
			),
			'referents' => array(
				'Typeorient.actif' => 'O',
				'Structurereferente.actif' => 'O',
				'Referent.actif' => 'O'
			)
		);

		/**
		 * Retourne la clé de session pour une méthode et un querydata donnés.
		 *
		 * @param string $method Le nom de la méthode
		 * @param array $query Le querydata
		 * @return string
		 */
		public function sessionKey( $method, array $query ) {
			$queryHash = sha1( serialize( $query ) );
			$sessionKey = "Auth.{$this->name}.{$method}.{$queryHash}";
			return $sessionKey;
		}

        /**
		 * Retourne la liste des types d'oriention.
		 * Mise en cache dans la session de l'utilisateur.
		 *
		 * Options par défaut
		 * <pre>
		 * array(
		 * 	'conditions' => array(
		 *		'Typeorient.actif' => 'O'
		 *	),
		 * 	'empty' => false
		 * );
		 * </pre>
		 *
		 * @todo InsertionsAllocataires->typesorients() <=> InsertionsBeneficiaires->typesorients( array( 'conditions' => array() ) )
		 * @todo Typeorient::listOptions (actif, parentid NULL)
		 *
		 * @param array $options La clé conditions permet de spécifier ou
		 *	de surcharger les conditions, la clé empty permet de spécifier si
		 *	l'on veut une entrée dont la clé sera 0 et la valeur 'Non orienté'.
		 * @return array
		 */
		public function typesorients( array $options = array() ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$options += array(
				'conditions' => $this->conditions[__FUNCTION__],
				'empty' => false
			);

            if( ( Configure::read( 'Cg.departement' ) == 66 ) && $this->Session->read( 'Auth.User.type' ) === 'externe_ci' ) {
                $sq = $Controller->Structurereferente->sq(
                   array(
                       'alias' => 'structuresreferentes',
                       'fields' => array(
                           'structuresreferentes.typeorient_id'
                       ),
                       'conditions' => array(
                           'structuresreferentes.id' => $this->Session->read( 'Auth.User.structurereferente_id' )
                       ),
                       'contain' => false
                   )
               );
                $options['conditions'][] = "Typeorient.id IN ( {$sq} )";
            }

			$query = array(
				'fields' => $Controller->Structurereferente->Typeorient->fields(),
				'conditions' => $options['conditions'],
				'contain' => false,
				'order' => array(
					'Typeorient.lib_type_orient ASC'
				)
			);

			$sessionKey = $this->sessionKey( __FUNCTION__, $query );
			$results = $this->Session->read( $sessionKey );

			if( $results === null ) {
				$results = array();

				$typesorients = $Controller->Structurereferente->Typeorient->find( 'all', $query );

				if( !empty( $typesorients ) ) {
					foreach( $typesorients as $typeorient ) {
						$results[$typeorient['Typeorient']['id']] = $typeorient['Typeorient']['lib_type_orient'];
					}
				}

				$this->Session->write( $sessionKey, $results );
			}

			if( Hash::get( $options, 'empty' ) ) {
				$results = array( 0 => 'Non orienté' ) + (array)$results;
			}

			return $results;
		}


		/**
		 * Retourne une condition à ajouter pour les utilisateurs CG 93 limités au
		 * niveau des zones géographiques.
		 *
		 * @return string
		 */
		protected function _sqStructurereferenteZonesgeographiques93() {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$sqStructurereferente = $Controller->Structurereferente->StructurereferenteZonegeographique->sq(
				array(
					'alias' => 'structuresreferentes_zonesgeographiques',
					'fields' => array( 'structuresreferentes_zonesgeographiques.structurereferente_id' ),
					'conditions' => array(
						'structuresreferentes_zonesgeographiques.zonegeographique_id' => array_keys( $this->Session->read( 'Auth.Zonegeographique' ) )
					),
					'contain' => false
				)
			);

			return "Structurereferente.id IN ( {$sqStructurereferente} )";
		}

		/**
		 * Retourne la liste des structures référentes actives (pour un dependant
		 * select avec le type d'orientation) liées à un type d'oientation actif.
		 * Mise en cache dans la session de l'utilisateur.
		 *
		 * Options par défaut
		 * <pre>
		 * array(
		 * 	'conditions' => array(
		 *		'Typeorient.actif' => 'O',
		 *		'Structurereferente.actif' => 'O'
		 *	),
		 * 	'prefix' => true,
		 * 	'type' => 'list'
		 * );
		 * </pre>
		 *
		 * @param array $options
		 * @return array
		 */
		public function structuresreferentes( $options = array( ) ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$options += array(
				'conditions' => $this->conditions[__FUNCTION__],
				'prefix' => true,
				'type' => self::TYPE_LIST
			);

			if( ( Configure::read( 'Cg.departement' ) == 93 ) && $this->Session->read( 'Auth.User.filtre_zone_geo' ) !== false ) {
				$options['conditions'][] = $this->_sqStructurereferenteZonesgeographiques93();
			}
			else if( ( Configure::read( 'Cg.departement' ) == 66 ) && $this->Session->read( 'Auth.User.type' ) === 'externe_ci' ) {
				$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
				$options['conditions']['Structurereferente.id'] = $structurereferente_id;
			}

			$query = array(
				'fields' => array_merge(
					$Controller->Structurereferente->Typeorient->fields(),
					$Controller->Structurereferente->fields()
				),
				'joins' => array(
					$Controller->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) )
				),
				'conditions' => $options['conditions'],
				'contain' => false,
				'order' => array(
					'Typeorient.lib_type_orient ASC',
					'Structurereferente.lib_struc ASC',
				)
			);

			$sessionKey = $Controller->Structurereferente->sessionKey( __FUNCTION__, $query );
			$results = $this->Session->read( $sessionKey );

			if( $results === null ) {
				$results = array(
					'optgroup' => array(),
					'optgroup_prefix' => array(),
					'ids' => array(),
					'ids_prefix' => array(),
					'list' => array(),
					'list_prefix' => array()
				);

				$structuresreferentes = $Controller->Structurereferente->find( 'all', $query );

				if( !empty( $structuresreferentes ) ) {
					foreach( $structuresreferentes as $structurereferente ) {
						$key = $structurereferente['Structurereferente']['id'];
						$keyPrefix = "{$structurereferente['Structurereferente']['typeorient_id']}_{$structurereferente['Structurereferente']['id']}";

						// Cas optgroup
						if( !isset( $results['optgroup'][$structurereferente['Typeorient']['lib_type_orient']] ) ) {
							$results['optgroup'][$structurereferente['Typeorient']['lib_type_orient']] = array();
						}
						$results['optgroup'][$structurereferente['Typeorient']['lib_type_orient']][$key] = $structurereferente['Structurereferente']['lib_struc'];
						$results['optgroup_prefix'][$structurereferente['Typeorient']['lib_type_orient']][$keyPrefix] = $structurereferente['Structurereferente']['lib_struc'];

						// Cas ids
						$results['ids'][$key] = $structurereferente['Structurereferente']['id'];
						$results['ids_prefix'][$keyPrefix] = $structurereferente['Structurereferente']['id'];

                        // Cas list
						$results['list'][$key] = $structurereferente['Structurereferente']['lib_struc'];
						$results['list_prefix'][$keyPrefix] = $structurereferente['Structurereferente']['lib_struc'];
					}
				}

				// Pour les listes optgroup, tri par clé
				ksort( $results['optgroup'] );
				ksort( $results['optgroup_prefix'] );

				$this->Session->write( $sessionKey, $results );
			}

			if( !empty( $results ) ) {
				if( $options['type'] === self::TYPE_OPTGROUP ) {
					$results = $options['prefix'] ? $results['optgroup_prefix'] : $results['optgroup'];
				}
				else if( $options['type'] === self::TYPE_IDS ) {
					$results = $options['prefix'] ? $results['ids_prefix'] : $results['ids'];
				}
				else {
					$results = $options['prefix'] ? $results['list_prefix'] : $results['list'];
				}
			}

			return $results;
		}

		/**
		 * Retourne la liste des référents actifs (pour un dependant select avec
		 * la structure référente) liés à une structure référente active, liée à
		 * un type d'oientation actif.
		 * Mise en cache dans la session de l'utilisateur.
		 *
		 * <pre>
		 * array(
		 * 	'conditions' => array(
		 *		'Typeorient.actif' => 'O',
		 *		'Structurereferente.actif' => 'O',
		 *		'Referent.actif' => 'O'
		 *	),
		 * 	'prefix' => true,
		 * 	'type' => 'list'
		 * );
		 * </pre>
		 *
		 * @todo Referent::listOptions() -> actif, prefix
		 * @todo InsertionsAllocataires::referents -> actif, sans prefix ni optgroup
		 *
		 * @param array $options
		 * @return array
		 */
		public function referents( $options = array() ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$options += array(
				'conditions' => $this->conditions[__FUNCTION__],
				'prefix' => true,
				'type' => self::TYPE_LIST
			);

			if( ( Configure::read( 'Cg.departement' ) == 93 ) && $this->Session->read( 'Auth.User.filtre_zone_geo' ) !== false ) {
				$options['conditions'][] = $this->_sqStructurereferenteZonesgeographiques93();
			}

			$query = array(
				'fields' => array_merge(
					$Controller->Structurereferente->Typeorient->fields(),
					$Controller->Structurereferente->fields(),
					$Controller->Structurereferente->Referent->fields()
				),
				'joins' => array(
					$Controller->Structurereferente->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					$Controller->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) )
				),
				'conditions' => $options['conditions'],
				'contain' => false,
				'order' => array(
					'Referent.nom ASC',
					'Referent.prenom ASC'
				)
			);

			$sessionKey = $this->sessionKey( __FUNCTION__, $query );
			$results = $this->Session->read( $sessionKey );

			if( $results === null ) {
				$results = array(
					'optgroup' => array(),
					'optgroup_prefix' => array(),
					'ids' => array(),
					'ids_prefix' => array(),
					'list' => array(),
					'list_prefix' => array()
				);

				$referents = $Controller->Structurereferente->Referent->find( 'all', $query );

				if( !empty( $referents ) ) {
					foreach( $referents as $referent ) {
						$libelle = "{$referent['Referent']['qual']} {$referent['Referent']['nom']} {$referent['Referent']['prenom']}";

						$key = $referent['Referent']['id'];
						$keyPrefix = "{$referent['Referent']['structurereferente_id']}_{$referent['Referent']['id']}";

						// Cas optgroup
						if( !isset( $results['optgroup'][$referent['Structurereferente']['lib_struc']] ) ) {
							$results['optgroup'][$referent['Structurereferente']['lib_struc']] = array();
						}
						$results['optgroup'][$referent['Structurereferente']['lib_struc']][$key] = $libelle;
						$results['optgroup_prefix'][$referent['Structurereferente']['lib_struc']][$keyPrefix] = $libelle;

						// Cas seulement les ids
						$results['ids'][$key] = $referent['Referent']['id'];
						$results['ids_prefix'][$keyPrefix] = $referent['Referent']['id'];

                        // Cas du find list
						$results['list'][$key] = $libelle;
						$results['list_prefix'][$keyPrefix] = $libelle;
					}
				}

				$this->Session->write( $sessionKey, $results );
			}

			if( !empty( $results ) ) {
				if( $options['type'] === self::TYPE_OPTGROUP ) {
					$results = $options['prefix'] ? $results['optgroup_prefix'] : $results['optgroup'];
				}
				else if( $options['type'] === self::TYPE_IDS ) {
					$results = $options['prefix'] ? $results['ids_prefix'] : $results['ids'];
				}
				else {
					$results = $options['prefix'] ? $results['list_prefix'] : $results['list'];
				}
			}

			return $results;
		}

		/**
		 * Permet d'ajouter les entrées de la structure référente et du référent
		 * de l'enregistrement à la liste des options pour ne pas perdre d'information
		 * lors de la modification d'un enregistrement.
		 *
		 * Le liste des structures référentes est une liste à deux niveaux avec
		 * en premier niveau le type d'orientation.
		 *
		 * La liste des référents est une liste à un niveau avec en clé
		 * <structurereferente_id>_<referent_id>.
		 *
		 * @fixme params options['prefix'] et options['type']
		 *
		 * @param array $options Les options qui seront envoyées à la vue
		 * @param array $data L'enregistrement en cours de modification
		 * @param array $params Les clés structurereferente_id et referent_id
		 *	contiennent les chemins vers ces champs, dans les options et dans data.
		 * @return array
		 */
		public function completeOptionsWithCurrentReferent( array $options, array $data, array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$params += array(
				'structurereferente_id' => 'structurereferente_id',
				'referent_id' => 'referent_id'
			);

			$structurereferente_id = Hash::get( $data, $params['structurereferente_id'] );
			$referent_id = Hash::get( $data, $params['referent_id'] );

			$structuresreferentes = (array)Hash::get( $options, $params['structurereferente_id'] );
			$referents = (array)Hash::get( $options, $params['referent_id'] );

			$available = array();
			foreach( $structuresreferentes as $group ) {
				$available = array_merge( $available, array_keys( $group ) );
			}

			if( in_array( $structurereferente_id, $available ) === false || in_array( $referent_id, $referents ) === false ) {
				$query = array(
					'fields' => array(
						'Typeorient.lib_type_orient',
						'Structurereferente.id',
						'Structurereferente.lib_struc',
						'Referent.id',
						'Referent.nom_complet',
					),
					'contain' => false,
					'joins' => array(
						$Controller->Structurereferente->Typeorient->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$Controller->Structurereferente->join( 'Referent', array( 'type' => 'INNER' ) )
					),
					'conditions' => array(
						'Structurereferente.id' => $structurereferente_id,
						'Referent.id' => suffix( $referent_id )
					)
				);

				$forceVirtualFields = $Controller->Structurereferente->Typeorient->forceVirtualFields;
				$Controller->Structurereferente->Typeorient->forceVirtualFields = true;
				$result = $Controller->Structurereferente->Typeorient->find( 'first', $query );
				$Controller->Structurereferente->Typeorient->forceVirtualFields = $forceVirtualFields;

				if( !empty( $result ) ) {
					$structuresreferentes = Hash::merge(
						$structuresreferentes,
						array(
							$result['Typeorient']['lib_type_orient'] => array(
								$result['Structurereferente']['id'] => $result['Structurereferente']['lib_struc']
							)
						)
					);
					$options = Hash::insert( $options, $params['structurereferente_id'], $structuresreferentes );

					$referents = Hash::merge(
						$referents,
						array(
							"{$result['Structurereferente']['id']}_{$result['Referent']['id']}" => $result['Referent']['nom_complet']
						)
					);
					$options = Hash::insert( $options, $params['referent_id'], $referents );
				}
			}

			return $options;
		}
	}
?>