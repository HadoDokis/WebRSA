<?php
	/**
	 * Fichier source de la classe Dossier.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 */
	class Dossier extends AppModel
	{
		public $name = 'Dossier';

		public $actsAs = array(
			'Conditionnable',
			'Formattable'
		);

		public $validate = array(
			'numdemrsa' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
				array(
					'rule' => array( 'between', 11, 11 ),
					'message' => 'Le n° de demande est composé de 11 caractères'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dtdemrsa' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.',
					'allowEmpty' => true
				)
			),
			'matricule' => array(
				'rule' => array( 'between', 15, 15 ),
				'message' => 'Le n° est composé de 15 caractères minimum (ajouter des zéros à la fin)',
				'allowEmpty' => true
			)
		);

		public $hasOne = array(
			'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'dossier_id',
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
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'dossier_id',
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
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'dossier_id',
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
			'Jeton' => array(
				'className' => 'Jeton',
				'foreignKey' => 'dossier_id',
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
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'dossier_id',
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
		);

		public $hasMany = array(
			'Infofinanciere' => array(
				'className' => 'Infofinanciere',
				'foreignKey' => 'dossier_id',
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
			'Suiviinstruction' => array(
				'className' => 'Suiviinstruction',
				'foreignKey' => 'dossier_id',
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

		/**
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Personne' => array(
				'className' => 'Personne',
				'joinTable' => 'derniersdossiersallocataires',
				'foreignKey' => 'dossier_id',
				'associationForeignKey' => 'personne_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'Dernierdossierallocataire'
			),
		);

		/**
		 * Mise en majuscule du champ numdemrsa.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array() ) {
			if( !empty( $this->data['Dossier']['numdemrsa'] ) ) {
				$this->data['Dossier']['numdemrsa'] = strtoupper( $this->data['Dossier']['numdemrsa'] );
			}

			return parent::beforeSave( $options );
		}

		/**
		 *
		 * @param type $zonesGeographiques
		 * @param type $filtre_zone_geo
		 * @return type
		 */
		/*public function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) {
			$this->Foyer->unbindModelAll();

			$this->Foyer->bindModel(
				array(
					'hasOne'=>array(
						'Adressefoyer' => array(
							'foreignKey'    => false,
							'type'          => 'LEFT',
							'conditions'    => array(
								'"Adressefoyer"."foyer_id" = "Foyer"."id"',
								'"Adressefoyer"."rgadr" = \'01\''
							)
						),
						'Adresse' => array(
							'foreignKey'    => false,
							'type'          => 'LEFT',
							'conditions'    => array(
								'"Adressefoyer"."adresse_id" = "Adresse"."id"'
							)
						)
					)
				)
			);

			if( $filtre_zone_geo ) {
				$params = array (
					'conditions' => array(
						'Adresse.numcomptt' => array_values( $zonesGeographiques )
					)
				);
			}
			else {
				$params = array();
			}

			$foyers = $this->Foyer->find( 'all', $params );

			$return = Set::extract( $foyers, '{n}.Foyer.dossier_id' );
			return ( !empty( $return ) ? $return : null );
		}*/


		/**
		 * Retourne un querydata prenant en compte les différents filtres du moteur de recherche.
		 *
		 * INFO (pour le CG66): ATTENTION, depuis que la possibilité de créer des dossiers avec un numéro
		 * temporaire existe, il est possible (via le bouton Ajouter) de créer des dossiers avec des allocataires
		 * ne possédant ni date de naissance, ni NIR.
		 * Du coup, lors de la recherche, si la case "Uniquement la dernière demande..." est cochée, les dossiers
		 * temporaires, avec allocataire sans NIR ou sans date de naissance ne ressortiront pas lors de cette
		 * recherche -> il faut donc décocher la case pour les voir apparaître
		 *
		 * @param array $mesCodesInsee
		 * @param boolean $filtre_zone_geo
		 * @param array $params
		 * @return array
		 */
		public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {

			$conditions = array(
			);

			$typeJointure = 'INNER';
			if( Configure::read( 'Cg.departement' ) != 66) {
				$conditions = array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.rgadr' => '01'
					),
					'Prestation.rolepers' => array( 'DEM', 'CJT' )
				);
			}
			else {
				$typeJointure = 'LEFT OUTER';
				$conditions = array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.rgadr' => '01'
					)
				);

				if( isset( $params['Prestation']['rolepers'] ) ){
					if( $params['Prestation']['rolepers'] == '0' ){
						$conditions[] = 'Prestation.rolepers IS NULL';
					}
					else if( $params['Prestation']['rolepers'] == '1' ){
						$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
					}
					else {
						$conditions[] = array(
							'OR' => array(
								'Prestation.rolepers IS NULL',
								'Prestation.rolepers IN ( \'DEM\', \'CJT\' )'
							)
						);
					}
				}
			}

			$joins = array(
				$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
				$this->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
				$this->Foyer->Personne->join( 'Prestation', array( 'type' => $typeJointure ) ),
				$this->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
				$this->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
				$this->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
				$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				$this->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) )
			);

			$conditions = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $params );

			// Critères sur le dossier - service instructeur
			if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ) {
				$conditions[] = "Dossier.id IN ( SELECT suivisinstruction.dossier_id FROM suivisinstruction INNER JOIN servicesinstructeurs ON suivisinstruction.numdepins = servicesinstructeurs.numdepins AND suivisinstruction.typeserins = servicesinstructeurs.typeserins AND suivisinstruction.numcomins = servicesinstructeurs.numcomins AND suivisinstruction.numagrins = servicesinstructeurs.numagrins WHERE servicesinstructeurs.id = '".Sanitize::paranoid( $params['Serviceinstructeur']['id'] )."' )";
			}

			/// Statut de présence contrat engagement reciproque
			$hasContrat  = Set::extract( $params, 'Personne.hascontrat' );
			if( !empty( $hasContrat ) && in_array( $hasContrat, array( 'O', 'N' ) ) ) {
				if( $hasContrat == 'O' ) {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) = 0';
				}
			}

			// Personne ne possédant pas d'orientation, ne possédant aucune entrée dans la table orientsstructs
