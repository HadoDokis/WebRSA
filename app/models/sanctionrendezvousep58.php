<?php
	/**
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Sanctionrendezvousep58 extends AppModel
	{
		public $name = 'Sanctionrendezvousep58';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Gedooo'
		);

		public $belongsTo = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
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
		);

		/**
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function qdDossiersParListe( $commissionep_id, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore($this->alias)];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			return array(
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.id IN ( '.
						$this->Dossierep->Passagecommissionep->sq(
							array(
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'alias' => 'passagescommissionseps',
								'conditions' => array(
									'passagescommissionseps.commissionep_id' => $commissionep_id
								)
							)
						)
					.' )'
				),
				'contain' => array(
					'Personne' => array(
						'Foyer' => array(
							'fields' => array(
								'id',
								'dossier_id',
								'sitfam',
								'ddsitfam',
								'typeocclog',
								'mtvallocterr',
								'mtvalloclog',
								'contefichliairsa',
								'mtestrsa',
								'raisoctieelectdom',
								"( SELECT COUNT(DISTINCT(personnes.id)) FROM personnes INNER JOIN prestations ON ( personnes.id = prestations.personne_id ) WHERE personnes.foyer_id = \"Foyer\".\"id\" AND prestations.natprest = 'RSA' AND prestations.rolepers = 'ENF' ) AS \"Foyer__nbenfants\"",
							),
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					),
					$this->alias => array(
						'fields' => array(
							'id',
							'dossierep_id',
							'rendezvous_id',
							'created',
							'modified'

						),
						'Rendezvous' => array(
							'Typerdv'
						)
					),
					'Passagecommissionep' => array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
						),
						'Decisionsanctionrendezvousep58' => array(
							'order' => array( 'etape DESC' )
						)
					)
				)
			);
		}

		/**
		* FIXME
		*
		* @param integer $commissionep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $commissionep_id, $datas, $niveauDecision ) {
			// Doit-on prendre une décision à ce niveau ?
			$themes = $this->Dossierep->Passagecommissionep->Commissionep->themesTraites( $commissionep_id );
			$niveauFinal = $themes[Inflector::underscore( $this->alias )];
			if( ( $niveauFinal == 'ep' ) && ( $niveauDecision == 'cg' ) ) {
				return array();
			}

			$formData = array();
			foreach( $datas as $key => $dossierep ) {
				$formData['Decisionsanctionrendezvousep58'][$key]['passagecommissionep_id'] = @$datas[$key]['Passagecommissionep'][0]['id'];

				// On modifie les enregistrements de cette étape
				if( @$dossierep['Passagecommissionep'][0]['Decisionsanctionrendezvousep58'][0]['etape'] == $niveauDecision ) {
					$formData['Decisionsanctionrendezvousep58'][$key] = @$dossierep['Passagecommissionep'][0]['Decisionsanctionrendezvousep58'][0];
				}
				// On ajoute les enregistrements de cette étape
				else {
					if( $niveauDecision == 'ep' ) {
						$formData['Decisionsanctionrendezvousep58'][$key]['raisonnonpassage'] = null;
					}
				}
			}
			return $formData;
		}

		/**
		* TODO: docs
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			// FIXME: filtrer les données
			$themeData = Set::extract( $data, '/Decisionsanctionrendezvousep58' );
			if( empty( $themeData ) ) {
				return true;
			}
			else {
				$success = $this->Dossierep->Passagecommissionep->Decisionsanctionrendezvousep58->saveAll( $themeData, array( 'atomic' => false ) );
				$this->Dossierep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'decision'.$niveauDecision.'\'' ),
					array( '"Passagecommissionep"."id"' => Set::extract( $data, '/Decisionsanctionrendezvousep58/passagecommissionep_id' ) )
				);
				return $success;
			}
		}

		/**
		* TODO: docs
		*/

		public function finaliser( $commissionep_id, $etape ) {
			// Aucune action utile ?
			return true;
		}
				/**
		*
		*/

		public function qdProcesVerbal() {
			return array(
				'fields' => array(
					'Sanctionrendezvousep58.id',
					'Sanctionrendezvousep58.dossierep_id',
					'Sanctionrendezvousep58.rendezvous_id',
					'Sanctionrendezvousep58.commentaire',
					'Sanctionrendezvousep58.created',
					'Sanctionrendezvousep58.modified',
					//
					'Decisionsanctionrendezvousep58.id',
					'Decisionsanctionrendezvousep58.etape',
					'Decisionsanctionrendezvousep58.decision',
					'Decisionsanctionrendezvousep58.commentaire',
					'Decisionsanctionrendezvousep58.created',
					'Decisionsanctionrendezvousep58.modified',
					'Decisionsanctionrendezvousep58.raisonnonpassage',
				),
				'joins' => array(
					array(
						'table'      => 'sanctionsrendezvouseps58',
						'alias'      => 'Sanctionrendezvousep58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Sanctionrendezvousep58.dossierep_id = Dossierep.id' ),
					),
					array(
						'table'      => 'decisionssanctionsrendezvouseps58',
						'alias'      => 'Decisionsanctionrendezvousep58',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisionsanctionrendezvousep58.passagecommissionep_id = Passagecommissionep.id',
							'Decisionsanctionrendezvousep58.etape' => 'ep'
						),
					),
				)
			);
		}

		/**
		* Récupération du courrier de convocation à l'allocataire pour un passage
		* en commission donné.
		* FIXME: spécifique par thématique
		*/

		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$gedooo_data = $this->Dossierep->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep' => array(
							'Personne',
						),
						'Commissionep'
					)
				)
			);

			return $this->ged( $gedooo_data, "Commissionep/convocationep_beneficiaire.odt" );
		}

		/**
		* Fonction retournant ce qui va aller dans un contain permettant de retrouver la liste des
		* dossierseps liés à une commission
		*/
		public function qdContainListeDossier() {
			return array(
				'Dossierep' => array(
					$this->alias,
					'Personne' => array(
						'Foyer' => array(
							'Dossier',
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse'
							)
						)
					)
				)
			);
		}

		/**
		* Fonction retournant un querydata qui va permettre de retrouver des dossiers d'EP
		*/
		public function qdListeDossier( $commissionep_id = null ) {
			$return = array(
				'fields' => array(
					'Dossierep.id',
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Adresse.locaadr',
					'Dossierep.created',
					'Dossierep.themeep',
					'Passagecommissionep.id',
					'Passagecommissionep.commissionep_id',
					'Passagecommissionep.etatdossierep',
				)
			);

			if( !empty( $commissionep_id ) ) {
				$join = array(
					'alias' => 'Dossierep',
					'table' => 'dossierseps',
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.id = '.$this->alias.'.dossierep_id'
					)
				);
			}
			else {
				$join = array(
					'alias' => $this->alias,
					'table' => Inflector::tableize( $this->alias ),
					'type' => 'INNER',
					'conditions' => array(
						'Dossierep.id = '.$this->alias.'.dossierep_id'
					)
				);
			}

			$return['joins'] = array(
				$join,
				array(
						'alias' => $this->alias,
						'table' => Inflector::tableize( $this->alias ),
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.id = '.$this->alias.'.dossierep_id'
						)
					),
					array(
						'alias' => 'Personne',
						'table' => 'personnes',
						'type' => 'INNER',
						'conditions' => array(
							'Dossierep.personne_id = Personne.id'
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
						'alias' => 'Passagecommissionep',
						'table' => 'passagescommissionseps',
						'type' => 'LEFT OUTER',
						'conditions' => Set::merge(
							array( 'Passagecommissionep.dossierep_id = Dossierep.id' ),
							empty( $commissionep_id ) ? array() : array(
								'OR' => array(
									'Passagecommissionep.commissionep_id IS NULL',
									'Passagecommissionep.commissionep_id' => $commissionep_id
								)
							)
						)
					)
				)
			);

			return $return;
		}
	}
?>