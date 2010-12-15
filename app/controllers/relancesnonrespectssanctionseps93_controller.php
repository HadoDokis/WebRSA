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

		public function index() {
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
									$dateecheance = strtotime( "{$relance['daterelance']['year']}{$relance['daterelance']['month']}{$relance['daterelance']['day']}" );
									$dateecheance = date( 'Y-m-d', strtotime( '+2 month', $dateecheance ) );// FIXME: paramétrage

									$item = array(
										'Nonrespectsanctionep93' => array(
											'orientstruct_id' => $relance['orientstruct_id'],
											'active' => $this->data['Relance']['numrelance'],
										),
										'Relancenonrespectsanctionep93' => array(
											array(
												'numrelance' => $relance['numrelance'],
												'dateecheance' => $dateecheance,
												'daterelance' => $relance['daterelance']
											)
										)
									);
									$success = $this->Nonrespectsanctionep93->saveAll( $item, array( 'atomic' => false ) ) && $success;
									break;
								case 2:
									$dateecheance = strtotime( "{$relance['daterelance']['year']}{$relance['daterelance']['month']}{$relance['daterelance']['day']}" );
									$dateecheance = date( 'Y-m-d', strtotime( '+2 month', $dateecheance ) );// FIXME: paramétrage

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
									break;
								case 3:
									$dateecheance = strtotime( "{$relance['daterelance']['year']}{$relance['daterelance']['month']}{$relance['daterelance']['day']}" );
									$dateecheance = date( 'Y-m-d', strtotime( '+2 month', $dateecheance ) );// FIXME: paramétrage

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
									$this->Orientstruct->id = $relance['orientstruct_id'];
									$dossierep = array(
										'Dossierep' => array(
											'personne_id' => $this->Orientstruct->field( 'personne_id' ),
											'themeep' => 'nonrespectssanctionseps93', // FIXME ?
										),
									);

									$this->Dossierep->create( $dossierep );
									$success = $this->Dossierep->save() && $success;

									// Nonrespectsanctionep93
									$this->Orientstruct->id = $relance['orientstruct_id'];
									$nonrespectsanctionep93 = array(
										'Nonrespectsanctionep93' => array(
											'id' => $relance['nonrespectsanctionep93_id'],
											'dossierep_id' => $this->Dossierep->id,
											'active' => 0,
										)
									);

									$this->Nonrespectsanctionep93->create( $nonrespectsanctionep93 );
									$success = $this->Nonrespectsanctionep93->save() && $success;
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
						$conditions["UPPER({$field}) LIKE"] = str_replace( '*', '%', $condition );
					}
					else if( $field != 'Relance.numrelance' ) {
						$conditions[$field] = $condition;
					}
				}

				// Personne orientée sans contrat
				// FIXME: dernière orientation
				// FIXME: et qui ne se trouve pas dans les EPs en cours de traitement
				// FIXME: sauvegarder le PDF

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
				// AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) <= ( Orientstruct.date_valid + INTERVAL \'2 mons\' )

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

				$this->set( compact( 'results' ) );
			}
		}
	}
?>