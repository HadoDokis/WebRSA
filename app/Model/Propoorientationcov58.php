<?php
	/**
	 * Code source de la classe Propoorientationcov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Propoorientationcov58 ...
	 *
	 * @package app.Model
	 */
	class Propoorientationcov58 extends AppModel
	{
		public $name = 'Propoorientationcov58';

		public $recursive = -1;

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'Cov58/decisionorientationpro.odt',
			'Cov58/decisionreorientationpro.odt',
			'Cov58/decisionorientationsoc.odt',
			'Cov58/decisionreorientationsoc.odt',
			'Cov58/decisionrefusreorientation.odt',
		);

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id', 'structureorientante_id', 'referentorientant_id' ),
			),
			'Gedooo.Gedooo'
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
			'Nvorientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'nvorientstruct_id',
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
			),
			'Structureorientante' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structureorientante_id',
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
		);

		/**
		 * Règle de validation.
		 *
		 * @param type $field
		 * @param type $compare_field
		 * @return boolean
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
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers de COV
		*/
		public function qdListeDossier( $cov58_id = null ) {
			$return = array(
				'fields' => array(
					'Dossiercov58.id',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.dtnai',
					'Personne.prenom',
					'Dossier.numdemrsa',
					'Adresse.locaadr',
// 					'Structurereferente.lib_struc',
					'Passagecov58.id',
					'Passagecov58.cov58_id',
					'Passagecov58.etatdossiercov'
				)
			);

			if( !empty( $cov58_id ) ) {
				$join = array(
					'alias' => 'Dossiercov58',
					'table' => 'dossierscovs58',
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = '.$this->alias.'.dossiercov58_id'
					)
				);
			}
			else {
				$join = array(
					'alias' => $this->alias,
					'table' => Inflector::tableize( $this->alias ),
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = '.$this->alias.'.dossiercov58_id'
					)
				);
			}

			$return['joins'] = array(
				$join,/*
				array(
					'alias' => 'Structurereferente',
					'table' => 'structuresreferentes',
					'type' => 'INNER',
					'conditions' => array(
						'Structurereferente.id = Contratinsertion.structurereferente_id'
					)
				),*/
				array(
					'alias' => 'Themecov58',
					'table' => 'themescovs58',
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.themecov58_id = Themecov58.id'
					)
				),
				array(
					'alias' => 'Personne',
					'table' => 'personnes',
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.personne_id = Personne.id'
					)
				),
				array(
					'alias' => 'Foyer',
					'table' => 'foyers',
					'type' => 'INNER',
					'conditions' => array(
						'Personne.foyer_id = Foyer.id'
					)
				),
				array(
					'alias' => 'Dossier',
					'table' => 'dossiers',
					'type' => 'INNER',
					'conditions' => array(
						'Foyer.dossier_id = Dossier.id'
					)
				),
				array(
					'alias' => 'Adressefoyer',
					'table' => 'adressesfoyers',
					'type' => 'INNER',
					'conditions' => array(
						'Adressefoyer.foyer_id = Foyer.id',
						'Adressefoyer.rgadr' => '01'
					)
				),
				array(
					'alias' => 'Adresse',
					'table' => 'adresses',
					'type' => 'INNER',
					'conditions' => array(
						'Adressefoyer.adresse_id = Adresse.id'
					)
				),
				array(
					'alias' => 'Passagecov58',
					'table' => 'passagescovs58',
					'type' => 'LEFT OUTER',
					'conditions' => Set::merge(
						array( 'Passagecov58.dossiercov58_id = Dossiercov58.id' ),
						empty( $cov58_id ) ? array() : array(
							'OR' => array(
								'Passagecov58.cov58_id IS NULL',
								'Passagecov58.cov58_id' => $cov58_id
							)
						)
					)
				)
			);

			return $return;
		}




		public function qdDossiersParListe( $cov58_id ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossiercov58->Passagecov58->Cov58->themesTraites( $cov58_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];

			return array(
				'conditions' => array(
					'Dossiercov58.themecov58' => Inflector::tableize( $this->alias ),
					'Dossiercov58.id IN ( '.
						$this->Dossiercov58->Passagecov58->sq(
							array(
								'fields' => array(
									'passagescovs58.dossiercov58_id'
								),
								'alias' => 'passagescovs58',
								'conditions' => array(
									'passagescovs58.cov58_id' => $cov58_id
								)
							)
						)
					.' )'
				),
				'contain' => array(
					'Personne' => array(
						'Foyer' => array(
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					),
					$this->alias => array(
						'Typeorient',
						'Structurereferente',
						'Referent'

					),
					'Passagecov58' => array(
						'conditions' => array(
							'Passagecov58.cov58_id' => $cov58_id
						),
						'Decision'.Inflector::underscore( $this->alias ) => array(
							'Typeorient',
							'Structurereferente',
							'order' => array( 'etapecov DESC' )
						)
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
						'Dossiercov58.personne_id' => $personne_id/*,
						'Dossiercov58.etapecovcov <>' => 'finalise'*/
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
								'Calculdroitrsa.toppersdrodevorsa' => '1'
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

		public function saveDecisions( $data ) {
			$modelDecisionName = 'Decision'.Inflector::underscore( $this->alias );
			$success = true;

			if ( isset( $data[$modelDecisionName] ) && !empty( $data[$modelDecisionName] ) ) {
				foreach( $data[$modelDecisionName] as $key => $values ) {
					$passagecov58 = $this->Dossiercov58->Passagecov58->find(
						'first',
						array(
							'fields' => array_merge(
								$this->Dossiercov58->Passagecov58->fields(),
								$this->Dossiercov58->Passagecov58->Cov58->fields(),
								$this->Dossiercov58->fields(),
								$this->fields()
							),
							'conditions' => array(
								'Passagecov58.id' => $values['passagecov58_id']
							),
							'joins' => array(
								$this->Dossiercov58->Passagecov58->join( 'Dossiercov58' ),
								$this->Dossiercov58->Passagecov58->join( 'Cov58' ),
								$this->Dossiercov58->join( $this->alias )
							)
						)
					);

					if( in_array( $values['decisioncov'], array( 'valide', 'refuse' ) ) ){

						if( $values['decisioncov'] == 'valide' ){
							$data[$modelDecisionName][$key]['typeorient_id'] = $passagecov58[$this->alias]['typeorient_id'];
							$data[$modelDecisionName][$key]['structurereferente_id'] = $passagecov58[$this->alias]['structurereferente_id'];
							$data[$modelDecisionName][$key]['referent_id'] = $passagecov58[$this->alias]['referent_id'];

							list($datevalidation, $heure) = explode(' ', $passagecov58['Cov58']['datecommission']);

							$orientstruct = array(
								'Orientstruct' => array(
									'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
									'typeorient_id' => $passagecov58[$this->alias]['typeorient_id'],
									'structurereferente_id' => $passagecov58[$this->alias]['structurereferente_id'],
									'referent_id' => $passagecov58[$this->alias]['referent_id'],
									'date_propo' => $passagecov58['Propoorientationcov58']['datedemande'],
									'date_valid' => $datevalidation,
									'rgorient' => $passagecov58['Propoorientationcov58']['rgorient'],
									'statut_orient' => 'Orienté',
									'etatorient' => 'decision',
									'origine' => 'manuelle',
									'user_id' => $passagecov58['Propoorientationcov58']['user_id'],
								)
							);

							$success = $this->Dossiercov58->Personne->PersonneReferent->changeReferentParcours(
								$passagecov58['Dossiercov58']['personne_id'],
								$passagecov58[$this->alias]['referent_id'],
								array(
									'PersonneReferent' => array(
										'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
										'referent_id' => $passagecov58[$this->alias]['referent_id'],
										'dddesignation' => $datevalidation,
										'structurereferente_id' => $passagecov58[$this->alias]['structurereferente_id'],
										'user_id' => $passagecov58[$this->alias]['user_id']
									)
								)
							) && $success;
						}
						else if( $values['decisioncov'] == 'refuse' ) {
							$referent_id = null;
							if( strstr( $values['referent_id'],  '_' ) !== false ) {
								list($structurereferente_id, $referent_id) = explode('_', $values['referent_id']);
							}
							list($typeorient_id, $structurereferente_id) = explode('_', $values['structurereferente_id']);
							$data[$modelDecisionName][$key]['typeorient_id'] = $typeorient_id;
							$data[$modelDecisionName][$key]['structurereferente_id'] = $structurereferente_id;
							$data[$modelDecisionName][$key]['referent_id'] = $referent_id;

							$data[$modelDecisionName][$key]['datevalidation'] = $passagecov58['Cov58']['datecommission'];
							list($datevalidation, $heure) = explode(' ', $passagecov58['Cov58']['datecommission']);

							$orientstruct = array(
								'Orientstruct' => array(
									'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
									'typeorient_id' => $data[$modelDecisionName][$key]['typeorient_id'],
									'structurereferente_id' => $data[$modelDecisionName][$key]['structurereferente_id'],
									'referent_id' => $data[$modelDecisionName][$key]['referent_id'],
									'date_propo' => $passagecov58['Propoorientationcov58']['datedemande'],
									'date_valid' => $datevalidation,
									'rgorient' => $passagecov58['Propoorientationcov58']['rgorient'],
									'statut_orient' => 'Orienté',
									'etatorient' => 'decision',
									'origine' => 'manuelle',
									'user_id' => $passagecov58['Propoorientationcov58']['user_id']
								)
							);

							$success = $this->Dossiercov58->Personne->PersonneReferent->changeReferentParcours(
								$passagecov58['Dossiercov58']['personne_id'],
								$data[$modelDecisionName][$key]['referent_id'],
								array(
									'PersonneReferent' => array(
										'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
										'referent_id' => $data[$modelDecisionName][$key]['referent_id'],
										'dddesignation' => $datevalidation,
										'structurereferente_id' => $data[$modelDecisionName][$key]['structurereferente_id'],
										'user_id' => $passagecov58[$this->alias]['user_id'],
									)
								)
							) && $success;
						}

						$this->Dossiercov58->Personne->Orientstruct->create( $orientstruct );
						$success = $this->Dossiercov58->Personne->Orientstruct->save() && $success;

						// Mise à jour de l'enregistrement de la thématique avec l'id du nouveau CER
						$success = $this->updateAll(
							array( "\"{$this->alias}\".\"nvorientstruct_id\"" => $this->Dossiercov58->Personne->Orientstruct->id ),
							array( "\"{$this->alias}\".\"id\"" => $data[$this->alias][$key] )
						) && $success;
					}


					// Modification etat du dossier passé dans la COV
					if( in_array( $values['decisioncov'], array( 'valide', 'refuse' ) ) ){
						$this->Dossiercov58->Passagecov58->updateAll(
							array( 'Passagecov58.etatdossiercov' => '\'traite\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'annule' ){
						$this->Dossiercov58->Passagecov58->updateAll(
							array( 'Passagecov58.etatdossiercov' => '\'annule\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'reporte' ){
						$this->Dossiercov58->Passagecov58->updateAll(
							array( 'Passagecov58.etatdossiercov' => '\'reporte\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
				}

				$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->saveAll( Set::extract( $data, '/'.$modelDecisionName ), array( 'atomic' => false ) );
			}

			return $success;
		}




		/**
		*
		*/

		public function qdProcesVerbal() {
			$modele = 'Propoorientationcov58';
			$modeleDecisions = 'Decisionpropoorientationcov58';

			return array(
				'fields' => array(
					"{$modele}.id",
					"{$modele}.dossiercov58_id",
					"{$modele}.typeorient_id",
					"{$modele}.structurereferente_id",
					"{$modele}.datedemande",
					"{$modele}.rgorient",
					"{$modele}.commentaire",
					"{$modele}.covtypeorient_id",
					"{$modele}.covstructurereferente_id",
					"{$modele}.datevalidation",
					"{$modele}.commentaire",
					"{$modele}.user_id",
					"{$modele}.decisioncov",
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					"{$modeleDecisions}.id",
					"{$modeleDecisions}.etapecov",
					"{$modeleDecisions}.decisioncov",
					"{$modeleDecisions}.typeorient_id",
					"{$modeleDecisions}.structurereferente_id",
					"{$modeleDecisions}.referent_id",
					"{$modeleDecisions}.commentaire",
					"{$modeleDecisions}.created",
					"{$modeleDecisions}.modified",
					"{$modeleDecisions}.passagecov58_id",
					"{$modeleDecisions}.datevalidation",
				),
				'joins' => array(
					array(
						'table'      => Inflector::tableize( $modele ),
						'alias'      => $modele,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$modele}.dossiercov58_id = Dossiercov58.id" ),
					),
					array(
						'table'      => Inflector::tableize( $modeleDecisions ),
						'alias'      => $modeleDecisions,
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							"{$modeleDecisions}.passagecov58_id = Passagecov58.id",
							"{$modeleDecisions}.etapecov" => 'finalise'
						),
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$modeleDecisions}.typeorient_id = Typeorient.id" ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( "{$modeleDecisions}.structurereferente_id = Structurereferente.id" ),
					)
				)
			);
		}


		/**
		*
		*/
/*
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
		}*/


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
					'Propoorientationcov58typeorient.lib_type_orient',
					'Propoorientationcov58structurereferente.lib_struc'
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
						'alias'      => 'Propoorientationcov58typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.typeorient_id = Propoorientationcov58typeorient.id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Propoorientationcov58structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientationcov58.structurereferente_id = Propoorientationcov58structurereferente.id' ),
					)
				)
			);
		}

		/**
		*
		*/

// 		public function getPdfDecision( $dossiercov58_id ) {
// 			$dossiercov58_data = $this->Dossiercov58->find(
// 				'first',
// 				array(
// 					'fields' => array(
// 						'Dossiercov58.id',
// 						'Dossiercov58.personne_id',
// 						'Dossiercov58.themecov58_id',
// 						//
// 						'Personne.id',
// 						'Personne.foyer_id',
// 						'Personne.qual',
// 						'Personne.nom',
// 						'Personne.prenom',
// 						'Personne.nomnai',
// 						'Personne.prenom2',
// 						'Personne.prenom3',
// 						'Personne.nomcomnai',
// 						'Personne.dtnai',
// 						'Personne.rgnai',
// 						'Personne.typedtnai',
// 						'Personne.nir',
// 						'Personne.topvalec',
// 						'Personne.sexe',
// 						'Personne.nati',
// 						'Personne.dtnati',
// 						'Personne.pieecpres',
// 						'Personne.idassedic',
// 						'Personne.numagenpoleemploi',
// 						'Personne.dtinscpoleemploi',
// 						'Personne.numfixe',
// 						'Personne.numport',
// 						'Adresse.locaadr',
// 						'Adresse.numcomptt',
// 						'Adresse.codepos',
// 						'Adresse.numvoie',
// 						'Adresse.typevoie',
// 						'Adresse.nomvoie',
// 						'Adresse.complideadr',
// 						'Adresse.compladr',
// 						'Dossier.numdemrsa',
// 						'Dossier.dtdemrsa',
// 						//
// 						'Propoorientationcov58.id',
// 						'Propoorientationcov58.dossiercov58_id',
// 						'Propoorientationcov58.typeorient_id',
// 						'Propoorientationcov58.structurereferente_id',
// 						'Propoorientationcov58.datedemande',
// 						'Propoorientationcov58.rgorient',
// 						'Propoorientationcov58.commentaire',
// 						'Propoorientationcov58.covtypeorient_id',
// 						'Propoorientationcov58.covstructurereferente_id',
// 						'Propoorientationcov58.datevalidation',
// 						'Propoorientationcov58.commentaire',
// 						'Propoorientationcov58.user_id',
// 						'Propoorientationcov58.decisioncov',
// 						'Typeorient.lib_type_orient',
// 						'Structurereferente.lib_struc',
// 						'Covtypeorient.lib_type_orient',
// 						'Covstructurereferente.lib_struc',
// 						'Covstructurereferente.num_voie',
// 						'Covstructurereferente.nom_voie',
// 						'Covstructurereferente.type_voie',
// 						'Covstructurereferente.code_postal',
// 						'Covstructurereferente.ville',
// 						'Sitecov58.name',
// 						//
// 						'User.nom',
// 						'User.prenom',
// 						'User.numtel',
// 						'Serviceinstructeur.lib_service',
// 					),
// 					'conditions' => array(
// 						'Dossiercov58.id' => $dossiercov58_id
// 					),
// 					'joins' => array(
// 						array(
// 							'table'      => 'personnes',
// 							'alias'      => 'Personne',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( "Personne.id = Dossiercov58.personne_id" ),
// 						),
// 						array(
// 							'table'      => 'foyers',
// 							'alias'      => 'Foyer',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
// 						),
// 						array(
// 							'table'      => 'dossiers',
// 							'alias'      => 'Dossier',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
// 						),
// 						array(
// 							'table'      => 'adressesfoyers',
// 							'alias'      => 'Adressefoyer',
// 							'type'       => 'LEFT OUTER',
// 							'foreignKey' => false,
// 							'conditions' => array(
// 								'Foyer.id = Adressefoyer.foyer_id',
// 								// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
// 								'Adressefoyer.id IN (
// 									'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
// 								)'
// 							)
// 						),
// 						array(
// 							'table'      => 'adresses',
// 							'alias'      => 'Adresse',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
// 						),
// 						array(
// 							'table'      => 'proposorientationscovs58',
// 							'alias'      => 'Propoorientationcov58',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Dossiercov58.id = Propoorientationcov58.dossiercov58_id' )
// 						),
// 						array(
// 							'table'      => 'typesorients',
// 							'alias'      => 'Typeorient',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Propoorientationcov58.typeorient_id = Typeorient.id' ),
// 						),
// 						array(
// 							'table'      => 'structuresreferentes',
// 							'alias'      => 'Structurereferente',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Propoorientationcov58.structurereferente_id = Structurereferente.id' ),
// 						),
// 						array(
// 							'table'      => 'typesorients',
// 							'alias'      => 'Covtypeorient',
// 							'type'       => 'LEFT OUTER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Propoorientationcov58.covtypeorient_id = Covtypeorient.id' ),
// 						),
// 						array(
// 							'table'      => 'structuresreferentes',
// 							'alias'      => 'Covstructurereferente',
// 							'type'       => 'LEFT OUTER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Propoorientationcov58.covstructurereferente_id = Covstructurereferente.id' ),
// 						),
// 						array(
// 							'table'      => 'users',
// 							'alias'      => 'User',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'Propoorientationcov58.typeorient_id = Typeorient.id' ),
// 						),
// 						array(
// 							'table'      => 'servicesinstructeurs',
// 							'alias'      => 'Serviceinstructeur',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'User.serviceinstructeur_id = Serviceinstructeur.id' ),
// 						),
// 						array(
// 							'table'      => 'sitescovs58',
// 							'alias'      => 'Sitecov58',
// 							'type'       => 'INNER',
// 							'foreignKey' => false,
// 							'conditions' => array( 'User.serviceinstructeur_id = Serviceinstructeur.id' ),
// 						)
// 					),
// 					'contain' => false
// 				)
// 			);
//
// 			$options = array(
// 				'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
// 				'Adresse' => array( 'typevoie' => ClassRegistry::init( 'Option' )->typevoie() ),
// 				'type' => array( 'voie' => ClassRegistry::init( 'Option' )->typevoie() )
// 			);
// 			$options = Set::merge( $options, $this->Dossiercov58->enums() );
//
// 			///FIXME: ajouter règles pour choisir le bon fichier
//
// 			$fileName = '';
// 			if ( $dossiercov58_data['Propoorientationcov58']['decisioncov'] == 'accepte' ) {
// 				if( strcmp( 'Emploi', $dossiercov58_data['Covtypeorient']['lib_type_orient'] ) != -1 ) {
// 					if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
// 						$fileName = 'decisionorientationpro.odt';
// 					}
// 					else {
// 						$fileName = 'decisionreorientationpro.odt';
// 					}
// 				}
// 				else {
// 					if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
// 						$fileName = 'decisionorientationsoc.odt';
// 					}
// 					else {
// 						$fileName = 'decisionreorientationsoc.odt';
// 					}
// 				}
// 			}
// 			else {
// 				if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
// 					return false;
// 				}
// 				else {
// 					$fileName = 'decisionrefusreorientation.odt';
// 				}
// 			}
//
// 			return $this->ged(
// 				$dossiercov58_data,
// 				"Cov58/{$fileName}",
// 				false,
// 				$options
// 			);
// 		}
		public function getPdfDecision( $passagecov58_id ) {
			$data = $this->Dossiercov58->Passagecov58->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Dossiercov58->Passagecov58->fields(),
						$this->Dossiercov58->Passagecov58->Dossiercov58->fields(),
						$this->Dossiercov58->Passagecov58->Decisionpropoorientationcov58->fields(),
						$this->Dossiercov58->Propoorientationcov58->fields(),
						$this->Dossiercov58->Personne->fields(),
						$this->Dossiercov58->Personne->Foyer->fields(),
						$this->Dossiercov58->Personne->Foyer->Dossier->fields(),
						$this->Dossiercov58->Personne->Foyer->Adressefoyer->fields(),
						$this->Dossiercov58->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Dossiercov58->Propoorientationcov58->Typeorient->fields(),
						$this->Dossiercov58->Propoorientationcov58->Structurereferente->fields(),
						$this->Dossiercov58->Propoorientationcov58->Covtypeorient->fields(),
						$this->Dossiercov58->Propoorientationcov58->Covstructurereferente->fields(),
						$this->Dossiercov58->Propoorientationcov58->User->fields(),
						$this->Dossiercov58->Propoorientationcov58->User->Serviceinstructeur->fields(),
						$this->Dossiercov58->Passagecov58->Cov58->fields(),
						$this->Dossiercov58->Passagecov58->Cov58->Sitecov58->fields()
					),
					'conditions' => array(
						'Passagecov58.id' => $passagecov58_id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ('.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').')'
						)
					),
					'joins' => array(
						$this->Dossiercov58->Passagecov58->join( 'Dossiercov58' ),
						$this->Dossiercov58->Passagecov58->join( 'Decisionpropoorientationcov58' ),
						$this->Dossiercov58->join( 'Propoorientationcov58' ),
						$this->Dossiercov58->join( 'Personne' ),
						$this->Dossiercov58->Personne->join( 'Foyer' ),
						$this->Dossiercov58->Personne->Foyer->join( 'Dossier' ),
						$this->Dossiercov58->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Dossiercov58->Personne->Foyer->Adressefoyer->join( 'Adresse' ),
						$this->Dossiercov58->Propoorientationcov58->join( 'Typeorient' ),
						$this->Dossiercov58->Propoorientationcov58->join( 'Structurereferente' ),
						$this->Dossiercov58->Propoorientationcov58->join( 'Covtypeorient', array( 'type' => 'LEFT OUTER' ) ),
						$this->Dossiercov58->Propoorientationcov58->join( 'Covstructurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Dossiercov58->Propoorientationcov58->join( 'User' ),
						$this->Dossiercov58->Propoorientationcov58->User->join( 'Serviceinstructeur' ),
						$this->Dossiercov58->Passagecov58->join( 'Cov58' ),
						$this->Dossiercov58->Passagecov58->Cov58->join( 'Sitecov58' )
					),
					'contain' => false
				)
			);

			$options = array(
				'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'Adresse' => array( 'typevoie' => ClassRegistry::init( 'Option' )->typevoie() ),
				'type' => array( 'voie' => ClassRegistry::init( 'Option' )->typevoie() )
			);
			$options = Set::merge( $options, $this->Dossiercov58->enums() );

			///FIXME: ajouter règles pour choisir le bon fichier

			$fileName = '';
// 			if ( $dossiercov58_data['Propoorientationcov58']['decisioncov'] == 'accepte' ) {
// 				if( strcmp( 'Emploi', $dossiercov58_data['Covtypeorient']['lib_type_orient'] ) != -1 ) {
// 					if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
// 						$fileName = 'decisionorientationpro.odt';
// 					}
// 					else {
// 						$fileName = 'decisionreorientationpro.odt';
// 					}
// 				}
// 				else {
// 					if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
// 						$fileName = 'decisionorientationsoc.odt';
// 					}
// 					else {
// 						$fileName = 'decisionreorientationsoc.odt';
// 					}
// 				}
// 			}
// 			else {
// 				if ( $dossiercov58_data['Propoorientationcov58']['rgorient'] == 0 ) {
// 					return false;
// 				}
// 				else {
// 					$fileName = 'decisionrefusreorientation.odt';
// 				}
// 			}
// debug($data);

			$typeorientEmploiId = Configure::read( 'Typeorient.emploi_id' );
			$rgOrientMax = $this->Dossiercov58->Personne->Orientstruct->rgorientMax( $data['Personne']['id'] );

			if ( $data['Decisionpropoorientationcov58']['decisioncov'] == 'valide' ) {
// 			in_array( $data['Decisionpropoorientationcov58']['decisioncov'], array( 'valide', 'refuse' ) )
				if( $typeorientEmploiId == $data['Decisionpropoorientationcov58']['typeorient_id'] ) {
					if ( $rgOrientMax == 0 ) {
						$fileName = 'decisionorientationpro.odt';
					}
					else {
						$fileName = 'decisionreorientationpro.odt';
					}
				}
				else {
					if ( $rgOrientMax == 0 ) {
						$fileName = 'decisionorientationsoc.odt';
					}
					else {
						$fileName = 'decisionreorientationsoc.odt';
					}
				}

			}
			else {
// 				if ( $data['Propoorientationcov58']['rgorient'] == 0 ) {
// // 					return false;
// 					$fileName='';
// 				}
// 				else {
					$fileName = 'decisionrefusreorientation.odt';
// 				}
			}
// debug($fileName);
// die();

			return $this->ged(
				$data,
				"Cov58/{$fileName}",
				false,
				$options
			);
		}

		/**
		 * Retourne un querydata permettant de trouver les propositions d'orientations en cours de
		 * traitement par une COV pour un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function qdEnCours( $personne_id ) {
			$sqDernierPassagecov58 = $this->Dossiercov58->Passagecov58->sqDernier();

			return array(
				'fields' => array(
					'Propoorientationcov58.id',
					'Propoorientationcov58.dossiercov58_id',
					'Propoorientationcov58.datedemande',
					'Propoorientationcov58.rgorient',
					'Propoorientationcov58.typeorient_id',
					'Typeorient.lib_type_orient',
					'Propoorientationcov58.structurereferente_id',
					'Structurereferente.lib_struc',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58',
					'Passagecov58.etatdossiercov',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom'
				),
				'conditions' => array(
					'Dossiercov58.personne_id' => $personne_id,
					'Themecov58.name' => 'proposorientationscovs58',
					array(
						'OR' => array(
							'Passagecov58.etatdossiercov NOT' => array( 'traite', 'annule' ),
							'Passagecov58.etatdossiercov IS NULL'
						),
					),
					array(
						'OR' => array(
							"Passagecov58.id IN ( {$sqDernierPassagecov58} )",
							'Passagecov58.etatdossiercov IS NULL'
						),
					),
				),
				'joins' => array(
					$this->join( 'Dossiercov58', array( 'type' => 'INNER' ) ),
					$this->Dossiercov58->join( 'Themecov58', array( 'type' => 'INNER' ) ),
					$this->Dossiercov58->join( 'Passagecov58', array( 'type' => 'LEFT OUTER' ) ),
					$this->Dossiercov58->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->join( 'Typeorient', array( 'type' => 'INNER' ) ),
					$this->join( 'Structurereferente', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
				'order' => array( 'Propoorientationcov58.rgorient DESC' )
			);
		}
	}
?>