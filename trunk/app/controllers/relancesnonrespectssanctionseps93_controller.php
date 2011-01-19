<?php
	App::import( 'Sanitize' );

	class Relancesnonrespectssanctionseps93Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		public $uses = array( 'Relancenonrespectsanctionep93', 'Nonrespectsanctionep93', 'Orientstruct', 'Contratinsertion', 'Dossierep', 'Dossier' );

		/**
		*
		*/

		protected function _setOptions() {
			/// Mise en cache (session) de la liste des codes Insee pour les selects
			/// TODO: Une fonction ?
			/// TODO: Voir où l'utiliser ailleurs
			if( !$this->Session->check( 'Cache.mesCodesInsee' ) ) {
				if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Personne->Cui->Structurereferente->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				}
				else {
					$listeCodesInseeLocalites = $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee();
				}
				$this->Session->write( 'Cache.mesCodesInsee', $listeCodesInseeLocalites );
			}
			else {
				$listeCodesInseeLocalites = $this->Session->read( 'Cache.mesCodesInsee' );
			}

			$options = array(
				'Adresse' => array( 'numcomptt' => $listeCodesInseeLocalites ),
				'Serviceinstructeur' => array( 'id' => $this->Orientstruct->Serviceinstructeur->find( 'list' ) )
			);

			$this->set( compact( 'options' ) );
		}

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
// debug( $search );
				// FIXME: jointures (Dossier)
				foreach( $search as $field => $condition ) {
					if( in_array( $field, array( 'Personne.nom', 'Personne.prenom' ) ) ) {
						$conditions["UPPER({$field}) LIKE"] = $this->Relancenonrespectsanctionep93->wildcard( strtoupper( replace_accents( $condition ) ) );
					}
					else if( $field == 'Adresse.numcomptt' && !empty( $condition ) ) {
						// FIXME: subquery / joins
						$conditions[] = 'Personne.foyer_id IN (
											SELECT
													foyer_id
												FROM adressesfoyers
													INNER JOIN adresses ON (
														adressesfoyers.adresse_id = adresses.id
														AND adressesfoyers.foyer_id = "Personne"."foyer_id"
														AND adressesfoyers.rgadr = \'01\'
													)
												WHERE adresses.numcomptt = \''.Sanitize::paranoid( $condition ).'\'
										)';
					}
					else if( $field == 'Serviceinstructeur.id' && !empty( $condition ) ) {
						// FIXME: subquery / joins
						$conditions[] = 'Personne.foyer_id IN (
											SELECT
													suivisinstruction.dossier_id
												FROM suivisinstruction
													INNER JOIN servicesinstructeurs ON (
														suivisinstruction.numdepins = servicesinstructeurs.numdepins
														AND suivisinstruction.typeserins = servicesinstructeurs.typeserins
														AND suivisinstruction.numcomins = servicesinstructeurs.numcomins
														AND suivisinstruction.numagrins = servicesinstructeurs.numagrins
													)
													INNER JOIN foyers ON (
														suivisinstruction.dossier_id = foyers.dossier_id
														AND foyers.id = "Personne"."foyer_id"
													)
												WHERE servicesinstructeurs.id = \''.Sanitize::paranoid( $condition ).'\' )';
					}
					else if( $field == 'Dossier.matricule' && !empty( $condition ) ) {
						// FIXME: subquery / joins
						$conditions[] = 'Personne.foyer_id IN (
											SELECT
													foyers.id
												FROM dossiers
													INNER JOIN foyers ON (
														foyers.dossier_id = dossiers.id
														AND foyers.id = "Personne"."foyer_id"
													)
												WHERE dossiers.matricule = \''.Sanitize::paranoid( $condition ).'\' )';
					}
					else if( ( $field == 'Dossiercaf.nomtitulaire' || $field == 'Dossiercaf.prenomtitulaire' ) && !empty( $condition ) ) {
						// FIXME: subquery / joins
						$conditions[] = 'Personne.id IN (
											SELECT
													personne_id
												FROM dossierscaf
												WHERE
													dossierscaf.personne_id = "Personne"."id"
													AND dossierscaf.toprespdos = true
													AND (
														dossierscaf.dfratdos IS NULL
														OR dossierscaf.dfratdos >= NOW()
													)
													AND dossierscaf.ddratdos <= NOW()
										)';
						$field = preg_replace( '/^Dossiercaf\.(.*)titulaire$/', '\1', $field );
						$conditions["UPPER({$field}) LIKE"] = $this->Relancenonrespectsanctionep93->wildcard( strtoupper( replace_accents( $condition ) ) );
					}
					else if( !in_array( $field, array( 'Relance.numrelance', 'Relance.contrat', 'Relance.compare0', 'Relance.compare1', 'Relance.nbjours0', 'Relance.nbjours1' ) ) ) {
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
					// Condition opérateur / nombre de jours ?
					$conditionOrientstructDateValid = '';
					if( !empty( $search['Relance.compare0'] ) && !empty( $search['Relance.nbjours0'] ) ) {
						$conditionOrientstructDateValid = "AND AGE(orientsstructs.date_valid) >= '0 days' AND AGE(orientsstructs.date_valid) {$search['Relance.compare0']} interval '{$search['Relance.nbjours0']} days'";
					}
					else {
						$conditionOrientstructDateValid = 'AND orientsstructs.date_valid < NOW() + \''.Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer1' ).' mons\'';
					}

					switch( $search['Relance.numrelance'] ) {
						case 1:
							// Dernière Orientstruct
							$conditions[] = 'Orientstruct.id IN (
								SELECT orientsstructs.id
									FROM orientsstructs
									WHERE
										orientsstructs.date_valid IS NOT NULL
										AND orientsstructs.personne_id = Personne.id
										'.$conditionOrientstructDateValid.'
									ORDER by orientsstructs.date_valid DESC
									LIMIT 1
							)';

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
							// Dernièr contrat
							$conditions[] = 'Contratinsertion.id IN (
								SELECT contratsinsertion.id
									FROM contratsinsertion
									WHERE
										contratsinsertion.datevalidation_ci IS NOT NULL
										AND contratsinsertion.datevalidation_ci < NOW() + \''.Configure::read( 'Nonrespectsanctionep93.relanceCerCer1' ).' mons\'
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

			$this->_setOptions();
		}
	}
?>