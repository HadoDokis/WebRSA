<?php
	/**
	 * Code source de la classe Apre.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Apre ...
	 *
	 * @package app.Model
	 */
	class Apre extends AppModel
	{
		public $name = 'Apre';

		public $displayField = 'numeroapre';

		public $aidesApre = array( 'Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' );

		public $modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );

		public $deepAfterFind = true;

		public $actsAs = array(
			'Allocatairelie',
			'Enumerable' => array(
				'fields' => array(
					'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
					'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
					'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
					'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
					'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
// 					'ajoutcomiteexamen' => array( 'type' => 'no', 'domain' => 'apre' ),
					'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' ),
					'eligibiliteapre' => array( 'type' => 'eligibiliteapre', 'domain' => 'apre' ),
// 					'presence' => array( 'type' => 'presence', 'domain' => 'apre' ),
					'justificatif' => array( 'type' => 'justificatif', 'domain' => 'apre' ),
					'isdecision' => array( 'domain' => 'apre' ),
					'haspiecejointe' => array( 'domain' => 'apre' )
				)
			),
			'Frenchfloat' => array(
				'fields' => array(
					'montantaverser',
					'montantattribue',
					'montantdejaverse'
				)
			),
			'Formattable',
			'Gedooo.Gedooo',
			'StorablePdf' => array(
				'afterSave' => 'deleteAll'
			),
			'ModelesodtConditionnables' => array(
				93 => 'APRE/apre.odt'
			)
		);

		public $validate = array(
			'secteurprofessionnel' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'typedemandeapre' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'activitebeneficiaire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'montantaverser' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
			),
			'montantattribue' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez entrer un nombre positif.'
				)
			),
			'structurereferente_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'nbheurestravaillees' => array(
				array(
					'rule' => array( 'comparison', '>=', 0 ),
					'message' => 'Veuillez saisir une valeur positive.',
					'allowEmpty' => true
				)
			),
			'datedemandeapre' => array(
				'rule' => 'date',
				'message' => 'Veuillez vérifier le format de la date.',
				'allowEmpty' => true
			),
			'dateentreeemploi' => array(
				'rule' => 'date',
				'message' => 'Veuillez vérifier le format de la date.',
				'allowEmpty' => true
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Acccreaentr' => array(
				'className' => 'Acccreaentr',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Acqmatprof' => array(
				'className' => 'Acqmatprof',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Actprof' => array(
				'className' => 'Actprof',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Amenaglogt' => array(
				'className' => 'Amenaglogt',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Permisb' => array(
				'className' => 'Permisb',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Formqualif' => array(
				'className' => 'Formqualif',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Formpermfimo' => array(
				'className' => 'Formpermfimo',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Locvehicinsert' => array(
				'className' => 'Locvehicinsert',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);


		public $hasMany = array(
			'Montantconsomme' => array(
				'className' => 'Montantconsomme',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Relanceapre' => array(
				'className' => 'Relanceapre',
				'foreignKey' => 'apre_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Fichiermodule' => array(
                'className' => 'Fichiermodule',
                'foreignKey' => false,
                'dependent' => false,
                'conditions' => array(
                    'Fichiermodule.modele = \'Apre\'',
                    'Fichiermodule.fk_value = {$__cakeID__$}'
                ),
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
            )
		);


		public $hasAndBelongsToMany = array(
			'Comiteapre' => array(
				'className' => 'Comiteapre',
				'joinTable' => 'apres_comitesapres',
				'foreignKey' => 'apre_id',
				'associationForeignKey' => 'comiteapre_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ApreComiteapre'
			),
			'Etatliquidatif' => array(
				'className' => 'Etatliquidatif',
				'joinTable' => 'apres_etatsliquidatifs',
				'foreignKey' => 'apre_id',
				'associationForeignKey' => 'etatliquidatif_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ApreEtatliquidatif'
			),
			'Pieceapre' => array(
				'className' => 'Pieceapre',
				'joinTable' => 'apres_piecesapre',
				'foreignKey' => 'apre_id',
				'associationForeignKey' => 'pieceapre_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'AprePieceapre'
			)
		);

		/**
		 * Surcharge du constructeur pour ajouter des champs virtuels.
		 *
		 * @param mixed $id Set this ID for this model on startup, can also be an array of options, see above.
		 * @param string $table Name of database table to use.
		 * @param string $ds DataSource connection name.
		 */
		public function __construct( $id = false, $table = null, $ds = null ) {
			parent::__construct( $id, $table, $ds );

			$departement = Configure::read( 'Cg.departement' );
			// Seulement pour le CG 93, lorsque l'on n'est pas en train d'importer des fixtures
			// TODO: mise en cache ?
			if( !( unittesting() && $this->useDbConfig === 'default' ) && $departement === 93 ) {
				$this->virtualFields['natureaide'] = $this->vfListeAidesLiees93( null );
			}
		}

		/**
		*
		*/

		public function sqApreNomaide() {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$natureAidesApres = ClassRegistry::init( 'Option' )->natureAidesApres();

			$case = "CASE \n";
			foreach( array_keys( $natureAidesApres ) as $aideModel ) {
				$tableName = $dbo->fullTableName( $this->{$aideModel}, false, false );
				$case .= "WHEN EXISTS( SELECT * FROM {$tableName} AS \"{$aideModel}\" WHERE \"Apre\".\"id\" = \"{$aideModel}\".\"apre_id\" ) THEN '{$aideModel}'\n";
			}
			$case .= 'ELSE NULL END';

			return $case;
		}

		/**
		*
		*/

		public function sqApreAllocation() {
			return "CASE WHEN \"Apre\".\"statutapre\" = 'F' THEN \"Apre\".\"mtforfait\" ELSE \"ApreEtatliquidatif\".\"montantattribue\" END";
		}

		/**
		*
		*/

		public function sousRequeteMontanttotal() {
			$fieldTotal = array();
			foreach( $this->aidesApre as $modelAide ) {
				$fieldTotal[] = "\"{$modelAide}\".\"montantaide\"";
			}
			return '( COALESCE( '.implode( ', 0 ) + COALESCE( ', $fieldTotal ).', 0 ) )';
		}

		/**
		*
		*/

		public function joinsAidesLiees( $tiersprestataire = false ) {
			$joins = array();
			foreach( $this->aidesApre as $modelAide ) {
				$joins[] = array(
					'table'      => Inflector::tableize( $modelAide ),
					'alias'      => $modelAide,
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Apre.id = {$modelAide}.apre_id" )
				);
			}
			return $joins;
		}

		/**
		*
		*/

		public function qdFormationsPourPdf() {
			$querydata = array();
			$conditionsTiersprestataireapre = array();

			foreach( $this->modelsFormation as $modelAide ) {
				$querydata['joins'][] = array(
					'table'      => Inflector::tableize( $modelAide ),
					'alias'      => $modelAide,
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array( "Apre.id = {$modelAide}.apre_id" )
				);

				$conditionsTiersprestataireapre[] = "{$modelAide}.tiersprestataireapre_id = Tiersprestataireapre.id";
			}

			$querydata['fields'] = array(
				'Tiersprestataireapre.nomtiers',
				'Tiersprestataireapre.guiban',
				'Tiersprestataireapre.etaban',
				'Tiersprestataireapre.numcomptban',
				'Tiersprestataireapre.clerib'
			);

			$querydata['joins'][] = array(
				'table'      => Inflector::tableize( 'Tiersprestataireapre' ),
				'alias'      => 'Tiersprestataireapre',
				'type'       => 'LEFT OUTER',
				'foreignKey' => false,
				'conditions' => array( 'OR' => $conditionsTiersprestataireapre )
			);

			return $querydata;
		}

		/**
		*   Récupération des pièces liées à une APRE ainsi que les pièces des aides liées à cette APRE
		*/

		public function _nbrNormalPieces() {
			$nbNormalPieces = array();
			$nbNormalPieces['Apre'] = $this->Pieceapre->find( 'count' );
			foreach( $this->aidesApre as $model ) {
				$nbNormalPieces[$model] = $this->{$model}->{'Piece'.strtolower( $model )}->find( 'count' );
			}
			return $nbNormalPieces;
		}

		/**
		*   Détails des APREs afin de récupérer les pièces liés à cette APRE ainsi que les aides complémentaires avec leurs pièces
		*   @param int $id
		*/

		public function _details( $apre_id ) {
			$nbNormalPieces = $this->_nbrNormalPieces();
			$details['Piecepresente'] = array();
			$details['Piecemanquante'] = array();

			// Nombre de pièces trouvées par-rapport au nombre de pièces prévues - Apre
			$details['Piecepresente']['Apre'] = $this->AprePieceapre->find( 'count', array( 'conditions' => array( 'apre_id' => $apre_id ) ) );
			$details['Piecemanquante']['Apre'] = abs( $details['Piecepresente']['Apre'] - $nbNormalPieces['Apre'] );

			// Quelles sont les pièces manquantes
			$piecesPresentes = Set::extract(
                $this->AprePieceapre->find(
                    'all',
                    array(
                        'fields' => array( 'AprePieceapre.pieceapre_id' ),
                        'conditions' => array( 'apre_id' => $apre_id ),
                        'contain' => false
                    )
                ),
                '/AprePieceapre/pieceapre_id'
            );

			$conditions = array();
			if( !empty( $piecesPresentes ) ) {
				$conditions = array( 'NOT' => array( 'Pieceapre.id' => $piecesPresentes ) );
			}

			$piecesAbsentes = $this->Pieceapre->find( 'list', array( 'conditions' => $conditions, 'recursive' => -1 ) );
			$details['Piece']['Manquante']['Apre'] = $piecesAbsentes;

			/// Essaie de récupération des pièces des aides liées
			foreach( $this->aidesApre as $model ) {
				// Nombre de pièces trouvées par-rapport au nombre de pièces prévues pour chaque type d'aide
				$aides = $this->{$model}->find(
					'all',
					array(
						'conditions' => array(
							"$model.apre_id" => $apre_id
						),
                        'contain' => array(
                            'Piece'.strtolower( $model )
                        )
					)
				);

				// Combien d'aides liées à l'APRE sont présentes pour chaque type d'aide
				$details['Natureaide'][$model] = count( $aides );

				if( !empty( $aides ) ) {
					$details['Piecepresente'][$model] = count( Hash::filter( (array)Set::extract( $aides, '/Piece'.strtolower( $model ) ) ) );
					$details['Piecemanquante'][$model] = abs( $nbNormalPieces[$model] - $details['Piecepresente'][$model] );

					if( !empty( $details['Piecemanquante'][$model] ) ) {
						$piecesAidesPresentes = Set::extract(
							$aides,
							'/Piece'.strtolower( $model ).'/'.$model.'Piece'.strtolower( $model ).'/piece'.strtolower( $model ).'_id'
						);

						$piecesAidesAbsentes = array();
						$conditions = array();
						if( !empty( $piecesAidesPresentes ) ) {
							$conditions = array( 'NOT' => array( 'Piece'.strtolower( $model ).'.id' => $piecesAidesPresentes ) );
						}
						$piecesAidesAbsentes = $this->{$model}->{'Piece'.strtolower( $model )}->find( 'list', array( 'recursive' => -1, 'conditions' => $conditions ) );

						$details['Piece']['Manquante'][$model] = $piecesAidesAbsentes;
					}
				}
			}

			return $details;
		}

		/**
		*
		*/

		public function afterFind( $results, $primary = false ) {
			parent::afterFind( $results, $primary );

			if( $this->deepAfterFind && !empty( $results ) && Set::check( $results, '0.Apre' ) ) {
				foreach( $results as $key => $result ) {
					if( isset( $result['Apre']['id'] ) ) {
						$results[$key]['Apre'] = Set::merge(
							$results[$key]['Apre'],
							$this->_details( $result['Apre']['id'] )
						);
					}
					else if( isset( $result['Apre'][0]['id'] ) ) {
						foreach( $result['Apre'] as $key2 => $result2 ) {
							$results[$key]['Apre'][$key2] = Set::merge(
								$results[$key]['Apre'][$key2],
								$this->_details( $result2['id'] )
							);
						}
					}
				}
			}

			return $results;
		}

		/**
		*
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );
			$statutapre = Set::classicExtract( $this->data, "{$this->alias}.statutapre" );

			if( $statutapre == 'C' ) {
				$valide = true;
				$nbNormalPieces = $this->_nbrNormalPieces();
				foreach( $nbNormalPieces as $aide => $nbPieces ) {
					$key = 'Piece'.strtolower( $aide );
					if( isset( $this->data[$aide] ) && isset( $this->data[$key] ) && isset( $this->data[$key][$key] ) ) {
						$valide = ( count( $this->data[$key][$key] ) == $nbPieces ) && $valide;
					}
				}
				$this->data['Apre']['etatdossierapre'] = ( $valide ? 'COM' : 'INC' );
			}
			else if( $statutapre == 'F' ){
				$this->data['Apre']['etatdossierapre'] = 'COM';
			}

			if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'referent_id', $this->data[$this->name] ) ) {
				$this->data = Hash::insert( $this->data, "{$this->alias}.referent_id", suffix( Set::extract( $this->data, "{$this->alias}.referent_id" ) ) );
			}
			return $return;
		}

		/**
		*
		*/

		public function supprimeFormationsObsoletes( $apre ) {
			foreach( $this->modelsFormation as $formation ) {
				if( !isset( $apre[$formation] ) ) {
					$this->{$formation}->deleteAll( array( "{$formation}.apre_id" => Set::classicExtract( $apre, 'Apre.id' ) ), true, true );
				}
			}
		}

		/**
		*
		*/

		public function supprimeAidesObsoletes( $apre ) {
			foreach( $this->aidesApre as $formation ) {
				if( !isset( $apre[$formation] ) ) {
					$this->{$formation}->deleteAll( array( "{$formation}.apre_id" => Set::classicExtract( $apre, 'Apre.id' ) ), true, true );
				}
			}
		}

		/**
		*
		*/


		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			$details = $this->_details( $this->id );

			$personne_id = Set::classicExtract( $this->data, "{$this->alias}.personne_id" );
			$statutapre = Set::classicExtract( $this->data, "{$this->alias}.statutapre" );

			if( !empty( $personne_id ) && ( $statutapre == 'C' ) && Configure::read( 'Cg.departement' ) == 66 ){
				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = {$personne_id} AND apres.etatdossierapre = 'COM' AND ( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = {$personne_id} ) > 0;" ) && $return;

				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = {$personne_id} AND NOT ( apres.etatdossierapre = 'COM' AND ( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = {$personne_id} ) > 0 );" ) && $return;
			}
			else if( Configure::read( 'Cg.departement' ) == 93 ){
				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = {$personne_id} AND apres.etatdossierapre = 'COM';" ) && $return;
				$return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = {$personne_id} AND NOT ( apres.etatdossierapre = 'COM' );" ) && $return;
			}

			// FIXME: return ?
			return $return;
		}


		/**
		* Mise à jour des montants déjà versés pour chacune des APREs
		* FIXME: pas de valeur de retour car $return est à false ?
		*/

		public function calculMontantsDejaVerses( $apre_ids ) {
			$return = true;

			if( !is_array( $apre_ids ) ) {
				$apre_ids = array( $apre_ids );
			}

			foreach( $apre_ids as $id ) {
				$this->query( "UPDATE apres SET montantdejaverse = ( SELECT SUM( apres_etatsliquidatifs.montantattribue ) FROM apres_etatsliquidatifs WHERE apres_etatsliquidatifs.apre_id = {$id} GROUP BY apres_etatsliquidatifs.apre_id ) WHERE apres.id = {$id};" )/* && $return*/;
			}

			return $return;
		}

		/**
		* Retourne un querydata permettant de sélectionner des APREs pour les faire passer dans un comité
		* qui n'a pas encore eu lieu.
		*
		* Ces APREs:
		* 	- doivent:
		*		* être complémentaires
		*		* être complètes
		*		* être éligibles
		*		* avoir une date de demande inférieure ou égale à la date du comité
		*	- il doit être possible de les associer à ce comité-ci:
		*		* si ce n'est pas pour un recours
		*			- soit elles sont associées à ce comité-ci
		*			- soit elles ne sont associées à aucun comité
		*			- soit le dernier comité auquel elles ont été associées est plus ancien que celui-ci
		*			  et la décision est un ajournement
		*		* si c'est pour un recours
		*			- soit elles sont associées à ce comité-ci, avec un comite_pcd_id
		*			- soit le dernier comité auquel elles ont été associées est plus ancien que celui-ci,
		*			  la décision est un refus pour laquelle il existe un recours
		*
		* @param integer $comiteapre_id L'id du comité pour lequel on veut sélectionner des APREs
		* @param boolean $isRecours Le fait que les APREs que l'on recherche fassent l'objet d'un recours ou non.
		* @return mixed false si le comité pour lequel on demande la liqste n'existe pas, un querydata CakePHP sinon
		*/

		public function qdPourComiteapre( $comiteapre_id, $isRecours ) {
			$dbo = $this->getDataSource( $this->ApreComiteapre->useDbConfig );
			$comiteapre = $this->ApreComiteapre->Comiteapre->find(
				'first',
				array(
					'conditions' => array(
						'Comiteapre.id' => $comiteapre_id
					),
					'contain' => false
				)
			);

			if( empty( $comiteapre ) ) {
				return false;
			}

			$querydata = array(
				'fields' => array(
					'Apre.id',
					'Apre.numeroapre',
					'Apre.datedemandeapre',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
				),
				'conditions' => array(
					// L'APRE doit être complémentaire
					'Apre.statutapre' => 'C',
					// Le dossier d'APRE doit être complèt
					'Apre.etatdossierapre' => 'COM',
					// L'APRE doit être éligible
					'Apre.eligibiliteapre' => 'O',
					// La date de demande d'APRE doit être inférieure ou égale à la date du comité
					'Apre.datedemandeapre <=' => $comiteapre['Comiteapre']['datecomite'],
				),
				'contain' => array(
					'Personne'
				)
			);

			// FIXME: une demande de recours ajournée n'apparait pas dans les demandes de recours
			// INFO: 'apres_comitesapres.recoursapre' => 'O', pour les ajournements également, à priori ce n'est pas copié lors de la création d'un ajournement.
			/*
				20111009: APREs 93 - on veut savoir si un entrée d'apres_comitesapres qui est
				ajournée provient d'une demande de recours ou pas.
				L'idée est de ne pas casser le fonctionnement précédent en mettant
				une valeur de recoursapre à O si l'entrée ne référençait pas
				l'entrée du recours.
			*/
			/*
				FIXME: On ne peut jamais savoir si une APRE ajournée est un recours ou pas
				(ou juste concernant le passage précédent); elle apparaîtra toujours dans la
				liste des APREs pas en recours lors de la sélection avec un comité d'APRE.
			*/
			/*
				FIXME/TODO approuvé: pour les aj, on vérifiera que c'est un recours ou non en regardant si une autre entrée existe pour cette apre, indiquant un recours (pas d'autre condition)
			*/

			// Il doit être possible de les associer à ce comité-ci
			if( $isRecours ) {
				$querydata['conditions']['OR'] = array(
					// soit elles sont associées à ce comité-ci, avec un comite_pcd_id
					'Apre.id IN ('
						.$this->ApreComiteapre->sq(
							array(
								'fields' => array( 'apres_comitesapres.apre_id' ),
								'alias' => 'apres_comitesapres',
								'conditions' => array(
									'apres_comitesapres.comiteapre_id' => $comiteapre_id,
									'apres_comitesapres.comite_pcd_id IS NOT NULL'
								)
							)
						)
					.')',
					'Apre.id IN ('
						.$this->ApreComiteapre->sq(
							array(
								'alias' => 'apres_comitesapres',
								'fields' => array( 'apres_comitesapres.apre_id' ),
								'joins' => array(
									array(
										'table' => $dbo->fullTableName( $this->Comiteapre, true, false ),
										'alias' => 'comitesapres',
										'type' => 'INNER',
										'conditions' => array(
											'apres_comitesapres.comiteapre_id = comitesapres.id'
										)
									)
								),
								'conditions' => array(
									// la décision lors de l'association avec ce dernier comité a été émise et est un refus, tant que ce n'atait pas un ajournement
									'apres_comitesapres.decisioncomite' => 'REF',
									'apres_comitesapres.recoursapre' => 'O',
									'apres_comitesapres.id IN ('
										.$this->ApreComiteapre->sqDernierComiteApre(
											'Apre.id',
											array(
												'apres_comitesapres.comiteapre_id <>' => $comiteapre_id,
												'apres_comitesapres.decisioncomite <>' => 'AJ'
											)
										)
									.')',
									// la date et l'heure du dernier comité avec lequel elles sont associées est inférieure à la date et l'heure de ce comité-ci
									'CAST( comitesapres.datecomite || \' \' || comitesapres.heurecomite AS TIMESTAMP ) <=' => "{$comiteapre['Comiteapre']['datecomite']} {$comiteapre['Comiteapre']['heurecomite']}",
									// la date du recours doit être inférieure à la date de ce comité-ci
									'apres_comitesapres.daterecours <=' => $comiteapre['Comiteapre']['datecomite'],
								)
							)
						)
					.')'
				);
			}
			else {
				$querydata['conditions']['OR'] = array(
					// soit elles sont associées à ce comité-ci sans comite_pcd_id
					'Apre.id IN ('
						.$this->ApreComiteapre->sq(
							array(
								'fields' => array( 'apres_comitesapres.apre_id' ),
								'alias' => 'apres_comitesapres',
								'conditions' => array(
									'apres_comitesapres.comiteapre_id' => $comiteapre_id,
									'apres_comitesapres.comite_pcd_id IS NULL'
								)
							)
						)
					.')',
					// soit elles ne sont associées à aucun comité
					'Apre.id NOT IN ('
						.$this->ApreComiteapre->sq(
							array(
								'fields' => array( 'apres_comitesapres.apre_id' ),
								'alias' => 'apres_comitesapres',
								'conditions' => array(
									'apres_comitesapres.apre_id = Apre.id'
								)
							)
						)
					.')',
					// soit le dernier comité auquel elles ont été associées est plus ancien que celui-ci et la décision est un ajournement
					'Apre.id IN ('
						.$this->ApreComiteapre->sq(
							array(
								'alias' => 'apres_comitesapres',
								'fields' => array( 'apres_comitesapres.apre_id' ),
								'joins' => array(
									array(
										'table' => $dbo->fullTableName( $this->Comiteapre, true, false ),
										'alias' => 'comitesapres',
										'type' => 'INNER',
										'conditions' => array(
											'apres_comitesapres.comiteapre_id = comitesapres.id'
										)
									)
								),
								'conditions' => array(
									// la décision lors de l'association avec ce dernier comité a été émise et est un ajournement
									'apres_comitesapres.decisioncomite' => 'AJ',
									'apres_comitesapres.id IN ('
										.$this->ApreComiteapre->sqDernierComiteApre( 'Apre.id' )
									.')',
									// la date et l'heure du dernier comité avec lequel elles sont associées est plus récente que la date et l'heure de ce comité-ci
									'CAST( comitesapres.datecomite || \' \' || comitesapres.heurecomite AS TIMESTAMP ) <=' => "{$comiteapre['Comiteapre']['datecomite']} {$comiteapre['Comiteapre']['heurecomite']}",
								)
							)
						)
					.')'
				);

				// Il n'existe pas de comité dans lequel cette APRE est passée, pour laquelle elle a été refusée, et dont le refus a engendré un recours
				$querydata['conditions'][] = 'Apre.id NOT IN ('
						.$this->ApreComiteapre->sq(
							array(
								'alias' => 'apres_comitesapres',
								'fields' => array( 'apres_comitesapres.apre_id' ),
								'joins' => array(
									array(
										'table' => $dbo->fullTableName( $this->Comiteapre, true, false ),
										'alias' => 'comitesapres',
										'type' => 'INNER',
										'conditions' => array(
											'apres_comitesapres.comiteapre_id = comitesapres.id'
										)
									)
								),
								'conditions' => array(
									'apres_comitesapres.decisioncomite' => 'REF',
									'apres_comitesapres.recoursapre' => 'O',
									'apres_comitesapres.id IN ('
										.$this->ApreComiteapre->sqDernierComiteApre(
											'Apre.id',
											array(
													'apres_comitesapres.comiteapre_id <>' => $comiteapre_id,
													'apres_comitesapres.decisioncomite <>' => 'AJ'
											)
										)
									.')',
									// la date et l'heure de ce comité avec lequel elles sont associées est plus récente que la date et l'heure de ce comité-ci
									'CAST( comitesapres.datecomite || \' \' || comitesapres.heurecomite AS TIMESTAMP ) <=' => "{$comiteapre['Comiteapre']['datecomite']} {$comiteapre['Comiteapre']['heurecomite']}",
								)
							)
						)
					.')';
			}

			return $querydata;
		}

		/**
		 * Retourne le chemin vers le modèle odt utilisé pour l'APRE
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return 'APRE/apre.odt';
		}

		/**
		 * Retourne les données nécessaires à l'impression d'une APRE complémentaire pour le CG 93
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$querydata = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Personne->fields(),
					$this->Referent->fields(),
					$this->Structurereferente->fields(),
					$this->Personne->Foyer->fields(),
					$this->Personne->Prestation->fields(),
					$this->Personne->Foyer->Dossier->fields(),
					$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
					array(
						'( '.$this->Personne->Foyer->vfNbEnfants().' ) AS "Foyer__nbenfants"'
					)
				),
				'joins' => array(
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				),
				'contain' => false,
				'conditions' => array(
					'Apre.id' => $id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ('
								.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' )
							.')',
						)
					),
				),
			);

			foreach( $this->aidesApre as $aideApre ) {
				$querydata['fields'] = Set::merge(
					$querydata['fields'],
					$this->{$aideApre}->fields()
				);

				$querydata['joins'][] = $this->join( $aideApre, array( 'type' => 'LEFT OUTER' ) );
			}

			$deepAfterFind = $this->deepAfterFind;
			$this->deepAfterFind = false;

			$apre = $this->find( 'first', $querydata );
			$this->deepAfterFind = $deepAfterFind;

			// Récupération du dernier CER signé à la création de l'Apre
			$contratinsertion = $this->Personne->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $apre['Apre']['personne_id'],
						'Contratinsertion.decision_ci' => 'V',
						'Contratinsertion.dd_ci <=' =>$apre['Apre']['datedemandeapre'],
						'Contratinsertion.df_ci >=' =>$apre['Apre']['datedemandeapre'],
					),
					'contain' => false
				)
			);
			if( empty( $contratinsertion ) ) {
				$fields = $this->Personne->Contratinsertion->fields();
				$contratinsertion = Hash::expand( Set::normalize( $fields ) );
			}
			$apre = Set::merge( $apre, $contratinsertion );

			// Récupération de l'utilisateur connecté
			$user = $this->Personne->Contratinsertion->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$apre = Set::merge( $apre, $user );

			return $apre;
		}

		/**
		 * Retourne le PDF par défaut généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo
		 *
		 * @param type $id Id de l'APRE
		 * @param type $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$pdf = $this->getStoredPdf( $id );

			if( !empty( $pdf ) ) {
				$pdf = $pdf['Pdf']['document'];
			}
			else {
				$Option = ClassRegistry::init( 'Option' );

				$options = Hash::merge(
					$this->Personne->Foyer->enums(),
					array(
						'Personne' => array(
							'qual' => $Option->qual(),
						),
						'Prestation' => array(
							'rolepers' => $Option->rolepers(),
						),
						'Type' => array(
							'voie' =>  $Option->typevoie(),
						),
						'type' => array(
							'voie' => $Option->typevoie()
						),
					)
				);

				$apre = $this->getDataForPdf( $id, $user_id );
				$modeledoc = $this->modeleOdt( $apre );

				$pdf = $this->ged( $apre, $modeledoc, false, $options );

				if( !empty( $pdf ) ) {
					$this->storePdf( $id, $modeledoc, $pdf ); // FIXME ?
				}
			}

			return $pdf;
		}

		/**
		 * Retourne un champ virtuel contenant la liste des aides liées à une
		 * APRE, séparées par la chaîne de caractères $glue.
		 *
		 * Si le nom du champ virtuel est vide, alors le champ non aliasé sera
		 * retourné.
		 *
		 * @param string $fieldName Le nom du champ virtuel; le modèle sera l'alias
		 *	du modèle (Apre) utilisé.
		 * @param string $glue La chaîne de caratcères utilisée pour séparer les
		 *	noms des aides.
		 * @return string
		 */
		public function vfListeAidesLiees93( $fieldName = 'aidesliees', $glue = '\\n\r-' ) {
			$unions = array();

			foreach( $this->aidesApre as $modelAide ) {
				$join = $this->join( $modelAide );
				$table = Inflector::tableize( $modelAide );
				$sql = $this->{$modelAide}->sq(
					array_words_replace(
						array(
							'fields' => array( 'COUNT(*)' ),
							'alias' => $table,
							'conditions' => $join['conditions']
						),
						array( $modelAide => $table )
					)
				);
				$unions[] = str_replace( 'COUNT(*)', "'{$modelAide}'", $sql );
			}

			$sql = "TRIM( BOTH ' ' FROM TRIM( TRAILING '{$glue}' FROM ARRAY_TO_STRING( ARRAY( ".implode( ' UNION ', $unions )." ), '{$glue}' ) ) )";

			if( !empty( $fieldName ) ) {
				$sql = "{$sql} AS \"{$this->alias}__{$fieldName}\"";
			}

			return $sql;
		}
	}
?>