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
						$success = $this->Relancenonrespectsanctionep93->saveCohorte($newData, $this->data);
						
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
				$results = $this->Relancenonrespectsanctionep93->search($this->data);
// debug( $results );
				$this->set( compact( 'results' ) );
				
				$searchError = false;
				if( $this->data['Relance']['contrat'] == 0 ) {
					if ( ( @$this->data['Relance']['compare0'] == '<' && @$this->data['Relance']['nbjours0'] <= Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer'.$this->data['Relance']['numrelance'] ) ) || ( @$this->data['Relance']['compare0'] == '<=' && @$this->data['Relance']['nbjours0'] < Configure::read( 'Nonrespectsanctionep93.relanceOrientstructCer'.$this->data['Relance']['numrelance'] ) ) )
						$searchError = true;
				}
				else {
					if ( ( @$this->data['Relance']['compare1'] == '<' && @$this->data['Relance']['nbjours1'] <= Configure::read( 'Nonrespectsanctionep93.relanceCerCer'.$this->data['Relance']['numrelance'] ) ) || ( @$this->data['Relance']['compare1'] == '<=' && @$this->data['Relance']['nbjours1'] < Configure::read( 'Nonrespectsanctionep93.relanceCerCer'.$this->data['Relance']['numrelance'] ) ) )
						$searchError = true;
				}
				
				if ($searchError)
					$this->Session->setFlash('Vos critères de recherche entrent en contradiction avec les critères de base', 'flash/error');
			}

			$this->_setOptions();
		}
	}
?>