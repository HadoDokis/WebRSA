<?php
	/**
	 * Fichier source de la classe Dossier.
	 *
	 * PHP 5.3
	 *
	 * @package       app.models
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
					'Adressefoyer.rgadr' => '01',
					'Prestation.rolepers' => array( 'DEM', 'CJT' )
				);
			}
			else {
				$typeJointure = 'LEFT OUTER';
				$conditions = array(
					'OR' => array(
						'Prestation.rolepers IS NULL',
						'Prestation.rolepers IN ( \'DEM\', \'CJT\' )'
					),
					'Adressefoyer.rgadr' => '01',
				);
			}

			$conditions = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $params );
						
// 			$sansPrestation  = Set::extract( $params, 'Personne.sansprestation' );
// 			if( ( $sansPrestation = '1' ) ) {
// 				$conditions[] = 'Prestation.rolepers IS NULL';
// 			}
// 			else {
// 				$conditions = array( 'Prestation.rolepers' => array( 'DEM', 'CJT' ) );
// 			}
			

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
			if( isset( $params['Orientstruct']['sansorientation'] ) && $params['Orientstruct']['sansorientation'] ) {
				$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) = 0';
			}


			$referent_id = Set::classicExtract( $params, 'PersonneReferent.referent_id' );
			if( !empty( $referent_id ) ) {
				$conditionsReferent = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';

				$conditions[] = 'Personne.id IN (
									SELECT personnes_referents.personne_id
										FROM personnes_referents
											INNER JOIN personnes ON (
												personnes_referents.personne_id = personnes.id
											)
										WHERE
											personnes_referents.dfdesignation IS NULL
											AND '.$conditionsReferent.'
								)';
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
					'Prestation.rolepers',
					'PersonneReferent.referent_id'
				),
				'recursive' => -1,
				'joins' => array(
					$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Foyer->Personne->join( 'Prestation', array( 'type' => $typeJointure ) ),
					$this->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$this->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$this->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$this->Foyer->Personne->join( 'PersonneReferent', array( 'type' => 'LEFT OUTER' ) ),
				),
				'limit' => 10,
				'order' => array( 'Personne.nom ASC' ),
				'conditions' => $conditions
			);

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
	}
?>