// 			if( isset( $params['Orientstruct']['sansorientation'] ) && $params['Orientstruct']['sansorientation'] ) {
// 				$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) = 0';
// 				if( Configure::read( 'Cg.departement' ) == 66 ) {
// 					$joins[] = $this->Foyer->Personne->join( 'Nonoriente66', array( 'type' => 'LEFT OUTER' ) );
// 					$conditions[] = array( 'Nonoriente66.id IS NULL' );
// 				}
// 			}

			if( isset( $params['Orientstruct']['exists'] ) && ( $params['Orientstruct']['exists'] != '' ) ) {
				if( $params['Orientstruct']['exists'] ) {
					$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) = 0';
					if( Configure::read( 'Cg.departement' ) == 66 ) {
						$joins[] = $this->Foyer->Personne->join( 'Nonoriente66', array( 'type' => 'LEFT OUTER' ) );
						$conditions[] = array( 'Nonoriente66.id IS NULL' );
					}
				}
			}

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				//Travailleur social chargé de l'évaluation
				// Représente le "Nom du chargé de l'évaluation" lorsque l'on crée une orientation
				// via la table proposorientaitonscovs58
				$referent_id = Set::classicExtract( $params, 'PersonneReferent.referent_id' );
				if( isset( $referent_id ) && !empty( $referent_id ) ) {
					$joins = array_merge(
						$joins,
						array(
							$this->Foyer->Personne->join( 'Dossiercov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Foyer->Personne->Dossiercov58->join( 'Propoorientationcov58', array( 'type' => 'LEFT OUTER' ) )
						)
					);
					$conditions[] = array( 'Propoorientationcov58.referentorientant_id = \''.Sanitize::clean( $referent_id ).'\'' );
				}

				// Présence DSP ?
				$sqDspId = 'SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1';
				$sqDspExists = "( {$sqDspId} ) IS NOT NULL";
				if( isset( $params['Dsp']['exists'] ) && ( $params['Dsp']['exists'] != '' ) ) {
					if( $params['Dsp']['exists'] ) {
						$conditions[] = "( {$sqDspExists} )";
					}
					else {
						$conditions[] = "( ( {$sqDspId} ) IS NULL )";
					}
				}
			}


			$query = array(
				'fields' => array(
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Dossier.fonorg',
					'Personne.nir',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.dtnai',
					'Personne.idassedic',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Situationdossierrsa.etatdosrsa',
					'Prestation.rolepers'
				),
				'recursive' => -1,
				'joins' => $joins,
				'limit' => 10,
				'order' => array( 'Personne.nom ASC' ),
				'conditions' => $conditions
			);

			if( isset( $referent_id ) && !empty( $referent_id ) ) {
				$query['joins'][] = $this->Foyer->Personne->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) );
				$query['fields'] = Set::merge(
					$query['fields'],
					array(
						'PersonneReferent.referent_id'
					)
				);
			}

			return $query;
		}

		/**
		 * Renvoit un numéro de RSA temporaire (sous la form "TMP00000000", suivant le numéro de la
		 * séquence dossiers_numdemrsatemp_seq) pour l'ajout de dossiers au CG 66.
		 *
		 * @return string
		 */
		public function generationNumdemrsaTemporaire() {
			$numSeq = $this->query( "SELECT nextval('dossiers_numdemrsatemp_seq');" );
			if( $numSeq === false ) {
				return null;
			}

			$numdemrsaTemp = sprintf( "TMP%08s",  $numSeq[0][0]['nextval'] );
			return $numdemrsaTemp;
		}

		/**
		 *
		 * @param array $params Une manière d'identifier le dossier, via la valeur
		 *	de l'une des clés suivantes: id, foyer_id, personne_id.
		 * @param array $qdPartsJetons Les parties de querydata permettant
		 *	d'obtenir des informations sur le jeton éventuel du dossier.
		 * @return array
		 * @throws NotFoundException
		 */
		public function menu( $params, $qdPartsJetons ) {
			$conditions = array();

			if( !empty( $params['id'] ) && is_numeric( $params['id'] ) ) {
				$conditions['Dossier.id'] = $params['id'];
			}
			else if( !empty( $params['foyer_id'] ) && is_numeric( $params['foyer_id'] ) ) {
				$conditions['Foyer.id'] = $params['foyer_id'];
			}
			else if( !empty( $params['personne_id'] ) && is_numeric( $params['personne_id'] ) ) {
				$conditions['Dossier.id'] = $this->Foyer->Personne->dossierId( $params['personne_id'] );
			}

			if( empty( $conditions ) ) {
				throw new NotFoundException();
			}

			// Données du dossier RSA.
			$querydata = array(
				'fields' => array(
					'Dossier.id',
					'Dossier.matricule',
					'Dossier.fonorg',
					'Dossier.numdemrsa',
					'Foyer.id',
					$this->Foyer->sqVirtualField( 'enerreur' ),
					$this->Foyer->sqVirtualField( 'sansprestation' ),
					'Situationdossierrsa.etatdosrsa',
				),
				'joins' => array(
					$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->join( 'Situationdossierrsa', array( 'type' => 'LEFT' ) ),
				),
				'conditions' => $conditions,
				'contain' => false
			);

			$keys = array( 'fields', 'joins' );
			foreach( $keys as $key ) {
				$querydata[$key] = array_merge( $querydata[$key], $qdPartsJetons[$key] );
			}
			$dossier = $this->find( 'first', $querydata );

			$adresses = $this->Foyer->Adressefoyer->find(
				'all',
				array(
					'fields' => array(
						'Adressefoyer.rgadr',
						'Adressefoyer.dtemm',
						'"Adresse"."numcomptt" AS "Adressefoyer__codeinsee"',
					),
					'conditions' => array(
						'Adressefoyer.foyer_id' => $dossier['Foyer']['id']
					),
					'joins' => array(
						$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) )
					),
					'contain' => false,
					'order' => array( 'Adressefoyer.rgadr ASC', 'Adressefoyer.dtemm DESC' )
				)
			);
			// Mise en forme des adresses du foyer, ajout des champs virtuels ddemm et dfemm
			$adresses = Hash::combine( $adresses, '{n}.Adressefoyer.rgadr', '{n}.Adressefoyer' );
			$ddemm = null;
			$dfemm = null;
			foreach( $adresses as $rgadr => $adresse ) {
				$dfemm = ( is_null( $ddemm ) ? null : date( 'Y-m-d', strtotime( '-1 day', strtotime( $ddemm ) ) ) );
				$ddemm = $adresse['dtemm'];

				$adresses[$rgadr]['ddemm'] = $ddemm;
				$adresses[$rgadr]['dfemm'] = $dfemm;
			}
			$dossier = Set::merge( $dossier, array( 'Adressefoyer' => $adresses ) );

			// Les personnes du foyer
			$conditionsDerniereOrientstruct = array(
				'Orientstruct.id IN ( '.$this->Foyer->Personne->Orientstruct->sqDerniere().' )'
			);

			$personnes = $this->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Prestation.rolepers',
						'Orientstruct.structurereferente_id',
						'Structurereferente.typestructure',
					),
					'conditions' => array(
						'Personne.foyer_id' => Hash::get( $dossier, 'Foyer.id' ),
					),
					'joins' => array(
						$this->Foyer->Personne->join( 'Prestation', array( 'type' => 'LEFT' ) ),
						$this->Foyer->Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER', 'conditions' => $conditionsDerniereOrientstruct ) ),
						$this->Foyer->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
					),
					'contain' => false,
					'order' => array(
						'( CASE WHEN Prestation.rolepers = \'DEM\' THEN 0 WHEN Prestation.rolepers = \'CJT\' THEN 1 WHEN Prestation.rolepers = \'ENF\' THEN 2 ELSE 3 END ) ASC',
						'Personne.nom ASC',
						'Personne.prenom ASC'
					)
				)
			);

			// Reformattage pour la vue
			$dossier['Foyer']['Personne'] = Hash::extract( $personnes, '{n}.Personne' );

			foreach( array( 'Prestation', 'Orientstruct', 'Structurereferente' ) as $modelName ) {
				foreach( Hash::extract( $personnes, "{n}.{$modelName}" ) as $i => $datas ) {
					$dossier['Foyer']['Personne'] = Hash::insert( $dossier['Foyer']['Personne'], "{$i}.{$modelName}", $datas );
				}
			}

			if( !empty( $params['personne_id'] ) && is_numeric( $params['personne_id'] ) ) {
				$dossier['personne_id'] = $params['personne_id'];
			}

			return $dossier;
		}
	}
?>