<?php
	App::import( 'Sanitize' );

	class Cohortenonoriente66 extends AppModel
	{
		public $name = 'Cohortenonoriente66';

		public $useTable = false;

		public $actsAs = array(
			'Conditionnable',
			'Gedooo.Gedooo'
		);

		/**
		*
		*/

		public function search( $statutNonoriente, $mesCodesInsee, $filtre_zone_geo, $criteresnonorientes, $lockedDossiers ) {
			$Personne = ClassRegistry::init( 'Personne' );
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			/// Conditions de base
			$conditions = array();
			if( $statutNonoriente != 'Nonoriente::oriente' ) {
				$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" AND orientsstructs.statut_orient = \'Orienté\' ) = 0';
			}
			$conditions[] =  array(
				'OR' => array(
					'Historiqueetatpe.id IS NULL',
					'Historiqueetatpe.id IN ( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
				)
			);

			if( !empty( $statutNonoriente ) ) {
				if( $statutNonoriente == 'Nonoriente::isemploi' ) {
					// FIXME: Historiqueetatpe::sqDerniere + historiqueetatspe.etat = \'inscription\'
					$conditions['Historiqueetatpe.etat'] = 'inscription';
				}
				else if( $statutNonoriente == 'Nonoriente::notisemploiaimprimer' ) {
					$conditions[] = 'Personne.id NOT IN (
						SELECT nonorientes66.personne_id
							FROM nonorientes66
								WHERE
									nonorientes66.personne_id = Personne.id
						)';
						
					$conditions['NOT'] = array( 'Historiqueetatpe.etat' => 'inscription' );
				}
				else if( $statutNonoriente == 'Nonoriente::notisemploi' ) {
					$conditions[] = 'Personne.id IN (
						SELECT nonorientes66.personne_id
							FROM nonorientes66
								WHERE
									nonorientes66.personne_id = Personne.id
						)';
						
					$conditions['NOT'] = array( 'Historiqueetatpe.etat' => 'inscription' );
				}
				else if( $statutNonoriente == 'Nonoriente::oriente' ) {
					$conditions[] = 'Personne.id IN (
						SELECT nonorientes66.personne_id
							FROM nonorientes66
								WHERE
									nonorientes66.personne_id = Personne.id
									AND nonorientes66.orientstruct_id IS NOT NULL
						)';
				}
			}

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criteresnonorientes['Search'], $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsDossier( $conditions, $criteresnonorientes['Search'] );
			$conditions = $this->conditionsPersonne( $conditions, $criteresnonorientes['Search'] );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresnonorientes['Search'] );


			/// Dossiers lockés FIXME
			if( !empty( $lockedDossiers ) && ( $statutNonoriente != 'Nonoriente::notisemploiaimprimer' ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}


			// Conditions pour les jointures
			$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
			$conditions['Calculdroitrsa.toppersdrodevorsa'] = 1;
			$conditions['Situationdossierrsa.etatdosrsa'] = $Personne->Orientstruct->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert();
			$conditions[] = array(
				'OR' => array(
					'Adressefoyer.id IS NULL',
					'Adressefoyer.id IN ( '
						.$Personne->Foyer->Adressefoyer->sqDerniereRgadr01('Adressefoyer.foyer_id')
					.' )'
				)
			);
			$conditions[] = array(
				'OR' => array(
					'Informationpe.id IS NULL',
					'Informationpe.id IN ( '
						.$Informationpe->sqDerniere('Personne')
					.' )'
				)
			);

			$query = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Foyer->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->Dossier->Situationdossierrsa->fields(),
					$Personne->Orientstruct->fields(),
					$Personne->Nonoriente66->fields(),
					array(
						$Personne->Foyer->sqVirtualField( 'enerreur' ),
						'Historiqueetatpe.id'
					)
				),
				'joins' => array(
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->join( 'Nonoriente66', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
					$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
				),
				'contain' => false,
				'conditions' => $conditions,
				'order' => array( 'Personne.id ASC' )
			);

			return $query;
		}

		/**
		 * Retourne les données nécessaires à l'impression du questionnaire pour les non orientés du CG66
		 * Les données contiennent les informations de la personne
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf() {
			$typesvoies = ClassRegistry::init( 'Option' )->typevoie();
			$Personne = ClassRegistry::init( 'Personne' );

			$querydata = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->fields(),
					$Personne->Foyer->Dossier->fields()
				),
				'joins' => array(
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				),
				'conditions' => array(
					'Adressefoyer.id IN ( '.$Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
				),
				'contain' => false
			);
			return $querydata;
		}

		/**
		 * Retourne le chemin vers le modèle odt (questionnaire)utilisé pour les non orientés du CG66
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return 'Orientation/questionnaireorientation66.odt'; // INFO courrier 1
		}

		/**
		 * Fonction permettant d'enregistrer la date du jour de l'impression du courrier envoyé
		 * aux allocataires ne possédant pas encore d'orientation
		 * @param array $data
		 * @return array
		 *
		 */
		protected function _saveImpression( $data ) {
			$success = true;
			$Nonoriente66 = ClassRegistry::init( 'Nonoriente66' );
			if( !empty( $data ) ) {
			
				$nonoriente66 = array(
					'Nonoriente66' => array(
						'personne_id' => $data['Personne']['id'],
						'dateimpression' => date( 'Y-m-d' ),
						'orientstruct_id' => null,
						'historiqueetatpe_id' => null,
						'origine' => 'notisemploi',
						'user_id' => $data['User']['id']
					)
				);

				$Nonoriente66->create( $nonoriente66 );
				$success = $Nonoriente66->save() && $success;
			}
			else {
				return false;
			}
			
			return $success;
		}
		
		
		/**
		 * Retourne le PDF par défaut généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo
		 *
		 * @param type $id Id de la personne
		 * @param type $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$Option = ClassRegistry::init( 'Option' );
			$Personne = ClassRegistry::init( 'Personne' );

			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				)
			);

			$querydata = $this->getDataForPdf();

			$querydata = Set::merge(
				$querydata,
				array(
					'conditions' => array(
						'Personne.id' => $id
					)
				)
			);
			$personne = $Personne->find( 'first', $querydata );

			/// Récupération de l'utilisateur
			$user = ClassRegistry::init( 'User' )->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$personne['User'] = $user['User'];

			if( empty( $personne ) ) {
				$this->cakeError( 'error404' );
			}
// debug($personne);
// die();

			if( !empty( $personne ) ){
				$this->_saveImpression( $personne );
			}



			return $this->ged(
				$personne,
				$this->modeleOdt( $personne ),
				false,
				$options
			);
		}

		/**
		 * Retourne le PDF concernant le questionnaire de la personne non orientée
		 *
		 * @param string $search
		 * @param integer $user_id
		 * @return string
		 */
		public function getDefaultCohortePdf( $statutNonoriente, $mesCodesInsee, $filtre_zone_geo, $user_id, $search, $page ) {
// 			$querydata = $this->getDataForPdf();

			$querydata = $this->search( $statutNonoriente, $mesCodesInsee, $filtre_zone_geo, $search, null );

			$querydata['limit'] = 100;
			$querydata['offset'] = ( ( $page ) <= 1 ? 0 : ( $querydata['limit'] * ( $page - 1 ) ) );

			// Jointure bizarre sur la table users pour récupérer l'utilisateur connecté
			$User = ClassRegistry::init( 'User' );
			$dbo = $User->getDataSource( $User->useDbConfig );
			$querydata['fields'] = Set::merge( $querydata['fields'], $User->fields() );
			$querydata['joins'][] = array(
				'table' => $dbo->fullTableName( $User ),
				'alias' => $User->alias,
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'User.id' => $user_id
				)
			);


			$Personne = ClassRegistry::init( 'Personne' );
			$nonorientes66 = $Personne->find( 'all', $querydata );


			$modeleodt = 'Orientation/questionnaireorientation66.odt';

			// Traductions
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				)
			);

			return $this->ged(
				array( 'cohorte' => $nonorientes66 ),
				$modeleodt,
				true,
				$options
			);
		}

	}
?>