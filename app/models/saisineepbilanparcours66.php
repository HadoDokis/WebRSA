<?php
	/**
	* Saisines d'EP pour les bilans de parcours pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Saisineepbilanparcours66 extends AppModel
	{
		public $name = 'Saisineepbilanparcours66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
// 			'Enumerable' => array(
// 				'fields' => array(
// 					'accordaccueil',
// 					'accordallocataire',
// 					'urgent',
// 				)
// 			)
		);

		public $belongsTo = array(
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'bilanparcours66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			/*'Motifreorient' => array(
				'className' => 'Motifreorient',
				'foreignKey' => 'motifreorient_id',
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
			),*/
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
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
		);

		public $hasMany = array(
			'Nvsrepreorient66' => array(
				'className' => 'Nvsrepreorient66',
				'foreignKey' => 'saisineepbilanparcours66_id',
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

		/**
		* TODO: comment finaliser l'orientation précédente ?
		* FIXME: à ne faire que quand le cg valide sa décision
		*/

		public function finaliser( $seanceep_id, $etape ) {
			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id
					),
					'contain' => array(
						'Nvsrepreorient66' => array(
							'conditions' => array(
								'Nvsrepreorient66.etape' => $etape
							)
						),
						'Dossierep',
						'Bilanparcours66' => array(
							'Orientstruct',
						)
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Nvsrepreorient66'][0]['decision'] == 'accepte' ) {
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'typeorient_id' => $dossierep['Nvsrepreorient66'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Nvsrepreorient66'][0]['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
						)
					);
					$this->Bilanparcours66->Orientstruct->create( $orientstruct );
					$success = $this->Bilanparcours66->Orientstruct->save() && $success;

					$this->Bilanparcours66->Orientstruct->Personne->Contratinsertion->updateAll(
						array( 'Contratinsertion.df_ci' => "'".date( 'Y-m-d' )."'" ),
						array(
							'Contratinsertion.personne_id' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'Contratinsertion.id' => $dossierep['Bilanparcours66']['contratinsertion_id']
						)
					);

					// TODO
					/*$this->Bilanparcours66->Orientstruct->Personne->Cui->updateAll(
						array( 'Cui.datefincontrat' => "'".date( 'Y-m-d' )."'" ),
						array( 'Cui.personne_id' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'] )
					);*/

					$this->Bilanparcours66->Orientstruct->Personne->PersonneReferent->updateAll(
						array( 'PersonneReferent.dfdesignation' => "'".date( 'Y-m-d' )."'" ),
						array(
							'PersonneReferent.personne_id' => $dossierep['Bilanparcours66']['Orientstruct']['personne_id'],
							'PersonneReferent.dfdesignation IS NULL'
						)
					);
				}
			}

			return $success;
		}
		
		/**
		 * INFO: Fonction inutile dans cette saisine donc elle retourne simplement true
		 */
		
		public function verrouiller( $seanceep_id, $etape ) {
			return true;
		}

		/**
		* Querydata permettant d'obtenir les dossiers qui doivent être traités
		* par liste pour la thématique de ce modèle.
		*
		* TODO: une autre liste pour avoir un tableau permettant d'accéder à la fiche
		* TODO: que ceux avec accord, les autres en individuel
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut les dossiers à passer par liste.
		* @return array
		* @access public
		*/

		public function qdDossiersParListe( $seanceep_id, $niveauDecision ) {
			return array(
				'conditions' => array(
					'Dossierep.themeep' => Inflector::tableize( $this->alias ),
					'Dossierep.seanceep_id' => $seanceep_id,
				),
				'contain' => array(
					'Personne',
					$this->alias => array(
						'Typeorient',
						'Structurereferente',
						'Bilanparcours66' => array(
							'Orientstruct' => array(
								'Typeorient',
								'Structurereferente',
							),
						),
						'Nvsrepreorient66' => array(
								'Typeorient',
								'Structurereferente',
							),
					)
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$success = $this->Nvsrepreorient66->saveAll( Set::extract( $data, '/Nvsrepreorient66' ), array( 'atomic' => false ) );

			$this->Dossierep->updateAll(
				array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
				array( 'Dossierep.id' => Set::extract( $data, '/Dossierep/id' ) )
			);

			return $success;
		}

		/**
		* Prépare les données du formulaire d'un niveau de décision
		* en prenant en compte les données du bilan ou du niveau de décision
		* précédent.
		*
		* @param integer $seanceep_id L'id technique de la séance d'EP
		* @param array $datas Les données des dossiers
		* @param string $niveauDecision Le niveau de décision pour lequel il
		* 	faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $seanceep_id, $datas, $niveauDecision ) {
			$formData = array();
			if( $niveauDecision == 'ep' ) {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsrepreorient66'][0]['id'])) {
						$formData['Nvsrepreorient66'][$key]['id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['id'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
					}
				}
			}
			else if( $niveauDecision == 'cg' ) {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsrepreorient66'][1]['id'])) {
						$formData['Nvsrepreorient66'][$key]['id'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['id'];
						$formData['Nvsrepreorient66'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['decision'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][1]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][1]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][1]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorient66'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['decision'];
						$formData['Nvsrepreorient66'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'];
						$formData['Nvsrepreorient66'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorient66'][0]['structurereferente_id']
							)
						);
					}
				}
			}

			return $formData;
		}
	}
?>
