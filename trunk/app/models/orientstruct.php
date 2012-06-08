<?php
	class Orientstruct extends AppModel
	{
		public $name = 'Orientstruct';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'accordbenef' => array(
						'values' => array( 0, 1 )
					),
					'urgent' => array(
						'values' => array( 0, 1 )
					),
					'etatorient' => array( 'domain' => 'orientstruct' ),
					'haspiecejointe' => array( 'domain' => 'orientstruct' ),
					'origine'
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id', 'structureorientante_id', 'referentorientant_id' ),
			),
			'Gedooo.Gedooo',
			'StorablePdf',
			'ModelesodtConditionnables' => array(
				66 => array(
					'Orientation/changement_referent_cgcg.odt',
					'Orientation/changement_referent_cgoa.odt',
					'Orientation/changement_referent_oacg.odt'
				)
			)
		);

		public $validate = array(
			'structurereferente_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'typeorient_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'date_propo' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'date_valid' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
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
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'orientstruct_id',
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
			'Reorientationep93' => array(
				'className' => 'Reorientationep93',
				'foreignKey' => 'orientstruct_id',
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
			'Nonorientationproep58' => array(
				'className' => 'Nonorientationproep58',
				'foreignKey' => 'orientstruct_id',
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
			'Nonorientationproep66' => array(
				'className' => 'Nonorientationproep66',
				'foreignKey' => 'orientstruct_id',
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
			'Nonorientationproep93' => array(
				'className' => 'Nonorientationproep93',
				'foreignKey' => 'orientstruct_id',
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
			'Propoorientationcov58nv' => array(
				'className' => 'Propoorientationcov58',
				'foreignKey' => 'nvorientstruct_id',
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
			'Propononorientationprocov58' => array(
				'className' => 'Propononorientationprocov58',
				'foreignKey' => 'orientstruct_id',
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
			'Propononorientationprocov58nv' => array(
				'className' => 'Propononorientationprocov58',
				'foreignKey' => 'nvorientstruct_id',
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
					'Fichiermodule.modele = \'Orientstruct\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'orientstruct_id',
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
			'Decisionreorientationep93' => array(
				'className' => 'Decisionreorientationep93',
				'foreignKey' => 'orientstruct_id',
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
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'orientstruct_id',
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
			'Sanctionep58' => array(
				'className' => 'Sanctionep58',
				'foreignKey' => 'orientstruct_id',
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

		public $hasAndBelongsToMany = array(
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'joinTable' => 'orientsstructs_servicesinstructeurs',
				'foreignKey' => 'orientstruct_id',
				'associationForeignKey' => 'serviceinstructeur_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'OrientstructServiceinstructeur'
			)
		);


		public $hasOne = array(
			'Nonoriente66' => array(
				'className' => 'Nonoriente66',
				'foreignKey' => 'orientstruct_id',
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

		public $virtualFields = array(
			'nbjours' => array(
				'type'      => 'integer',
				'postgres'  => 'DATE_PART( \'day\', NOW() - "%s"."date_impression" )'
			),
		);

		/**
		* Surcharge du constructeur pour ajouter des règles de validation suivant le CG
		*/

		public function __construct( $id = false, $table = null, $ds = null ) {
			parent::__construct( $id, $table, $ds );

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$this->validate['structureorientante_id'] = array(
					'notEmptyIf' => array(
						'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
						'message' => 'Veuillez choisir une structure orientante',
					)
				);

				$this->validate['referentorientant_id'] = array(
					'notEmptyIf' => array(
						'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
						'message' => 'Veuillez choisir un référent orientant',
					)
				);
			}
		}

		/**
		*
		*/

		public function choixStructure( $field = array(), $compare_field = null ) {
			foreach( $field as $key => $value ) {
				if( !empty( $this->data[$this->name][$compare_field] ) && ( $this->data[$this->name][$compare_field] != 'En attente' ) && empty( $value ) ) {
					return false;
				}
			}
			return true;
		}

		/**
		*
		*/

		public function dossierId( $ressource_id ) {
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$ressource = $this->findById( $ressource_id, null, null, 1 );

			if( !empty( $ressource ) ) {
				return $ressource['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		* Retourne le chemin relatif du modèle de document à utiliser pour l'enregistrement du PDF.
		*/

		public function modeleOdt( $data ) {
			return "Orientation/{$data['Typeorient']['modele_notif']}.odt";
		}

		/**
		* Récupère les données pour le PDf
		*/

		public function getDataForPdf( $id/*, $user_id*/ ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
			$qual = $optionModel->qual();
			$typevoie = $optionModel->typevoie();

			$orientstruct = $this->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.id' => $id
					),
					'contain' => array(
						'Personne' => array(
							'Foyer' => array(
								'Adressefoyer' => array(
									'conditions' => array(
										'rgadr' => '01'
									),
									'Adresse'
								),
								'Dossier'
							),
						),
						'Typeorient',
						'Structurereferente',
						'Referent',
						'User',
					)
				)
			);

			if( $orientstruct['Orientstruct']['statut_orient'] != 'Orienté' ) {
				return false;
			}

			$orientstruct['Dossier'] = $orientstruct['Personne']['Foyer']['Dossier'];
			if( isset( $orientstruct['Personne']['Foyer']['Adressefoyer'][0]['Adresse'] ) ){
				$orientstruct['Adresse'] = $orientstruct['Personne']['Foyer']['Adressefoyer'][0]['Adresse'];
				unset( $orientstruct['Personne']['Foyer'] );
				$orientstruct['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Adresse.typevoie' ) );
			}

			$orientstruct['Structurereferente']['type_voie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Structurereferente.type_voie' ) );
			$orientstruct['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $orientstruct, 'Personne.qual' ) );


			/// Recherche référent à tout prix ....
			// Premère étape: référent du parcours.
			$referent = Set::filter( $orientstruct['Referent'] );
			if( empty( $referent ) ) {
				$referent = $this->Personne->Referent->PersonneReferent->find(
					'first',
					array(
						'conditions' => array(
							'PersonneReferent.personne_id' => $orientstruct['Personne']['id']
						),
						'recursive' => -1
					)
				);
				if( !empty( $referent ) ) {
					$orientstruct['Referent'] = $referent['PersonneReferent'];
				}
			}

			// Deuxième étape: premier référent renseigné pour la structure sélectionnée
			$referent = Set::filter( $orientstruct['Referent'] );
			if( empty( $referent ) && !empty( $orientstruct['Structurereferente']['id'] ) ) {
				$referent = $this->Personne->Referent->find(
					'first',
					array(
						'conditions' => array(
							'Referent.structurereferente_id' => $orientstruct['Structurereferente']['id']
						),
						'recursive' => -1
					)
				);
				if( !empty( $referent ) ) {
					$orientstruct['Referent'] = $referent['Referent'];
				}
			}

			return $orientstruct;
		}

		/**
		* Ajout d'entrée dans la table orientsstructs pour les DEM ou CJT RSA n'en possédant pas
		*/

		public function fillAllocataire() {
			$sql = "INSERT INTO orientsstructs( personne_id, statut_orient )
					(
						SELECT
								DISTINCT personnes.id,
								'Non orienté' AS statut_orient
							FROM personnes
								INNER JOIN prestations ON (
									prestations.personne_id = personnes.id
									AND prestations.natprest = 'RSA'
									AND (
										prestations.rolepers = 'DEM'
										OR prestations.rolepers = 'CJT'
									)
								)
							WHERE personnes.id NOT IN (
								SELECT orientsstructs.personne_id
									FROM orientsstructs
							)
					);";
			return $this->query( $sql );
		}

		/**
		* FIXME: select max(rgorient), si on a besoin d'archiver
		*/

		public function rgorientMax( $personne_id ) {
			return $this->find(
				'count',
				array(
					'conditions' => array(
						"{$this->alias}.statut_orient" => 'Orienté',
						"{$this->alias}.personne_id" => $personne_id
					),
					'contain' => false
				)
			);
		}

		/**
		* Construit les conditions pour ajout possible à partir de la configuration,
		* du webrsa.inc, en prenant en compte le traitement spécial à appliquer
		* pour la valeur NULL.
		* ATTENTION: in_array confond null et 0
		* @see http://fr.php.net/manual/en/function.in-array.php#99676
		*/

		protected function _conditionsAjoutOrientationPossible( $key, $values ) {
			$hasNull = false;

			if( !is_array( $values ) ) {
				$values = array( $values );
			}

			foreach( $values as $value ) {
				if( $value === null ) {
					$hasNull = true;
				}
			}

			$conditions = array( $key => array_diff( $values, array( null ) ) );

			if( $hasNull ) {
				$conditions = array(
					'OR' => array(
						$conditions,
						"{$key} IS NULL"
					)
				);
			}
			return $conditions;
		}

		/**
		* FIXME -> aucun dossier en cours, pour certains thèmes:
		*		- CG 93
		*			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		*			* Reorientationep93 -> peut déboucher sur une réorientation
		*			* Nonorientationproep93 -> peut déboucher sur une orientation
		*		- CG 66
		*			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		*			* Saisinebilanparcoursep66 -> peut déboucher sur une réorientation
		*			* Saisinepdoep66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
		*		- CG 58
		*			* Nonorientationproep58 -> peut déboucher sur une orientation
		* FIXME -> CG 93: s'il existe une procédure de relance, on veut faire signer un contrat,
					mais on veut peut-être aussi demander une réorientation.
		* FIXME -> doit-on vérifier si:
		* 			- la personne est soumise à droits et devoirs (oui)
		*			- la personne est demandeur ou conjoint RSA (oui) ?
		*			- le dossier est dans un état ouvert (non) ?
		*/

		public function ajoutPossible( $personne_id ) {
			$nbDossiersep = $this->Personne->Dossierep->find(
				'count',
				$this->Personne->Dossierep->qdDossiersepsOuverts( $personne_id )
			);

			// Quelles sont les valeurs de Calculdroitrsa.toppersdrodevorsa pour lesquelles on peut ajouter une orientation ?
			// Si la valeur null est dans l'array, il faut un traitement un peu spécial
			$conditionsToppersdrodevorsa = array( 'Calculdroitrsa.toppersdrodevorsa' => 1 );
			if( Configure::read( 'AjoutOrientationPossible.toppersdrodevorsa' ) != NULL ) {
				$conditionsToppersdrodevorsa = $this->_conditionsAjoutOrientationPossible(
					'Calculdroitrsa.toppersdrodevorsa',
					Configure::read( 'AjoutOrientationPossible.toppersdrodevorsa' )
				);
			}

			$conditionsSituationetatdosrsa = array( 'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' ) );
			if( Configure::read( 'AjoutOrientationPossible.situationetatdosrsa' )  != NULL ) {
				$conditionsSituationetatdosrsa = $this->_conditionsAjoutOrientationPossible(
					'Situationdossierrsa.etatdosrsa',
					Configure::read( 'AjoutOrientationPossible.situationetatdosrsa' )
				);
			}


			$nbPersonnes = $this->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'joins' => array(
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest = \'RSA\'',
								'Prestation.rolepers' => array( 'DEM', 'CJT' )
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => Set::merge(
								array( 'Personne.id = Calculdroitrsa.personne_id' ),
								$conditionsToppersdrodevorsa
							)
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						),
						array(
							'table'      => 'dossiers',
							'alias'      => 'Dossier',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => Set::merge(
								array( 'Situationdossierrsa.dossier_id = Dossier.id' ),
								$conditionsSituationetatdosrsa
							)
						),
					),
					'recursive' => -1
				)
			);

			return ( ( $nbDossiersep == 0 ) && ( $nbPersonnes == 1 ) );
		}

		/**
		* Vérifie si pour une personne donnée la nouvelle orientation est une régression ou nonrespectssanctionseps93
		* Orientation du pro vers le social
		*/

		public function isRegression( $personne_id, $newtypeorient_id ) {
			$return = false;

			if( !$this->Typeorient->isProOrientation( $newtypeorient_id ) ) {
				$lastOrient = $this->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personne_id
						),
						'contain' => array(
							'Typeorient'
						),
						'order' => array(
							'date_valid DESC'
						)
					)
				);

				if( !empty($lastOrient) && ( Configure::read( 'Typeorient.emploi_id' ) == $lastOrient['Typeorient']['id'] ) ) {
					$return = true;
				}
			}

			return $return;
		}

		/**
		* Ajout du rang d'orientation à la sauvegarde, lorsqu'on passe en 'Orienté'.
		* Mise à jour de l'origine suivant le statut et le rang de l'orientation.
		*/

		public function beforeSave( $options = array() ) {
			// Si on change le statut_orient de <> 'Orienté' en 'Orienté', alors, il faut changer le rang
			if( isset( $this->data[$this->alias]['statut_orient'] ) && ( $this->data[$this->alias]['statut_orient'] == 'Orienté' ) ) {
				// Change-t'on le statut ?
				if( isset( $this->data[$this->alias]['id'] ) && !empty( $this->data[$this->alias]['id'] ) ) {
					$tuple_pcd = $this->find( 'first', array( 'conditions' => array( "{$this->alias}.{$this->primaryKey}" => $this->data[$this->alias]['id'] ), 'contain' => false ) );
					if( $tuple_pcd[$this->alias]['statut_orient'] != 'Orienté' ) {
						$this->data[$this->alias]['rgorient'] = ( $this->rgorientMax( $this->data[$this->alias]['personne_id'] ) + 1 );
					}
					else {
						$this->data[$this->alias]['rgorient'] = $tuple_pcd[$this->alias]['rgorient'];
					}
				}
				// Nouvelle entrée
				else if( isset( $this->data[$this->alias]['personne_id'] ) && !empty( $this->data[$this->alias]['personne_id'] ) ) {
					$this->data[$this->alias]['rgorient'] = ( $this->rgorientMax( $this->data[$this->alias]['personne_id'] ) + 1 );
				}
			}

			if( isset( $this->data[$this->alias]['statut_orient'] ) ) {
				if( empty( $this->data[$this->alias]['statut_orient'] ) /*|| in_array( $this->data[$this->alias]['statut_orient'], array( 'Non orienté', 'En attente' ) )*/ ) {
					$this->data[$this->alias]['origine'] = null;
				}
				else if( $this->data[$this->alias]['statut_orient'] == 'Orienté' ) {
					if( $this->data[$this->alias]['rgorient'] > 1 ) {
						$this->data[$this->alias]['origine'] = 'reorientation';
					}
				}
			}

			return true;
		}

		/**
		 * Retourne la dernière orientation orientée pour une personne.
		 *
		 * @param string $personneIdFied
		 * @return string
		 */
		public function sqDerniere( $personneIdFied = 'Personne.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'orientsstructs.id'
					),
					'alias' => 'orientsstructs',
					'conditions' => array(
						"orientsstructs.personne_id = {$personneIdFied}",
						'orientsstructs.statut_orient = \'Orienté\'',
						'orientsstructs.date_valid IS NOT NULL'
					),
					'order' => array( 'orientsstructs.date_valid DESC' ),
					'limit' => 1
				)
			);
		}


		/**
		 *
		 * @param integer $apre_id
		 * @return string
		 */
		public function getChangementReferentOrientation( $orientstruct_id ) {
			$orientation = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Personne->fields(),
						$this->Typeorient->fields(),
						$this->Structurereferente->fields(),
						$this->Referent->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields()
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Typeorient', array( 'type' => 'INNER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Orientstruct.id' => $orientstruct_id,
					),
					'contain' => false
				)
			);

			if( empty( $orientation ) ) {
				return false;
			}

			$structurereferentePrecedente = $this->find(
				'first',
				array(
					'fields' => array(
						'Structurereferente.typestructure'
					),
					'conditions' => array(
						'Orientstruct.personne_id' => $orientation['Orientstruct']['personne_id'],
						'Orientstruct.date_valid <' => $orientation['Orientstruct']['date_valid'],
						'Orientstruct.statut_orient' => 'Orienté',
						'Orientstruct.id <>' => $orientation['Orientstruct']['id']
					),
					'joins' => array(
						$this->join( 'Structurereferente', array( 'type' => 'INNER') )
					),
					'order' => array( 'Orientstruct.date_valid DESC' ),
					'contain' => false
				)
			);

			// Options pour les traductions
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				),
				'Referent' => array(
					'qual' => $Option->qual()
				),
				'Structurereferente' => array(
					'type_voie' => $Option->typevoie()
				),
				'type' => array(
					'voie' => $Option->typevoie()
				)
			);

			// Choix du modèle de document
			$typestructure = Set::classicExtract( $orientation, 'Structurereferente.typestructure' );
			$typestructurepassee = Set::classicExtract( $structurereferentePrecedente, 'Structurereferente.typestructure' );

			if( $typestructure == $typestructurepassee ) {
				if( $typestructure == 'oa' ) {
					// INFO: Réponse du CG66 : d'expérience cela se fait à la marge donc pour le moment
					// aucun traitement particulier
					$modeleodt = "Orientation/changement_referent_cgcg.odt"; // FIXME: devrait être paoa
				}
				else {
					$modeleodt = "Orientation/changement_referent_cgcg.odt";
				}
			}
			else {
				if( $typestructure == 'oa' ) {
					$modeleodt = "Orientation/changement_referent_cgoa.odt";
				}
				else {
					$modeleodt = "Orientation/changement_referent_oacg.odt";
				}
			}

