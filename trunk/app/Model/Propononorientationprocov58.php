<?php
	/**
	 * Code source de la classe Propononorientationprocov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Propononorientationprocov58 ...
	 *
	 * @package app.Model
	 */
	class Propononorientationprocov58 extends AppModel
	{
		public $name = 'Propononorientationprocov58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
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
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Nvorientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'nvorientstruct_id',
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
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers de COV
		*/
		public function qdListeDossier( $cov58_id = null ) {
			$return = array(
				'fields' => array(
					'Dossiercov58.id',
					'Dossiercov58.created',
					'Dossiercov58.themecov58_id',
					'Dossier.numdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Orientstruct.date_valid',
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
				$join,
				array(
					'alias' => 'Orientstruct',
					'table' => 'orientsstructs',
					'type' => 'INNER',
					'conditions' => array(
						'Orientstruct.id = '.$this->alias.'.orientstruct_id'
					)
				),
				array(
					'alias' => 'Structurereferente',
					'table' => 'structuresreferentes',
					'type' => 'INNER',
					'conditions' => array(
						'Structurereferente.id = Orientstruct.structurereferente_id'
					)
				),
				array(
					'alias' => 'Typeorient',
					'table' => 'typesorients',
					'type' => 'INNER',
					'conditions' => array(
						'Typeorient.id = Orientstruct.typeorient_id'
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
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
							'Referent'
						)
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
					'table' => 'proposnonorientationsproscovs58',
					'alias' => $this->alias,
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = Propononorientationprocov58.dossiercov58_id'
					)
				),
				array(
					'table' => 'structuresreferentes',
					'alias' => 'Structurereferente',
					'type' => 'INNER',
					'conditions' => array(
						'Propononorientationprocov58.structurereferente_id = Structurereferente.id'
					)
				),
				array(
					'table' => 'typesorients',
					'alias' => 'Typeorient',
					'type' => 'INNER',
					'conditions' => array(
						'Propononorientationprocov58.typeorient_id = Typeorient.id'
					)
				),
				array(
					'table' => 'referents',
					'alias' => 'Referent',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'Propononorientationprocov58.referent_id = Referent.id'
					)
				)
			);
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

					if( in_array( $values['decisioncov'], array( 'valide', 'refuse' ) ) ) {

						if( $values['decisioncov'] == 'valide' ){
							$data[$modelDecisionName][$key]['typeorient_id'] = $passagecov58[$this->alias]['typeorient_id'];
							$data[$modelDecisionName][$key]['structurereferente_id'] = $passagecov58[$this->alias]['structurereferente_id'];
							$data[$modelDecisionName][$key]['referent_id'] = $passagecov58[$this->alias]['referent_id'];

							list($datevalidation, $heure) = explode(' ', $passagecov58['Cov58']['datecommission']);

							//Sauvegarde des décisions
							$this->Dossiercov58->Passagecov58->{$modelDecisionName}->create( array( $modelDecisionName => $data[$modelDecisionName][$key] ) );
							$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->save() && $success;

							$orientstruct = array(
								'Orientstruct' => array(
									'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
									'typeorient_id' => $passagecov58[$this->alias]['typeorient_id'],
									'structurereferente_id' => $passagecov58[$this->alias]['structurereferente_id'],
									'referent_id' => $passagecov58[$this->alias]['referent_id'],
									'date_propo' => $passagecov58[$this->alias]['datedemande'],
									'date_valid' => $datevalidation,
									'rgorient' => $passagecov58[$this->alias]['rgorient'],
									'statut_orient' => 'Orienté',
									'etatorient' => 'decision',
									'origine' => 'manuelle',
									'user_id' => ( isset( $passagecov58[$this->alias]['user_id'] ) ) ? $passagecov58[$this->alias]['user_id'] : null
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
										'user_id' => ( isset( $passagecov58[$this->alias]['user_id'] ) ) ? $passagecov58[$this->alias]['user_id'] : null
									)
								)
							) && $success;

							//Validation par la COV donc on déverse le dossier dans la corbeille EP
							$dossierep = array(
								'Dossierep' => array(
									'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
									'themeep' => 'nonorientationsproseps58'
								)
							);
							$this->Dossiercov58->Passagecov58->Decisionpropononorientationprocov58->Nonorientationproep58->Dossierep->create( $dossierep );
							$success = $this->Dossiercov58->Passagecov58->Decisionpropononorientationprocov58->Nonorientationproep58->Dossierep->save() && $success;

							$nonorientationproep = array(
								'Nonorientationproep58' => array(
									'dossierep_id' => $this->Dossiercov58->Passagecov58->Decisionpropononorientationprocov58->Nonorientationproep58->Dossierep->id,
									'orientstruct_id' => $passagecov58[$this->alias]['orientstruct_id'],
									'user_id' => $passagecov58['Passagecov58']['user_id'],
									'decisionpropononorientationprocov58_id' => $this->Dossiercov58->Passagecov58->{$modelDecisionName}->id
								)
							);
							$this->Dossiercov58->Passagecov58->Decisionpropononorientationprocov58->Nonorientationproep58->create( $nonorientationproep );
							$success = $this->Dossiercov58->Passagecov58->Decisionpropononorientationprocov58->Nonorientationproep58->save() && $success;


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

							//Sauvegarde des décisions
							$this->Dossiercov58->Passagecov58->{$modelDecisionName}->create( array( $modelDecisionName => $data[$modelDecisionName][$key] ) );
							$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->save() && $success;

							$orientstruct = array(
								'Orientstruct' => array(
									'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
									'typeorient_id' => $data[$modelDecisionName][$key]['typeorient_id'],
									'structurereferente_id' => $data[$modelDecisionName][$key]['structurereferente_id'],
									'referent_id' => $data[$modelDecisionName][$key]['referent_id'],
									'date_propo' => $passagecov58['Propononorientationprocov58']['datedemande'],
									'date_valid' => $datevalidation,
									'rgorient' => $passagecov58['Propononorientationprocov58']['rgorient'],
									'statut_orient' => 'Orienté',
									'etatorient' => 'decision',
									'origine' => 'manuelle',
									'user_id' => ( isset( $passagecov58['Propononorientationprocov58']['user_id'] ) ) ? $passagecov58['Propononorientationprocov58']['user_id'] : null
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
										'user_id' =>  ( isset( $passagecov58['Propononorientationprocov58']['user_id'] ) ) ? $passagecov58['Propononorientationprocov58']['user_id'] : null
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
					else{
						//Sauvegarde des décisions
						$this->Dossiercov58->Passagecov58->{$modelDecisionName}->create( array( $modelDecisionName => $data[$modelDecisionName][$key] ) );
						$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->save() && $success;
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
				}// Fin du foreach
			}

			return $success;
		}


		/**
		*
		*/

		public function qdProcesVerbal() {
			$modele = 'Propononorientationprocov58';
			$modeleDecisions = 'Decisionpropononorientationprocov58';

			return array(
				'fields' => array(
					"{$modele}.id",
					"{$modele}.dossiercov58_id",
					"{$modele}.orientstruct_id",
					"{$modele}.typeorient_id",
					"{$modele}.structurereferente_id",
					"{$modele}.referent_id",
					"{$modele}.datedemande",
					"{$modele}.datevalidation",
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
				)
			);
		}

		/**
		*
		*/

		public function qdOrdreDuJour() {
			return array(
				'fields' => array(
					'Propononorientationprocov58.id',
					'Propononorientationprocov58.dossiercov58_id',
					'Propononorientationprocov58.typeorient_id',
					'Propononorientationprocov58.structurereferente_id',
					'Propononorientationprocov58.orientstruct_id',
					'Propononorientationprocov58.datedemande',
					'Propononorientationprocov58.rgorient',
					'Propononorientationprocov58.commentaire',
					'Propononorientationprocov58.datevalidation',
					'Propononorientationprocov58.commentaire',
					'Propononorientationprocov58typeorient.lib_type_orient',
					'Propononorientationprocov58structurereferente.lib_struc',
				),
				'joins' => array(
					array(
						'table'      => 'proposnonorientationsproscovs58',
						'alias'      => 'Propononorientationprocov58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propononorientationprocov58.dossiercov58_id = Dossiercov58.id' ),
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Propononorientationprocov58typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propononorientationprocov58.typeorient_id = Propononorientationprocov58typeorient.id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Propononorientationprocov58structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propononorientationprocov58.structurereferente_id = Propononorientationprocov58structurereferente.id' ),
					)
				)
			);
		}


	}
?>