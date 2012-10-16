<?php
	class Dossierpcg66 extends AppModel
	{
		public $name = 'Dossierpcg66';

		public $recursive = -1;
		
		public $virtualFields = array( 
			'nbpropositions' => array(
				'type'      => 'integer',
				'postgres'  => '(
					SELECT COUNT(*)
						FROM decisionsdossierspcgs66
						WHERE
							decisionsdossierspcgs66.dossierpcg66_id = "%s"."id"
				)',
			),
		);

		public $actsAs = array(
			'Autovalidate2',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'orgpayeur',
					'iscomplet',
					'etatdossierpcg',
					'haspiecejointe',
					'istransmis'
				)
			)
		);

		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisiondefautinsertionep66' => array(
				'className' => 'Decisiondefautinsertionep66',
				'foreignKey' => 'decisiondefautinsertionep66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'foyer_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typepdo' => array(
				'className' => 'Typepdo',
				'foreignKey' => 'typepdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Originepdo' => array(
				'className' => 'Originepdo',
				'foreignKey' => 'originepdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
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
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'bilanparcours66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Decisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'foreignKey' => 'dossierpcg66_id',
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
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'dossierpcg66_id',
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
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Dossierpcg66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

// 		public $validate = array(
// 			'user_id' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'iscomplet', false, array( null ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			),
// 			'iscomplet' => array(
// 				'notEmptyIf' => array(
// 					'rule' => array( 'notEmptyIf', 'user_id', false, array( null ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			)
// 		);

		/**
		*
		*/

		public function etatPcg66( $dossierpcg66 ) {
			$dossierpcg66 = XSet::bump( Set::filter( ( $dossierpcg66 ) ) );

			$typepdo_id = Set::classicExtract( $dossierpcg66, 'Dossierpcg66.typepdo_id' );
		}

		/**
		* Calcul de l'état du dossierpcg66 lors de sa sauvegarde
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			if( isset( $this->data['Dossierpcg66']['originepdo_id'] ) ) {
				$typepdo_id = Set::extract( $this->data, 'Dossierpcg66.typepdo_id' );
				$user_id = Set::extract( $this->data, 'Dossierpcg66.user_id' );
				$decisionpdoId = Set::extract( $this->data, 'Decisiondossierpcg66.decisionpdo_id' );
				$etatdossierpcg = Set::extract( $this->data, 'Dossierpcg66.etatdossierpcg' );
				$retouravistechnique = Set::extract( $this->data, 'Decisiondossierpcg66.retouravistechnique' );
				$vuavistechnique = Set::extract( $this->data, 'Decisiondossierpcg66.vuavistechnique' );
				$decisionpdo_id = null;
				$avistechnique = null;
				$validationavis = null;

				$this->data['Dossierpcg66']['etatdossierpcg'] = $this->etatDossierPcg66( $typepdo_id, $user_id, $decisionpdoId, $avistechnique, $validationavis, $retouravistechnique, $vuavistechnique, /*$iscomplet,*/ $etatdossierpcg );
// 				debug($this->data);
// 				die();
			}

			return $return;
		}

		/**
		* Function qui retourne l'état du dossierpcg66 dont les différents champs nécessaires à son calcul sont passés en paramètres
		**/

		public function etatDossierPcg66( $typepdo_id = null, $user_id = null, $decisionpdoId = null, $avistechnique = null, $validationavis = null, $retouravistechnique = null, $vuavistechnique = null,/*$iscomplet = null,*/ $etatdossierpcg = null ) {
			$etat = 'instrencours';

			if ( !empty($typepdo_id) && empty($user_id) ) {
				$etat = 'attaffect';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && $etatdossierpcg == 'attaffect' ) {
				$etat = 'attinstr';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && empty($avistechnique) ) {
				$etat = 'attavistech';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && !empty($avistechnique) && empty( $validationavis ) ) {
				$etat = 'attval';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'N' && $retouravistechnique == '0' ) {
				$etat = 'decisionnonvalid';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'N' && $retouravistechnique == '1' && $vuavistechnique == '0' ) {
				$etat = 'decisionnonvalidretouravis';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($decisionpdoId) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'N' && $retouravistechnique == '1' && $vuavistechnique == '1' ) {
				$etat = 'decisionnonvalid';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'O' && $retouravistechnique == '1' && $vuavistechnique == '0' ) {
				$etat = 'decisionvalidretouravis';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'O' && $retouravistechnique == '1' && $vuavistechnique == '1' ) {
				$etat = 'decisionvalid';
			}
			elseif ( !empty($typepdo_id) && !empty($user_id) && !empty($avistechnique) && !empty($validationavis) && $validationavis == 'O' && $retouravistechnique == '0' && $vuavistechnique == '0' ) {
				$etat = 'decisionvalid';
			}
			elseif( !empty( $etatdossierpcg ) ) {
				$etat = $etatdossierpcg;
			}
// debug( $etat );
// die();
			return $etat;
		}

