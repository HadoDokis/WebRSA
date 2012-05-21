<?php
	class Cohortesnonorientes66Controller extends AppController
	{
		public $name = 'Cohortesnonorientes66';

		public $uses = array(
			'Cohortenonoriente66',
			'Personne',
			'Zonegeographique',
			'Dossier',
			'Option',
			'Canton'
		);

		public $helpers = array( 'Csv', 'Ajax', 'Default2', 'Locale', 'Search' );

		public $components = array(
			'Prg' => array(
				'actions' => array(
					'isemploi' => array( 'filter' => 'Search' ),
					'notisemploi' => array( 'filter' => 'Search' ),
					'oriente' => array( 'filter' => 'Search' )
				)
			)
		);


		/**
		*
		*/
		public function _setOptions() {
			$this->set( 'options',  $this->Personne->allEnumLists() );
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );


			$this->set( 'qual',  $this->Option->qual() );
			
			$etats = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $etats ) );
			
			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);
			$this->set( 'typesOrient', $this->Personne->Orientstruct->Typeorient->listOptions() );
			$this->set( 'structuresReferentes', $this->Personne->Orientstruct->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
		}



		/**
		*
		*/

		public function isemploi() {
			$this->_index( 'Nonoriente::isemploi' );
		}

		/**
		*
		*/

		public function notisemploi() {
			$this->_index( 'Nonoriente::notisemploi' );
		}

		/**
		*
		*/

		public function oriente() {
			$this->_index( 'Nonoriente::oriente' );
		}


		/**
		*
		*/

		protected function _index( $statutNonoriente = null ) {
			$this->assert( !empty( $statutNonoriente ), 'invalidParameter' );

			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {

				/**
				*
				* Sauvegarde
				*
				*/

				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Orientstruct'] ) ) {

					$valid = $this->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'only', 'atomic' => false ) );

					if( $valid ) {
						$this->Personne->Orientstruct->begin();

						$saved = $this->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							
							foreach( array_unique( Set::extract( $this->data, 'Orientstruct.{n}.dossier_id' ) ) as $dossier_id ) {
								$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
							}
// 							$this->log( var_export( $this->data['Orientstruct'], true ), LOG_DEBUG );
// 							$this->log( var_export( $this->data['Orientstruct'], true ), LOG_DEBUG );
							$this->Personne->Orientstruct->commit();
							unset( $this->data['Orientstruct'] );
							if( isset( $this->data['sessionKey'] ) ) {
								$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
							}
						}
						else {
							$this->Personne->Orientstruct->rollback();
						}
					}
				}

				/**
				*
				* Filtrage
				*
				*/

				if( ( $statutNonoriente == 'Nonoriente::isemploi' ) || ( $statutNonoriente == 'Nonoriente::notisemploi' ) || ( $statutNonoriente == 'Nonoriente::oriente' )  && !empty( $this->data ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$this->paginate = $this->Cohortenonoriente66->search( $statutNonoriente, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$this->paginate['limit'] = 10;
					$cohortesnonorientes66 = $this->paginate( 'Personne' );
					
					if( $statutNonoriente == 'Nonoriente::isemploi') {
						$typeorient_id = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
						$this->set( 'typeorient_id', $typeorient_id);
						
						
						$structurereferente_id = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
						$this->set( 'structurereferente_id', $structurereferente_id );
					}
					else{
					//FIXME
						$typeorient_id = 8;
					}

					$this->Dossier->commit();

					$this->set( 'cohortesnonorientes66', $cohortesnonorientes66 );

				}

			}

			$this->_setOptions();
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}


			switch( $statutNonoriente ) {
				case 'Nonoriente::isemploi':
					$this->render( $this->action, null, 'isemploi' );
					break;
				case 'Nonoriente::notisemploi':
					$this->render( $this->action, null, 'notisemploi' );
					break;
				case 'Nonoriente::oriente':
					$this->render( $this->action, null, 'oriente' );
					break;
			}
		}
	}
?>