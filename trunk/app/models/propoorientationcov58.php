<?php
    
	class Propoorientationcov58 extends AppModel
	{
		public $name = 'Propoorientationcov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			),
			'Gedooo'
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
			'Covtypeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'covtypeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Covstructurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'covstructurereferente_id',
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
		
		public function getFields() {
			return array(
				$this->alias.'.id',
				$this->alias.'.datedemande',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Referent.qual',
				'Referent.nom',
				'Referent.prenom'
			);
		}
		
		/**
		*
		*/
		
		public function getJoins() {
			return array(
				array(
					'table' => 'proposorientationscovs58',
					'alias' => $this->alias,
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = Propoorientationcov58.dossiercov58_id'
					)
				),
				array(
					'table' => 'structuresreferentes',
					'alias' => 'Structurereferente',
					'type' => 'INNER',
					'conditions' => array(
						'Propoorientationcov58.structurereferente_id = Structurereferente.id'
					)
				),
				array(
					'table' => 'typesorients',
					'alias' => 'Typeorient',
					'type' => 'INNER',
					'conditions' => array(
						'Propoorientationcov58.typeorient_id = Typeorient.id'
					)
				),
				array(
					'table' => 'referents',
					'alias' => 'Referent',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'Propoorientationcov58.referent_id = Referent.id'
					)
				)
			);
		}

		/**
		* FIXME -> aucun dossier en cours, pour certains thèmes:
		*		- CG 93
		*			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		*			* Propoorientationcov58 -> peut déboucher sur une réorientation
		*		- CG 66
		*			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		*			* Saisinebilanparcoursep66 -> peut déboucher sur une réorientation
		*			* Saisinepdoep66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
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
		*
		*/
		
		public function saveDecision($data, $cov58) {
			$success = true;
			$dossier = $this->find(
				'first',
				array(
					'conditions' => array(
						'Propoorientationcov58.id' => $data['id']
					),
					'contain' => array(
						'Dossiercov58'
					)
				)
			);
			if ( $data['decisioncov'] == 'accepte' ) {
				$dossier['Propoorientationcov58']['covtypeorient_id'] = $dossier['Propoorientationcov58']['typeorient_id'];
				$dossier['Propoorientationcov58']['covstructurereferente_id'] = $dossier['Propoorientationcov58']['structurereferente_id'];
				$dossier['Propoorientationcov58']['covreferent_id'] = $dossier['Propoorientationcov58']['referent_id'];
			}
			else {
				list($structurereferente_id, $referent_id) = explode('_', $data['referent_id']);
				list($typeorient_id, $structurereferente_id) = explode('_', $data['structurereferente_id']);
				$dossier['Propoorientationcov58']['covtypeorient_id'] = $typeorient_id;
				$dossier['Propoorientationcov58']['covstructurereferente_id'] = $structurereferente_id;
				$dossier['Propoorientationcov58']['referent_id'] = $referent_id;
			}
			
			$dossier['Dossiercov58']['etapecov'] = 'finalise';
			$success = $this->Dossiercov58->save($dossier['Dossiercov58']) && $success;

			list($jour, $heure) = explode(' ', $cov58['Cov58']['datecommission']);
			$dossier['Propoorientationcov58']['datevalidation'] = $jour;
			$dossier['Propoorientationcov58']['decisioncov'] = $data['decisioncov'];
			$success = $this->save($dossier['Propoorientationcov58']) && $success;
			
			$orientstruct = array(
				'Orientstruct' => array(
					'personne_id' => $dossier['Dossiercov58']['personne_id'],
					'typeorient_id' => $dossier['Propoorientationcov58']['covtypeorient_id'],
					'structurereferente_id' => $dossier['Propoorientationcov58']['covstructurereferente_id'],
					'referent_id' => $dossier['Propoorientationcov58']['referent_id'],
					'date_propo' => $dossier['Propoorientationcov58']['datedemande'],
					'date_valid' => $dossier['Propoorientationcov58']['datevalidation'],
					'rgorient' => $dossier['Propoorientationcov58']['rgorient'],
					'statut_orient' => 'Orienté',
					'etatorient' => 'decision',
					'user_id' => $dossier['Propoorientationcov58']['user_id']
				)
			);
			$this->Dossiercov58->Personne->Orientstruct->create( $orientstruct );
			$success = $this->Dossiercov58->Personne->Orientstruct->save() && $success;
// 			$success = $this->Dossiercov58->Personne->Orientstruct->generatePdf( $this->Dossiercov58->Personne->Orientstruct->id, $dossier['Propoorientationcov58']['user_id'] ) && $success;
			
			return $success;
		}

		/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Propoorientationcov58.id',
					'Propoorientationcov58.dossiercov58_id',
					'Propoorientationcov58.typeorient_id',
					'Propoorientationcov58.structurereferente_id',
					'Propoorientationcov58.datedemande',
					'Propoorientationcov58.rgorient',
					'Propoorientationcov58.commentaire',
					'Propoorientationcov58.covtypeorient_id',
					'Propoorientationcov58.covstructurereferente_id',
					'Propoorientationcov58.datevalidation',
					'Propoorientationcov58.commentaire',
					'Propoorientationcov58.user_id',
					'Propoorientationcov58.decisioncov',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc'
				),
				'joins' => array(
					array(
						'table'      => 'proposorientationscovs58',
						'alias'      => 'Propoorientationcov58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.dossiercov58_id = Dossiercov58.id' ),
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.covtypeorient_id = Typeorient.id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.covstructurereferente_id = Structurereferente.id' ),
					)
				)
			);
		}

		/**
		*
		*/

		public function qdOrdreDuJour() {
			return array(
				'fields' => array(
					'Propoorientationcov58.id',
					'Propoorientationcov58.dossiercov58_id',
					'Propoorientationcov58.typeorient_id',
					'Propoorientationcov58.structurereferente_id',
					'Propoorientationcov58.datedemande',
					'Propoorientationcov58.rgorient',
					'Propoorientationcov58.commentaire',
					'Propoorientationcov58.datevalidation',
					'Propoorientationcov58.commentaire',
					'Propoorientationcov58.user_id',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc'
				),
				'joins' => array(
					array(
						'table'      => 'proposorientationscovs58',
						'alias'      => 'Propoorientationcov58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.dossiercov58_id = Dossiercov58.id' ),
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.typeorient_id = Typeorient.id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.structurereferente_id = Structurereferente.id' ),
					)
				)
			);
		}

		/**
		*
		*/

		public function getPdfDecision( $dossiercov58_id ) {
			$dossiercov58_data = $this->Dossiercov58->find(
				'first',
				array(
					'fields' => array(
						'Dossiercov58.id',
						'Dossiercov58.personne_id',
						'Dossiercov58.themecov58_id',
						//
						'Personne.id',
						'Personne.foyer_id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Personne.nomnai',
						'Personne.prenom2',
						'Personne.prenom3',
						'Personne.nomcomnai',
						'Personne.dtnai',
						'Personne.rgnai',
						'Personne.typedtnai',
						'Personne.nir',
						'Personne.topvalec',
						'Personne.sexe',
						'Personne.nati',
						'Personne.dtnati',
						'Personne.pieecpres',
						'Personne.idassedic',
						'Personne.numagenpoleemploi',
						'Personne.dtinscpoleemploi',
						'Personne.numfixe',
						'Personne.numport',
						'Adresse.locaadr',
						'Adresse.numcomptt',
						'Adresse.codepos',
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.complideadr',
						'Adresse.compladr',
						'Dossier.numdemrsa',
						'Dossier.dtdemrsa',
						//
						'Propoorientationcov58.id',
						'Propoorientationcov58.dossiercov58_id',
						'Propoorientationcov58.typeorient_id',
						'Propoorientationcov58.structurereferente_id',
						'Propoorientationcov58.datedemande',
						'Propoorientationcov58.rgorient',
						'Propoorientationcov58.commentaire',
						'Propoorientationcov58.covtypeorient_id',
						'Propoorientationcov58.covstructurereferente_id',
						'Propoorientationcov58.datevalidation',
						'Propoorientationcov58.commentaire',
						'Propoorientationcov58.user_id',
						'Propoorientationcov58.decisioncov',
						'Typeorient.lib_type_orient',
						'Structurereferente.lib_struc',
						'Covtypeorient.lib_type_orient',
						'Covstructurereferente.lib_struc',
						'Covstructurereferente.num_voie',
						'Covstructurereferente.nom_voie',
						'Covstructurereferente.code_postal',
						'Covstructurereferente.ville',
						'Sitecov58.name',
						//
						'User.nom',
						'User.prenom',
						'User.numtel',
						'Serviceinstructeur.lib_service',
					),
					'conditions' => array(
						'Dossiercov58.id' => $dossiercov58_id
					),
					'joins' => array(
						array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( "Personne.id = Dossiercov58.personne_id" ),
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
						),
						array(
							'table'      => 'dossiers',
							'alias'      => 'Dossier',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
						),
						array(
							'table'      => 'adressesfoyers',
							'alias'      => 'Adressefoyer',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Adressefoyer.foyer_id',
								// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
								'Adressefoyer.id IN (
									'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
								)'
							)
						),
						array(
							'table'      => 'adresses',
							'alias'      => 'Adresse',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
						),
						array(
							'table'      => 'proposorientationscovs58',
							'alias'      => 'Propoorientationcov58',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossiercov58.id = Propoorientationcov58.dossiercov58_id' )
						),
						array(
							'table'      => 'typesorients',
							'alias'      => 'Typeorient',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Propoorientationcov58.typeorient_id = Typeorient.id' ),
						),
						array(
							'table'      => 'structuresreferentes',
							'alias'      => 'Structurereferente',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Propoorientationcov58.structurereferente_id = Structurereferente.id' ),
						),
						array(
							'table'      => 'typesorients',
							'alias'      => 'Covtypeorient',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Propoorientationcov58.covtypeorient_id = Covtypeorient.id' ),
						),
						array(
							'table'      => 'structuresreferentes',
							'alias'      => 'Covstructurereferente',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Propoorientationcov58.covstructurereferente_id = Covstructurereferente.id' ),
						),
						array(
							'table'      => 'users',
							'alias'      => 'User',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Propoorientationcov58.typeorient_id = Typeorient.id' ),
						),
						array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'User.serviceinstructeur_id = Serviceinstructeur.id' ),
						),
						array(
							'table'      => 'sitescovs58',
							'alias'      => 'Sitecov58',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'User.serviceinstructeur_id = Serviceinstructeur.id' ),
						)
					),
					'contain' => false
				)
			);

			$options = array(
				'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'Adresse' => array( 'typevoie' => ClassRegistry::init( 'Option' )->typevoie() )
			);
			$options = Set::merge( $options, $this->Dossiercov58->enums() );

			///FIXME: ajouter règles pour choisir le bon fichier

			$fileName = '';
			if ( $dossiercov58_data['Propoorientationcov58']['decisioncov'] == 'accepte' ) {
				if( strcmp( 'Emploi', $dossiercov58_data['Covtypeorient']['lib_type_orient'] ) != -1 ) {
					if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
						$fileName = 'decisionorientationpro.odt';
					}
					else {
						$fileName = 'decisionreorientationpro.odt';
					}
				}
				else {
					if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
						$fileName = 'decisionorientationsoc.odt';
					}
					else {
						$fileName = 'decisionreorientationsoc.odt';
					}
				}
			}
			else {
				if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
					return false;
				}
				else {
					$fileName = 'decisionrefusreorientation.odt';
				}
			}

// debug( $fileName );
// debug( $dossiercov58_data );
// die();

			return $this->ged(
				$dossiercov58_data,
				"Cov58/{$fileName}",
				false,
				$options
			);

		}

	}
?>