// 		/**
// 		* Mise à jour de l'état du dossierpcg66 du traitementpcg66 passé en paramètre.
// 		* Si le nouveau traitement nécessite une décision, le dossier repasse en instruction en cours
// 		**/
// 
// 		public function updateEtatViaTraitement( $traitementpcg66_id ) {
// 			$traitementpcg66 = $this->Personnepcg66->Traitementpcg66->find(
// 				'first',
// 				array(
// 					'conditions' => array(
// 						'Traitementpcg66.id' => $traitementpcg66_id
// 					),
// 					'contain' => array(
// 						'Descriptionpdo',
// 						'Personnepcg66'
// 					)
// 				)
// 			);
// 			$return = true;
// 			if ( $traitementpcg66['Descriptionpdo']['decisionpcg'] == 'O' ) {
// 				$this->id = $traitementpcg66['Personnepcg66']['dossierpcg66_id'];
// 				$return = $this->saveField( 'etatdossierpcg', 'instrencours' );
// 			}
// 			return $return;
// 		}

		/**
		* Mise à jour de l'état du dossierpcg66. On vérifie qu'il existe au moins un traitement
		* nécessitant une décision en ai une active.
		**/

		public function updateEtatViaDecisionTraitement( $dossierpcg66_id ) {
			$dossierpcg66 = $this->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $dossierpcg66_id
					),
					'contain' => false
				)
			);

			if ( $dossierpcg66['Dossierpcg66']['etatdossierpcg'] != 'attval' ) {
				$checkDecisionsTraitements = $this->query(
					'SELECT
						EVERY(necessaires.decisionok)
						FROM (
							SELECT
									(
										EXISTS(
											SELECT
													*
												FROM decisionstraitementspcgs66
												WHERE
													decisionstraitementspcgs66.traitementpcg66_id = traitementspcgs66.id
												ORDER BY decisionstraitementspcgs66.created DESC
												LIMIT 1
										)
										AND
										EXISTS(
											SELECT
													(
														/*decisionstraitementspcgs66.valide = \'O\'
														AND*/ decisionstraitementspcgs66.actif = \'1\'
													)
												FROM decisionstraitementspcgs66
												WHERE
													decisionstraitementspcgs66.traitementpcg66_id = traitementspcgs66.id
												ORDER BY decisionstraitementspcgs66.created DESC
												LIMIT 1
										)
									)AS decisionok
								FROM dossierspcgs66
									INNER JOIN personnespcgs66 ON (
										dossierspcgs66.id = personnespcgs66.dossierpcg66_id
									)
									INNER JOIN traitementspcgs66 ON (
										personnespcgs66.id = traitementspcgs66.personnepcg66_id
										AND traitementspcgs66.clos = \'N\'
										AND traitementspcgs66.annule = \'N\'
									)
									INNER JOIN descriptionspdos ON (
										descriptionspdos.id = traitementspcgs66.descriptionpdo_id
										AND descriptionspdos.decisionpcg = \'O\'
									)
					) AS necessaires'
				);

				if ( $checkDecisionsTraitements[0][0]['every'] == true ) {
					$this->id = $dossierpcg66_id;
					$return = $this->saveField( 'etatdossierpcg', 'attavistech' );
					return $return;
				}
			}
			return true;
		}



		/**
		* Mise à jour de l'état du dossierpcg66. On vérifie qu'il existe au moins un traitement
		* nécessitant une décision en ai une active.
		**/

		public function updateEtatViaDecisionPersonnepcg( $dossierpcg66_id ) {
			$dossierpcg66 = $this->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $dossierpcg66_id
					),
					'contain' => false
				)
			);

			if ( $dossierpcg66['Dossierpcg66']['etatdossierpcg'] != 'attval' ) {
				$checkDecisionsPersonnes = $this->query(
					'SELECT
						EVERY(necessaires.decisionok)
						FROM (
							SELECT
									(
										EXISTS(
											SELECT
													*
												FROM decisionspersonnespcgs66
												WHERE
													decisionspersonnespcgs66.personnepcg66_situationpdo_id = personnespcgs66_situationspdos.id
												ORDER BY decisionspersonnespcgs66.created DESC
												LIMIT 1
										)
									)
									AS decisionok
								FROM dossierspcgs66
									INNER JOIN personnespcgs66 ON (
										dossierspcgs66.id = personnespcgs66.dossierpcg66_id
									)
									INNER JOIN traitementspcgs66 ON (
										personnespcgs66.id = traitementspcgs66.personnepcg66_id
										AND traitementspcgs66.clos = \'N\'
										AND traitementspcgs66.annule = \'N\'
									)
									INNER JOIN descriptionspdos ON (
										descriptionspdos.id = traitementspcgs66.descriptionpdo_id
										AND descriptionspdos.decisionpcg = \'O\'
									)
									INNER JOIN personnespcgs66_situationspdos ON (
										personnespcgs66_situationspdos.personnepcg66_id = personnespcgs66.id
									)
					) AS necessaires'
				);
// debug($checkDecisionsPersonnes);
				if ( $checkDecisionsPersonnes[0][0]['every'] == true ) {
					$this->id = $dossierpcg66_id;
					$return = $this->saveField( 'etatdossierpcg', 'attavistech' );
					return $return;
				}
			}
			return true;
		}


		public function updateEtatViaPersonne( $dossierpcg66_id ) {

			if ( $this->existePropoParMotif( $dossierpcg66_id ) ) {
				$etat = 'attavistech';
			}
			else {
				$etat = 'instrencours';
			}

			$this->id = $dossierpcg66_id;
			$return = $this->saveField( 'etatdossierpcg', $etat );
			return $return;
		}



		public function existePropoParMotif( $dossierpcg66_id ) {
			$existe = true;
			$personnespcgs66 = $this->Personnepcg66->find(
				'all',
				array(
					'conditions' => array(
						'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
					),
					'contain' => false
				)
			);

			foreach( $personnespcgs66 as $personnepcg66 ) {
				$situationsParPersonne = $this->Personnepcg66->Personnepcg66Situationpdo->find(
					'all',
					array(
						'conditions' => array(
							'Personnepcg66Situationpdo.personnepcg66_id' => $personnepcg66['Personnepcg66']['id']
						),
						'contain' => array(
							'Decisionpersonnepcg66'
						)
					)
				);

// debug($situationsParPersonne);
// die();

// 				foreach( $situationsParPersonne as $situationParPersonne ) {
// 					if ( empty( $situationParPersonne['Decisionpersonnepcg66'] ) ) {
// 						$existe = false;
// 					}
// 				}
			}
			return ( $existe && !empty( $personnespcgs66 ) );
		}

		/**
		* 
		*/

		public function updateEtatViaDecisionFoyer( $decisiondossierpcg66_id ) {
			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
					),
					'contain' => array(
						'Dossierpcg66'
					)
				)
			);

