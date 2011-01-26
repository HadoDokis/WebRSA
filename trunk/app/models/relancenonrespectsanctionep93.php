<?php

	/**
	* ...
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Relancenonrespectsanctionep93 extends AppModel
	{
		public $name = 'Relancenonrespectsanctionep93';

		public $recursive = -1;

		public $validate = array(
			'daterelance' => array(
				array(
					'rule' => 'date',
					'message' => 'Merci de rentrer une date valide'
				),
				array(
					'rule' => array( 'checkForRelance' ),
					'message' => 'Date de relance trop récente'
				)
			)
		);

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
		);

		public $belongsTo = array(
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'nonrespectsanctionep93_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public function checkForRelance( $check ) {
			$nonrespectsanctionep93 = $this->Nonrespectsanctionep93->find(
				'first',
				array(
					'conditions'=>array(
						'Nonrespectsanctionep93.id'=>$this->data['Relancenonrespectsanctionep93']['nonrespectsanctionep93_id']
					),
					'contain'=>false
				)
			);
			$possible = true;
			if ($nonrespectsanctionep93['Nonrespectsanctionep93']['origine']=='orientstruct') {
				switch($this->data['Relancenonrespectsanctionep93']['numrelance']) {
					case 1:
						$orientstruct = $this->Nonrespectsanctionep93->Orientstruct->find(
							'first',
							array(
								'conditions' => array(
									'Orientstruct.id' => $nonrespectsanctionep93['Nonrespectsanctionep93']['orientstruct_id']
								),
								'contain' => false
							)
						);
						$nbjours = Configure::read('Nonrespectsanctionep93.relanceOrientstructCer1');
						$dateminrelance = strtotime( "+{$nbjours} days", strtotime( $orientstruct['Orientstruct']['date_impression'] ) );
						break;
					case 2:
					case 3:
						$relanceprecedente = $this->find(
							'first',
							array(
								'conditions'=>array(
									'Relancenonrespectsanctionep93.numrelance' => $this->data['Relancenonrespectsanctionep93']['numrelance']-1,
									'Relancenonrespectsanctionep93.nonrespectsanctionep93_id' => $this->data['Relancenonrespectsanctionep93']['nonrespectsanctionep93_id']
								),
								'contain'=>false
							)
						);
						$nbjours = Configure::read('Nonrespectsanctionep93.relanceOrientstructCer'.$this->data['Relancenonrespectsanctionep93']['numrelance']);
						$dateminrelance = strtotime( "+{$nbjours} days", strtotime( $relanceprecedente['Relancenonrespectsanctionep93']['daterelance'] ) );
						break;
				}
			}
			else {
				switch($this->data['Relancenonrespectsanctionep93']['numrelance']) {
					case 1:
						$contratinsertion = $this->Nonrespectsanctionep93->Contratinsertion->find(
							'first',
							array(
								'conditions' => array(
									'Contratinsertion.id' => $nonrespectsanctionep93['Nonrespectsanctionep93']['contratinsertion_id']
								),
								'contain' => false
							)
						);
						$nbjours = Configure::read('Nonrespectsanctionep93.relanceCerCer1');
						$dateminrelance = strtotime( "+{$nbjours} days", strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) );
						break;
					case 2:
						$relanceprecedente = $this->find(
							'first',
							array(
								'conditions'=>array(
									'Relancenonrespectsanctionep93.numrelance' => 1,
									'Relancenonrespectsanctionep93.nonrespectsanctionep93_id' => $this->data['Relancenonrespectsanctionep93']['nonrespectsanctionep93_id']
								),
								'contain'=>false
							)
						);
						$nbjours = Configure::read('Nonrespectsanctionep93.relanceCerCer2');
						$dateminrelance = strtotime( "+{$nbjours} days", strtotime( $relanceprecedente['Relancenonrespectsanctionep93']['daterelance'] ) );
						break;
				}
			}

			if ( $dateminrelance >= strtotime( $this->data['Relancenonrespectsanctionep93']['daterelance'] ) ) {
				$possible = false;
			}
			return $possible;
		}

		/**
		* Fonction de sauvegarde de la cohorte
		*/

		public function saveCohorte($newdata, $data) {
			$success = true;
			$validationErrors = array( $this->alias => array() );
			foreach( $newdata as $i => $relance ) {
				switch( $data['Relance']['numrelance'] ) {
					case 1:
						if( $data['Relance']['contrat'] == 0 ) {
							$this->Nonrespectsanctionep93->Orientstruct->id = $relance['orientstruct_id'];
							$personne_id = $this->Nonrespectsanctionep93->Orientstruct->field( 'personne_id' );
							$days = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' );
						}
						else {
							$days = Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' );
							$this->Nonrespectsanctionep93->Contratinsertion->id = $relance['contratinsertion_id'];
							$personne_id = $this->Nonrespectsanctionep93->Contratinsertion->field( 'personne_id' );
						}

						$nbpassagespcd = $this->Nonrespectsanctionep93->Dossierep->find(
							'count',
							array(
								'conditions' => array(
									'Dossierep.personne_id' => $personne_id,
									'Dossierep.themeep' => 'nonrespectssanctionseps93',
								)
							)
						);

						if( $data['Relance']['contrat'] == 0 ) {
							$item = array(
								'Nonrespectsanctionep93' => array(
									'orientstruct_id' => $relance['orientstruct_id'],
									'origine' => 'orientstruct',
									'active' => 1,
									'rgpassage' => 1,
								),
								'Relancenonrespectsanctionep93' => array(
									array(
										'numrelance' => $relance['numrelance'],
										'daterelance' => $relance['daterelance']
									)
								)
							);
						}
						else {
							$item = array(
								'Nonrespectsanctionep93' => array(
									'contratinsertion_id' => $relance['contratinsertion_id'],
									'origine' => 'contratinsertion',
									'active' => 1,
									'rgpassage' => 1,
								),
								'Relancenonrespectsanctionep93' => array(
									array(
										'numrelance' => $relance['numrelance'],
										'daterelance' => $relance['daterelance']
									)
								)
							);
						}

						$success = $this->Nonrespectsanctionep93->saveAll( $item, array( 'atomic' => false ) ) && $success;
						if( !empty( $this->Nonrespectsanctionep93->validationErrors ) ) {
							$validationErrors['Relancenonrespectsanctionep93'][$i] = $this->Nonrespectsanctionep93->validationErrors['Relancenonrespectsanctionep93'][0];
						}
						break;
					case 2:
						if( $data['Relance']['contrat'] == 0 ) {
							$days = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer2' );
						}
						else {
							$days = Configure::read( 'Nonrespectsanctionep93.relanceCerCer2' );
						}

						$item = array(
							'Relancenonrespectsanctionep93' => array(
								'nonrespectsanctionep93_id' => $relance['nonrespectsanctionep93_id'],
								'numrelance' => $relance['numrelance'],
								'daterelance' => $relance['daterelance']
							)
						);
						$this->create( $item );
						$success = $this->save() && $success;

						if( !empty( $this->validationErrors ) ) {
							$validationErrors[$this->alias][$i] = $this->validationErrors;
						}

						if( $data['Relance']['contrat'] == 1 ) {
							$this->Nonrespectsanctionep93->Contratinsertion->id = $relance['contratinsertion_id'];
							$personne_id = $this->Nonrespectsanctionep93->Contratinsertion->field( 'personne_id' );

							$dossierep = array(
								'Dossierep' => array(
									'personne_id' => $personne_id,
									'themeep' => 'nonrespectssanctionseps93',
								),
							);

							$this->Nonrespectsanctionep93->Dossierep->create( $dossierep );
							$success = $this->Nonrespectsanctionep93->Dossierep->save() && $success;

							// Nonrespectsanctionep93
							$nonrespectsanctionep93 = array(
								'Nonrespectsanctionep93' => array(
									'id' => $relance['nonrespectsanctionep93_id'],
									'dossierep_id' => $this->Nonrespectsanctionep93->Dossierep->id,
									'active' => 0,
								)
							);

							$this->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
							$success = $this->Nonrespectsanctionep93->save() && $success;
						}
						break;
					case 3:
						if( $data['Relance']['contrat'] == 0 ) {
							$days = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer3' );

							$item = array(
								'Relancenonrespectsanctionep93' => array(
									'nonrespectsanctionep93_id' => $relance['nonrespectsanctionep93_id'],
									'numrelance' => $relance['numrelance'],
									'daterelance' => $relance['daterelance']
								)
							);
							$this->create( $item );
							$success = $this->save() && $success;

							if( !empty( $this->validationErrors ) ) {
								$validationErrors[$this->alias][$i] = $this->validationErrors;
							}

							// Dossier EP
							$this->Nonrespectsanctionep93->Orientstruct->id = $relance['orientstruct_id'];
							$personne_id = $this->Nonrespectsanctionep93->Orientstruct->field( 'personne_id' );

							$dossierep = array(
								'Dossierep' => array(
									'personne_id' => $personne_id,
									'themeep' => 'nonrespectssanctionseps93',
								),
							);

							$this->Nonrespectsanctionep93->Dossierep->create( $dossierep );
							$success = $this->Nonrespectsanctionep93->Dossierep->save() && $success;

							// Nonrespectsanctionep93
							$nonrespectsanctionep93 = array(
								'Nonrespectsanctionep93' => array(
									'id' => $relance['nonrespectsanctionep93_id'],
									'dossierep_id' => $this->Nonrespectsanctionep93->Dossierep->id,
									'active' => 0,
								)
							);

							$this->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
							$success = $this->Nonrespectsanctionep93->save() && $success;

						}
						break;
				}
			}

			$this->validationErrors = $validationErrors[$this->alias];

			return $success;
		}

		/**
		 * Fonction de recherche des dossiers à relancer
		 */
		public function search($search) {
			unset( $search['Relancenonrespectsanctionep93'] );
			$search = Set::flatten( $search );
			$search = Set::filter( $search );

			$conditions = array();
			$joins = array();

			// Personne orientée sans contrat
			// FIXME: dernière orientation
			// FIXME: et qui ne se trouve pas dans les EPs en cours de traitement
			// FIXME: sauvegarder le PDF

			// Champs de base
			$fields = array(
				'Personne.id',
				'Personne.nom',
				'Personne.prenom',
				'Personne.nir',
				'Personne.dtnai',
				'Dossier.matricule',
				'Adresse.locaadr'
			);

			/// Jointures de base
			if( $search['Relance.contrat'] == 0 ) {
				$fields = array_merge($fields, array(
					'Orientstruct.id',
					'Orientstruct.propo_algo',
					'Orientstruct.valid_cg',
					'Orientstruct.date_propo',
					'Orientstruct.date_valid',
					'Orientstruct.statut_orient',
					'Orientstruct.date_impression',
					'Orientstruct.daterelance',
					'Orientstruct.statutrelance',
					'Orientstruct.date_impression_relance',
					'Orientstruct.etatorient',
					'Orientstruct.rgorient'
				) );
				$joins[] = array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
						);
			}
			else {
				$fields = array_merge( $fields, array(
					'Contratinsertion.id',
					'Contratinsertion.df_ci'
				) );
				$joins[] = array(
							'table'      => 'personnes',
							'alias'      => 'Personne',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Contratinsertion.personne_id' )
						);
			}
			$joins[] = array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						)
					);
			$joins[] = array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => 1
						)
					);
			$joins[] = array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					);
			$joins[] = array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					);
			$joins[] = array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Dossier.id = Situationdossierrsa.dossier_id',
							'Situationdossierrsa.etatdosrsa' => $this->Nonrespectsanctionep93->Orientstruct->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert()
						)
					);
			$joins[] = array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Adressefoyer.foyer_id = Foyer.id',
							'Adressefoyer.rgadr' => '01'
						)
					);
			$joins[] = array(
				'table'      => 'adresses',
				'alias'      => 'Adresse',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array( 'Adressefoyer.adresse_id = Adresse.id' )
			);

			if( ( isset( $search['Dossiercaf.nomtitulaire'] ) && !empty( $search['Dossiercaf.nomtitulaire'] ) ) ||
				( isset( $search['Dossiercaf.prenomtitulaire'] ) && !empty( $search['Dossiercaf.prenomtitulaire'] ) ) ) {
				$joins[] = array(
					'table'      => 'dossierscaf',
					'alias'      => 'Dossiercaf',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						'Dossiercaf.personne_id = Personne.id',
						'Dossiercaf.toprespdos = true',
						'OR' => array(
							'Dossiercaf.dfratdos IS NULL',
							'Dossiercaf.dfratdos >= NOW()'
						),
						'Dossiercaf.ddratdos <= NOW()'
					)
				);
			}

			// FIXME: jointures (Dossier)
			foreach( $search as $field => $condition ) {
				if( in_array( $field, array( 'Personne.nom', 'Personne.prenom' ) ) ) {
					$conditions["UPPER({$field}) LIKE"] = $this->wildcard( strtoupper( replace_accents( $condition ) ) );
				}
				else if( $field == 'Adresse.numcomptt' && !empty( $condition ) ) {
					$conditions[] = array( 'Adresse.numcomptt' => $condition );
				}
				else if( $field == 'Serviceinstructeur.id' && !empty( $condition ) ) {
					$joins[] = array(
							'table'      => 'suivisinstruction',
							'alias'      => 'Suivisinstruction',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Suivisinstruction.dossier_id = Foyer.dossier_id',
								'Foyer.id = Personne.foyer_id'
							)
						);
					$joins[] = array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Suivisinstruction.numdepins = Serviceinstructeur.numdepins',
								'Suivisinstruction.typeserins = Serviceinstructeur.typeserins',
								'Suivisinstruction.numcomins = Serviceinstructeur.numcomins',
								'Suivisinstruction.numagrins = Serviceinstructeur.numagrins'
							)
						);
					$conditions[] = array( 'Serviceinstructeur.id' => $condition );
				}
				else if( $field == 'Dossier.matricule' && !empty( $condition ) ) {
					$conditions[] = array( 'Dossier.matricule' => $condition );
				}
				else if( ( $field == 'Dossiercaf.nomtitulaire' || $field == 'Dossiercaf.prenomtitulaire' ) && !empty( $condition ) ) {
					$field = preg_replace( '/^Dossiercaf\.(.*)titulaire$/', '\1', $field );
					$conditions["UPPER({$field}) LIKE"] = $this->wildcard( strtoupper( replace_accents( $condition ) ) );
				}
				else if( !in_array( $field, array( 'Relance.numrelance', 'Relance.contrat', 'Relance.compare0', 'Relance.compare1', 'Relance.nbjours0', 'Relance.nbjours1' ) ) ) {
					$conditions[$field] = $condition;
				}
			}

			if ( $search['Relance.numrelance'] > 1 ) {
				if( $search['Relance.contrat'] == 0 ) {
					$joins[] = array(
								'table'      => 'nonrespectssanctionseps93',
								'alias'      => 'Nonrespectsanctionep93',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Orientstruct.id = Nonrespectsanctionep93.orientstruct_id'
									// Sous requête pour avoir le Nonrespectsanctionep93 le plus récent
								),
// 								'order' => array( 'Nonrespectsanctionep93.created DESC' ),
// 								'limit' => 1
							);
				}
				else {
					$joins[] = array(
								'table'      => 'nonrespectssanctionseps93',
								'alias'      => 'Nonrespectsanctionep93',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Contratinsertion.id = Nonrespectsanctionep93.contratinsertion_id'
									// Sous requête pour avoir le Nonrespectsanctionep93 le plus récent
								),
	// 							'order' => array( 'Nonrespectsanctionep93.created DESC' ),
	// 							'limit' => 1
							);
				}
				$joins[] = array(
							'table'      => 'relancesnonrespectssanctionseps93',
							'alias'      => 'Relancenonrespectsanctionep93',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Nonrespectsanctionep93.id = Relancenonrespectsanctionep93.nonrespectsanctionep93_id',
								// On ne fait la jointure que sur la dernière relance
								'Relancenonrespectsanctionep93.id IN (
									SELECT relancesnonrespectssanctionseps93.id
										FROM relancesnonrespectssanctionseps93
										WHERE relancesnonrespectssanctionseps93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id
										ORDER BY relancesnonrespectssanctionseps93.numrelance DESC
										LIMIT 1
								)',
								'Relancenonrespectsanctionep93.numrelance' => ( $search['Relance.numrelance']-1 )
							),
						);
				$fieldssup = array(
					'Nonrespectsanctionep93.id',
					'Relancenonrespectsanctionep93.daterelance',
				);
				$fields = array_merge($fields, $fieldssup);
			}

			// Relances pour personnes sans contrat
			/// FIXME: que les dernières orientations / les derniers contrats
			/// Exemple: 1ère relance de /orientsstructs/index/351610
			if( $search['Relance.contrat'] == 0 ) {
				$conditions['Orientstruct.statut_orient'] = 'Orienté';

				// Dernière Orientstruct de la personne
				$conditions[] = 'Orientstruct.id IN (
					SELECT orientsstructs.id
						FROM orientsstructs
						WHERE
							orientsstructs.date_valid IS NOT NULL
							AND orientsstructs.personne_id = Personne.id
						ORDER by orientsstructs.date_valid DESC
						LIMIT 1
				)';

				$conditions[] = 'Orientstruct.personne_id NOT IN (
									SELECT contratsinsertion.personne_id
										FROM contratsinsertion
										WHERE
											contratsinsertion.personne_id = Orientstruct.personne_id
											AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) >= Orientstruct.date_valid
										)';
				$conditions[] = "Orientstruct.date_impression <= DATE( NOW() )";
				if( !empty( $search['Relance.compare0'] ) && !empty( $search['Relance.nbjours0'] ) ) {
					$conditions[] = "( DATE( NOW() ) - Orientstruct.date_impression ) {$search['Relance.compare0']} {$search['Relance.nbjours0']}";
				}

				switch( $search['Relance.numrelance'] ) {
					case 1:
						$conditions[] = "( DATE( NOW() ) - Orientstruct.date_impression ) >= ".Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' );

						$conditions[] = 'Orientstruct.id NOT IN (
							SELECT nonrespectssanctionseps93.orientstruct_id
								FROM nonrespectssanctionseps93
								WHERE
									nonrespectssanctionseps93.active = \'1\'
									AND nonrespectssanctionseps93.dossierep_id IS NULL
									AND nonrespectssanctionseps93.orientstruct_id = Orientstruct.id
						)';
						break;
					case 2:
					case 3:
						/// FIXME: dateimpression plutôt que daterelance
						$conditions[] = 'Orientstruct.id IN (
							SELECT nonrespectssanctionseps93.orientstruct_id
								FROM nonrespectssanctionseps93
								WHERE
									nonrespectssanctionseps93.active = \'1\'
									AND nonrespectssanctionseps93.dossierep_id IS NULL
									AND nonrespectssanctionseps93.orientstruct_id = Orientstruct.id
									AND (
										SELECT
												relancesnonrespectssanctionseps93.numrelance
												FROM relancesnonrespectssanctionseps93
												WHERE
													relancesnonrespectssanctionseps93.nonrespectsanctionep93_id = nonrespectssanctionseps93.id
													AND ( DATE( NOW() ) - relancesnonrespectssanctionseps93.daterelance ) >= '.Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer{$search['Relance.numrelance']}" ).'
												ORDER BY relancesnonrespectssanctionseps93.numrelance DESC
												LIMIT 1
									) = '.( $search['Relance.numrelance'] - 1 ).'
						)';
						break;
				}

				/// FIXME: que les dernières orientations / les derniers contrats
				$queryData = array(
					'fields' => $fields,
					'conditions' => $conditions,
					'joins' => $joins,
					'contain' => false,
					'limit' => 10,
					'order' => array( 'Orientstruct.date_impression ASC' ),
				);

				$results = $this->Nonrespectsanctionep93->Orientstruct->find( 'all', $queryData );

				if( !empty( $results ) ) {
					foreach( $results as $i => $result ) {
						// Calcul de la date de relance minimale
						if( $search['Relance.numrelance'] == 1 ) {
							$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
								'Y-m-d',
								strtotime(
									'+'.( Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' ) + 1 ).' days',
									strtotime( $result['Orientstruct']['date_impression'] )
								)
							);
						}
						else if( $search['Relance.numrelance'] > 1 ) {
							$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
								'Y-m-d',
								strtotime(
									'+'.( Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer{$search['Relance.numrelance']}" ) + 1 ).' days',
									strtotime( $result['Relancenonrespectsanctionep93']['daterelance'] )
								)
							);
						}

						$results[$i]['Orientstruct']['nbjours'] = round(
							//( mktime() - strtotime( $result['Orientstruct']['date_valid'] ) ) / ( 60 * 60 * 24 )
							( mktime() - strtotime( $result['Orientstruct']['date_impression'] ) ) / ( 60 * 60 * 24 )
						);
					}
				}
			}
			else {
				// Dernièr contrat
				$conditions[] = '( DATE( NOW() ) - Contratinsertion.df_ci ) >= '.Configure::read( 'Nonrespectsanctionep93.relanceCerCer'.$search['Relance.numrelance'] );
				$conditions[] = 'Contratinsertion.df_ci <= DATE( NOW() )';
				if( !empty( $search['Relance.compare1'] ) && !empty( $search['Relance.nbjours1'] ) ) {
					$conditions[] = '( DATE( NOW() ) - Contratinsertion.df_ci ) '.$search['Relance.compare1'].' '.$search['Relance.nbjours1'];
				}

				switch( $search['Relance.numrelance'] ) {
					case 1:
						$conditions[] = 'Contratinsertion.id IN (
							SELECT contratsinsertion.id
								FROM contratsinsertion
								WHERE
									contratsinsertion.datevalidation_ci IS NOT NULL
									AND contratsinsertion.df_ci < NOW()
									AND contratsinsertion.personne_id = Personne.id
								ORDER by contratsinsertion.datevalidation_ci DESC
								LIMIT 1
						)';

						$conditions[] = 'Contratinsertion.id NOT IN (
							SELECT nonrespectssanctionseps93.orientstruct_id
								FROM nonrespectssanctionseps93
								WHERE
									nonrespectssanctionseps93.active = \'1\'
									AND nonrespectssanctionseps93.dossierep_id IS NULL
									AND nonrespectssanctionseps93.contratinsertion_id = Contratinsertion.id
						)';
						break;
					case 2:
						$conditions[] = 'Contratinsertion.id IN (
							SELECT nonrespectssanctionseps93.contratinsertion_id
								FROM nonrespectssanctionseps93
								WHERE
									nonrespectssanctionseps93.active = \'1\'
									AND nonrespectssanctionseps93.dossierep_id IS NULL
									AND nonrespectssanctionseps93.contratinsertion_id = Contratinsertion.id
									AND (
										SELECT
												relancesnonrespectssanctionseps93.numrelance
												FROM relancesnonrespectssanctionseps93
												WHERE
													relancesnonrespectssanctionseps93.nonrespectsanctionep93_id = nonrespectssanctionseps93.id
												ORDER BY relancesnonrespectssanctionseps93.numrelance DESC
												LIMIT 1
									) = '.( $search['Relance.numrelance'] - 1 ).'
						)';
						break;
				}

				$queryData = array(
					'fields' => $fields,
					'conditions' => $conditions,
					'joins' => $joins,
					'contain' => false,
					'limit' => 10,
					'order' => array( 'Contratinsertion.df_ci ASC' ),
				);

				$results = $this->Nonrespectsanctionep93->Contratinsertion->find( 'all', $queryData );

				if( !empty( $results ) ) {
					foreach( $results as $i => $result ) {
						// Calcul de la date de relance minimale
						if( $search['Relance.numrelance'] == 1 ) {
							$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
								'Y-m-d',
								strtotime(
									'+'.( Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' ) + 1 ).' days',
									strtotime( $result['Contratinsertion']['df_ci'] )
								)
							);
						}
						else if( $search['Relance.numrelance'] > 1 ) {
							$results[$i]['Nonrespectsanctionep93']['datemin'] = date(
								'Y-m-d',
								strtotime(
									'+'.( Configure::read( "Nonrespectsanctionep93.relanceCerCer{$search['Relance.numrelance']}" ) + 1 ).' days',
									strtotime( $result['Relancenonrespectsanctionep93']['daterelance'] )
								)
							);
						}

						$results[$i]['Contratinsertion']['nbjours'] = round(
							( mktime() - strtotime( $result['Contratinsertion']['df_ci'] ) ) / ( 60 * 60 * 24 )
						);
					}
				}
			}
			return $results;
		}

		/**
		*
		*/

		public function checkCompareError($datas) {
			$searchError = false;
			if( $datas['Relance']['contrat'] == 0 ) {
				if ( ( @$datas['Relance']['compare0'] == '<' && @$datas['Relance']['nbjours0'] <= Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer'.$datas['Relance']['numrelance'] ) ) || ( @$datas['Relance']['compare0'] == '<=' && @$datas['Relance']['nbjours0'] < Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer'.$datas['Relance']['numrelance'] ) ) )
					$searchError = true;
			}
			else {
				if ( ( @$datas['Relance']['compare1'] == '<' && @$datas['Relance']['nbjours1'] <= Configure::read( 'Nonrespectsanctionep93.relanceCerCer'.$datas['Relance']['numrelance'] ) ) || ( @$datas['Relance']['compare1'] == '<=' && @$datas['Relance']['nbjours1'] < Configure::read( 'Nonrespectsanctionep93.relanceCerCer'.$datas['Relance']['numrelance'] ) ) )
					$searchError = true;
			}
			return $searchError;
		}

		/**
		* Retourne un array de chaînes de caractères indiquant pourquoi on ne
		* peut pas créer de relance pour cette personne.
		*
		* Les valeurs possibles sont:
		*	- 1°) Par-rapport à la possibilité de créer un dossier d'EP:
		* 		* Personne.id: la personne n'existe pas en base ou n'a pas de prestation RSA
		* 		* Situationdossierrsa.etatdosrsa: le dossier ne se trouve pas dans un état ouvert
		* 		* Prestation.rolepers: la personne n'est ni demandeur ni conjoint RSA
		* 		* Calculdroitrsa.toppersdrodevorsa: la personne n'est pas soumise à droits et devoirs
		*	- 2°)
		* 		* Dossierep.id: il existe déjà un dossier d'EP non finalisé pour "Demande de suspension"
		*		* Contratinsertion.df_ci: la date de fin du contrat d'insertion est égale ou supérieure à la date du jour
		*		* Contratinsertion.Orientstruct: la personne ne possède ni orientation validée et éditée, ni contrat validé
		*		* Nonrespectsanctionep93.relanceCerCer1: le délai pour la relance n° 1 pour non recontratctualisation n'est pas encore dépassé
		*		* Nonrespectsanctionep93.relanceCerCer2: le délai pour la relance n° 2 pour non recontratctualisation n'est pas encore dépassé
		*		* Nonrespectsanctionep93.relanceOrientstructCer1: le délai pour la relance n° 1 pour non contratctualisation n'est pas encore dépassé
		*		* Nonrespectsanctionep93.relanceOrientstructCer2: le délai pour la relance n° 2 pour non contratctualisation n'est pas encore dépassé
		*		* Nonrespectsanctionep93.relanceOrientstructCer3: le délai pour la relance n° 3 pour non contratctualisation n'est pas encore dépassé
		*
		* @param integer $personne_id L'id technique de la personne
		* @return array
		* @access public
		*/

		public function erreursPossibiliteAjout( $personne_id ) {
			$erreurs = $this->Nonrespectsanctionep93->Dossierep->erreursCandidatePassage( $personne_id );

			if( empty( $erreurs ) ) {
				// 0°) Il n'existe pas de dossier d'EP en cours pour la thématique "Demande de suspension"
				$count = $this->Nonrespectsanctionep93->Dossierep->find(
					'count',
					array(
						'conditions' => array(
							'Dossierep.personne_id' => $personne_id,
							'Dossierep.etapedossierep <>' => 'traite',
							'Dossierep.themeep' => 'nonrespectssanctionseps93',
						),
						'contain' => false
					)
				);

				if( $count > 0 ) {
					$erreurs[] = 'Dossierep.id';
				}
				else {
					// 1°) La personne possède un dernièr contrat d'insertion validé
					$contratinsertion = $this->Nonrespectsanctionep93->Contratinsertion->find(
						'first',
						array(
							'conditions' => array(
								'Contratinsertion.personne_id' => $personne_id,
								'Contratinsertion.decision_ci' => 'V',
								'Contratinsertion.df_ci IS NOT NULL',
								'Contratinsertion.datevalidation_ci IS NOT NULL',

							),
							'order' => array( 'Contratinsertion.df_ci DESC' ),
							'contain' => false
						)
					);

					$orientstruct = $this->Nonrespectsanctionep93->Orientstruct->find(
						'first',
						array(
							'conditions' => array(
								'Orientstruct.personne_id' => $personne_id,
								'Orientstruct.date_valid IS NOT NULL',
								'Orientstruct.statut_orient' => 'Orienté',
								'Orientstruct.date_impression IS NOT NULL',

							),
							'order' => array( 'Orientstruct.date_impression DESC' ),
							'contain' => false
						)
					);

					// 2.2°) La personne ne possède ni orientation validée et éditée, ni contrat validé
					if( empty( $contratinsertion ) && empty( $orientstruct ) ) {
						$erreurs[] = 'Contratinsertion.Orientstruct';
					}
					else {
						// On a un contrat, pas d'orientation ou une date de contrat postérieure à la date d'orientation
						// Donc on se base sur le contrat
						if( !empty( $contratinsertion ) && ( empty( $orientstruct ) || ( $orientstruct['Orientstruct']['date_impression'] < $contratinsertion['Contratinsertion']['datevalidation_ci'] ) ) ) {
							$relanceCerCer1 = Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' );
							$relanceCerCer2 = Configure::read( 'Nonrespectsanctionep93.relanceCerCer2' );

							$relances = $this->Nonrespectsanctionep93->find(
								'first',
								array(
									'conditions' => array(
										'Nonrespectsanctionep93.origine' => 'contratinsertion',
										'Nonrespectsanctionep93.contratinsertion_id' => $contratinsertion['Contratinsertion']['id'],
										'Nonrespectsanctionep93.dossierep_id IS NULL',
										'Nonrespectsanctionep93.active' => 1,
									),
									'joins' => array(
										array(
											'table'      => 'relancesnonrespectssanctionseps93',
											'alias'      => 'Relancenonrespectsanctionep93',
											'type'       => 'INNER',
											'foreignKey' => false,
											'conditions' => array(
												'Relancenonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id'
											)
										),
									),
									'contain' => array(
										'Relancenonrespectsanctionep93' => array(
											'order' => array( 'daterelance ASC' )
										)
									)
								)
							);

							$nbrelances = count( @$relances['Relancenonrespectsanctionep93'] );
							if( $nbrelances > 0 ) {
								$derniererelance = $relances['Relancenonrespectsanctionep93'][$nbrelances-1];
							}

							// 2.1°) La contrat est toujours en cours
							if( strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) >= strtotime( date( 'Y-m-d' ) ) ) {
								$erreurs[] = 'Contratinsertion.df_ci';
							}
							else if( ( $nbrelances == 1 ) && strtotime( "+{$relanceCerCer2} days", strtotime( $derniererelance['daterelance'] ) ) >= strtotime( date( 'Y-m-d' ) ) ) {
								$erreurs[] = 'Nonrespectsanctionep93.relanceCerCer2';
							}
							else if( ( $nbrelances == 0 ) && strtotime( "+{$relanceCerCer1} days", strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) ) >= strtotime( date( 'Y-m-d' ) ) ) {
								$erreurs[] = 'Nonrespectsanctionep93.relanceCerCer1';
							}
						}
						// On a une orientation, pas de contrat, ou une date d'orientation postérieure à la date de contrat
						// Donc on se base sur l'orientation
						else {
							$relanceOrientstructCer1 = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' );
							$relanceOrientstructCer2 = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer2' );
							$relanceOrientstructCer3 = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer3' );

							$nbrelances = $this->Nonrespectsanctionep93->find(
								'first',
								array(
									'conditions' => array(
										'Nonrespectsanctionep93.origine' => 'orientstruct',
										'Nonrespectsanctionep93.orientstruct_id' => $orientstruct['Orientstruct']['id'],
										'Nonrespectsanctionep93.dossierep_id IS NULL',
										'Nonrespectsanctionep93.active' => 1,
									),
									'joins' => array(
										array(
											'table'      => 'relancesnonrespectssanctionseps93',
											'alias'      => 'Relancenonrespectsanctionep93',
											'type'       => 'INNER',
											'foreignKey' => false,
											'conditions' => array(
												'Relancenonrespectsanctionep93.nonrespectsanctionep93_id = Nonrespectsanctionep93.id'
											)
										),
									),
									'contain' => array(
										'Relancenonrespectsanctionep93' => array(
											'order' => array( 'daterelance ASC' )
										)
									)
								)
							);

							$nbrelances = count( @$relances['Relancenonrespectsanctionep93'] );
							if( $nbrelances > 0 ) {
								$derniererelance = $relances['Relancenonrespectsanctionep93'][$nbrelances-1];
							}

							if( ( $nbrelances == 2 ) && strtotime( "+{$relanceOrientstructCer3} days", strtotime( $derniererelance['daterelance'] ) ) >= strtotime( date( 'Y-m-d' ) ) ) {
								$erreurs[] = 'Nonrespectsanctionep93.relanceOrientstructCer3';
							}
							else if( ( $nbrelances == 1 ) && strtotime( "+{$relanceOrientstructCer2} days", strtotime( $derniererelance['daterelance'] ) ) >= strtotime( date( 'Y-m-d' ) ) ) {
								$erreurs[] = 'Nonrespectsanctionep93.relanceOrientstructCer2';
							}
							else if( ( $nbrelances == 0 ) && strtotime( "+{$relanceOrientstructCer1} days", strtotime( $orientstruct['Orientstruct']['date_impression'] ) ) >= strtotime( date( 'Y-m-d' ) ) ) {
								$erreurs[] = 'Nonrespectsanctionep93.relanceOrientstructCer1';
							}
						}
					}
				}
			}

			return $erreurs;
		}

		/**
		*
		*/

		public function dateRelanceMinimale( $typerelance, $numrelance, $data ) {
			if( $typerelance == 'orientstruct' ) {
				if( $numrelance == 1 ) {
					return date(
						'Y-m-d',
						strtotime(
							'+'.( Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' ) + 1 ).' days',
							strtotime( $data['Orientstruct']['date_impression'] )
						)
					);
				}
				else if( $numrelance > 1 ) {
					return date(
						'Y-m-d',
						strtotime(
							'+'.( Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer{$numrelance}" ) + 1 ).' days',
							strtotime( $data['Relancenonrespectsanctionep93']['daterelance'] )
						)
					);
				}
			}
			else if( $typerelance == 'contratinsertion' ) {
				// Calcul de la date de relance minimale
				if( $numrelance == 1 ) {
					return date(
						'Y-m-d',
						strtotime(
							'+'.( Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' ) + 1 ).' days',
							strtotime( $data['Contratinsertion']['df_ci'] )
						)
					);
				}
				else if( $numrelance > 1 ) {
					return date(
						'Y-m-d',
						strtotime(
							'+'.( Configure::read( "Nonrespectsanctionep93.relanceCerCer{$numrelance}" ) + 1 ).' days',
							strtotime( $data['Relancenonrespectsanctionep93']['daterelance'] )
						)
					);
				}
			}
		}
	}
?>