// debug($typestructurepassee);
// debug($typestructure);
// debug( $modeleodt );
// die();
			// Génération du PDF
			return $this->ged( $orientation, $modeleodt, false, $options );
		}

		/**
		 * Fonction permettant la mise à jour de la table nonorientes66
		 *
		 * @param integer id de l'orientation
		 * @return boolean
		 */
		protected function _updateNonoriente66( $orientstruct_id ) {
			$success = true;

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$orientationAvecEntreeNonoriente66 = $this->find(
					'first',
					array(
						'fields' => array(
							'Nonoriente66.id'
						),
						'conditions' => array(
							'Orientstruct.id' => $orientstruct_id
						),
						'joins' => array(
							$this->join( 'Personne', array(  'type' => 'INNER' ) ),
							$this->Personne->join( 'Nonoriente66', array(  'type' => 'INNER' ) )
						),
						'contain' => false
					)
				);

				if( !empty( $orientationAvecEntreeNonoriente66 )  ) {
					$success = $this->Nonoriente66->updateAll(
						array( 'Nonoriente66.orientstruct_id' => $orientstruct_id ),
						array(
							'"Nonoriente66"."id"' => $orientationAvecEntreeNonoriente66['Nonoriente66']['id']
						)
					);
				}
			}

			return $success;
		}

		/**
		*   AfterSave
		*/

		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			$return = $this->_updateNonoriente66( $this->id ) && $return;

			return $return;
		}
	}
?>