// debug( $decisiondossierpcg66 );
// die();
			$etat = $this->etatDossierPcg66( $decisiondossierpcg66['Dossierpcg66']['typepdo_id'], $decisiondossierpcg66['Dossierpcg66']['user_id'], $decisiondossierpcg66['Decisiondossierpcg66']['decisionpdo_id'], $decisiondossierpcg66['Decisiondossierpcg66']['avistechnique'], $decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'], $decisiondossierpcg66['Decisiondossierpcg66']['retouravistechnique'], $decisiondossierpcg66['Decisiondossierpcg66']['vuavistechnique'],/*, $decisiondossierpcg66['Dossierpcg66']['iscomplet']*/ $decisiondossierpcg66['Dossierpcg66']['etatdossierpcg'] );
// debug($etat);
// die();
			$this->id = $decisiondossierpcg66['Dossierpcg66']['id'];
			$return = $this->saveField( 'etatdossierpcg', $etat );

			return $return;
		}




		/**
		* 
		*/

		public function updateEtatViaTransmissionop( $decisiondossierpcg66_id ) {
			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisiondossierpcg66.id' => $decisiondossierpcg66_id
					),
					'contain' => array(
						'Dossierpcg66'
					)
				)
			);


			if ( $decisiondossierpcg66['Decisiondossierpcg66']['etatop'] == 'transmis' ) {
				$etat = 'transmisop';
			}
			else if ( $decisiondossierpcg66['Decisiondossierpcg66']['etatop'] == 'atransmettre' ) {
				$etat = 'atttransmisop';
			}

			$this->id = $decisiondossierpcg66['Dossierpcg66']['id'];
			$return = $this->saveField( 'etatdossierpcg', $etat );

			return $return;
		}

		
						/**
		*   AfterSave
		*/

		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			$return = $this->_updateDecisionCerParticulier( $created ) && $return;

			return $return;
		}

		protected function _updateDecisionCerParticulier( $created ) {
			$success = true;

			$decisiondossierpcg66 = $this->Decisiondossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisiondossierpcg66.dossierpcg66_id' => $this->id
					),
					'contain' => array(
						'Decisionpdo'
					),
					'order' => array( 'Decisiondossierpcg66.datevalidation DESC')
				)
			);
			
			$dossierpcg66 = $this->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $this->id
					),
					'contain' => false
				)
			);
			
			
