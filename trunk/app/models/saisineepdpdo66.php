<?php
	/**
	* Saisines d'EP pour les PDOs pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Saisineepdpdo66 extends AppModel
	{
		public $name = 'Saisineepdpdo66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate'
		);

		public $belongsTo = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'traitementpdo_id',
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
			)
		);

		public $hasMany = array(
			'Nvsepdpdo66' => array(
				'className' => 'Nvsepdpdo66',
				'foreignKey' => 'saisineepdpdo66_id',
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
			$success = true;
			
			if ($etape=='cg') {
				$dossierseps = $this->find(
					'all',
					array(
						'conditions' => array(
							'Dossierep.seanceep_id' => $seanceep_id
						),
						'contain' => array(
							'Nvsepdpdo66',
							'Dossierep',
							'Traitementpdo' => array(
								'Propopdo'
							)
						)
					)
				);
			
				foreach( $dossierseps as $dossierep ) {
					$propopdo = $this->Traitementpdo->Propopdo->find(
						'first',
						array(
							'conditions' => array(
								'Propopdo.id' => $dossierep['Traitementpdo']['propopdo_id']
							),
							'contain' => array(
							
							)
						)
					);
					$propopdo['Propopdo']['decision'] = '1';
					$propopdo['Propopdo']['datedecisionpdo'] = $dossierep['Nvsepdpdo66'][1]['datedecisionpdo'];
					$propopdo['Propopdo']['decisionpdo_id'] = $dossierep['Nvsepdpdo66'][1]['decisionpdo_id'];
					//$propopdo['Propopdo']['motifpdo'] = $dossierep['Nvsepdpdo66'][1]['motifpdo'];
					//$propopdo['Propopdo']['nonadmis'] = $dossierep['Nvsepdpdo66'][1]['nonadmis'];
					$propopdo['Propopdo']['commentairepdo'] = $dossierep['Nvsepdpdo66'][1]['commentaire'];
				
					$success = $this->Traitementpdo->Propopdo->save($propopdo) && $success;
				}
			}
			
			return $success;
		}
		
		public function verrouiller( $seanceep_id, $etape ) {
			$success = true;
			
			if ($etape=='ep') {
				$dossierseps = $this->find(
					'all',
					array(
						'conditions' => array(
							'Dossierep.seanceep_id' => $seanceep_id
						),
						'contain' => array(
							'Nvsepdpdo66',
							'Dossierep' => array(
								'Seanceep'
							),
							'Traitementpdo' => array(
								'Descriptionpdo',
								'Propopdo'
							)
						)
					)
				);
			
				foreach( $dossierseps as $dossierep ) {
					$traitementpdo['Traitementpdo']['descriptionpdo_id'] = Configure::read( 'traitementResultatId' );
					$traitementpdo['Traitementpdo']['traitementtypepdo_id'] = 2;
					$dateseance = $dossierep['Dossierep']['Seanceep']['dateseance'];
					list($jour, $heure) = explode(' ', $dateseance);
					$traitementpdo['Traitementpdo']['datereception'] = $jour;
					$traitementpdo['Traitementpdo']['personne_id'] = $dossierep['Traitementpdo']['personne_id'];
					$traitementpdo['Traitementpdo']['propopdo_id'] = $dossierep['Traitementpdo']['Propopdo']['id'];
			
					$success = $this->Traitementpdo->save($traitementpdo) && $success;
					
					$this->Traitementpdo->id = $dossierep['Traitementpdo']['id'];
					$success = $this->Traitementpdo->saveField('clos', 1) && $success;
				}
			}
			
			return $success;
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
						'Nvsepdpdo66'=>array(
							'Decisionpdo'
						),
						'Traitementpdo' => array(
							'Descriptionpdo',
							'Propopdo' => array(
								'Situationpdo'
							)		
						)
					)
				)
			);
		}
		
		/**
		 *
		 */
		public function containQueryData() {
			return array(
				'Saisineepdpdo66' => array(
					'Nvsepdpdo66'=>array(
						'Decisionpdo'
					),
				)
			);
		}

		/**
		*
		*/

		public function saveDecisions( $data, $niveauDecision ) {
			$success = $this->Nvsepdpdo66->saveAll( Set::extract( $data, '/Nvsepdpdo66' ), array( 'atomic' => false ) );

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
			if ($niveauDecision=='ep') {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsepdpdo66'][0]['id'])) {
						$formData['Nvsepdpdo66'][$key]['id'] = $dossierep[$this->alias]['Nvsepdpdo66'][0]['id'];
						$formData['Nvsepdpdo66'][$key]['decisionpdo_id'] = $dossierep[$this->alias]['Nvsepdpdo66'][0]['decisionpdo_id'];
						$formData['Nvsepdpdo66'][$key]['commentaire'] = $dossierep[$this->alias]['Nvsepdpdo66'][0]['commentaire'];
					}
					$formData['Saisineepdpdo66'][$key]['id'] = $dossierep[$this->alias]['id'];
					$formData['Dossierep'][$key]['id'] = $dossierep['Dossierep']['id'];
				}
			}
			elseif ($niveauDecision=='cg') {
				foreach( $datas as $key => $dossierep ) {
					if (isset($dossierep[$this->alias]['Nvsepdpdo66'][1]['id'])) {
						$formData['Nvsepdpdo66'][$key]['id'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['id'];
						$formData['Nvsepdpdo66'][$key]['decisionpdo_id'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['decisionpdo_id'];
						$formData['Nvsepdpdo66'][$key]['commentaire'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['commentaire'];
					}
					$formData['Saisineepdpdo66'][$key]['id'] = $dossierep[$this->alias]['id'];
					$formData['Dossierep'][$key]['id'] = $dossierep['Dossierep']['id'];
				}
			}
			return $formData;
		}
		
		public function prepareFormDataUnique( $dossierep_id, $dossierep, $niveauDecision ) {
			$formData = array();
			if ($niveauDecision=='cg') {
				if (!isset($dossierep[$this->alias]['Nvsepdpdo66'][1]['id'])) {
					$formData['Nvsepdpdo66']['decisionpdo_id'] = $dossierep[$this->alias]['Nvsepdpdo66'][0]['decisionpdo_id'];
					$formData['Nvsepdpdo66']['commentaire'] = $dossierep[$this->alias]['Nvsepdpdo66'][0]['commentaire'];
					$formData['Nvsepdpdo66']['saisineepdpdo66_id'] = $dossierep[$this->alias]['Nvsepdpdo66'][0]['saisineepdpdo66_id'];
				}
				else {
					$formData['Nvsepdpdo66']['decisionpdo_id'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['decisionpdo_id'];
					$formData['Nvsepdpdo66']['commentaire'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['commentaire'];
					$formData['Nvsepdpdo66']['id'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['id'];
					$formData['Nvsepdpdo66']['saisineepdpdo66_id'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['saisineepdpdo66_id'];
					//$formData['Nvsepdpdo66']['motifpdo'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['motifpdo'];
					//$formData['Nvsepdpdo66']['nonadmis'] = $dossierep[$this->alias]['Nvsepdpdo66'][1]['nonadmis'];
				}
				
				$formData['Saisineepdpdo66']['id'] = $dossierep[$this->alias]['id'];
				$formData['Dossierep']['id'] = $dossierep['Dossierep']['id'];
			}
			return $formData;
		}

		/**
		*
		*/

		public function saveDecisionUnique( $data, $niveauDecision ) {
			$success = $this->Nvsepdpdo66->save( $data, array( 'atomic' => false ) );
			
			$this->Dossierep->updateAll(
				array( 'Dossierep.etapedossierep' => '\'decision'.$niveauDecision.'\'' ),
				array( 'Dossierep.id' => Set::extract( $data, '/Dossierep/id' ) )
			);

			return $success;
		}
	}
?>
