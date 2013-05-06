<?php
	/**
	 * Fichier source de la classe Propoorientsocialecov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Propoorientsocialecov58 est la classe qui gère la thématique de COV "Orientation sociale de
	 * fait" pour le CG 58.
	 *
	 * @package app.Model
	 */
	class Propoorientsocialecov58 extends AppModel
	{
		public $name = 'Propoorientsocialecov58';

		public $recursive = -1;

		/**
		 * Chemin relatif pour les modèles de documents .odt utilisés lors des
		 * impressions. Utiliser %s pour remplacer par l'alias.
		 */
		public $modelesOdt = array(
			'Cov58/decisionrefusreorientsocdefait.odt',
			'Cov58/decisionorientationsocdefait.odt',
		);

		public $actsAs = array(
			'Autovalidate2',
			'Formattable',
			'Gedooo.Gedooo'
		);

		public $belongsTo = array(
			'Dossiercov58' => array(
				'className' => 'Dossiercov58',
				'foreignKey' => 'dossiercov58_id',
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
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'rendezvous_id',
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

		/**
		*
		*/

		public function getFields() {
			/*return array(
				$this->alias.'.id',
				$this->alias.'.datedemande',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc',
				'Referent.qual',
				'Referent.nom',
				'Referent.prenom'
			);*/
		}

		/**
		*
		*/

		public function getJoins() {
			/*return array(
				array(
					'table' => 'proposorientationscovs58',
					'alias' => $this->alias,
					'type' => 'INNER',
					'conditions' => array(
						'Dossiercov58.id = Propoorientsocialecov58.dossiercov58_id'
					)
				),
				array(
					'table' => 'structuresreferentes',
					'alias' => 'Structurereferente',
					'type' => 'INNER',
					'conditions' => array(
						'Propoorientsocialecov58.structurereferente_id = Structurereferente.id'
					)
				),
				array(
					'table' => 'typesorients',
					'alias' => 'Typeorient',
					'type' => 'INNER',
					'conditions' => array(
						'Propoorientsocialecov58.typeorient_id = Typeorient.id'
					)
				),
				array(
					'table' => 'referents',
					'alias' => 'Referent',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'Propoorientsocialecov58.referent_id = Referent.id'
					)
				)
			);*/
		}



		/**
		 * Fonction retournant un querydata qui va permettre de retrouver des dossiers de COV
		 *
		 * FIXME: dans la classe abstraite car on dirait que toutes les thématiques de COV utilisent la même méthode
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
				$join,
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

		/**
		 *
		 * @param type $cov58_id
		 * @return type
		 */
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
					$this->alias,
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
		 * 		- CG 93
		 * 			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		 * 			* Propoorientsocialecov58 -> peut déboucher sur une réorientation
		 * 		- CG 66
		 * 			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		 * 			* Saisinebilanparcoursep66 -> peut déboucher sur une réorientation
		 * 			* Saisinepdoep66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
		 * FIXME -> CG 93: s'il existe une procédure de relance, on veut faire signer un contrat,
		  mais on veut peut-être aussi demander une réorientation.
		 * FIXME -> doit-on vérifier si:
		 * 			- la personne est soumise à droits et devoirs (oui)
		 * 			- la personne est demandeur ou conjoint RSA (oui) ?
		 * 			- le dossier est dans un état ouvert (non) ?
		 *
		 * FIXME: à mettre en commun ?
		 */
		public function ajoutPossible( $personne_id ) {
			/*$nbDossierscov = $this->Dossiercov58->find(
				'count',
				array(
					'conditions' => array(
						'Dossiercov58.personne_id' => $personne_id
					),
					'contain' => array(
						'Propoorientsocialecov58'
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

			return ( ( $nbDossierscov == 0 ) && ( $nbPersonnes == 1 ) );*/
		}

		/**
		 *
		 * @param array $data
		 * @return boolean
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

					if( $values['decisioncov'] == 'valide' ) {
						list($datevalidation, $heure) = explode(' ', $passagecov58['Cov58']['datecommission']);

						$rgorient = $this->Dossiercov58->Personne->Orientstruct->rgorientMax( $passagecov58['Dossiercov58']['personne_id'] ) + 1;
						$origine = ( $rgorient > 1 ? 'reorientation' : 'manuelle' );

						$orientstruct = array(
							'Orientstruct' => array(
								'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
								'typeorient_id' => $values['typeorient_id'],
								'structurereferente_id' => $values['structurereferente_id'],
								'referent_id' => $values['referent_id'],
								'date_propo' => date( 'Y-m-d', strtotime( $passagecov58['Propoorientsocialecov58']['created'] ) ),
								'date_valid' => $datevalidation,
								'rgorient' => $rgorient,
								'statut_orient' => 'Orienté',
								'etatorient' => 'decision',
								'origine' => $origine,
								'user_id' => $passagecov58['Propoorientsocialecov58']['user_id']
							)
						);

						$success = $this->Dossiercov58->Personne->PersonneReferent->changeReferentParcours(
							$passagecov58['Dossiercov58']['personne_id'],
							$values['referent_id'],
							array(
								'PersonneReferent' => array(
									'personne_id' => $passagecov58['Dossiercov58']['personne_id'],
									'referent_id' => $values['referent_id'],
									'dddesignation' => $datevalidation,
									'structurereferente_id' => $values['structurereferente_id'],
									'user_id' => $passagecov58[$this->alias]['user_id']
								)
							)
						) && $success;

						$this->Dossiercov58->Personne->Orientstruct->create( $orientstruct );
						$success = $this->Dossiercov58->Personne->Orientstruct->save() && $success;

						// Mise à jour de l'enregistrement de la thématique avec l'id de la nouvelle orientation
						$success = $success && $this->updateAllUnBound(
							array( "\"{$this->alias}\".\"nvorientstruct_id\"" => $this->Dossiercov58->Personne->Orientstruct->id ),
							array( "\"{$this->alias}\".\"id\"" => $passagecov58[$this->alias]['id'] )
						);
					}

					// Modification etat du dossier passé dans la COV
					if( in_array( $values['decisioncov'], array( 'valide', 'refuse' ) ) ){
						$this->Dossiercov58->Passagecov58->updateAllUnBound(
							array( 'Passagecov58.etatdossiercov' => '\'traite\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'annule' ){
						$this->Dossiercov58->Passagecov58->updateAllUnBound(
							array( 'Passagecov58.etatdossiercov' => '\'annule\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
					else if( $values['decisioncov'] == 'reporte' ){
						$this->Dossiercov58->Passagecov58->updateAllUnBound(
							array( 'Passagecov58.etatdossiercov' => '\'reporte\'' ),
							array('"Passagecov58"."id"' => $passagecov58['Passagecov58']['id'] )
						);
					}
				}

				$success = $this->Dossiercov58->Passagecov58->{$modelDecisionName}->saveAll( Set::extract( $data, '/'.$modelDecisionName ), array( 'atomic' => false ) ) && $success;
			}

			return $success;
		}

		/**
		 *
		 * @return array
		 */
		public function qdProcesVerbal() {
			$modele = 'Propoorientsocialecov58';
			$modeleDecisions = 'Decisionpropoorientsocialecov58';

			return array(
				'fields' => array(
					"{$modele}.id",
					"{$modele}.dossiercov58_id",
					"{$modele}.created",
					"{$modele}.commentaire",
					"{$modele}.commentaire",
					"{$modele}.user_id",
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
						'conditions' => array( 'Decisionpropoorientsocialecov58.typeorient_id = Typeorient.id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Decisionpropoorientsocialecov58.structurereferente_id = Structurereferente.id' ),
					)
				),
				'contain' => false
			);
		}

		/**
		 * FIXME: orientation actuelle ?
		 */
		public function qdOrdreDuJour() {
			return array(
				'fields' => array(
					'Propoorientsocialecov58.id',
					'Propoorientsocialecov58.dossiercov58_id',
					'Propoorientsocialecov58.created',
					'Propoorientsocialecov58.commentaire',
					'Propoorientsocialecov58.user_id',
				),
				'joins' => array(
					array(
						'table'      => 'proposorientssocialescovs58',
						'alias'      => 'Propoorientsocialecov58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Propoorientsocialecov58.dossiercov58_id = Dossiercov58.id' ),
					),
				),
			);
		}

		/**
		 *
		 * @param integer $passagecov58_id
		 * @return string
		 */
		public function getPdfDecision( $passagecov58_id ) {
			$data = $this->Dossiercov58->Passagecov58->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Dossiercov58->Passagecov58->fields(),
						$this->Dossiercov58->Passagecov58->Dossiercov58->fields(),
						$this->Dossiercov58->Passagecov58->Decisionpropoorientsocialecov58->fields(),
						$this->Dossiercov58->Propoorientsocialecov58->fields(),
						$this->Dossiercov58->Personne->fields(),
						$this->Dossiercov58->Personne->Foyer->fields(),
						$this->Dossiercov58->Personne->Foyer->Dossier->fields(),
						$this->Dossiercov58->Personne->Foyer->Adressefoyer->fields(),
						$this->Dossiercov58->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Dossiercov58->Propoorientsocialecov58->User->fields(),
						$this->Dossiercov58->Propoorientsocialecov58->User->Serviceinstructeur->fields(),
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
						$this->Dossiercov58->Passagecov58->join( 'Decisionpropoorientsocialecov58' ),
						$this->Dossiercov58->join( 'Propoorientsocialecov58' ),
						$this->Dossiercov58->join( 'Personne' ),
						$this->Dossiercov58->Personne->join( 'Foyer' ),
						$this->Dossiercov58->Personne->Foyer->join( 'Dossier' ),
						$this->Dossiercov58->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Dossiercov58->Personne->Foyer->Adressefoyer->join( 'Adresse' ),
						$this->Dossiercov58->Propoorientsocialecov58->join( 'User' ),
						$this->Dossiercov58->Propoorientsocialecov58->User->join( 'Serviceinstructeur' ),
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

			$fileName = '';

			$typeorientEmploiId = Configure::read( 'Typeorient.emploi_id' );
			$rgOrientMax = $this->Dossiercov58->Personne->Orientstruct->rgorientMax( $data['Personne']['id'] );

			if ( $data['Decisionpropoorientsocialecov58']['decisioncov'] == 'valide' ) {
				$fileName = 'decisionorientationsocdefait.odt';
			}
			else {
				$fileName = 'decisionrefusreorientsocdefait.odt';
			}

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
					'Propoorientsocialecov58.id',
					'Propoorientsocialecov58.dossiercov58_id',
					'Propoorientsocialecov58.created',
					'Dossiercov58.personne_id',
					'Dossiercov58.themecov58',
					'Passagecov58.etatdossiercov',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom'
				),
				'conditions' => array(
					'Dossiercov58.personne_id' => $personne_id,
					'Themecov58.name' => 'proposorientssocialescovs58',
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
					$this->Dossiercov58->join( 'Personne', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
			);
		}
	}
?>