// debug($decisiondossierpcg66);
// die();
			$dateDecision = $decisiondossierpcg66['Decisiondossierpcg66']['datevalidation'];
			$propositiondecision = $decisiondossierpcg66['Decisionpdo']['decisioncerparticulier'];
			if( !empty( $decisiondossierpcg66 ) && isset( $decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'] ) ) {
				if( ( $decisiondossierpcg66['Decisiondossierpcg66']['validationproposition'] == 'O' ) && ( ( ( $decisiondossierpcg66['Decisiondossierpcg66']['retouravistechnique'] == '0' ) && ( $decisiondossierpcg66['Decisiondossierpcg66']['vuavistechnique'] == '0' ) ) || ( ( $decisiondossierpcg66['Decisiondossierpcg66']['retouravistechnique'] == '1' ) && ( $decisiondossierpcg66['Decisiondossierpcg66']['vuavistechnique'] == '1' ) ) ) ) {
					
					if( $propositiondecision == 'N' ) {
						$success = $this->Contratinsertion->updateAll(
							array(
								'Contratinsertion.decision_ci' => "'".$propositiondecision."'",
								'Contratinsertion.datevalidation_ci' => null,
								'Contratinsertion.datedecision' => "'".$dateDecision."'",
								'Contratinsertion.positioncer' => '\'nonvalid\'',
							),
							array(
								'Contratinsertion.id' => $dossierpcg66['Dossierpcg66']['contratinsertion_id']
							)
						) && $success;
					}
					else {
						$success = $this->Contratinsertion->updateAll(
							array(
								'Contratinsertion.decision_ci' => "'".$propositiondecision."'",
								'Contratinsertion.datevalidation_ci' => "'".$dateDecision."'",
								'Contratinsertion.datedecision' => "'".$dateDecision."'",
								'Contratinsertion.positioncer' => '\'valid\'',
							),
							array(
								'Contratinsertion.id' => $dossierpcg66['Dossierpcg66']['contratinsertion_id']
							)
						) && $success;
					}
				}
			}
			
// 			debug($decisiondossierpcg66);
// 			die();
			
			return $success;
		}
		
        /**
		 * Retourne l'id du dossier à partir de l'id du dosiserpcg66
		 *
		 * @param integer $dossierpcg66_id
		 * @return integer
		 */
		public function dossierId( $dossierpcg66_id ) {
			$querydata = array(
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Dossierpcg66.id' => $dossierpcg66_id
				),
				'recursive' => -1
			);

			$dossierpcg66 = $this->find( 'first', $querydata );

			if( !empty( $dossierpcg66 ) ) {
				return $dossierpcg66['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
	}
?>