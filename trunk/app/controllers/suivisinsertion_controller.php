<?php
	class SuivisinsertionController extends AppController
	{

		public $name = 'Suivisinsertion';
		public $uses = array( 'Foyer', 'Dossier', 'Suiviinstruction', 'Contratinsertion', 'Orientstruct', 'Structurereferente', 'Typocontrat', 'Typeorient', 'Actioninsertion', 'Option', 'Rendezvous', 'Aidedirecte', 'Cui' );

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'decision_ci', $this->Option->decision_ci() );
			$this->set( 'relance', $this->Dossier->Foyer->Personne->Orientstruct->Nonrespectsanctionep93->allEnumLists() );
			$this->set( 'dossierep', $this->Dossier->Foyer->Personne->Dossierep->allEnumLists() );
			$this->set( 'typeserins', $this->Option->typeserins() );

		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function index( $dossier_id = null ){
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$details = array();

			$tDossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $dossier_id
					),
					'contain' => false
				)
			);

			$this->assert( !empty( $tDossier ), 'invalidParameter' );
			$details = Set::merge( $details, $tDossier );

			$tSuiviinstruction = $this->Suiviinstruction->find(
				'first',
				array(
					'conditions' => array(
						'Suiviinstruction.dossier_id' => $dossier_id
					),
					'joins' => array(
						array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
						)
					),
					'order' => 'Suiviinstruction.date_etat_instruction DESC'
				)
			);
			$details = Set::merge( $details, $tSuiviinstruction );

			$tFoyer = $this->Dossier->Foyer->findByDossierId( $dossier_id, null, null, -1 );
			$this->assert( !empty( $tFoyer ), 'invalidParameter' );
			$details = Set::merge( $details, $tFoyer );

			// Récupération du services instructeur lié au contrat
			$user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
			$this->assert( !empty( $user ), 'error500' ); // FIXME
			$details = Set::merge( $details, $user );

			/**
				Personnes
			*/
			$bindPrestation = $this->Foyer->Personne->hasOne['Prestation'];
			$this->Foyer->Personne->unbindModelAll();
			$this->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Dsp', 'Prestation' => $bindPrestation ) ) );
			$personnesFoyer = $this->Foyer->Personne->find(
				'all',
				array(
					'conditions' => array(
						'Personne.foyer_id' => $tFoyer['Foyer']['id'],
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'recursive' => 0
				)
			);

			$this->set(compact('personnesFoyer'));

			$options = array(
				'Passagecommissionep' => $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->allEnumLists()
			);
			$roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
			foreach( $roles as $index => $role ) {
				// Contrat insertion lié à la personne
				$tContratinsertion = $this->Contratinsertion->find(
					'first',
					array(
						'conditions' => array( 'Contratinsertion.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'recursive' => -1,
						'order' => array( 'Contratinsertion.rg_ci DESC' )
					)
				);
				$personnesFoyer[$index]['Contratinsertion'] = $tContratinsertion['Contratinsertion'];

				// Contrat insertion lié à la personne
				$tCui = $this->Cui->find(
					'first',
					array(
						'conditions' => array( 'Cui.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'recursive' => -1,
						'order' => array( 'Cui.datecontrat DESC' )
					)
				);
				$personnesFoyer[$index]['Cui'] = $tCui['Cui'];

				// Actions insertions engagées par la personne
				$tActioninsertion = $this->Actioninsertion->find(
					'all',
					array(
						'conditions' => array( 'Actioninsertion.contratinsertion_id' => $personnesFoyer[$index]['Contratinsertion']['id'] ),
						'recursive' => -1,
						'order' => 'Actioninsertion.dd_action DESC'
					)
				);
				$personnesFoyer[$index]['Actioninsertion'] = Set::extract( $tActioninsertion, '/Actioninsertion' );

				// Premier Rendez-vous
				$tRendezvous = $this->Rendezvous->find(
					'first',
					array(
						'conditions' => array(
							'Rendezvous.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Rendezvous.daterdv ASC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Rendezvous']['premier'] = $tRendezvous['Rendezvous'];

				// Dernier Rendez-vous
				$tRendezvous = $this->Rendezvous->find(
					'first',
					array(
						'conditions' => array(
							'Rendezvous.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Rendezvous.daterdv DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Rendezvous']['dernier'] = $tRendezvous['Rendezvous'];

				// Première Orientation
				$tOrientstruct = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Orientstruct.date_valid ASC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Orientstruct']['premiere'] = $tOrientstruct['Orientstruct'];

				// Dernière Orientation
				$tOrientstruct = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Orientstruct.date_valid DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Orientstruct']['derniere'] = $tOrientstruct['Orientstruct'];

				// Dernier passage effectif (lié à un passagecommissionep)
				$tdossierEp = $this->Dossier->Foyer->Personne->Dossierep->find(
					'first',
					array(
						'fields' => array(
							'Dossierep.themeep',
							'Commissionep.dateseance',
							'Passagecommissionep.id',
							'Passagecommissionep.etatdossierep',
						),
						'joins' => array(
							array(
								'table'      => 'passagescommissionseps',
								'alias'      => 'Passagecommissionep',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Passagecommissionep.dossierep_id = Dossierep.id' )
							),
							array(
								'table'      => 'commissionseps',
								'alias'      => 'Commissionep',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array( 'Passagecommissionep.commissionep_id = Commissionep.id' )
							),
						),
						'conditions' => array(
							'Dossierep.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => array(
							'Commissionep.dateseance DESC'
						),
						'contain' => false,
					)
				);

				$decisionEP = array();
				if( !empty( $tdossierEp ) ) {
					$themeEP = Set::classicExtract( $tdossierEp, 'Dossierep.themeep' );
					$modelTheme = Inflector::classify( Inflector::singularize( $themeEP ) );
					$modelDecision = 'Decision'.Inflector::singularize( $themeEP );

					if( !isset( $options[$modelDecision] ) ) {
						$options[$modelDecision] = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->allEnumLists();
					}

					$decisionEP = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->find(
						'all',
						array(
							'conditions' => array(
								"{$modelDecision}.passagecommissionep_id" => $tdossierEp['Passagecommissionep']['id']
							),
							'contain' => false
						)
					);
				}

				$personnesFoyer[$index]['Dossierep']['derniere'] = Set::merge( $tdossierEp, $decisionEP );

				if( Configure::read( 'Cg.departement' ) == 93 ) {
					// Dernière relance effective
					$tRelance = $this->Dossier->Foyer->Personne->Contratinsertion->Nonrespectsanctionep93->Relancenonrespectsanctionep93->find(
						'first',
						array(
							'fields' => array(
								'Nonrespectsanctionep93.created',
								'Nonrespectsanctionep93.origine',
								'Nonrespectsanctionep93.rgpassage',
								'Relancenonrespectsanctionep93.daterelance',
								'Relancenonrespectsanctionep93.numrelance'
							),
							'contain' => false,
							'joins' => array(
								array(
									'table'      => 'nonrespectssanctionseps93',
									'alias'      => 'Nonrespectsanctionep93',
									'type'       => 'LEFT OUTER',
									'foreignKey' => false,
									'conditions' => array( 'Nonrespectsanctionep93.id = Relancenonrespectsanctionep93.nonrespectsanctionep93_id' )
								),
								array(
									'table'      => 'orientsstructs',
									'alias'      => 'Orientstruct',
									'type'       => 'LEFT OUTER',
									'foreignKey' => false,
									'conditions' => array( 'Orientstruct.id = Nonrespectsanctionep93.orientstruct_id' )
								),
								array(
									'table'      => 'contratsinsertion',
									'alias'      => 'Contratinsertion',
									'type'       => 'LEFT OUTER',
									'foreignKey' => false,
									'conditions' => array( 'Contratinsertion.id = Nonrespectsanctionep93.contratinsertion_id' )
								),
								array(
									'table'      => 'personnes',
									'alias'      => 'Personne',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array(
										'OR' => array(
											array(
												'Contratinsertion.personne_id = Personne.id'
											),
											array(
												'Orientstruct.personne_id = Personne.id'
											)
										)
									)
								)
							),
							'conditions' => array(
								'OR' => array(
									array(
										'Nonrespectsanctionep93.orientstruct_id IN ( '.$this->Dossier->Foyer->Personne->Orientstruct->sq( array( 'fields' => array( 'Orientstruct.id' ), 'conditions' => array( 'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id'] ) ) ).' )'
									),
									array(
										'Nonrespectsanctionep93.contratinsertion_id IN ( '.$this->Dossier->Foyer->Personne->Contratinsertion->sq( array( 'fields' => array( 'id' ), 'conditions' => array( 'personne_id' => $personnesFoyer[$index]['Personne']['id'] ) ) ).' )'
									)
								)
							),
							'order' => "Relancenonrespectsanctionep93.daterelance DESC",
						)
					);
					$personnesFoyer[$index]['Nonrespectsanctionep93']['derniere'] = $tRelance;
				}

				$details[$role] = $personnesFoyer[$index];
			}
			// Structure référentes
			$structuresreferentes = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$typesorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
			$typoscontrat = $this->Typocontrat->find( 'list', array( 'fields' => array( 'id', 'lib_typo' ) ) );
			$this->set( 'structuresreferentes', $structuresreferentes );
			$this->set( 'typesorient', $typesorient );
			$this->set( 'typoscontrat', $typoscontrat );

			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'details', $details );
			$this->set( 'options', $options );
		}
	}
?>