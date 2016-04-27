<?php
	/**
	 * Code source de la classe WebrsaTableausuivipdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe WebrsaTableausuivipdv93 ...
	 *
	 * @package app.Model
	 */
	class WebrsaTableausuivipdv93 extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaTableausuivipdv93';

		/**
		 * Ce modèle n'est pas lié à une table.
		 *
		 * @var string|boolean
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array();

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Tableausuivipdv93' );

		/**
		 * Retourne le querydata utilisé dans la recherche de tableaux de suivi.
		 *
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types += array(
				'Communautesr' => 'LEFT OUTER',
				'Pdv' => 'LEFT OUTER',
				'Photographe' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER'
			);

			$vfNomcomplet = $this->Tableausuivipdv93->Photographe->sqVirtualfield( 'nom_complet', false );

			// Jointure spéciale pour le PDV: soit via Pdv, soit via la structure du référent
			$joins = array(
				'Structurereferente' => array_words_replace(
					$this->Tableausuivipdv93->Referent->join( 'Structurereferente', array( 'type' => $types['Pdv'] ) ),
					array( 'Structurereferente' => 'Pdv' )
				),
				'Pdv' => $this->Tableausuivipdv93->join( 'Pdv', array( 'type' => $types['Pdv'] ) )
			);
			$joinPdv = $joins['Pdv'];
			$joinPdv['conditions'] = array(
				'OR' => array(
					$joins['Pdv']['conditions'],
					$joins['Structurereferente']['conditions']
				)
			);

			$query = array(
				'fields' => array(
					'Tableausuivipdv93.id',
					'Tableausuivipdv93.annee',
					'Tableausuivipdv93.type',
					'Communautesr.name',
					'Pdv.lib_struc',
					$this->Tableausuivipdv93->Referent->sqVirtualField( 'nom_complet' ),
					'Tableausuivipdv93.name',
					'Tableausuivipdv93.version',
					"( CASE WHEN \"Photographe\".\"id\" IS NOT NULL THEN {$vfNomcomplet} ELSE 'Photographie automatique' END ) AS \"Photographe__nom_complet\"",
					'Tableausuivipdv93.created',
					'Tableausuivipdv93.modified',
				),
				'contain' => false,
				'joins' => array(
					$this->Tableausuivipdv93->join( 'Communautesr', array( 'type' => $types['Communautesr'] ) ),
					$this->Tableausuivipdv93->join( 'Photographe', array( 'type' => $types['Photographe'] ) ),
					$this->Tableausuivipdv93->join( 'Referent', array( 'type' => $types['Referent'] ) ),
					$joinPdv
				),
				'order' => array(
					'Tableausuivipdv93.annee DESC',
					'Pdv.lib_struc ASC',
					'Referent.nom_complet ASC',
					'Tableausuivipdv93.name ASC',
					'Tableausuivipdv93.modified DESC'
				)
			);

			return $query;
		}

		/**
		 * Complète le querydata avec des conditions issues des filtres du moteur
		 * de recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search = array() ) {
			// 1. Valeurs simples
			$fields = array( 'annee' => 'annee', 'tableau' => 'name', 'type' => 'type' );
			foreach( $fields as $searchField => $tableField ) {
				$value = Hash::get( $search, "Search.{$searchField}" );
				if( !empty( $value ) ) {
					$query['conditions']["Tableausuivipdv93.{$tableField}"] = $value;
				}
			}

			// 2. Valeurs particulières avec potentiellement la chaîne de caractères NULL
			// FIXME: si je choisis la SR "Conseil général", j'ai aussi les communautés
			$fields = array( 'communautesr_id', 'user_id' );
			foreach( $fields as $field ) {
				$value = suffix( Hash::get( $search, "Search.{$field}" ) );
				if( !empty( $value ) ) {
					if( $value == 'NULL' ) {
						$query['conditions'][] = "Tableausuivipdv93.{$field} IS NULL";
					}
					else {
						$query['conditions']["Tableausuivipdv93.{$field}"] = $value;
					}
				}
			}

			$referent_id = suffix( Hash::get( $search, 'Search.referent_id' ) );
			if( !empty( $referent_id ) ) {
				$query['conditions']['Referent.id'] = $referent_id;
			}
			else {
				$structurereferente_id = suffix( Hash::get( $search, 'Search.structurereferente_id' ) );
				if( !empty( $structurereferente_id ) ) {
					$query['conditions'][] = array( 'Pdv.id' => $structurereferente_id );
				}
			}

			return $query;
		}

		/**
		 * Retourne la liste des photographes des tableaux PDV.
		 *
		 * @fixme la liste des photographes contient des doublons
		 *
		 * @return array
		 */
		public function listePhotographes() {
			$sq = $this->Tableausuivipdv93->sq( array( 'fields' => array( 'DISTINCT(user_id)' ) ) );

			$list = $this->Tableausuivipdv93->Photographe->find(
				'list',
				array(
					'fields' => array( 'Photographe.id', 'Photographe.nom_complet' ),
					'contain' => false,
					'order' => array( 'Photographe.nom_complet' ),
					'conditions' => array(
						"Photographe.id IN ( {$sq} )"
					)
				)
			);

			$list = Hash::merge( array( 'NULL' => 'Photographie automatique' ), $list );

			return $list;
		}

		/**
		 * Retourne la liste des référents des PDV pour lesquels les tableaux de
		 * PDV doivent être calculés.
		 *
		 * @see Tableausuivipdv93.conditionsPdv dans le webrsa.inc
		 *
		 * @param integer $structurereferente_id L'id du PDV pour filtrage éventuel
		 * @return array
		 */
		public function listeReferentsPdvs( $structurereferente_id = null ) {
			$query = array(
				'fields' => array(
					'( "Structurereferente"."id" || \'_\' || "Referent"."id" ) AS "Referent__id"',
					'Referent.nom_complet'
				),
				'contain' => false,
				'joins' => array(
					$this->Tableausuivipdv93->Pdv->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					$this->Tableausuivipdv93->Pdv->Referent->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array_words_replace(
					(array)Configure::read( 'Tableausuivipdv93.conditionsPdv' ),
					array( 'Pdv' => 'Structurereferente' )
				),
				'order' => array( 'Referent.nom_complet_court' )
			);

			if( !empty( $structurereferente_id ) ) {
				$query['conditions']['Referent.structurereferente_id'] = $structurereferente_id;
			}

			$results = $this->Tableausuivipdv93->Pdv->Referent->find( 'all', $query );
			$results = Hash::combine( $results, '{n}.Referent.id', '{n}.Referent.nom_complet' );

			return $results;
		}

		/**
		 * Retourne la liste des PDV pour lesquels les tableaux de PDV doivent
		 * être calculés.
		 *
		 * @see Tableausuivipdv93.conditionsPdv dans le webrsa.inc
		 *
		 * @return array
		 */
		public function listePdvs() {
			return $this->Tableausuivipdv93->Pdv->find(
				'list',
				array(
					'contain' => false,
					'joins' => array(
						$this->Tableausuivipdv93->Pdv->join( 'Typeorient', array( 'type' => 'INNER' ) ),
					),
					'conditions' => (array)Configure::read( 'Tableausuivipdv93.conditionsPdv' ),
					'order' => array( 'Pdv.lib_struc' )
				)
			);
		}

		/**
		 * @todo Tableausuivipdv93::getOptions()
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$params += array(
				'user_type' => null,
				'tableau' => null,
				'structuresreferentes' => null,
				'referents' => null
			);

			if( $params['tableau'] === null ) {
				$params['structuresreferentes']['NULL'] = 'Conseil général';
			}

			$years = array_reverse( range( 2009, date( 'Y' ) ) );

			$options = array(
				'Search' => array(
					'annee' => array_combine( $years, $years ),
					'communautesr_id' => $this->Tableausuivipdv93->Communautesr->find( 'list' ),
					'structurereferente_id' => $params['structuresreferentes'],
					'referent_id' => $params['referents'],
					'user_id' => $this->listePhotographes(),
					'tableau' => $this->Tableausuivipdv93->tableaux,
					'typethematiquefp93_id' => ClassRegistry::init( 'Thematiquefp93' )->enum( 'type' ),
					'mode' => array( 'fse' => 'FSE', 'statistiques' => 'Statistiques' )
				),
				'problematiques' => $this->Tableausuivipdv93->problematiques(),
				'acteurs' => $this->Tableausuivipdv93->acteurs(),
				'Tableausuivipdv93' => array( 'name' => $this->Tableausuivipdv93->tableaux )
			);

			return $options;
		}
	}
?>