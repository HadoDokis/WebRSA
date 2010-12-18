<?php
	class Relancesnonrespectssanctionseps93Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		public $uses = array( 'Relancenonrespectsanctionep93', 'Nonrespectsanctionep93', 'Orientstruct', 'Contratinsertion', 'Dossierep' );

		/**
		*
		*/

		public function beforeFilter() {
			$this->Auth->allow( '*' ); // FIXME
		}

		/**
		*
		*/

		public function index( $personne_id ) {
			$conditions = array( 'OR' => array(), 'origine' => array( 'orientstruct', 'contratinsertion' ) );

			$orientsstructs = $this->Orientstruct->find(
				'list',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id
					)
				)
			);
			if( !empty( $orientsstructs ) ) {
				$conditions['OR']['Nonrespectsanctionep93.orientstruct_id'] = $orientsstructs;
			}

			$contratsinsertion = $this->Contratinsertion->find(
				'list',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					)
				)
			);
			if( !empty( $contratsinsertion ) ) {
				$conditions['OR']['Nonrespectsanctionep93.contratinsertion_id'] = $contratsinsertion;
			}

			$relances = array();
			if( !empty( $conditions['OR'] ) ) {
				$relances = $this->Nonrespectsanctionep93->find(
					'all',
					array(
						'conditions' => $conditions,
						'contain' => array(
							'Orientstruct' => array(
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
							),
							'Relancenonrespectsanctionep93' => array(
								'order' => array( 'Relancenonrespectsanctionep93.daterelance DESC' ),
								'limit' => 1
							)
						),
						'order' => array( 'Nonrespectsanctionep93.created DESC' ),
					)
				);
			}

			$this->set( compact( 'relances' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		*
		*/

		public function add( $personne_id ) {
			$this->set( 'personne_id', $personne_id );
		}

		/**
		*
		*/

		public function cohorte() {
			if( !empty( $this->data ) ) {
				/// Enregistrement de la cohorte de relances
				if( isset( $this->data['Relancenonrespectsanctionep93'] ) ) {
					$data = $this->data['Relancenonrespectsanctionep93'];

					// On filtre les relances en attente
					$newData = array();
					foreach( $data as $relance ) {
						if( $relance['arelancer'] == 'R' ) {
							$newData[] = $relance;
						}
					}

					if( !empty( $newData ) ) {
						$this->Nonrespectsanctionep93->begin();
						$success = true;
						// Relances non respect orientation
						foreach( $newData as $relance ) {
							switch( $this->data['Relance']['numrelance'] ) {
								case 1:
									if( $this->data['Relance']['contrat'] == 0 ) {
										$this->Nonrespectsanctionep93->Orientstruct->id = $relance['orientstruct_id'];
										$personne_id = $this->Nonrespectsanctionep93->Orientstruct->field( 'personne_id' );
										$months = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' );
									}
									else {
										$months = Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' );
										$this->Nonrespectsanctionep93->Contratinsertion->id = $relance['contratinsertion_id'];
										$personne_id = $this->Nonrespectsanctionep93->Contratinsertion->field( 'personne_id' );
									}

									$dateecheance = strtotime( "{$relance['daterelance']['year']}{$relance['daterelance']['month']}{$relance['daterelance']['day']}" );
									$dateecheance = date( 'Y-m-d', strtotime( "+{$months} month", $dateecheance ) );

									$nbpassagespcd = $this->Nonrespectsanctionep93->Dossierep->find(
										'count',
										array(
											'conditions' => array(
												'Dossierep.personne_id' => $personne_id,
												'Dossierep.themeep' => 'nonrespectssanctionseps93',
											)
										)
									);

									if( $this->data['Relance']['contrat'] == 0 ) {
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
													'dateecheance' => $dateecheance,
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
													'dateecheance' => $dateecheance,
													'daterelance' => $relance['daterelance']
												)
											)
										);
									}


									$success = $this->Nonrespectsanctionep93->saveAll( $item, array( 'atomic' => false ) ) && $success;
									break;
								case 2:
									if( $this->data['Relance']['contrat'] == 0 ) {
										$months = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer2' );
									}
									else {
										$months = Configure::read( 'Nonrespectsanctionep93.relanceCerCer2' );
									}

									$dateecheance = strtotime( "{$relance['daterelance']['year']}{$relance['daterelance']['month']}{$relance['daterelance']['day']}" );
									$dateecheance = date( 'Y-m-d', strtotime( "+{$months} month", $dateecheance ) );

									$item = array(
										'Relancenonrespectsanctionep93' => array(
											'nonrespectsanctionep93_id' => $relance['nonrespectsanctionep93_id'],
											'numrelance' => $relance['numrelance'],
											'dateecheance' => $dateecheance,
											'daterelance' => $relance['daterelance']
										)
									);
									$this->Relancenonrespectsanctionep93->create( $item );
									$success = $this->Relancenonrespectsanctionep93->save() && $success;

									if( $this->data['Relance']['contrat'] == 1 ) {
										$this->Nonrespectsanctionep93->Contratinsertion->id = $relance['contratinsertion_id'];
										$personne_id = $this->Nonrespectsanctionep93->Contratinsertion->field( 'personne_id' );

										$dossierep = array(
											'Dossierep' => array(
												'personne_id' => $personne_id,
												'themeep' => 'nonrespectssanctionseps93',
											),
										);

										$this->Dossierep->create( $dossierep );
										$success = $this->Dossierep->save() && $success;

										// Nonrespectsanctionep93
										$nonrespectsanctionep93 = array(
											'Nonrespectsanctionep93' => array(
												'id' => $relance['nonrespectsanctionep93_id'],
												'dossierep_id' => $this->Dossierep->id,
												'active' => 0,
											)
										);

										$this->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
										$success = $this->Nonrespectsanctionep93->save() && $success;
									}
									break;
								case 3:
									if( $this->data['Relance']['contrat'] == 0 ) {
										$months = Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer3' );
										$dateecheance = strtotime( "{$relance['daterelance']['year']}{$relance['daterelance']['month']}{$relance['daterelance']['day']}" );
										$dateecheance = date( 'Y-m-d', strtotime( "+{$months} month", $dateecheance ) );

										$item = array(
											'Relancenonrespectsanctionep93' => array(
												'nonrespectsanctionep93_id' => $relance['nonrespectsanctionep93_id'],
												'numrelance' => $relance['numrelance'],
												'dateecheance' => $dateecheance,
												'daterelance' => $relance['daterelance']
											)
										);
										$this->Relancenonrespectsanctionep93->create( $item );
										$success = $this->Relancenonrespectsanctionep93->save() && $success;

										// Dossier EP
										$this->Nonrespectsanctionep93->Orientstruct->id = $relance['orientstruct_id'];
										$personne_id = $this->Nonrespectsanctionep93->Orientstruct->field( 'personne_id' );

										$dossierep = array(
											'Dossierep' => array(
												'personne_id' => $personne_id,
												'themeep' => 'nonrespectssanctionseps93',
											),
										);

										$this->Dossierep->create( $dossierep );
										$success = $this->Dossierep->save() && $success;

										// Nonrespectsanctionep93
										$nonrespectsanctionep93 = array(
											'Nonrespectsanctionep93' => array(
												'id' => $relance['nonrespectsanctionep93_id'],
												'dossierep_id' => $this->Dossierep->id,
												'active' => 0,
											)
										);

										$this->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
										$success = $this->Nonrespectsanctionep93->save() && $success;

									}
									break;
							}
						}

						$this->_setFlashResult( 'Save', $success );
						if( $success ) {
							unset( $this->data['Relancenonrespectsanctionep93'] );
							$this->Nonrespectsanctionep93->commit();
						}
						else {
							$this->Nonrespectsanctionep93->rollback();
						}
					}
				}

				/// Moteur de recherche
				$search = $this->data;
				unset( $search['Relancenonrespectsanctionep93'] );
				$search = Set::flatten( $search );
				$search = Set::filter( $search );


				$conditions = array();

				// FIXME: jointures (Dossier)
				foreach( $search as $field => $condition ) {
					if( in_array( $field, array( 'Personne.nom', 'Personne.prenom' ) ) ) {
						$conditions["UPPER({$field}) LIKE"] = $this->Relancenonrespectsanctionep93->wildcard( strtoupper( replace_accents( $condition ) ) );
					}
					else if( !in_array( $field, array( 'Relance.numrelance', 'Relance.contrat' ) ) ) {
						$conditions[$field] = $condition;
					}
				}

				// Personne orientée sans contrat
				// FIXME: dernière orientation
				// FIXME: et qui ne se trouve pas dans les EPs en cours de traitement
				// FIXME: sauvegarder le PDF

				// Relances pour personnes sans contrat
				/// FIXME: que les dernières orientations / les derniers contrats
				/// Exemple: 1ère relance de /orientsstructs/index/351610
				if( $search['Relance.contrat'] == 0 ) {
					switch( $search['Relance.numrelance'] ) {
						case 1:
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
							// FIXME ??
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
													ORDER BY relancesnonrespectssanctionseps93.numrelance DESC
													LIMIT 1
										) = '.( $search['Relance.numrelance'] - 1 ).'
							)';
							break;
					}

					$conditions['Orientstruct.statut_orient'] = 'Orienté';
					$conditions[] = 'Orientstruct.personne_id NOT IN (
										SELECT contratsinsertion.personne_id
											FROM contratsinsertion
											WHERE
												contratsinsertion.personne_id = Orientstruct.personne_id
												AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) >= Orientstruct.date_valid
											)';

					$queryData = array(
						'conditions' => $conditions,
						'contain' => array(
							'Personne' => array(
								'fields' => array(
									'Personne.nom',
									'Personne.prenom',
									'Personne.nir',
									'Personne.dtnai',
								),
								'Foyer' => array(
									'Dossier' => array(
										'fields' => array(
											'Dossier.matricule',
										)
									),
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01'
										),
										'Adresse' => array(
											'fields' => array(
												'Adresse.locaadr',
											)
										)
									)
								)
							),
							'Nonrespectsanctionep93' => array(
								'Relancenonrespectsanctionep93' => array(
									'fields' => array(
										'Relancenonrespectsanctionep93.daterelance'
									),
									'order' => array( 'Relancenonrespectsanctionep93.daterelance DESC' ),
									'limit' => 1
								),
								'fields' => array(
									'Nonrespectsanctionep93.id'
								),
								'order' => array( 'Nonrespectsanctionep93.created DESC' ),
								'limit' => 1
							)
						),
						'limit' => 10,
						'order' => array( 'Orientstruct.date_valid ASC' ),
					);

					$results = $this->Orientstruct->find( 'all', $queryData );

					if( !empty( $results ) ) {
						foreach( $results as $i => $result ) {
							$results[$i]['Orientstruct']['nbjours'] = round(
								( mktime() - strtotime( $result['Orientstruct']['date_valid'] ) ) / ( 60 * 60 * 24 )
							);
						}
					}
				}
				else {
					switch( $search['Relance.numrelance'] ) {
						case 1:
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
						case 3:
							// FIXME ??
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

					$conditions[] = 'Contratinsertion.datevalidation_ci IS NOT NULL';
					$conditions[] = 'Contratinsertion.personne_id NOT IN (
										SELECT contratsinsertion.personne_id
											FROM contratsinsertion
											WHERE
												contratsinsertion.personne_id = Contratinsertion.personne_id
												AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) >= Contratinsertion.df_ci
											)';

					$queryData = array(
						'conditions' => $conditions,
						'contain' => array(
							'Personne' => array(
								'fields' => array(
									'Personne.nom',
									'Personne.prenom',
									'Personne.nir',
									'Personne.dtnai',
								),
								'Foyer' => array(
									'Dossier' => array(
										'fields' => array(
											'Dossier.matricule',
										)
									),
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01'
										),
										'Adresse' => array(
											'fields' => array(
												'Adresse.locaadr',
											)
										)
									)
								)
							),
							'Nonrespectsanctionep93' => array(
								'Relancenonrespectsanctionep93' => array(
									'fields' => array(
										'Relancenonrespectsanctionep93.daterelance'
									),
									'order' => array( 'Relancenonrespectsanctionep93.daterelance DESC' ),
									'limit' => 1
								),
								'fields' => array(
									'Nonrespectsanctionep93.id'
								),
								'order' => array( 'Nonrespectsanctionep93.created DESC' ),
								'limit' => 1
							)
						),
						'limit' => 10,
						'order' => array( 'Contratinsertion.df_ci ASC' ),
					);

					$results = $this->Contratinsertion->find( 'all', $queryData );

					if( !empty( $results ) ) {
						foreach( $results as $i => $result ) {
							$results[$i]['Contratinsertion']['nbjours'] = round(
								( mktime() - strtotime( $result['Contratinsertion']['df_ci'] ) ) / ( 60 * 60 * 24 )
							);
						}
					}
				}

				$this->set( compact( 'results' ) );
			}
		}
	}
?>