<?php
	/**
	 * Code source de la classe InsertionsAllocatairesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe InsertionsAllocatairesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class InsertionsAllocatairesComponent extends Component
	{
		/**
		 *
		 * @var string
		 */
		public $name = 'InsertionsAllocataires';

		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array( 'Session' );

		/**
		 * Appelée avant Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
		 */
		public function initialize( Controller $controller ) {
			$this->Controller = $controller;
		}

		/**
		 *
		 * <pre>
		 * $options = array(
		 * 	'conditions' => array(),
		 * 	'optgroup' => false,
		 *	'ids' => false,
		 * );
		 * </pre>
		 *
		 * @param array $options
		 * @return array
		 */
		public function structuresreferentes( $options = array( ) ) {
			$Structurereferente = ClassRegistry::init( 'Structurereferente' );

			$options = Set::merge(
				array(
					'conditions' => array(),
					'optgroup' => false,
					'ids' => false,
				),
				$options
			);

			$conditions = array(
				'Typeorient.actif' => 'O',
				'Structurereferente.actif' => 'O',
			);

			$conditions = Set::merge( $conditions, $options['conditions'] );
			$serializedConditions = serialize( $conditions );
			$sessionKey = 'Auth.InsertionsAllocataires.'.sha1( $serializedConditions );
			$results = $this->Session->read( $sessionKey );

			if( is_null( $results ) ) {
				$results = array();

				if( ( Configure::read( 'Cg.departement' ) == 93 ) && $this->Session->read( 'Auth.User.filtre_zone_geo' ) !== false ) {
					$zonesgeographiques_ids = array_keys( $this->Session->read( 'Auth.Zonegeographique' ) );

					$sqStructurereferente = $Structurereferente->StructurereferenteZonegeographique->sq(
						array(
							'alias' => 'structuresreferentes_zonesgeographiques',
							'fields' => array( 'structuresreferentes_zonesgeographiques.structurereferente_id' ),
							'conditions' => array(
								'structuresreferentes_zonesgeographiques.zonegeographique_id' => $zonesgeographiques_ids
							),
							'contain' => false
						)
					);
					$conditions[] = "Structurereferente.id IN ( {$sqStructurereferente} )";
				}

				$tmps = $Structurereferente->find(
					'all',
					array(
						'fields' => array_merge(
							$Structurereferente->Typeorient->fields(),
							$Structurereferente->fields()
						),
						'joins' => array(
							$Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) )
						),
						'conditions' => $conditions,
						'contain' => false,
						'order' => array(
							'Typeorient.lib_type_orient ASC',
							'Structurereferente.lib_struc ASC',
						)
					)
				);

				if( !empty( $tmps ) ) {
					foreach( $tmps as $tmp ) {
						// Cas optgroup, structurereferente_id
						if( !isset( $results['optgroup'][$tmp['Typeorient']['lib_type_orient']] ) ) {
							$results['optgroup'][$tmp['Typeorient']['lib_type_orient']] = array();
						}
						$results['optgroup'][$tmp['Typeorient']['lib_type_orient']][$tmp['Structurereferente']['id']] = $tmp['Structurereferente']['lib_struc'];

						// Cas seulement les ids
						$results['ids'][] = $tmp['Structurereferente']['id'];

						// Cas typeorient_id_structurereferente_id
						$results['normal']["{$tmp['Structurereferente']['typeorient_id']}_{$tmp['Structurereferente']['id']}"] = $tmp['Structurereferente']['lib_struc'];
					}
				}

				$this->Session->write( $sessionKey, $results );
			}

			// Cas optgroup, structurereferente_id
			if( $options['optgroup'] ) {
				$results = $results['optgroup'];
			}
			// Cas où l'on ne veut que les ids des structures référentes
			else if( $options['ids'] ) {
				$results = $results['ids'];
			}
			// Cas typeorient_id_structurereferente_id
			else {
				$results = $results['normal'];
			}

			return $results;
		}

		/**
		 *
		 * @param type $options
		 * @return type
		 */
		public function referents( $options = array() ) {
			$Referent = ClassRegistry::init( 'Referent' );

			$options = Set::merge(
				array(
					'conditions' => array(),
				),
				$options
			);

			$conditions = array(
				'Typeorient.actif' => 'O',
				'Structurereferente.actif' => 'O',
				'Referent.actif' => 'O',
			);

			$conditions = Set::merge( $conditions, $options['conditions'] );

			if( ( Configure::read( 'Cg.departement' ) == 93 ) && $this->Session->read( 'Auth.User.filtre_zone_geo' ) !== false ) {
				$zonesgeographiques_ids = array_keys( $this->Session->read( 'Auth.Zonegeographique' ) );

				$sqStructurereferente = $Referent->Structurereferente->StructurereferenteZonegeographique->sq(
					array(
						'alias' => 'structuresreferentes_zonesgeographiques',
						'fields' => array( 'structuresreferentes_zonesgeographiques.structurereferente_id' ),
						'conditions' => array(
							'structuresreferentes_zonesgeographiques.zonegeographique_id' => $zonesgeographiques_ids
						),
						'contain' => false
					)
				);
				$conditions[] = "Structurereferente.id IN ( {$sqStructurereferente} )";
			}

			$tmps = $Referent->find(
				'all',
				array(
					'fields' => array_merge(
						$Referent->Structurereferente->Typeorient->fields(),
						$Referent->Structurereferente->fields(),
						$Referent->fields()
					),
					'joins' => array(
						$Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$Referent->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) ),
					),
					'conditions' => $conditions,
					'contain' => false,
					'order' => array(
						'Referent.nom ASC',
						'Referent.prenom ASC',
					)
				)
			);

			if( !empty( $tmps ) ) {
				$ids = Set::extract( $tmps, '/Referent/id' );
				$values = Set::format( $tmps, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
				$results = array_combine( $ids, $values );
			}

			return $results;
		}
	}
?>