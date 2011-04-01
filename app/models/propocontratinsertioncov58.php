<?php
	class Propocontratinsertioncov58 extends AppModel
	{
		public $name = 'Propocontratinsertioncov58';

		public $actsAs = array(
			'Autovalidate',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'num_contrat' => array( 'type' => 'num_contrat', 'domain' => 'propocontratinsertioncov58' )
                )
			),
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
				'Structurereferente.lib_struc',
				'Referent.nom',
				'Referent.prenom',
				'Referent.qual'
			);
		}
		
		/**
		*
		*/
		
		public function getJoins() {
			return array(
				array(
					'table' => 'proposcontratsinsertioncovs58',
					'alias' => $this->alias,
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = Propocontratinsertioncov58.dossiercov58_id'
					)
				),
				array(
					'table' => 'structuresreferentes',
					'alias' => 'Structurereferente',
					'type' => 'INNER',
					'conditions' => array(
						'Propocontratinsertioncov58.structurereferente_id = Structurereferente.id'
					)
				),
				array(
					'table' => 'referents',
					'alias' => 'Referent',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'Propocontratinsertioncov58.referent_id = Referent.id'
					)
				)
			);
		}

		/**
		* FIXME -> aucun dossier en cours, pour certains thèmes:
		*		- CG 93
		*			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		*			* Reorientationep93 -> peut déboucher sur une réorientation
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
						'Propocontratinsertioncov58'
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
		debug($data);
			$success = true;
			$dossier = $this->find(
				'first',
				array(
					'conditions' => array(
						'Propocontratinsertioncov58.id' => $data['id']
					),
					'contain' => array(
						'Dossiercov58'
					)
				)
			);
			
			$dossier['Dossiercov58']['etapecov'] = 'finalise';
			$success = $this->Dossiercov58->save($dossier['Dossiercov58']) && $success;
			$success = $this->save($dossier['Propocontratinsertioncov58']) && $success;
			
			$contratinsertion = array(
				'Contratinsertion' => array(
					'personne_id' => $dossier['Dossiercov58']['personne_id'],
					'structurereferente_id' => $dossier['Propocontratinsertioncov58']['structurereferente_id'],
					'referent_id' => $dossier['Propocontratinsertioncov58']['referent_id'],
					'num_contrat' => $dossier['Propocontratinsertioncov58']['num_contrat'],
					'dd_ci' => $dossier['Propocontratinsertioncov58']['dd_ci'],
					'duree_engag' => $dossier['Propocontratinsertioncov58']['duree_engag'],
					'df_ci' => $dossier['Propocontratinsertioncov58']['df_ci'],
					'forme_ci' => $dossier['Propocontratinsertioncov58']['forme_ci'],
					'avisraison_ci' => $dossier['Propocontratinsertioncov58']['avisraison_ci'],
					'rg_ci' => $dossier['Propocontratinsertioncov58']['rg_ci'],
					'observ_ci' => $data['commentaire'],
					'date_saisi_ci' => $dossier['Propocontratinsertioncov58']['datedemande'],
					'datevalidation_ci' => $data['datevalidation'],
					'decision_ci' => 'V'
				)
			);
			$success = $this->Dossiercov58->Personne->Contratinsertion->save($contratinsertion) && $success;
			
			return $success;
		}
		
	}
?>