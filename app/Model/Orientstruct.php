<?php
	/**
	 * Fichier source de la classe Orientstruct.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Orientstruct ...
	 *
	 * @package app.Model
	 */

	define( 'ORIENTSTRUCT_STORABLE_PDF_ACTIVE', ( Configure::read( 'Cg.departement' ) != 66 ) );

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
					'origine',
					'typenotification'
				)
			),
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id',
					'referent_id',
					'structureorientante_id',
					'referentorientant_id'
				),
			),
			'Gedooo.Gedooo',
			'StorablePdf' => array( 'active' => ORIENTSTRUCT_STORABLE_PDF_ACTIVE ),
			'ModelesodtConditionnables' => array(
				66 => array(
					'Orientation/changement_referent_cgcg.odt',
					'Orientation/changement_referent_cgoa.odt',
					'Orientation/changement_referent_oacg.odt',
					'Orientation/orientationpe.odt',
					'Orientation/orientationpedefait.odt',
					'Orientation/orientationsociale.odt',
					'Orientation/orientationsocialeauto.odt',
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
			'Structureorientante' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structureorientante_id',
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
			'Referentorientant' => array(
				'className' => 'Referent',
				'foreignKey' => 'referentorientant_id',
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
			'Nonorientationproep58nv' => array(
				'className' => 'Nonorientationproep58',
				'foreignKey' => 'nvorientstruct_id',
				'dependent' => false,
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
			'Nonorientationproep93nv' => array(
				'className' => 'Nonorientationproep93',
				'foreignKey' => 'nvorientstruct_id',
				'dependent' => false,
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
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Propoorientsocialecov58' => array(
				'className' => 'Propoorientsocialecov58',
				'foreignKey' => 'nvorientstruct_id',
				'dependent' => false,
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
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Regressionorientationep58nv' => array(
				'className' => 'Regressionorientationep58',
				'foreignKey' => 'nvorientstruct_id',
				'dependent' => false,
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
			'Saisinebilanparcoursep66nv' => array(
				'className' => 'Saisinebilanparcoursep66',
				'foreignKey' => 'nvorientstruct_id',
				'dependent' => false,
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
			'Reorientationep93nv' => array(
				'className' => 'Reorientationep93',
				'foreignKey' => 'nvorientstruct_id',
				'dependent' => false,
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
			),
			'VxTransfertpdv93' => array(
				'className' => 'Transfertpdv93',
				'foreignKey' => 'vx_orientstruct_id',
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
			'NvTransfertpdv93' => array(
				'className' => 'Transfertpdv93',
				'foreignKey' => 'nv_orientstruct_id',
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

		public $virtualFields = array(
			'nbjours' => array(
				'type'      => 'integer',
				'postgres'  => 'DATE_PART( \'day\', NOW() - "%s"."date_impression" )'
			),
		);

		/**
		 * Surcharge du constructeur pour ajouter des règles de validation suivant le CG
		 *
		 * @param mixed $id Set this ID for this model on startup, can also be an array of options, see above.
		 * @param string $table Name of database table to use.
		 * @param string $ds DataSource connection name.
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
		 * Retourne l'id technique du dossier auquel appartient cette orientation.
		 *
		 * @param integer $orientstruct_id L'id technique de l'orientation
		 * @return integer
		 */
		public function dossierId( $orientstruct_id ) {
			$qd_orientstruct = array(
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Orientstruct.id' => $orientstruct_id
				),
				'recursive' => -1
			);
			$orientstruct = $this->find( 'first', $qd_orientstruct );

			if( !empty( $orientstruct ) ) {
				return $orientstruct['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Retourne le chemin relatif du modèle de document à utiliser pour l'enregistrement du PDF.
		 *
		 * @param array $data Les données envoyées au modèle pour construire le PDF
		 * @return string
		 */
		public function modeleOdt( $data ) {
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$typenotification = $data['Orientstruct']['typenotification'];
				if( !empty( $typenotification ) && $typenotification == 'systematique' ) {
					return "Orientation/orientationsystematiquepe.odt";
				}
				else {
					return "Orientation/{$data['Typeorient']['modele_notif']}.odt";
				}
			}
			return "Orientation/{$data['Typeorient']['modele_notif']}.odt";
		}

		/**
		 * Récupère les données pour le PDF.
		 *
		 * @param integer $id L'id technique de l'orientation
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id = null ) {
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

			if( !is_null( $user_id ) ) {
				$user = $this->User->find(
					'first',
					array(
						'conditions' => array(
							'User.id' => $user_id
						),
						'contain' => array(
							'Serviceinstructeur'
						)
					)
				);
				$orientstruct = Set::merge( $orientstruct, $user );
			}


			if( $orientstruct['Orientstruct']['statut_orient'] != 'Orienté' ) {
				return false;
			}

			$orientstruct['Dossier'] = $orientstruct['Personne']['Foyer']['Dossier'];
			if( isset( $orientstruct['Personne']['Foyer']['Adressefoyer'][0]['Adresse'] ) ){
				$orientstruct['Adresse'] = $orientstruct['Personne']['Foyer']['Adressefoyer'][0]['Adresse'];
				unset( $orientstruct['Personne']['Foyer'] );
				if( Configure::read( 'Cg.departement' ) != 66 ) {
					$orientstruct['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Adresse.typevoie' ) );
				}
			}

			if( Configure::read( 'Cg.departement' ) != 66 ) {
				$orientstruct['Structurereferente']['type_voie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Structurereferente.type_voie' ) );
				$orientstruct['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $orientstruct, 'Personne.qual' ) );
			}

			/// Recherche référent à tout prix ....
			// Premère étape: référent du parcours.
			$referent = Hash::filter( (array)$orientstruct['Referent'] );
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
			$referent = Hash::filter( (array)$orientstruct['Referent'] );
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
		 * Ajout d'entrée dans la table orientsstructs pour les DEM ou CJT RSA n'en possédant pas.
		 *
		 * @return boolean
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
			return ( $this->query( $sql ) !== false );
		}

		/**
		 * FIXME: select max(rgorient), si on a besoin d'archiver
		 *
		 * @param integer $personne_id
		 * @return integer
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
		 *
		 * @param type $key
		 * @param type $values
		 * @return array
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
		 * 		- CG 93
		 * 			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		 * 			* Reorientationep93 -> peut déboucher sur une réorientation
		 * 			* Nonorientationproep93 -> peut déboucher sur une orientation
		 * 		- CG 66
		 * 			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		 * 			* Saisinebilanparcoursep66 -> peut déboucher sur une réorientation
		 * 			* Saisinepdoep66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
		 * 		- CG 58
		 * 			* Nonorientationproep58 -> peut déboucher sur une orientation
		 * FIXME -> CG 93: s'il existe une procédure de relance, on veut faire signer un contrat,
		  mais on veut peut-être aussi demander une réorientation.
		 * FIXME -> doit-on vérifier si:
		 * 			- la personne est soumise à droits et devoirs (oui)
		 * 			- la personne est demandeur ou conjoint RSA (oui) ?
		 * 			- le dossier est dans un état ouvert (non) ?
		 */
		public function ajoutPossible( $personne_id ) {
			$nbDossiersep = $this->Personne->Dossierep->find(
				'count',
				$this->Personne->Dossierep->qdDossiersepsOuverts( $personne_id )
			);

			// Quelles sont les valeurs de Calculdroitrsa.toppersdrodevorsa pour lesquelles on peut ajouter une orientation ?
			// Si la valeur null est dans l'array, il faut un traitement un peu spécial
			$conditionsToppersdrodevorsa = array( 'Calculdroitrsa.toppersdrodevorsa' => '1' );
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
		 *
		 * @param integer $personne_id
		 * @param integer $newtypeorient_id
		 * @return boolean
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
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array( ) ) {
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
					if( ( $this->data[$this->alias]['rgorient'] > 1 ) && isset( $this->data[$this->alias]['origine'] ) && ( $this->data[$this->alias]['origine'] != 'demenagement' ) ) {
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
		 * @param integer $orientstruct_id
		 * @param integer $user_id
		 * @return boolean
		 */
		public function getChangementReferentOrientation( $orientstruct_id, $user_id ) {
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
                        'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).' )'
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

			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Serviceinstructeur'
					)
				)
			);
			$orientation = Set::merge( $orientation, $user );
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

			// Génération du PDF
			return $this->ged( $orientation, $modeleodt, false, $options );
		}

		/**
		 * Fonction permettant la mise à jour de la table nonorientes66.
		 *
		 * @param integer $orientstruct_id L'id de l'orientation
		 * @return type
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
					$success = $this->Nonoriente66->updateAllUnBound(
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
		 * AfterSave.
		 *
		 * @param boolean $created
		 * @return boolean
		 */
		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			$return = $this->_updateNonoriente66( $this->id ) && $return;

			return $return;
		}

		/**
		 *
		 * @param integer $orientstruct_id
		 * @return string
		 */
		public function getPdfNonoriente66 ( $orientstruct_id, $user_id ) {
			$data = $this->getDataForPdf( $orientstruct_id, $user_id );

			$Option = ClassRegistry::init( 'Option' );

			$options = array();

			$nonoriente66 = $this->Personne->Nonoriente66->find(
				'first',
				array(
					'conditions' => array(
						'Nonoriente66.orientstruct_id' => $orientstruct_id
					),
					'contain' => false
				)
			);
			$originePdfOrientation = Set::classicExtract( $nonoriente66, 'Nonoriente66.origine' );
			$typeOrientParentIdPdf = Set::classicExtract( $data, 'Typeorient.parentid' );
			$reponseAllocataire = Set::classicExtract( $nonoriente66, 'Nonoriente66.reponseallocataire' );


			$typesorientsParentidsSocial = Configure::read( 'Orientstruct.typeorientprincipale.SOCIAL' );
			$typesorientsParentidsEmploi = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );

			if( $originePdfOrientation == 'isemploi' ) {
				$modeleodt = 'Orientation/orientationpedefait.odt'; // INFO courrier 1
			}
			else {
				if( in_array( $typeOrientParentIdPdf, $typesorientsParentidsEmploi) ) {
					$modeleodt = 'Orientation/orientationpe.odt'; //INFO = courrier 3
				}
				else if( in_array( $typeOrientParentIdPdf, $typesorientsParentidsSocial) && ( $reponseAllocataire == 'O' ) ) {
					$modeleodt = 'Orientation/orientationsociale.odt';// INFO = courrier 4
				}
				else if( in_array( $typeOrientParentIdPdf, $typesorientsParentidsSocial) && ( $reponseAllocataire == 'N' ) ) {
					$modeleodt = 'Orientation/orientationsocialeauto.odt';// INFO = courrier 5
				}
				else if( in_array( $typeOrientParentIdPdf, $typesorientsParentidsSocial ) ) {
					$modeleodt = 'Orientation/orientationsociale.odt';// INFO = courrier 5
				}
			}

			$pdf = $this->ged( $data, $modeleodt, false, $options );

			if( !empty( $pdf ) ) {
				$this->Nonoriente66->updateAllUnBound(
					array( 'Nonoriente66.datenotification' => "'".date( 'Y-m-d' )."'" ),
					array(
						'"Nonoriente66"."id"' => $nonoriente66['Nonoriente66']['id']
					)
				);
			}

			return $pdf;
		}

		/**
		 * Retourne un querydata permettant de connaître la liste des orientations d'un allocataire, en
		 * fonction du CG (Configure::read( 'Cg.departement' )).
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function qdIndex( $personne_id) {
			$querydata = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Personne->fields(),
					$this->Typeorient->fields(),
					$this->Structurereferente->fields(),
					$this->Referent->fields(),
					array(
						ClassRegistry::init( 'Pdf' )->sqImprime( $this, 'imprime' ),
						$this->Fichiermodule->sqNbFichiersLies( $this, 'nombre' )
					)
				),
				'conditions' => array(
					'Orientstruct.personne_id' => $personne_id
				),
				'joins' => array(
					$this->join( 'Personne' ),
					$this->join( 'Typeorient' ),
					$this->join( 'Structurereferente' ),
					$this->join( 'Referent' ),
				),
				'contain' => false,
				'order' => array(
					'COALESCE( Orientstruct.rgorient, \'0\') DESC',
					'Orientstruct.date_valid DESC'
				)
			);

			// On complète le querydata suivant le CG
			if( Configure::read( 'Cg.departement' ) == 58 ) {
				// Si l'orientation est passée en COV, on va récupérer ces informations
				$sqPassagecovDossiercov58Id = $this->Personne->Dossiercov58->Passagecov58->sq(
					array(
						'fields' => array(
							'Passagecov58.dossiercov58_id'
						),
						'joins' => array(
							$this->Personne->Dossiercov58->Passagecov58->join( 'Dossiercov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Personne->Dossiercov58->join( 'Themecov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Personne->Dossiercov58->join( 'Propononorientationprocov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Personne->Dossiercov58->join( 'Propoorientationcov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Personne->Dossiercov58->join( 'Propoorientsocialecov58', array( 'type' => 'LEFT OUTER' ) ),
						),
						'conditions' => array(
							'Dossiercov58.personne_id = Personne.id',
							'Themecov58.name' => array( 'proposorientationscovs58', 'proposnonorientationsproscovs58', 'proposorientssocialescovs58' ),
							'Passagecov58.etatdossiercov' => 'traite',
// 							'Cov58.etatcov' => 'finalise',
// 							'DATE_trunc( \'day\', Cov58.datecommission ) = Orientstruct.date_valid',
							'OR' => array(
								array(
									'Propoorientationcov58.nvorientstruct_id IS NULL',
									'Propoorientsocialecov58.nvorientstruct_id IS NULL',
									'Propononorientationprocov58.nvorientstruct_id IS NOT NULL',
									'Propononorientationprocov58.nvorientstruct_id = Orientstruct.id',
								),
								array(
									'Propoorientationcov58.nvorientstruct_id IS NOT NULL',
									'Propoorientsocialecov58.nvorientstruct_id IS NULL',
									'Propononorientationprocov58.nvorientstruct_id IS NULL',
									'Propoorientationcov58.nvorientstruct_id = Orientstruct.id',
								),
								array(
									'Propoorientationcov58.nvorientstruct_id IS NULL',
									'Propoorientsocialecov58.nvorientstruct_id IS NOT NULL',
									'Propononorientationprocov58.nvorientstruct_id IS NULL',
									'Propoorientsocialecov58.nvorientstruct_id = Orientstruct.id',
								),
// 								array(
// 									'Propoorientationcov58.nvorientstruct_id IS NULL',
// 									'Propoorientsocialecov58.nvorientstruct_id IS NULL',
// 									'Propononorientationprocov58.nvorientstruct_id IS NULL',
// 								),
							)
						),
						'contain' => false,
					)
				);

				$sqPassagecovDossiercov58Id = array_words_replace(
					array( $sqPassagecovDossiercov58Id ),
					array(
						'Passagecov58' => 'passagescovs58',
						'Dossiercov58' => 'dossierscovs58',
						'Themecov58' => 'themescovs58',
						'Propoorientationcov58' => 'proposorientationscovs58',
						'Propononorientationprocov58' => 'proposnonorientationsproscovs58',
						'Propoorientsocialecov58' => 'proposorientssocialescovs58',
						'Passagecov58__dossiercov58_id' => 'passagescovs58__dossiercov58_id',
					)
				);
				$sqPassagecovDossiercov58Id = $sqPassagecovDossiercov58Id[0];

				$querydata['fields'] = Set::merge(
					$querydata['fields'],
					array_merge(
						$this->Personne->Dossiercov58->fields(),
						$this->Personne->Dossiercov58->Passagecov58->fields(),
						$this->Personne->Dossiercov58->Passagecov58->Cov58->fields(),
						$this->Personne->Dossiercov58->Passagecov58->Cov58->Sitecov58->fields()
					)
				);

				$querydata['joins'][] = $this->Personne->join(
					'Dossiercov58',
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'OR' => array(
								'Dossiercov58.id IS NULL',
								array(
									'Dossiercov58.themecov58' => array( 'proposorientationscovs58', 'proposnonorientationsproscovs58', 'proposorientssocialescovs58' ),
									"Dossiercov58.id IN ( {$sqPassagecovDossiercov58Id} )"
								)
							)
						)
					)
				);

				$querydata['joins'][] = $this->Personne->Dossiercov58->join( 'Passagecov58', array( 'type' => 'LEFT OUTER' ) );

				 $joinPassagecov58Cov58 = $this->Personne->Dossiercov58->Passagecov58->join( 'Cov58', array( 'type' => 'LEFT OUTER' ) );
				 $joinPassagecov58Cov58['conditions'] = array(
					$joinPassagecov58Cov58['conditions'],
					'Cov58.etatcov' => 'finalise',
					'DATE_trunc( \'day\', Cov58.datecommission ) = Orientstruct.date_valid'
				);
				$querydata['joins'][] = $joinPassagecov58Cov58;

				$querydata['joins'][] = $this->Personne->Dossiercov58->Passagecov58->Cov58->join( 'Sitecov58', array( 'type' => 'LEFT OUTER' ) );

// 				$querydata['conditions'][] = array(
// 					'OR' => array(
// 						'Passagecov58.id IS NULL',
// 						"Passagecov58.id IN ( {$sqPassagecov} )"
// 					)
// 				);
			}
			else if( Configure::read( 'Cg.departement' ) == 66 ) {
				// Pour le CG 66, on ne eut cliquer sur certains liens que sous certaines conditions
				$sq = '"Typeorient"."parentid" IN ('.implode( ',', (array)Configure::read( 'Orientstruct.typeorientprincipale.SOCIAL' ) ).')';
				$querydata['fields'][] = "( {$sq} ) AS \"{$this->alias}__notifbenefcliquable\"";
			}

			return $querydata;
		}


		/**
		 * Retourne le PDF par défaut, stocké, ou généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo et le stocke,
		 *
		 * @param integer $id Id du CER
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {

			$Option = ClassRegistry::init( 'Option' );
			$options = Set::merge(
				array(
					'Personne' => array(
						'qual' => $Option->qual(),
					),
					'Adresse' => array(
						'typevoie' => $Option->typevoie(),
					),
					'Prestation' => array(
						'rolepers' => $Option->rolepers(),
					),
					'Foyer' => array(
						'sitfam' => $Option->sitfam(),
						'typeocclog' => $Option->typeocclog(),
					),
					'Type' => array(
						'voie' => $Option->typevoie(),
					),
					'Detaildroitrsa' => array(
						'oridemrsa' => $Option->oridemrsa(),
					),
				),
				$this->enums()
			);

			$orientstruct = $this->getDataForPdf( $id, $user_id );
			$modeledoc = $this->modeleOdt( $orientstruct );

			$pdf = $this->ged( $orientstruct, $modeledoc, false, $options );

			return $pdf;
		}
	}
?>