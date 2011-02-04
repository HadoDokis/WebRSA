<?php
	class Propoorientationcov58 extends AppModel
	{
		public $name = 'Propoorientationcov58';

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			)
		);

		public $validate = array(
			'structurereferente_id' => array(
				array(
					'rule' => array( 'choixStructure', 'statut_orient' ),
					'message' => 'Champ obligatoire'
				)
			),
			'typeorient_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
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
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'dossiercov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		/**
		*
		*/
		
		public function getContain() {
			return array(
				$this->alias => array(
					'Typeorient',
					'Structurereferente',
					'Referent'
				)
			);
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
		* Récupère les données pour le PDf
		*/

		public function getDataForPdf( $id, $user_id ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
			$qual = $optionModel->qual();
			$typevoie = $optionModel->typevoie();


			$orientstruct = $this->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.id' => $id
					)
				)
			);

			/*$typeorient = $this->Structurereferente->Typeorient->find(
				'first',
				array(
					'conditions' => array(
						'Typeorient.id' => $orientstruct['Orientstruct']['typeorient_id'] // FIXME structurereferente_id
					)
				)
			);*/

			$this->Personne->Foyer->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->Personne->Foyer->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => $orientstruct['Personne']['foyer_id'],
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$orientstruct['Adresse'] = $adresse['Adresse'];

			// Récupération de l'utilisateur
			$user = ClassRegistry::init( 'User' )->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					)
				)
			);
			$orientstruct['User'] = $user['User'];

			// Recherche des informations du dossier
			$foyer = $this->Personne->Foyer->findById( $orientstruct['Personne']['foyer_id'], null, null, -1 );
			$dossier = $this->Personne->Foyer->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $foyer['Foyer']['dossier_id']
					),
					'recursive' => -1
				)
			);

			$orientstruct['Dossier'] = $dossier['Dossier'];

			if( isset( $orientstruct[$this->alias]['statut_orient'] ) && $orientstruct[$this->alias]['statut_orient'] == 'Orienté' ) {
				//Ajout pour le numéro de poste du référent de la structure
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

			$orientstruct['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $orientstruct['Personne']['dtnai'] ) );
			$orientstruct['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $orientstruct, 'Personne.qual' ) );
			$orientstruct['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Adresse.typevoie' ) );
			$orientstruct['Structurereferente']['type_voie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Structurereferente.type_voie' ) );


			$personne_referent = $this->Personne->Referent->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => Set::classicExtract( $orientstruct, 'Personne.id' )
					)
				)
			);

			if( !empty( $personne_referent ) ){
				$orientstruct = Set::merge( $orientstruct, $personne_referent );
			}

			return $orientstruct;
		}

		/**
		* Ajout d'entrée dans la table orientsstructs pour les DEM ou CJT RSA n'en possédant pas
		*/

		public function fillAllocataire() {
			$sql = "INSERT INTO orientsstructs (personne_id, statut_orient)
					(
						SELECT DISTINCT personnes.id, 'Non orienté' AS statut_orient
							FROM personnes
								INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = 'RSA' AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' ) )
							WHERE personnes.id NOT IN (
								SELECT orientsstructs.personne_id
									FROM orientsstructs
							)
					);";
			return $this->query( $sql );
		}

		/**
		* FIXME -> aucun dossier en cours, pour certains thèmes:
		*		- CG 93
		*			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		*			* Saisineepreorientsr93 -> peut déboucher sur une réorientation
		*		- CG 66
		*			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		*			* Saisineepbilanparcours66 -> peut déboucher sur une réorientation
		*			* Saisineepdpdo66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
		* FIXME -> CG 93: s'il existe une procédure de relance, on veut faire signer un contrat,
					mais on veut peut-être aussi demander une réorientation.
		* FIXME -> doit-on vérifier si:
		* 			- la personne est soumise à droits et devoirs (oui)
		*			- la personne est demandeur ou conjoint RSA (oui) ?
		*			- le dossier est dans un état ouvert (non) ?
		*/

		public function ajoutPossible( $personne_id ) {
			$nbDossierscov = $this->Dossiercov58->find(
				'count',
				array(
					'conditions' => array(
						'Dossiercov58.personne_id' => $personne_id,
						'Dossiercov58.etapecov <>' => 'finalise'
					),
					'contain' => array(
						'Propoorientationcov58'
					)
				)
			);

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
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id',
								'Calculdroitrsa.toppersdrodevorsa' => 1
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
							'conditions' => array(
								'Situationdossierrsa.dossier_id = Dossier.id',
								'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
							)
						),
					),
					'recursive' => -1
				)
			);
			
			return ( ( $nbDossierscov == 0 ) && ( $nbPersonnes == 1 ) );
		}

		/**
		* Ajout du rang d'orientation à la sauvegarde, lorsqu'on passe en 'Orienté'
		*/

		public function beforeSave( $options = array() ) {
			// Si on change le statut_orient de <> 'Orienté' en 'Orienté', alors, il faut changer le rang
			if( isset( $this->data[$this->alias]['statut_orient'] ) && ( $this->data[$this->alias]['statut_orient'] == 'Orienté' ) ) {
				// Change-t'on le statut ?
				if( isset( $this->data[$this->alias]['id'] ) && !empty( $this->data[$this->alias]['id'] ) ) {
					$tuple_pcd = $this->find( 'first', array( 'conditions' => array( "{$this->alias}.{$this->primaryKey}" => $this->data[$this->alias]['id'] ), 'contain' => false ) );
					if( $tuple_pcd[$this->alias]['statut_orient'] != 'Orienté' ) {
						//$rgprecedent = $this->find( 'count', array( 'conditions' => array( "{$this->alias}.statut_orient" => 'Orienté', "{$this->alias}.personne_id" => $this->data[$this->alias]['personne_id'] ), 'contain' => false ) );
						$this->data[$this->alias]['rgorient'] = ( $this->rgorientMax( $this->data[$this->alias]['personne_id'] ) + 1 );
					}
				}
				// Nouvelle entrée
				else if( isset( $this->data[$this->alias]['personne_id'] ) && !empty( $this->data[$this->alias]['personne_id'] ) ) {
					$this->data[$this->alias]['rgorient'] = ( $this->rgorientMax( $this->data[$this->alias]['personne_id'] ) + 1 );
				}
			}

			return true;
		}
	}
?>