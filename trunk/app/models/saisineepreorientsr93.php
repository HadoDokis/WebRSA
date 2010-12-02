<?php
	/**
	* Saisines d'EP pour les réorientations proposées par les structures
	* référentes pour le conseil général du département 93.
	*
	* Il s'agit de l'un des thèmes des EPs pour le CG 93.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Saisineepreorientsr93 extends AppModel
	{
		public $name = 'Saisineepreorientsr93';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable' => array(
				'suffix' => array(
					'structurereferente_id'
				)
			),
			'Enumerable' => array(
				'fields' => array(
					'accordaccueil',
					'accordallocataire',
					'urgent',
				)
			)
		);

		public $belongsTo = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Motifreorient' => array(
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
			),
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
			'Nvsrepreorientsr93' => array(
				'className' => 'Nvsrepreorientsr93',
				'foreignKey' => 'saisineepreorientsr93_id',
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
		*/

		public function finaliser( $seanceep_id, $etape ) {
			$dossierseps = $this->find(
				'all',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id
					),
					'contain' => array(
						'Nvsrepreorientsr93' => array(
							'conditions' => array(
								'Nvsrepreorientsr93.etape' => $etape
							)
						),
						'Dossierep',
						'Orientstruct',
					)
				)
			);

			$success = true;
			foreach( $dossierseps as $dossierep ) {
				if( $dossierep['Nvsrepreorientsr93'][0]['decision'] == 'accepte' ) {
					// Nouvelle orientation
					$orientstruct = array(
						'Orientstruct' => array(
							'personne_id' => $dossierep['Orientstruct']['personne_id'],
							'typeorient_id' => $dossierep['Nvsrepreorientsr93'][0]['typeorient_id'],
							'structurereferente_id' => $dossierep['Nvsrepreorientsr93'][0]['structurereferente_id'],
							'date_propo' => date( 'Y-m-d' ),
							'date_valid' => date( 'Y-m-d' ),
							'statut_orient' => 'Orienté',
						)
					);
					$this->Orientstruct->create( $orientstruct );
					$success = $this->Orientstruct->save() && $success;

					// Recherche dernier CER
					$dernierCerId = $this->Orientstruct->Personne->Contratinsertion->find(
						'first',
						array(
							'fields' => array( 'Contratinsertion.id' ),
							'conditions' => array(
								'Contratinsertion.personne_id' => $dossierep['Orientstruct']['personne_id']
							),
							array( 'Contratinsertion.df_ci DESC' )
						)
					);

					// Clôture anticipée du dernier CER
					$this->Orientstruct->Personne->Contratinsertion->updateAll(
						array( 'Contratinsertion.df_ci' => "'".date( 'Y-m-d' )."'" ),
						array( 'Contratinsertion.id' => $dernierCerId['Contratinsertion']['id'] )
					);

					// TODO
					/*$this->Orientstruct->Personne->Cui->updateAll(
						array( 'Cui.datefincontrat' => "'".date( 'Y-m-d' )."'" )
// 						array( 'Cui.datefincontrat IS NULL' )
					) ;*/

					// Fin de désignation du référent de la personne
					$this->Orientstruct->Personne->PersonneReferent->updateAll(
						array( 'PersonneReferent.dfdesignation' => "'".date( 'Y-m-d' )."'" ),
						array(
							'PersonneReferent.personne_id' => $dossierep['Orientstruct']['personne_id'],
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
					'Dossierep.id IN (
						SELECT saisinesepsreorientsrs93.dossierep_id
							FROM saisinesepsreorientsrs93
							/*WHERE saisinesepsreorientsrs93.accordaccueil = \'1\'
								AND saisinesepsreorientsrs93.accordallocataire = \'1\'*/
					)'

				),
				'contain' => array(
					'Personne',
					$this->alias => array(
						'Nvsrepreorientsr93',
						'Motifreorient',
						'Typeorient',
						'Structurereferente',
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						)
					),
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$success = $this->Nvsrepreorientsr93->saveAll( Set::extract( $data, '/Nvsrepreorientsr93' ), array( 'atomic' => false ) );

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
		* @param string $niveauDecision Le niveau de décision ('ep' ou 'cg') pour
		*	lequel il faut préparer les données du formulaire
		* @return array
		* @access public
		*/

		public function prepareFormData( $seanceep_id, $datas, $niveauDecision ) {
			$formData = array();
			if( $niveauDecision == 'ep' ) {
				foreach( $datas as $key => $dossierep ) {
					$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
					$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
						'_',
						array(
							$dossierep[$this->alias]['typeorient_id'],
							$dossierep[$this->alias]['structurereferente_id']
						)
					);
					// Si accords -> accepté par défaut, sinon, refusé par défaut
					$accord = ( $dossierep[$this->alias]['accordaccueil'] && $dossierep[$this->alias]['accordallocataire'] );
					$formData['Nvsrepreorientsr93'][$key]['decision'] = ( $accord ? 'accepte' : 'refuse' );
				}
			}
			else if( $niveauDecision == 'cg' ) {
				foreach( $datas as $key => $dossierep ) {
					$formData['Nvsrepreorientsr93'][$key]['decision'] = $dossierep[$this->alias]['Nvsrepreorientsr93'][0]['decision'];
					if( $formData['Nvsrepreorientsr93'][$key]['decision'] == 'refuse' ) {
						$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = $dossierep[$this->alias]['typeorient_id'];
						$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['typeorient_id'],
								$dossierep[$this->alias]['structurereferente_id']
							)
						);
					}
					else {
						$formData['Nvsrepreorientsr93'][$key]['typeorient_id'] = $dossierep[$this->alias]['Nvsrepreorientsr93'][0]['typeorient_id'];
						$formData['Nvsrepreorientsr93'][$key]['structurereferente_id'] = implode(
							'_',
							array(
								$dossierep[$this->alias]['Nvsrepreorientsr93'][0]['typeorient_id'],
								$dossierep[$this->alias]['Nvsrepreorientsr93'][0]['structurereferente_id']
							)
						);
					}
				}
			}

			return $formData;
		}
	}
?>
