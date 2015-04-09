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

	define( 'ORIENTSTRUCT_STORABLE_PDF_ACTIVE', !in_array( Configure::read( 'Cg.departement' ), array( 66, 976 ) ) );

	class Orientstruct extends AppModel
	{
		public $name = 'Orientstruct';

		public $actsAs = array(
			'Allocatairelie',
			'Dependencies',
			'Enumerable',
            'Pgsqlcake.PgsqlAutovalidate',
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
                    'Orientation/orientationsystematiquepe.odt'
				)
			)
		);

		public $validate = array(
			// Validation des détails de l'orientation
			'typeorient_id' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'structurereferente_id' => array(
				'dependentForeignKeys' => array(
					'rule' => array( 'dependentForeignKeys', 'Structurereferente', 'Typeorient' ),
					'message' => 'La structure référente ne correspond pas au type d\'orientation',
				),
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'referent_id' => array(
				'dependentForeignKeys' => array(
					'rule' => array( 'dependentForeignKeys', 'Referent', 'Structurereferente' ),
					'message' => 'La référent n\'appartient pas à la structure référente',
				),
			),
			// Validation des dates
			'date_propo' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'date_valid' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté' ) ),
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'statut_orient' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty'
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
			'Nonorientationprocov58' => array(
				'className' => 'Nonorientationprocov58',
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
			'Nonorientationprocov58nv' => array(
				'className' => 'Nonorientationprocov58',
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

			$departement = Configure::read( 'Cg.departement' );
			if( $departement == 66 ) {
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
					),
					'dependentForeignKeys' => array(
						'rule' => array( 'dependentForeignKeys', 'Referentorientant', 'Structureorientante', 'Structurereferente' ),
						'message' => 'La référent n\'appartient pas à la structure référente',
					),
				);
			}
			else if( $departement == 976 ) {
				$this->validate['typeorient_id']['notEmptyIf'] = array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté', 'En attente', '' ) ),
					'message' => 'Champ obligatoire',
				);
				$this->validate['structurereferente_id']['notEmptyIf'] = array(
					'rule' => array( 'notEmptyIf', 'statut_orient', true, array( 'Orienté', 'En attente', '' ) ),
					'message' => 'Champ obligatoire',
				);
			}
		}

		/**
		 * Retourne le chemin relatif du modèle de document à utiliser pour l'enregistrement du PDF.
		 *
		 * @param array $data Les données envoyées au modèle pour construire le PDF
		 * @return string
		 */
		public function modeleOdt( $data ) {
			// Au CG 93, lorsqu'une orientation fait suite à un déménagement, il
			// faut imprimer le courrier de transfert PDV
			$isDemenagement = false;

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$isDemenagement = ( Hash::get( $data, 'NvOrientstruct.origine' ) === 'demenagement' );
			}

			if( $isDemenagement ) {
				return ClassRegistry::init( 'Transfertpdv93' )->modeleOdt( $data );
			}
			else {
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					$typenotification = $data['Orientstruct']['typenotification'];
					if( !empty( $typenotification ) && $typenotification == 'systematique' ) {
						return "Orientation/orientationsystematiquepe.odt";
					}
					else if( !empty( $typenotification ) && $typenotification == 'dejainscritpe' ) {
						return "Orientation/orientationpedefait.odt";
					}
					else {
						return "Orientation/{$data['Typeorient']['modele_notif']}.odt";
					}
				}
				return "Orientation/{$data['Typeorient']['modele_notif']}.odt";
			}
		}

		/**
		 * Récupère les données pour le PDF.
		 *
		 * @param integer $id L'id technique de l'orientation
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id = null ) {
			$departement = Configure::read( 'Cg.departement' );

			// Au CG 93, lorsqu'une orientation fait suite à un déménagement, il
			// faut imprimer le courrier de transfert PDV
			$isDemenagement = false;

			if( $departement == 93 ) {
				$demenagement = $this->find(
					'first',
					array(
						'fields' => array(
							"{$this->alias}.{$this->primaryKey}"
						),
						'contain' => false,
						'conditions' => array(
							"{$this->alias}.{$this->primaryKey}" => $id,
							"{$this->alias}.origine" => 'demenagement'
						)
					)
				);

				$isDemenagement = !empty( $demenagement );
			}

			if( $isDemenagement ) {
				$Transfertpdv93 = ClassRegistry::init( 'Transfertpdv93' );
				$orientstruct = $Transfertpdv93->getDataForPdf( $id, $user_id );

				// Traduction car elles sont faites directement dans les données pour les orientsstructs
				$options = $Transfertpdv93->getPdfOptions();
				foreach( $options as $modelAlias => $modelOptions ) {
					foreach( $modelOptions as $fieldName => $fieldOptions ) {
						if( isset( $orientstruct[$modelAlias][$fieldName] ) ) {
							$orientstruct[$modelAlias][$fieldName] = Set::enum(
								$orientstruct[$modelAlias][$fieldName],
								$options[$modelAlias][$fieldName]
							);
						}
					}
				}
			}
			else {
				// TODO: error404/error500 si on ne trouve pas les données
				$Option = ClassRegistry::init( 'Option' );
				$qual = $Option->qual();
				$typevoie = $Option->typevoie();

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

				$statut_orient = Hash::get( $orientstruct, "{$this->alias}.statut_orient" );

				$printable = (
					( $departement == 976 && ( $statut_orient == 'En attente' ) )
					|| ( $statut_orient == 'Orienté' )
				);

				if( !$printable ) {
					return false;
				}

				$orientstruct['Dossier'] = $orientstruct['Personne']['Foyer']['Dossier'];
				if( isset( $orientstruct['Personne']['Foyer']['Adressefoyer'][0]['Adresse'] ) ){
					$orientstruct['Adresse'] = $orientstruct['Personne']['Foyer']['Adressefoyer'][0]['Adresse'];
					unset( $orientstruct['Personne']['Foyer'] );
				}

				if( $departement != 66 ) {
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
			$success = parent::beforeSave( $options );

			$id = Hash::get( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$statut_orient = Hash::get( $this->data, "{$this->alias}.statut_orient" );

			// Si on change le statut_orient de <> 'Orienté' en 'Orienté', alors, il faut changer le rang
			if( $statut_orient === 'Orienté' ) {
				$personne_id = Hash::get( $this->data, "{$this->alias}.personne_id" );

				// Change-t'on le statut ?
				if( !empty( $id ) ) {
					$query = array(
						'conditions' => array(
							"{$this->alias}.{$this->primaryKey}" => $id
						),
						'contain' => false
					);
					$tuple_pcd = $this->find( 'first', $query );
					if( $tuple_pcd[$this->alias]['statut_orient'] !== 'Orienté' ) {
						$this->data[$this->alias]['rgorient'] = ( $this->rgorientMax( $personne_id ) + 1 );
					}
					else {
						$this->data[$this->alias]['rgorient'] = $tuple_pcd[$this->alias]['rgorient'];
					}
				}
				// Nouvelle entrée
				else if( !empty( $personne_id ) ) {
					$this->data[$this->alias]['rgorient'] = ( $this->rgorientMax( $personne_id ) + 1 );
				}

				$origine = Hash::get( $this->data, "{$this->alias}.origine" );
				if( ( $this->data[$this->alias]['rgorient'] > 1 ) && !in_array( $origine, array( null, 'demenagement' ), true ) ) {
					$this->data[$this->alias]['origine'] = 'reorientation';
				}
			}
			// Il ne s'agit pas d'une orientation effective
			else {
				if( empty( $id ) ) {
					$this->data[$this->alias]['origine'] = null;
				}
				$this->data[$this->alias]['rgorient'] = null;
				$this->data[$this->alias]['date_valid'] = null;
			}

			if( isset( $this->data[$this->alias]['statut_orient'] ) && empty( $this->data[$this->alias]['statut_orient'] ) ) {
				$this->data[$this->alias]['origine'] = null;
			}

			return $success;
		}

		/**
		 * Retourne la dernière orientation orientée pour une personne.
		 *
		 * @param string $personneIdFied
		 * @param string $alias
		 * @return string
		 */
		public function sqDerniere( $personneIdFied = 'Personne.id', $alias = 'orientsstructs' ) {
			return $this->sq(
				array(
					'fields' => array(
						"{$alias}.id"
					),
					'alias' => $alias,
					'conditions' => array(
						"{$alias}.personne_id = {$personneIdFied}",
						"{$alias}.statut_orient = 'Orienté'",
						"{$alias}.date_valid IS NOT NULL"
					),
					'order' => array( "{$alias}.date_valid DESC" ),
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
		 * @todo renommer en getQueryIndex( $personne_id )
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
					'type' => array(
						'voie' => $Option->typevoie()
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

		/**
		 * Lorsqu'on crée une nouvelle orientation via les EP (CG 93) et qu'il
		 * s'agit d'une réelle réorientation (changement de structure référente
		 * et/ou de type d'orientaion) et que l'allocataire est suivi par un PDV,
		 * sans questionnaire D2 lié, il faut en créer un de manière automatique
		 * pour cette réorientation.
		 *
		 * @param array $dossierep
		 * @param string $modeleDecision
		 * @param integer $nvorientstruct_id
		 * @return boolean
		 */
		public function reorientationEpQuestionnaired2pdv93Auto( $dossierep, $modeleDecision, $nvorientstruct_id ) {
			$success = true;

			$orientstructPcd = $this->find(
				'first',
				array(
					'contain' => false,
					'conditions' => array(
						'Orientstruct.personne_id' => $dossierep['Dossierep']['personne_id'],
						'Orientstruct.statut_orient' => 'Orienté',
						'NOT' => array(
							'Orientstruct.id' => $nvorientstruct_id,
						)
					),
					'order' => array( 'Orientstruct.date_valid DESC' )
				)
			);

			$reorientation = (
				empty( $orientstructPcd )
				|| $orientstructPcd['Orientstruct']['typeorient_id'] != $dossierep[$modeleDecision]['typeorient_id']
				|| $orientstructPcd['Orientstruct']['structurereferente_id'] != $dossierep[$modeleDecision]['structurereferente_id']
			);

			if( $reorientation ) {
				$success = $this->Personne->Questionnaired2pdv93->saveAuto( $dossierep['Dossierep']['personne_id'], 'reorientation' ) && $success;
			}

			return $success;
		}

		// ---------------------------------------------------------------------

		/**
		 * Permet d'obtenir les données du formulaire d'ajout / de modification,
		 * en fonction du bénéficiaire, parfois de l'orientation.
		 *
		 * @param integer $personne_id
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 * @throws NotFoundException
		 */
		public function getAddEditFormData( $personne_id, $id = null, $user_id = null ) {
			$departement = Configure::read( 'Cg.departement' );
			$data = array();

			// Modification
			if( $id !== null ) {
				$data = $this->find(
					'first',
					array(
						'fields' => array_merge(
							$this->fields(),
							$this->Personne->Calculdroitrsa->fields()
						),
						'joins' => array(
							$this->join( 'Personne' ),
							$this->Personne->join( 'Calculdroitrsa' ),
						),
						'conditions' => array(
							"{$this->alias}.{$this->primaryKey}" => $id
						),
						'contain' => false
					)
				);

				if( empty( $data  ) ) {
					throw new NotFoundException();
				}

				// Listes dépendantes
				$data[$this->alias]['referent_id'] = "{$data[$this->alias]['structurereferente_id']}_{$data[$this->alias]['referent_id']}";
				$data[$this->alias]['structurereferente_id'] = "{$data[$this->alias]['typeorient_id']}_{$data[$this->alias]['structurereferente_id']}";

				if( $departement == 66 ) {
					$data[$this->alias]['referentorientant_id'] = "{$data[$this->alias]['structureorientante_id']}_{$data[$this->alias]['referentorientant_id']}";
				}
			}
			// Ajout
			else {
				$data = array(
					$this->alias => array(
						'personne_id' => $personne_id,
						'user_id' => $user_id,
						'origine' => 'manuelle'
					)
				);

				// On propose la date de demande RSA comme date de demande par défaut
				$dossier = $this->Personne->find(
					'first',
					array(
						'fields' => array( 'Dossier.dtdemrsa' ),
						'joins' => array(
							$this->Personne->join( 'Foyer' ),
							$this->Personne->Foyer->join( 'Dossier' ),
						),
						'conditions' => array(
							'Personne.id' => $personne_id
						),
						'contain' => false
					)
				);
				$data['Orientstruct']['date_propo'] = $dossier['Dossier']['dtdemrsa'];
				$data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
			}

			// Soumission à droits et devoirs
			$query = array(
				'fields' => array(
					'Calculdroitrsa.id',
					'Calculdroitrsa.toppersdrodevorsa'
				),
				'conditions' => array(
					'Calculdroitrsa.personne_id' => $personne_id
				),
				'contain' => false
			);
			$calculdroitrsa = $this->Personne->Calculdroitrsa->find( 'first', $query );

			$data['Calculdroitrsa'] = array(
				'id' => Hash::get( $calculdroitrsa, 'Calculdroitrsa.id' ),
				'toppersdrodevorsa' => Hash::get( $calculdroitrsa, 'Calculdroitrsa.toppersdrodevorsa' ),
				'personne_id' => $personne_id
			);

			return $data;
		}

		/**
		 * Sauvegarde du formulaire d'ajout / de modification de l'orientation
		 * d'un bénéficiaire.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEditFormData( array $data, $user_id = null ) {
			$success = true;
			$departement = Configure::read( 'Cg.departement' );

			if( !empty( $user_id ) ) {
				$data[$this->alias]['user_id'] = $user_id;
			}

			$primaryKey = Hash::get( $data, "{$this->alias}.id" );
			$personne_id = Hash::get( $data, "{$this->alias}.personne_id" );
			$typeorient_id = Hash::get( $data, "{$this->alias}.typeorient_id" );
			$referent_id = suffix( Hash::get( $data, "{$this->alias}.referent_id" ) );

			$origine = Hash::get( $data, "{$this->alias}.origine" );
			if( empty( $origine ) ) {
				$data[$this->alias]['origine'] = 'manuelle';
			}

			if( $departement == 58 && empty( $primaryKey ) && $this->isRegression( $personne_id, $typeorient_id ) ) {
				$theme = 'Regressionorientationep58';

				$dossierep = array(
					'Dossierep' => array(
						'personne_id' => $personne_id,
						'themeep' => Inflector::tableize( $theme )
					)
				);

				$success = $this->Personne->Dossierep->save( $dossierep ) && $success;

				$regressionorientationep = array(
					$theme => Hash::merge(
						(array)Hash::get( $data, $this->alias ),
						array(
							'personne_id' => $personne_id,
							'dossierep_id' => $this->Personne->Dossierep->id,
							'datedemande' => Hash::get( $data, "{$this->alias}.date_propo" )
						)
					)
				);

				$success = $this->Personne->Dossierep->{$theme}->save( $regressionorientationep ) && $success;
			}
			else {
				// Orientstruct
				$orientstruct = array( $this->alias => (array)Hash::get( $data, $this->alias ) );
				$orientstruct[$this->alias]['personne_id'] = $personne_id;
				$orientstruct[$this->alias]['valid_cg'] = true;

				if( $departement == 976 ) {
					$statut_orient = Hash::get( $orientstruct, "{$this->alias}.statut_orient" );

					if( $statut_orient != 'Orienté' ) {
						$orientstruct[$this->alias]['origine'] = null;
						$orientstruct[$this->alias]['date_valid'] = null;
					}
				}
				else if( empty( $primaryKey ) ) {
					$orientstruct[$this->alias]['statut_orient'] = 'Orienté';
				}

				$statut_orient = Hash::get( $orientstruct, "{$this->alias}.statut_orient" );

				$this->create( $orientstruct );
				$success = $this->save() && $success;

				// Calculdroitrsa
				$calculdroitsrsa = array( 'Calculdroitrsa' => (array)Hash::get( $data, 'Calculdroitrsa' ) );
				$this->Personne->Calculdroitrsa->create( $calculdroitsrsa );
				$success = $this->Personne->Calculdroitrsa->save() && $success;

				// PersonneReferent
				if( !empty( $referent_id ) && ( $statut_orient == 'Orienté' ) ) {
					$success = $this->Referent->PersonneReferent->referentParModele( $data, $this->alias, 'date_valid' ) && $success;
				}
			}

			return $success;
		}

		/**
		 * Retourne une sous-requête, aliasée si le paramètre $fieldName n'est
		 * pas vide, permettant de savoir si un enregistrement est imprimable,
		 * suivant l'état de l'orientation et le CG connecté.
		 *
		 * @see Configure Cg.departement
		 *
		 * @param string $fieldName
		 * @return string
		 */
		public function getPrintableSq( $fieldName = 'printable' ) {
			$departement = Configure::read( 'Cg.departement' );
			if( $departement == 976 ) {
				$sqPrintable = "\"{$this->alias}\".\"statut_orient\" IN ( 'En attente', 'Orienté' )";
			}
			else if( $departement == 66 ) {
				$sqPrintable = "\"{$this->alias}\".\"statut_orient\" = 'Orienté'";
			}
			else {
				$Pdf = ClassRegistry::init( 'Pdf' );
				$sqPrintable = $Pdf->sqImprime( $this, null );
			}

			if( !empty( $fieldName ) ) {
				$sqPrintable = "( {$sqPrintable} ) AS \"{$this->alias}__{$fieldName}\"";
			}

			return $sqPrintable;
		}

		/**
		 * Retourne un querydata permettant de connaître la liste des orientations
		 * d'un allocataire, en fonction du département.
		 *
		 * @see Configure::read( 'Cg.departement' )
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function getIndexQuery( $personne_id ) {
			$cacheKey = implode( '_', array( $this->useDbConfig, $this->alias, __FUNCTION__ ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				// Il n'est possible d'imprimer une orientation que suivant certaines conditions
				$sqPrintable = $this->getPrintableSq( 'printable' );

				// Il n'est possible de supprimer une orientation que si elle n'est pas liée à d'autres enregistrements
				$sqLinkedRecords = $this->getSqLinkedModelsDepartement( 'linked_records' );

				// La requête
				$query = array(
					'fields' => array_merge(
						$this->fields(),
						$this->Personne->fields(),
						$this->Typeorient->fields(),
						$this->Structurereferente->fields(),
						$this->Referent->fields(),
						array(
							$this->Fichiermodule->sqNbFichiersLies( $this, 'nombre' ),
							$sqPrintable,
							$sqLinkedRecords
						)
					),
					'conditions' => array(),
					'joins' => array(
						$this->join( 'Personne' ),
						$this->join( 'Typeorient' ),
						$this->join( 'Structurereferente' ),
						$this->join( 'Referent' ),
					),
					'contain' => false,
					'order' => array(
						'COALESCE( "Orientstruct"."rgorient", \'0\') DESC',
						'"Orientstruct"."date_valid" DESC',
						'"Orientstruct"."id" DESC'
					)
				);

				// On complète le querydata suivant le CG:
				// 1. Au CG 58, on veut savoir quelle COV a réalisé l'orientation
				if(  Configure::read( 'Cg.departement' ) == 58 ) {
					$query = $this->Personne->Dossiercov58->getCompletedQueryOrientstruct( $query );
				}
				// 2. Au CG 66, on ne peut cliquer sur certains liens que sous certaines conditions
				else if( Configure::read( 'Cg.departement' ) == 66 ) {
					$Dbo = $this->getDataSource();
					$sql = $Dbo->conditions( array( 'Typeorient.parentid' => (array)Configure::read( 'Orientstruct.typeorientprincipale.SOCIAL' ) ), true, false );
					$query['fields'][] = "( {$sql} ) AS \"{$this->alias}__notifbenefcliquable\"";
				}

				// Sauvegarde dans le cache
				Cache::write( $cacheKey, $query );
			}

			$query['conditions']['Orientstruct.personne_id'] = $personne_id;

			return $query;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$query = $this->getIndexQuery( null );
			return !empty( $query );
		}

		/**
		 * Permet de savoir si un allocataire est en cours de procédure de
		 * relance pour une de ses orientations, en fonction du CG.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function enProcedureRelance( $personne_id ) {
			return (
				Configure::read( 'Cg.departement' ) == 93
				&& $this->Nonrespectsanctionep93->enProcedureRelance( $personne_id )
			);
		}
	}
?>