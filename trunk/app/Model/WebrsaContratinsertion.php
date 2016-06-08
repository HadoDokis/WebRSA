<?php
	/**
	 * Code source de la classe WebrsaContratinsertion.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('WebrsaAbstractLogic', 'Model');
	App::uses('WebrsaLogicAccessInterface', 'Model/Interface');
	App::uses('WebrsaModelUtility', 'Utility');

	/**
	 * La classe WebrsaContratinsertion possède la logique métier web-rsa
	 *
	 * @package app.Model
	 */
	class WebrsaContratinsertion extends WebrsaAbstractLogic implements WebrsaLogicAccessInterface
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaContratinsertion';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array('Contratinsertion');
		
		/**
		 * Mémorise le résultat d'une fonction en cas d'appels succéssifs de celles-ci
		 * 
		 * @var array - array(__FUNCTION__.'.'.md5(json_encode(array($param1, $param2, ...))) => $results)
		 */
		private $_mem = array();
		
		/**
		 * Permet d'obtenir la clef pour le stockage du résultat de fonction en fonction des paramètres
		 * 
		 * @param String $functionName
		 * @param mixed $params
		 * @return String
		 */
		private function _getKeyMem($functionName, $params = 'empty') {
			return $functionName.'.'.md5(json_encode($params));
		}

		/**
		 * Ajoute les virtuals fields pour permettre le controle de l'accès à une action
		 * 
		 * @param array $query
		 * @return type
		 */
		public function completeVirtualFieldsForAccess(array $query = array(), array $params = array()) {
			$departement = (int)Configure::read('Cg.departement');
			$fields = array(
				'positioncer' => 'Contratinsertion.positioncer',
				'datenotification' => 'Contratinsertion.datenotification',
				'dd_ci' => 'Contratinsertion.dd_ci',
				'df_ci' => 'Contratinsertion.df_ci',
				'decision_ci' => 'Contratinsertion.decision_ci',
				'forme_ci' => 'Contratinsertion.forme_ci',
				'Personne.age' => $this->Contratinsertion->Personne->sqVirtualfield('age'),
				'Contratinsertion.dernier' => $this->Contratinsertion->sqVirtualfield('dernier'),
			);
			
			$query['joins'] = isset($query['joins']) ? $query['joins'] : array();
			$joinsAvailables = Hash::extract($query, 'joins.{n}.alias');
			
			if (!in_array('Personne', $joinsAvailables)) {
				$query['joins'][] = $this->Contratinsertion->join('Personne');
			}
			
			if ($departement === 66) {
				$fields['Propodecisioncer66.isvalidcer'] = 'Propodecisioncer66.isvalidcer';
				if (!in_array('Propodecisioncer66', $joinsAvailables)) {
					$query['joins'][] = $this->Contratinsertion->join('Propodecisioncer66');
				}
			} elseif ($departement === 58) {
				$fields['Passagecommissionep.etatdossierep'] = 'Passagecommissionep.etatdossierep';
				
				if (!in_array('Sanctionep58', $joinsAvailables)) {
					$query['joins'][] = $this->Contratinsertion->join('Sanctionep58');
				}
				if (!in_array('Dossierep', $joinsAvailables)) {
					$query['joins'][] = $this->Contratinsertion->Sanctionep58->join('Dossierep',
						array(
							'conditions' => array(
								'Dossierep.actif' => 1,
								'Dossierep.themeep' => 'sanctionseps58',
								'Dossierep.id NOT IN ( ' . $this->Contratinsertion->Sanctionep58->Dossierep->Passagecommissionep->sq(
										array(
											'alias' => 'passagescommissionseps',
											'fields' => array(
												'passagescommissionseps.dossierep_id'
											),
											'conditions' => array(
												'passagescommissionseps.etatdossierep' => array('traite', 'annule')
											)
										)
								) . ' )'
							)
						)
					);
				}
				if (!in_array('Passagecommissionep', $joinsAvailables)) {
					$query['joins'][] = $this->Contratinsertion->Sanctionep58->Dossierep->join('Passagecommissionep');
				}
			}
			
			return Hash::merge($query, array('fields' => array_values($fields)));
		}
		
		/**
		 * Permet d'obtenir le nécéssaire pour calculer les droits d'accès métier à une action
		 * 
		 * @param array $conditions
		 * @return array
		 */
		public function getDataForAccess(array $conditions, array $params = array()) {
			$query = array(
				'fields' => array(
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
				),
				'conditions' => $conditions,
				'joins' => array(
					$this->Contratinsertion->join('Personne')
				),
				'contain' => false,
				'order' => array(
					'Contratinsertion.date_saisi_ci' => 'DESC',
					'Contratinsertion.df_ci' => 'DESC',
					'Contratinsertion.id' => 'DESC',
				)
			);
			
			$results = $this->Contratinsertion->find('all', $this->completeVirtualFieldsForAccess($query));
			return $results;
		}
		
		/**
		 * Permet d'obtenir les paramètres à envoyer à WebrsaAccess pour une personne en particulier
		 * 
		 * @see WebrsaAccess::getParamsList
		 * @param integer $personne_id
		 * @param array $params - Liste des paramètres actifs
		 */
		public function getParamsForAccess($personne_id, array $params = array()) {
			$results = $this->haveNeededDatas($personne_id);
			
			if (in_array('haveSanctionep', $params)) {
				$querydata = $this->qdThematiqueEp('Sanctionep58', $personne_id);
				$querydata['fields'] = 'Sanctionep58.id';
				$sanctionseps58 = $this->Contratinsertion->Signalementep93->Dossierep->find('first', $querydata);
				$results['haveSanctionep'] = !empty($sanctionseps58);
			}
			if (in_array('erreursCandidatePassage', $params)) {
				$results['erreursCandidatePassage'] = $this->Contratinsertion
					->Sanctionep58->Dossierep->getErreursCandidatePassage($personne_id)
				;
			}
			if (in_array('ajoutPossible', $params)) {
				$results['ajoutPossible'] = $this->ajoutPossible($personne_id);
			}
			
			return $results;
		}
		
		/**
		 * Permet de savoir si il est possible d'ajouter un enregistrement
		 * 
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function ajoutPossible($personne_id) {
			$departement = Configure::read('Cg.departement');
			extract($this->haveNeededDatas($personne_id));
			
			// Spécial CG
			$cgCond = true;
			if ($departement === 58) {
				$cgCond = $isSoumisdroitetdevoir;
			}
			
			return $haveOrient && !$haveOrientEmploi && !$haveCui && !$haveDossiercovnonfinal && $cgCond;
		}
		
		/**
		 * Vérifi la présence ou non, d'enregistrements sur d'autres tables qui 
		 * influent sur la possibilitée d'ajout d'un contrat insertion
		 * 
		 * @param integer $personne_id
		 * @return array
		 */
		public function haveNeededDatas($personne_id) {
			$memKey = $this->_getKeyMem(__FUNCTION__, func_get_args());
			if (!isset($this->_mem[$memKey])) {
				$departement = (int)Configure::read('Cg.departement');
				$typeOrientPrincipaleEmploiId = Hash::get((array)Configure::read('Orientstruct.typeorientprincipale.Emploi'), 0);

				if ($typeOrientPrincipaleEmploiId === null) {
					$typeOrientPrincipaleEmploiId = Configure::read('Typeorient.emploi_id');
					if ($typeOrientPrincipaleEmploiId === null) {
						trigger_error(__('Le type orientation principale Emploi n\'est pas bien défini.'), E_USER_WARNING);
						$typeOrientPrincipaleEmploiId = 'NULL';
					}
				}

				$Personne =& $this->Contratinsertion->Personne;

				/**
				 * Query
				 */
				$query = array(
					'fields' => array(
						'("Orientstruct"."id" IS NOT NULL) AS "Personne__haveoriente"',
						'("Typeorient"."id" IS NOT NULL) AS "Personne__haveoriente_emploi"',
						'Typeorient.parentid'
					),
					'joins' => array(
						$Personne->join(
							'Orientstruct', array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Orientstruct.statut_orient' => 'Orienté',
									'Orientstruct.id IN ('.$Personne->Orientstruct->sqDerniere('Orientstruct.personne_id').')'
								)
							)
						),
						$Personne->Orientstruct->join(
							'Typeorient', array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'OR' => array(
										array(
											'Typeorient.parentid IS NULL',
											'Typeorient.id' => $typeOrientPrincipaleEmploiId, 
										),
										'Typeorient.parentid' => $typeOrientPrincipaleEmploiId
									)
								)
							)
						),

					),
					'contain' => false,
					'conditions' => array(
						'Personne.id' => $personne_id,
					)
				);

				if ($departement === 66) {
					$query['fields'][] = '("Cui66"."id" IS NOT NULL) AS "Personne__havecui"';
					$query['joins'][] = $Personne->join('Cui');
					$query['joins'][] = $Personne->Cui->join(
						'Cui66', array(
							'conditions' => array(
								'NOT' => array(
									'Cui66.etatdossiercui66' => array(
										'perime', 'rupturecontrat', 'decisionsanssuite', 'nonvalide', 'annule'
									)
								)
							)
						)
					);
				}

				if ($departement === 58) {
					$dossiercov = $this->Contratinsertion->Personne->Dossiercov58->qdDossiersNonFinalises(
						$personne_id, 'proposcontratsinsertioncovs58'
					);

					$query['fields'][] = '("Dossiercov58"."id" IS NOT NULL) AS "Personne__havedossiercovnonfinal"';
					$query['joins'][] = $Personne->join('Dossiercov58', 
						array('conditions' => $dossiercov['conditions'])
					);

					$query['fields'][] = '("Structurereferente"."typestructure" = \'oa\') AS "Personne__needReorientationsociale"';
					$query['joins'][] = $Personne->Orientstruct->join('Structurereferente');
				}

				/**
				 * Find
				 */
				$record = $Personne->find('first', $query);

				/**
				 * Résultats
				 */
				$record['Personne']['isSoumisdroitetdevoir'] = 
					$this->Contratinsertion->Personne->Calculdroitrsa->isSoumisAdroitEtDevoir($personne_id)
				;

				if ($departement === 58) {
					if ($this->Contratinsertion->limiteCumulDureeCER($personne_id) >= 12) {
						$demandedemaintien = $this->Contratinsertion->Personne->Dossiercov58->qdDossiersNonFinalises(
							$personne_id, 'proposnonorientationsproscovs58'
						);
						$demandedemaintien['fields'] = 'Dossiercov58.id';
						$demandeCovNonFinal = $this->Contratinsertion->Personne->Dossiercov58->find(
							'first', $demandedemaintien
						);
						$record['Personne']['haveDemandemaintiencovnonfinal'] = Hash::get($demandeCovNonFinal, 'Dossiercov58.id');
					}
				}

				$results = array(
					'haveOrient' => (boolean)Hash::get($record, 'Personne.haveoriente'),
					'haveOrientEmploi' => (boolean)Hash::get($record, 'Personne.haveoriente_emploi'),
					'haveCui' => (boolean)Hash::get($record, 'Personne.havecui'),
					'haveDossiercovnonfinal' => (boolean)Hash::get($record, 'Personne.havedossiercovnonfinal'),
					'isSoumisdroitetdevoir' => (boolean)Hash::get($record, 'Personne.isSoumisdroitetdevoir'),
					'haveDemandemaintiencovnonfinal' => (boolean)Hash::get($record, 'Personne.haveDemandemaintiencovnonfinal'),
					'needReorientationsociale' => (boolean)Hash::get($record, 'Personne.needReorientationsociale'),
				);
				
				$this->_mem[$memKey] = $results;
			}
			
			return $this->_mem[$memKey];
		}
		
		/**
		 * (CG 58, 93)
		 *
		 * @param type $modele
		 * @param type $personne_id
		 * @return type
		 */
		public function qdThematiqueEp($modele, $personne_id) {
			return array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					'Passagecommissionep.etatdossierep',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
				),
				'conditions' => array(
					'Dossierep.actif' => '1',
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => Inflector::tableize($modele),
					'Dossierep.id NOT IN ( ' . $this->Contratinsertion->{$modele}->Dossierep->Passagecommissionep->sq(
							array(
								'alias' => 'passagescommissionseps',
								'fields' => array(
									'passagescommissionseps.dossierep_id'
								),
								'conditions' => array(
									'passagescommissionseps.etatdossierep' => array('traite', 'annule')
								)
							)
					) . ' )'
				),
				'joins' => array(
					array(
						'table' => Inflector::tableize($modele),
						'alias' => $modele,
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array("Dossierep.id = {$modele}.dossierep_id")
					),
					array(
						'table' => 'contratsinsertion',
						'alias' => 'Contratinsertion',
						'type' => 'INNER',
						'foreignKey' => false,
						'conditions' => array("Contratinsertion.id = {$modele}.contratinsertion_id")
					),
					array(
						'table' => 'passagescommissionseps',
						'alias' => 'Passagecommissionep',
						'type' => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array('Dossierep.id = Passagecommissionep.dossierep_id')
					),
				),
			);
		}	
	}