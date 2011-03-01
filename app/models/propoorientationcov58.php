<?php
    
	class Propoorientationcov58 extends AppModel
	{
		public $name = 'Propoorientationcov58';

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			)Z
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
		
		public function getFields() {
			return array(
				$this->alias.'.id',
				$this->alias.'.datedemande',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc'
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
				)
			);
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
			}
			else {
				$dossier['Propoorientationcov58']['referent_id'] = null;
				list($typeorient_id, $structurereferente_id) = explode('_', $data['structurereferente_id']);
				$dossier['Propoorientationcov58']['covtypeorient_id'] = $typeorient_id;
				$dossier['Propoorientationcov58']['covstructurereferente_id'] = $structurereferente_id;
			}
			list($jour, $heure) = explode(' ', $cov58['Cov58']['datecommission']);
			$dossier['Propoorientationcov58']['datevalidation'] = $jour;
			
			$dossier['Dossiercov58']['etapecov'] = 'finalise';
			$success = $this->Dossiercov58->save($dossier['Dossiercov58']) && $success;
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
					'etatorient' => 'decision'
				)
			);
			$success = $this->Dossiercov58->Personne->Orientstruct->save($orientstruct) && $success;
// 			$success = $this->Gedooo->mkOrientstructPdf( $this->Dossiercov58->Personne->Orientstruct->getLastInsertId() ) && $success;
			
			return $success;
		}
		
	}
?>