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
		 * Retourne les options par défaut pour les différentes méthodes.
		 *
		 * @param string $method
		 * @param array $options
		 * @return array
		 * @throws RuntimeException
		 */
		public function options( $method, array $options = array() ) {
			switch( $method ) {
				case 'typesorients':
					$options += array(
						'conditions' => $this->conditions['typesorients'],
						'empty' => false,
						'cache' => true
					);
					break;
				case 'structuresreferentes':
					$options += array(
						'conditions' => $this->conditions['structuresreferentes'],
						'prefix' => true,
						'type' => self::TYPE_LIST,
						'cache' => true
					);
					break;
				case 'referents':
					$options += array(
						'conditions' => $this->conditions['referents'],
						'prefix' => true,
						'type' => self::TYPE_LIST,
						'cache' => true
					);
					break;
				default:
					$msgstr = sprintf( 'La méthode %s:%s n\'accepte pas la valeur %s comme paramètre $method', __CLASS__, __FUNCTION__, $method );
					throw new RuntimeException( $msgstr, 500 );
			}

			return $options;
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
		 * 	'empty' => false,
		 * 	'cache' => true
		 * );
		 * </pre>
		 *
		 * @todo Typeorient::listOptions (actif, parentid NULL)
		 *
		 * @see options()
		 *
		 * @param array $options La clé conditions permet de spécifier ou
		 *	de surcharger les conditions, la clé empty permet de spécifier si
		 *	l'on veut une entrée dont la clé sera 0 et la valeur 'Non orienté'.
		 * @return array
		 */
		public function typesorients( array $options = array() ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$options = $this->options( __FUNCTION__, $options );

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

			if( $results === null || false == $options['cache'] ) {
				$results = array();

				$typesorients = $Controller->Structurereferente->Typeorient->find( 'all', $query );

				if( !empty( $typesorients ) ) {
					foreach( $typesorients as $typeorient ) {
						$results[$typeorient['Typeorient']['id']] = $typeorient['Typeorient']['lib_type_orient'];
					}
				}

				if( true == $options['cache'] ) {
					$this->Session->write( $sessionKey, $results );
				}
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
						'structuresreferentes_zonesgeographiques.zonegeographique_id' => array_keys( (array)$this->Session->read( 'Auth.Zonegeographique' ) )
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
		 * 	'type' => 'list',
		 * 	'cache' => true
		 * );
		 * </pre>
		 *
		 * @param array $options
		 * @return array
		 */
		public function structuresreferentes( $options = array( ) ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$options = $this->options( __FUNCTION__, $options );

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

			$sessionKey = $this->sessionKey( __FUNCTION__, $query );
			$results = $this->Session->read( $sessionKey );

			if( $results === null || false == $options['cache'] ) {
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

				asort( $results['list'] );
				asort( $results['list_prefix'] );

				if( true == $options['cache'] ) {
					$this->Session->write( $sessionKey, $results );
				}
			}

			if( !empty( $results ) ) {
				if( $options['type'] === self::TYPE_OPTGROUP ) {
					$results = $options['prefix'] ? $results['optgroup_prefix'] : $results['optgroup'];
				}
				else if( $options['type'] === self::TYPE_IDS ) {
					$results = $options['prefix'] ? $results['ids_prefix'] : $results['ids'];
				}
				else if( $options['type'] === self::TYPE_LIST ) {
					$results = $options['prefix'] ? $results['list_prefix'] : $results['list'];
				}
				else {
					$msgstr = sprintf( 'La valeur du paramètre "type" "%s" n\'est pas acceptée dans la méthode %s::%s', $options['type'], __CLASS__, __FUNCTION__ );
					throw new RuntimeException( $msgstr, 500 );
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
		 * 	'type' => 'list',
		 * 	'cache' => true
		 * );
		 * </pre>
		 *
		 * @todo Referent::listOptions() -> actif, prefix
		 *
		 * @param array $options
		 * @return array
		 */
		public function referents( $options = array() ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$options = $this->options( __FUNCTION__, $options );

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

			if( $results === null || false == $options['cache'] ) {
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

				 if( true == $options['cache'] ) {
					$this->Session->write( $sessionKey, $results );
				 }
			}

			if( !empty( $results ) ) {
				if( $options['type'] === self::TYPE_OPTGROUP ) {
					$results = $options['prefix'] ? $results['optgroup_prefix'] : $results['optgroup'];
				}
				else if( $options['type'] === self::TYPE_IDS ) {
					$results = $options['prefix'] ? $results['ids_prefix'] : $results['ids'];
				}
				else if( $options['type'] === self::TYPE_LIST ) {
					$results = $options['prefix'] ? $results['list_prefix'] : $results['list'];
				}
				else {
					$msgstr = sprintf( 'La valeur du paramètre "type" "%s" n\'est pas acceptée dans la méthode %s::%s', $options['type'], __CLASS__, __FUNCTION__ );
					throw new RuntimeException( $msgstr, 500 );
				}
			}

			return $results;
		}

		/**
		 * Permet d'ajouter les entrées du type d'orientation, de la structure
		 * référente et du référent de l'enregistrement à la liste des options
		 * pour ne pas perdre d'information lors de la modification d'un
		 * enregistrement.
		 * Les entrées ajoutées ne sont pas triées.
		 *
		 * <pre>
		 * array(
		 *	'typesorients' => array(
		 *		'path' => 'typeorient_id',
		 *		'cache' => false
		 *	),
		 *	'structuresreferentes' => array(
		 *		'path' => 'structurereferente_id',
		 *		'cache' => false
		 *	),
		 *	'referents' => array(
		 *		'path' => 'referent_id',
		 *		'cache' => false
		 *	)
		 * );
		 * </pre>
		 *
		 * @see InsertionsBeneficiairesComponent::options qui sera utilisée pour
		 * les autres paramètres.
		 *
		 * @param array $options Les options qui seront envoyées à la vue
		 * @param array $data L'enregistrement en cours de modification
		 * @param array $params Les paramètres à utiliser pour chacune des méthodes
		 * @return array
		 */
		public function completeOptions( array $options, array $data, array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$Controller->loadModel( 'Structurereferente' );

			$tmpParams = array(
				'typesorients' => array(
					'path' => 'typeorient_id',
					'cache' => false
				),
				'structuresreferentes' => array(
					'path' => 'structurereferente_id',
					'cache' => false
				),
				'referents' => array(
					'path' => 'referent_id',
					'cache' => false
				)
			);

			foreach( $tmpParams as $method => $tmpParam ) {
				$foo = Hash::get( $params, $method );
				if( false !== $foo ) {
					$params[$method] = $this->options( $method, (array)$foo + $tmpParam );
				}
				else {
					$params[$method] = false;
				}
			}

			foreach( $params as $method => $methodParams ) {
				if( Hash::check( $data, $methodParams['path'] ) && false !== $methodParams ) {
					$value = Hash::get( $data, $methodParams['path'] );
					if( false === empty( $value ) ) {
						$methodParams['conditions'] = array( Inflector::classify( $method ).'.id' => suffix( $value ) );
						$results = $this->{$method}( $methodParams );
						$options[$methodParams['path']] = Hash::merge( $options[$methodParams['path']], $results );
					}
				}
			}

			return $options;
		}
	}
?>