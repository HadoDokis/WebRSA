<?php
	/**
	 * Fichier source du modèle Cohortetransfertpdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Cohortetransfertpdv93.
	 *
	 * @package app.Model
	 */
	class Cohortetransfertpdv93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Cohortetransfertpdv93';

		public $useTable = false;

		public $actsAs = array(
			'Conditionnable'
		);

		/**
		 * Retourne un querydata résultant du traitement du formulaire de
		 * recherche des cohortes de transfert de PDV.
		 *
		 * @param array $mesCodesInsee La liste des codes INSEE à laquelle est lié l'utilisateur
		 * @param boolean $filtre_zone_geo L'utilisateur est-il limité au niveau des zones géographiques ?
		 * @param array $search Critères du formulaire de recherche
		 * @param mixed $lockedDossiers
		 * @return array
		 */
		public function search( $statut, $mesCodesInsee, $filtre_zone_geo, $search, $lockedDossiers ) {
			$Dossier = ClassRegistry::init( 'Dossier' );

			$sqDerniereRgadr01 = $Dossier->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' );
			$sqDerniereRgadr02 = str_replace( '01', '02', $sqDerniereRgadr01 );

			$sqDerniereOrientstruct = $Dossier->Foyer->Personne->Orientstruct->sqDerniere( 'Personne.id' );
			$sqZonesgeographiquesStructuresreferentes = $Dossier->Foyer->Personne->Orientstruct->Structurereferente->StructurereferenteZonegeographique->sq(
				array(
					'alias' => 'structuresreferentes_zonesgeographiques',
					'fields' => array( 'zonesgeographiques.codeinsee' ),
					'joins' => array(
						array_words_replace(
							$Dossier->Foyer->Personne->Orientstruct->Structurereferente->StructurereferenteZonegeographique->join( 'Zonegeographique', array( 'type' => 'INNER' ) ),
							array(
								'StructurereferenteZonegeographique' => 'structuresreferentes_zonesgeographiques',
								'Zonegeographique' => 'zonesgeographiques',
							)
						)
					),
					'contain' => false,
					'conditions' => array(
						'structuresreferentes_zonesgeographiques.structurereferente_id = Structurereferente.id'
					)
				)
			);

			// Un dossier possède un seul detail du droit RSA mais ce dernier possède plusieurs details de calcul
			// donc on limite au dernier detail de calcul du droit rsa
			$sqDernierDetailcalculdroitrsa = $Dossier->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->sqDernier( 'Detaildroitrsa.id' );

			//Dernier CER en cours pour un allocataire
			$sqDernierContratinsertion = $Dossier->Foyer->Personne->sqLatest( 'Contratinsertion', 'dd_ci' );

			$conditions = array(
				'Prestation.natprest' => 'RSA',
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				'Adressefoyer.rgadr' => '01',
				"Adressefoyer.id IN ( {$sqDerniereRgadr01} )",
				"VxAdressefoyer.id IN ( {$sqDerniereRgadr02} )",
				'Orientstruct.statut_orient' => 'Orienté',
				"Orientstruct.id IN ( {$sqDerniereOrientstruct} )",
				"Detailcalculdroitrsa.id IN ( {$sqDernierDetailcalculdroitrsa} )",
				$sqDernierContratinsertion
			);

			if( $statut == 'atransferer' ) {
				$conditions = array_merge(
					$conditions,
					array(
						'Adresse.numcomptt LIKE' => Configure::read( 'Cg.departement' ).'%',
						"Adressefoyer.dtemm > Orientstruct.date_valid",
						'Structurereferente.filtre_zone_geo' => true,
						"Adresse.numcomptt NOT IN ( {$sqZonesgeographiquesStructuresreferentes} )",
					)
				);
			}
			else {
				$conditions = array_merge(
					$conditions,
					array(
//						"Adressefoyer.dtemm <= Orientstruct.date_valid",
						'Orientstruct.origine' => 'demenagement'
					)
				);
			}

			$conditions = $this->conditionsAdresse( $conditions, $search, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $search );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $search );

			if( isset( $search['Orientstruct']['typeorient_id'] ) && trim( $search['Orientstruct']['typeorient_id'] ) != '' ) {
				$conditions['Orientstruct.typeorient_id'] = $search['Orientstruct']['typeorient_id'];
			}

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				if( is_array( $lockedDossiers ) ) {
					$lockedDossiers = implode( ', ', $lockedDossiers );
				}
				$conditions[] = "NOT {$lockedDossiers}";
			}

            // Conditions sur les dates de validation de l'orientation
            $conditions = $this->conditionsDates( $conditions, $search, 'Orientstruct.date_valid' );
            // Conditions sur les dates de transfert du dossier
            $conditions = $this->conditionsDates( $conditions, $search, 'Transfertpdv93.created' );


			$querydata = array(
				'fields' => array_merge(
					$Dossier->fields(),
					$Dossier->Detaildroitrsa->fields(),
					$Dossier->Detaildroitrsa->Detailcalculdroitrsa->fields(),
					$Dossier->Foyer->Adressefoyer->fields(),
					array_words_replace( $Dossier->Foyer->Adressefoyer->fields(), array( 'Adressefoyer' => 'VxAdressefoyer' ) ),
					$Dossier->Foyer->Personne->fields(),
					$Dossier->Foyer->Adressefoyer->Adresse->fields(),
					array_words_replace( $Dossier->Foyer->Adressefoyer->Adresse->fields(), array( 'Adresse' => 'VxAdresse' ) ),
					$Dossier->Foyer->Personne->Calculdroitrsa->fields(),
					$Dossier->Foyer->Personne->Orientstruct->fields(),
					$Dossier->Foyer->Personne->Contratinsertion->Cer93->fields(),
					$Dossier->Foyer->Personne->Prestation->fields(),
					$Dossier->Foyer->Personne->Orientstruct->Structurereferente->fields(),
					$Dossier->Foyer->Personne->Orientstruct->Typeorient->fields()
				),
				'joins' => array(
					$Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					array_words_replace( $Dossier->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ), array( 'Adressefoyer' => 'VxAdressefoyer' ) ),
					$Dossier->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
					array_words_replace( $Dossier->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ), array( 'Adresse' => 'VxAdresse' ) ),
					$Dossier->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Orientstruct', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Contratinsertion', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions,
				'contain' => false,
				'order' => array( 'Adressefoyer.dtemm DESC', 'Dossier.id ASC', 'Personne.nom ASC', 'Personne.prenom ASC' ),
				'limit' => 10
			);

			if( $statut != 'atransferer' ) {
				$Transfertpdv93 = ClassRegistry::init( 'Transfertpdv93' );

				$querydata['fields'] = array_merge( $querydata['fields'], $Transfertpdv93->fields() );
				$querydata['fields'] = array_merge( $querydata['fields'], $Transfertpdv93->VxOrientstruct->fields() );
				$querydata['fields'] = array_merge( $querydata['fields'], array_words_replace( $Transfertpdv93->VxOrientstruct->Structurereferente->fields(), array( 'Structurereferente' => 'VxStructurereferente' ) ) );

				$querydata['joins'][] = array_words_replace( $Transfertpdv93->NvOrientstruct->join( 'NvTransfertpdv93', array( 'type' => 'INNER' ) ), array( 'NvOrientstruct' => 'Orientstruct', 'NvTransfertpdv93' => 'Transfertpdv93' ) );
				$querydata['joins'][] = array_words_replace( $Transfertpdv93->join( 'VxOrientstruct', array( 'type' => 'INNER' ) ), array( 'VxTransfertpdv93' => 'Transfertpdv93' ) );
				$querydata['joins'][] = array_words_replace( $Transfertpdv93->VxOrientstruct->join( 'Structurereferente', array( 'type' => 'INNER' ) ), array( 'Structurereferente' => 'VxStructurereferente' ) );
			}

			if( $statut != 'impressions' ) {
				// TODO: Pdf::sqImprime
//				$querydata['joins'][] = $Dossier->Foyer->Personne->Orientstruct->
			}

			// FIXME: et qui n'ont pas encore été transférés
			// FIXME: ici, on a ceux qui sortent du département

			$querydata = $Dossier->Foyer->Personne->PersonneReferent->completeQdReferentParcours( $querydata, $search );

			return $querydata;
		}

		/**
		 * Liste des structures référentes groupées par type d'orientation.
		 *
		 * TODO, à vérifier:
		 *	- ajouter un shell qui clôture XXXXX en cas de déménagement hors département
		 *  - ajouter un filtre que dans le dépt/que hors dépt
		 *
		 * @return array
		 */
		public function structuresParZonesGeographiques() {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$Typeorient = ClassRegistry::init( 'Typeorient' );

				$results = $Typeorient->find(
					'all',
					array(
						'fields' => array(
							'Typeorient.id',
							'Typeorient.lib_type_orient',
							'Structurereferente.id',
							'Structurereferente.lib_struc',
							'Zonegeographique.codeinsee'
						),
						'conditions' => array(
							'Typeorient.actif' => 'O',
							'Structurereferente.actif' => 'O',
						),
						'joins' => array(
							$Typeorient->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
							$Typeorient->Structurereferente->join( 'StructurereferenteZonegeographique', array( 'type' => 'INNER' ) ),
							$Typeorient->Structurereferente->StructurereferenteZonegeographique->join( 'Zonegeographique', array( 'type' => 'INNER' ) )
						),
						'contain' => false,
						'order' => array(
							'Zonegeographique.codeinsee ASC',
							'Typeorient.lib_type_orient ASC',
							'Structurereferente.lib_struc ASC',
						)
					)
				);

				$tmp = array();
				if( !empty( $results ) ) {
					foreach( $results as $result ) {
						if( !isset( $tmp[$result['Typeorient']['id']] ) ) {
							$tmp[$result['Typeorient']['id']] = array();
						}

						$tmp[$result['Typeorient']['id']]["{$result['Zonegeographique']['codeinsee']}_{$result['Structurereferente']['id']}"] = $result['Structurereferente']['lib_struc'];
					}
				}
				$results = $tmp;

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Typeorient', 'Structurereferente', 'Zonegeographique' ) );
			}

			return $results;
		}

		/**
		 * Liste des structures référentes groupées par code INSEE.
		 *
		 * @return array
		 */
		public function structuresParZonesGeographiquesPourTransfertPdv() {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$structuresParZonesGeographiques = $this->structuresParZonesGeographiques();

				// Comptage
				$foos = array();
				foreach( $structuresParZonesGeographiques as $typeorient_id => $datas ) {
					foreach( $datas as $key => $label ) {
						list( $codeinsee, $structurereferente_id ) = explode( '_', $key );

						if( !isset( $foos[$codeinsee][$typeorient_id] ) ) {
							$foos[$codeinsee][$typeorient_id] = 0;
						}
						$foos[$codeinsee][$typeorient_id]++;
					}
				}

				// Nouvelle liste d'options
				// Configure::write( 'Orientstruct.typeorientprincipale', array( 'Socioprofessionnelle' => array( 1 ), 'Social' => array( 2 ), 'Emploi' => array( 3 ) ) );
				$typesorients = Configure::read( 'Orientstruct.typeorientprincipale' );
				$pdvsCodeInsee = array();
				foreach( $foos as $codeinsee => $datas ) {

					$hasSociopro = false;
					foreach( $typesorients['Socioprofessionnelle'] as $typeorient_sociopro_id ) {
						if( isset( $datas[$typeorient_sociopro_id] ) && !empty( $datas[$typeorient_sociopro_id] ) ) {
							$hasSociopro = true;
						}
					}

					$pdvsCodeInsee[$codeinsee] = $hasSociopro;
				}

				$results = array();
				foreach( $pdvsCodeInsee as $codeinsee => $hasPdv ) {
					$results[$codeinsee] = $structuresParZonesGeographiques;

					// Si mon code INSEE n'a pas de sociopro, alors les options auront tous les sociopro + tous les emploi
					if( !$hasPdv ) {
						foreach( $typesorients['Socioprofessionnelle'] as $typeorient_sociopro_id ) {
							foreach( $typesorients['Emploi'] as $typeorient_emploi_id ) {
								$results[$codeinsee][$typeorient_sociopro_id] = array_merge(
									$results[$codeinsee][$typeorient_sociopro_id],
									$results[$codeinsee][$typeorient_emploi_id]
								);
							}
						}
					}
				}

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, array( 'Typeorient', 'Structurereferente', 'Zonegeographique' ) );
			}

			return $results;
		}

		/**
		 * TODO: mettre En attente par défaut
		 *
		 * @param array $results
		 * @param array $structuresParZonesGeographiques
		 * @return array
		 */
		public function prepareFormDataIndex( $results, $structuresParZonesGeographiques ) {
			$formData = array( 'Transfertpdv93' => array() );

			if( !empty( $results ) ) {
				foreach( $results as $index => $result ) {
					$formData['Transfertpdv93'][$index] = array();
					$formData['Transfertpdv93'][$index]['dossier_id'] = $result['Dossier']['id'];
					$formData['Transfertpdv93'][$index]['vx_adressefoyer_id'] = $result['VxAdressefoyer']['id'];
					$formData['Transfertpdv93'][$index]['nv_adressefoyer_id'] = $result['Adressefoyer']['id'];
					$formData['Transfertpdv93'][$index]['vx_orientstruct_id'] = $result['Orientstruct']['id'];
					$formData['Transfertpdv93'][$index]['personne_id'] = $result['Orientstruct']['personne_id'];
					$formData['Transfertpdv93'][$index]['typeorient_id'] = $result['Orientstruct']['typeorient_id'];
					$formData['Transfertpdv93'][$index]['action'] = '0';

					$structurereferente_dst_id = null;

					if( isset( $structuresParZonesGeographiques[$result['Adresse']['numcomptt']] ) ) {
						if( isset( $structuresParZonesGeographiques[$result['Adresse']['numcomptt']][$result['Orientstruct']['typeorient_id']] ) ) {
							$selectables = array();
							$structures = $structuresParZonesGeographiques[$result['Adresse']['numcomptt']][$result['Orientstruct']['typeorient_id']];

							if( !empty( $structures ) ) {
								foreach( array_keys( $structures ) as $key ) {
									if( preg_match( "/^{$result['Adresse']['numcomptt']}_/", $key ) ) {
										$selectables[] = $key;
									}
								}
							}

							if( count( $selectables ) == 1 ) {
								$structurereferente_dst_id = $selectables[0];
							}
						}
					}

					$formData['Transfertpdv93'][$index]['structurereferente_dst_id'] = $structurereferente_dst_id;
				}
			}

			return $formData;
		}

		// FIXME: vx_orientstruct_id, nv_orientstruct_id
		// TODO:
		// Formattable.suffix -> structurereferente_dst_id
		// Validation structurereferente_dst_id -> NOT NULL
		// FIXME: mettre la date de fin de transfert à jour (ajouter personne_id et nvorientstruct_id dans la table ???)
		public function transfertAllocataire( $data, $user_id ) {
			$success = true;

			$Orientstruct = ClassRegistry::init( 'Orientstruct' );
			$orientstruct = array(
				'Orientstruct' => array(
					'personne_id' => $data['Transfertpdv93']['personne_id'],
					'typeorient_id' => $data['Transfertpdv93']['typeorient_id'],
					'structurereferente_id' => $data['Transfertpdv93']['structurereferente_dst_id'],
					'date_valid' => date( 'Y-m-d' ),
					'statut_orient' => 'Orienté',
					'user_id' => $user_id,
					'origine' => 'demenagement', // FIXME: changer le beforeSave de orientstruct
				)
			);
			$Orientstruct->create( $orientstruct );
			$success = $Orientstruct->save() && $success;

			if( !empty( $Orientstruct->validationErrors ) ) {
				debug( $Orientstruct->validationErrors );
			}

			if( $success && !empty( $data['Transfertpdv93']['structurereferente_dst_id'] ) ) {
				$Transfertpdv93 = ClassRegistry::init( 'Transfertpdv93' );

				$data['Transfertpdv93']['user_id'] = $orientstruct['Orientstruct']['user_id'];
				$data['Transfertpdv93']['vx_orientstruct_id'] = $data['Transfertpdv93']['vx_orientstruct_id'];
				$data['Transfertpdv93']['nv_orientstruct_id'] = $Orientstruct->id;

				$Transfertpdv93->create( $data );
				$success = $Transfertpdv93->save() && $success;
				if( !empty( $Transfertpdv93->validationErrors ) ) {
					debug( $Transfertpdv93->validationErrors );
				}
			}

			// Si on change de PDV, et que l'allocataire possède un D1 sans D2 dans l'ancien PDV, on enregistre automatiquement un D2
			if( $data['Transfertpdv93']['vx_orientstruct_id'] !== $data['Transfertpdv93']['nv_orientstruct_id'] ) {
				$questionnaired1pdv93_id = $Orientstruct->Personne->Questionnaired2pdv93->questionnairesd1pdv93Id( $data['Transfertpdv93']['personne_id'] );
				if( !empty( $questionnaired1pdv93_id ) ) {
					$success = $Orientstruct->Personne->Questionnaired2pdv93->saveAuto(
						$data['Transfertpdv93']['personne_id'],
						'changement_situation',
						'modif_commune'
					) && $success;
				}
			}

			// On clôture le référent actuel à la date
			$count = $Orientstruct->Personne->PersonneReferent->find(
				'count',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $data['Transfertpdv93']['personne_id'],
						'PersonneReferent.dfdesignation IS NULL'
					)
				)
			);

			$datedfdesignation = ( is_array( date( 'Y-m-d' ) ) ? date_cakephp_to_sql( date( 'Y-m-d' ) ) : date( 'Y-m-d' ) );

			if( $count > 0 ) {
				$success = $Orientstruct->Personne->PersonneReferent->updateAllUnBound(
					array( 'PersonneReferent.dfdesignation' => '\''.$datedfdesignation.'\'' ),
					array(
						'"PersonneReferent"."personne_id"' => $data['Transfertpdv93']['personne_id'],
						'PersonneReferent.dfdesignation IS NULL'
					)
				) && $success;
			}

			return $success;
		}

		/**
		 *
		 * @param array $data
		 * @param integer $user_id
		 * @return boolean
		 */
		public function saveCohorte( $data, $user_id ) {
			$success = true;

			if( !empty( $data ) ) {
				foreach( $data as $line ) {
					$success = $this->transfertAllocataire( $line, $user_id ) && $success;
				}
			}

			return $success;
		}

		/**
		 * Suppression et regénération du cache.
		 *
		 * @return boolean
		 */
		protected function _regenerateCache() {
			// Suppression des éléments du cache.
			$this->_clearModelCache();
			$success = true;

			// Regénération des éléments du cache.
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$success = ( $this->structuresParZonesGeographiques() !== false ) && $success;
				$success = ( $this->structuresParZonesGeographiquesPourTransfertPdv() !== false ) && $success;
			}

			return $success;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$success = $this->_regenerateCache();
			return $success;
		}
	}
?>