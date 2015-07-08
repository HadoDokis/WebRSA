<?php
	/**
	 * Code source de la classe WebrsaRechercheDossier.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheDossier ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheDossier extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheDossier';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Dossiers.search.fields',
			'Dossiers.search.innerTable',
			'Dossiers.exportcsv'
		);

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$departement = (int)Configure::read( 'Cg.departement' );

				$Allocataire = ClassRegistry::init( 'Allocataire' );
				$Dossier = ClassRegistry::init( 'Dossier' );

				$types += array(
					'Calculdroitrsa' => 'LEFT OUTER',
					'Foyer' => 'INNER',
					'Prestation' => $departement == 66 ? 'LEFT OUTER' : 'INNER',
					'Personne' => 'LEFT OUTER',
					'Adressefoyer' => 'LEFT OUTER',
					'Dossier' => 'INNER',
					'Adresse' => 'LEFT OUTER',
					'Situationdossierrsa' => 'INNER',
					'Detaildroitrsa' => 'LEFT OUTER'
				);
				$query = $Allocataire->searchQuery( $types, 'Dossier' );

				// Le CD 66 veut pouvoir trouver les allocataires et les personnes sans prestation
				if( $departement === 66 ) {
					$index = null;
					foreach( $query['joins'] as $i => $join ) {
						if( $join['alias'] === 'Prestation' ) {
							$index = $i;
						}
					}
					unset( $query['conditions']['Prestation.rolepers'] );
					$query['joins'][$index] = $Dossier->Foyer->Personne->join(
						'Prestation',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Prestation.rolepers' => array( 'DEM', 'CJT' )
							)
						)
					);
				}

				$query['joins'] = array_values( $query['joins'] );

				$query['order'] = array( 'Personne.nom ASC' );

				// Ajout des spécificités du moteur de recherche
				$query['fields'] = array_merge(
					array( 0 => 'Dossier.id' ),
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Dossier->Foyer->Personne->Dsp,
							$Dossier->Foyer->Personne->DspRev,
							$Dossier->Foyer->Personne->Orientstruct,
							$Dossier->Foyer->Personne->Orientstruct->Structurereferente,
							$Dossier->Foyer->Personne->Orientstruct->Typeorient
						)
					)
				);

				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$Dossier->Foyer->Personne->join(
							'Dsp',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Dsp.id IN ( '.$Dossier->Foyer->Personne->Dsp->sqDerniereDsp().' )'
								)
							)
						),
						$Dossier->Foyer->Personne->join(
							'DspRev',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'DspRev.id IN ( '.$Dossier->Foyer->Personne->DspRev->sqDerniere().' )'
								)
							)
						),
						$Dossier->Foyer->Personne->join(
							'Orientstruct',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Orientstruct.statut_orient' => 'Orienté',
									'Orientstruct.id IN ( '.$Dossier->Foyer->Personne->Orientstruct->sqDerniere().' )'
								)
							)
						),
						$Dossier->Foyer->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$Dossier->Foyer->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
					)
				);

				// Début des jointures supplémentaires par département

				// CD 58
				if( $departement == 58 ) {
					// Travailleur social chargé de l'évaluation: "Nom du chargé de
					// l'évaluation" lorsque l'on crée une orientation
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields(
							array(
								$Dossier->Foyer->Personne->Dossiercov58,
								$Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58,
								$Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58->Referentorientant,
								$Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58->Structureorientante
							)
						)
					);
					$query['joins'][] = $Dossier->Foyer->Personne->join(
						'Dossiercov58',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array( 'Dossiercov58.themecov58' => 'proposorientationscovs58' )
						)
					);
					$query['joins'][] = $Dossier->Foyer->Personne->Dossiercov58->join( 'Propoorientationcov58', array( 'type' => 'LEFT OUTER' ) );
					$query['joins'][] = $Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58->join( 'Referentorientant', array( 'type' => 'LEFT OUTER' ) );
					$query['joins'][] = $Dossier->Foyer->Personne->Dossiercov58->Propoorientationcov58->join( 'Structureorientante', array( 'type' => 'LEFT OUTER' ) );

					// Dernière activité
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields(
							array(
								$Dossier->Foyer->Personne->Activite
							)
						)
					);

					$query['joins'][] = $Dossier->Foyer->Personne->join(
						'Activite',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Activite.id IN ( '.$Dossier->Foyer->Personne->Activite->sqDerniere().' )'
							),
						)
					);

					$query = $Dossier->Foyer->Personne->completeQueryVfEtapeDossierOrientation58( $query );
				}

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$Dossier = ClassRegistry::init( 'Dossier' );

			$query = $Allocataire->searchConditions( $query, $search );

			// Possède...
			if( $Dossier->Foyer->Personne->Behaviors->attached( 'LinkedRecords' ) === false ) {
				$Dossier->Foyer->Personne->Behaviors->attach( 'LinkedRecords' );
			}
			$linkedModelNames = array( 'Cui', 'Orientstruct', 'Contratinsertion', 'Dsp' );
			foreach( $linkedModelNames as $linkedModelName ) {
				$fieldName = 'has_'.Inflector::underscore( $linkedModelName );
				$exists = (string)Hash::get( $search, "Personne.{$fieldName}" );
				if( in_array( $exists, array( '0', '1' ), true ) ) {
					$sql = $Dossier->Foyer->Personne->linkedRecordVirtualField( $linkedModelName );
					$query['conditions'][] = $exists ? $sql : 'NOT ' . $sql;
				}
			}

			// Condition sur la nature du logement
			$natlog = (string)Hash::get( $search, 'Dsp.natlog' );
			if( $natlog !== '' ) {
				$query['conditions'][] = array(
					'OR' => array(
						array(
							// On cherche dans les Dsp si pas de Dsp mises à jour
							'DspRev.id IS NULL',
							'Dsp.natlog' => $natlog
						),
						'DspRev.natlog' => $natlog,
					)
				);
			}

			// Début des spécificités par département
			$departement = Configure::read( 'Cg.departement' );

			// La personne possède-t-elle un rôle ?
			if( $departement === 66 ) {
				$exists = (string)Hash::get( $search, 'Personne.has_prestation' );
				if( $exists === '0' ) {
					$query['conditions'][] = 'Prestation.rolepers IS NULL';
				}
				else if( $exists === '1' ) {
					$query['conditions']['Prestation.rolepers'] = array( 'DEM', 'CJT' );
				}
			}
			else {
				//$query['conditions'][]
			}


			// CD 58: travailleur social chargé de l'évaluation: "Nom du chargé de
			// l'évaluation" lorsque l'on crée une orientation
			if( $departement == 58 ) {
				$referentorientant_id = (string)Hash::get( $search, 'Propoorientationcov58.referentorientant_id' );
				if( $referentorientant_id !== '' ) {
					$query['conditions']['Propoorientationcov58.referentorientant_id'] = $referentorientant_id;
				}

				$query = $Dossier->Foyer->Personne->completeQueryVfEtapeDossierOrientation58( $query, $search );
			}

			// CD 66: Personne ne possédant pas d'orientation et sans entrée Nonoriente66
			if( $departement == 66 ) {
				$exists = (string)Hash::get( $search, 'Personne.has_orientstruct' );
				if( $exists === '0' ) {
					$sql = $Dossier->Foyer->Personne->linkedRecordVirtualField( 'Nonoriente66' );
					$query['conditions'][] = 'NOT ' . $sql;
				}
			}

			return $query;
		}
	}
?>