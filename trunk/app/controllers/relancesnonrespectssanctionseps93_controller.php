<?php

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

		public function index( $personne_id ) {
			$erreurs = $this->Relancenonrespectsanctionep93->erreursPossibiliteAjout( $personne_id );

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
							'Contratinsertion' => array(
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

			$this->set( compact( 'relances', 'erreurs' ) );
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

						// Relances non respect orientation
						$success = $this->Relancenonrespectsanctionep93->saveCohorte( $newData, $this->data );

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
				$results = $this->Relancenonrespectsanctionep93->search( $this->data );
				$this->set( compact( 'results' ) );

				if( $this->Relancenonrespectsanctionep93->checkCompareError( $this->data ) == true ) {
					$this->Session->setFlash( 'Vos critères de recherche entrent en contradiction avec les critères de base', 'flash/error' );
				}
			}

			$this->_setOptions();
		}

		/**
		*
		*/

		public function add( $personne_id ) {
			$erreurs = $this->Relancenonrespectsanctionep93->erreursPossibiliteAjout( $personne_id );
			if( !empty( $erreurs ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}
			else {
				$orientstruct = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.statut_orient' => 'Orienté',
							'Orientstruct.date_valid IS NOT NULL',
							'Orientstruct.date_impression IS NOT NULL',
						),
						'order' => array( 'Orientstruct.date_impression DESC' ),
						'contain' => false
					)
				);
// 				debug( $orientstruct );

				$contratinsertion = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->Contratinsertion->find(
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
// 				debug( $contratinsertion );

				if( ( empty( $orientstruct ) && !empty( $contratinsertion ) ) || ( strtotime( $orientstruct['Orientstruct']['date_impression'] ) < strtotime( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) ) ) {
					$orientstruct_id = null;
					$contratinsertion_id = $contratinsertion['Contratinsertion']['id'];
					$origine = 'contratinsertion';
				}
				else {
					$orientstruct_id = $orientstruct['Orientstruct']['id'];
					$contratinsertion_id = null;
					$origine = 'orientstruct';
				}

				// Calcul du rang de la relance
				$numrelance_pcd = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->find(
					'count',
					array(
						'conditions' => array(
							'Nonrespectsanctionep93.contratinsertion_id' => $contratinsertion_id,
							'Nonrespectsanctionep93.orientstruct_id' => $orientstruct_id,
							'Nonrespectsanctionep93.origine' => $origine,
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
					)
				);

				// Calcul du rang de passage
				$rgpassage_pcd = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->find(
					'count',
					array(
						'conditions' => array(
							'Nonrespectsanctionep93.contratinsertion_id' => $contratinsertion_id,
							'Nonrespectsanctionep93.orientstruct_id' => $orientstruct_id,
							'Nonrespectsanctionep93.origine' => $origine,
							'Nonrespectsanctionep93.dossierep_id IS NOT NULL',
							'Nonrespectsanctionep93.active' => 0,
						)
					)
				);

				$this->set( compact( 'origine', 'contratinsertion_id' ) );

				if( !empty( $this->data ) ) {
// 					debug( $this->data );
					$this->Relancenonrespectsanctionep93->begin();
					$this->data['Nonrespectsanctionep93']['orientstruct_id'] = $orientstruct_id;
					$this->data['Nonrespectsanctionep93']['contratinsertion_id'] = $contratinsertion_id;
					$this->data['Nonrespectsanctionep93']['origine'] = $origine;
					$this->data['Relancenonrespectsanctionep93']['numrelance'] = ( $numrelance_pcd + 1 );
					$this->data['Nonrespectsanctionep93']['rgpassage'] = ( $rgpassage_pcd + 1 );
					$this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->create( $this->data );
					$success = $this->Relancenonrespectsanctionep93->Nonrespectsanctionep93->save();
					$this->_setFlashResult( 'Save', $success );
					$this->Relancenonrespectsanctionep93->rollback();
				}
			}

			$this->set( 'personne_id', $personne_id );
		}
	}
?>