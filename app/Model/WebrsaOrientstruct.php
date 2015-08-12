<?php
	/**
	 * Code source de la classe WebrsaOrientstruct.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractLogic', 'Model' );

	/**
	 * La classe WebrsaOrientstruct possède la logique métier web-rsa pour les
	 * orientations stockées dans Orientstruct.
	 *
	 * @todo WebrsaLogicOrientstruct ?
	 *
	 * @package app.Model
	 */
	class WebrsaOrientstruct extends WebrsaAbstractLogic
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaOrientstruct';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Orientstruct' );

		/**
		 * Permet d'obtenir les données du formulaire d'ajout / de modification,
		 * en fonction du bénéficiaire, parfois de l'orientation.
		 *
		 * @param integer $personne_id
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 * @throws NotFoundException
		 */
		public function getAddEditFormData( $personne_id, $id = null, $user_id = null ) {
			$departement = Configure::read( 'Cg.departement' );
			$data = array();

			// Modification
			if( $id !== null ) {
				$data = $this->Orientstruct->find(
					'first',
					array(
						'fields' => array_merge(
							$this->Orientstruct->fields(),
							$this->Orientstruct->Personne->Calculdroitrsa->fields()
						),
						'joins' => array(
							$this->Orientstruct->join( 'Personne' ),
							$this->Orientstruct->Personne->join( 'Calculdroitrsa' ),
						),
						'conditions' => array(
							"{$this->Orientstruct->alias}.{$this->Orientstruct->primaryKey}" => $id
						),
						'contain' => false
					)
				);

				if( empty( $data  ) ) {
					throw new NotFoundException();
				}

				// Listes dépendantes
				$data[$this->Orientstruct->alias]['referent_id'] = "{$data[$this->Orientstruct->alias]['structurereferente_id']}_{$data[$this->Orientstruct->alias]['referent_id']}";
				$data[$this->Orientstruct->alias]['structurereferente_id'] = "{$data[$this->Orientstruct->alias]['typeorient_id']}_{$data[$this->Orientstruct->alias]['structurereferente_id']}";

				if( $departement == 66 ) {
					$data[$this->Orientstruct->alias]['referentorientant_id'] = "{$data[$this->Orientstruct->alias]['structureorientante_id']}_{$data[$this->Orientstruct->alias]['referentorientant_id']}";
				}
			}
			// Ajout
			else {
				$data = array(
					$this->Orientstruct->alias => array(
						'personne_id' => $personne_id,
						'user_id' => $user_id,
						'origine' => 'manuelle'
					)
				);

				// On propose la date de demande RSA comme date de demande par défaut
				$dossier = $this->Orientstruct->Personne->find(
					'first',
					array(
						'fields' => array( 'Dossier.dtdemrsa' ),
						'joins' => array(
							$this->Orientstruct->Personne->join( 'Foyer' ),
							$this->Orientstruct->Personne->Foyer->join( 'Dossier' ),
						),
						'conditions' => array(
							'Personne.id' => $personne_id
						),
						'contain' => false
					)
				);
				$data['Orientstruct']['date_propo'] = $dossier['Dossier']['dtdemrsa'];
				$data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
			}

			// Soumission à droits et devoirs
			$query = array(
				'fields' => array(
					'Calculdroitrsa.id',
					'Calculdroitrsa.toppersdrodevorsa'
				),
				'conditions' => array(
					'Calculdroitrsa.personne_id' => $personne_id
				),
				'contain' => false
			);
			$calculdroitrsa = $this->Orientstruct->Personne->Calculdroitrsa->find( 'first', $query );

			$data['Calculdroitrsa'] = array(
				'id' => Hash::get( $calculdroitrsa, 'Calculdroitrsa.id' ),
				'toppersdrodevorsa' => Hash::get( $calculdroitrsa, 'Calculdroitrsa.toppersdrodevorsa' ),
				'personne_id' => $personne_id
			);

			return $data;
		}

		/**
		 * Sauvegarde du formulaire d'ajout / de modification de l'orientation
		 * d'un bénéficiaire.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEditFormData( array $data, $user_id = null ) {
			$success = true;
			$departement = Configure::read( 'Cg.departement' );

			if( !empty( $user_id ) ) {
				$data[$this->Orientstruct->alias]['user_id'] = $user_id;
			}

			$primaryKey = Hash::get( $data, "{$this->Orientstruct->alias}.id" );
			$personne_id = Hash::get( $data, "{$this->Orientstruct->alias}.personne_id" );
			$typeorient_id = Hash::get( $data, "{$this->Orientstruct->alias}.typeorient_id" );
			$referent_id = suffix( Hash::get( $data, "{$this->Orientstruct->alias}.referent_id" ) );

			$origine = Hash::get( $data, "{$this->Orientstruct->alias}.origine" );
			if( empty( $origine ) ) {
				$data[$this->Orientstruct->alias]['origine'] = 'manuelle';
			}

			if( $departement == 58 && empty( $primaryKey ) && $this->isRegression( $personne_id, $typeorient_id ) ) {
				$theme = 'Regressionorientationep58';

				$dossierep = array(
					'Dossierep' => array(
						'personne_id' => $personne_id,
						'themeep' => Inflector::tableize( $theme )
					)
				);

				$success = $this->Orientstruct->Personne->Dossierep->save( $dossierep ) && $success;

				$regressionorientationep = array(
					$theme => Hash::merge(
						(array)Hash::get( $data, $this->Orientstruct->alias ),
						array(
							'personne_id' => $personne_id,
							'dossierep_id' => $this->Orientstruct->Personne->Dossierep->id,
							'datedemande' => Hash::get( $data, "{$this->Orientstruct->alias}.date_propo" )
						)
					)
				);

				$success = $this->Orientstruct->Personne->Dossierep->{$theme}->save( $regressionorientationep ) && $success;
			}
			else {
				// Orientstruct
				$orientstruct = array( $this->Orientstruct->alias => (array)Hash::get( $data, $this->Orientstruct->alias ) );
				$orientstruct[$this->Orientstruct->alias]['personne_id'] = $personne_id;
				$orientstruct[$this->Orientstruct->alias]['valid_cg'] = true;

				if( $departement == 976 ) {
					$statut_orient = Hash::get( $orientstruct, "{$this->Orientstruct->alias}.statut_orient" );

					if( $statut_orient != 'Orienté' ) {
						$orientstruct[$this->Orientstruct->alias]['origine'] = null;
						$orientstruct[$this->Orientstruct->alias]['date_valid'] = null;
					}
				}
				else if( empty( $primaryKey ) ) {
					$orientstruct[$this->Orientstruct->alias]['statut_orient'] = 'Orienté';
				}

				$statut_orient = Hash::get( $orientstruct, "{$this->Orientstruct->alias}.statut_orient" );

				$this->Orientstruct->create( $orientstruct );
				$success = $this->Orientstruct->save() && $success;

				// Calculdroitrsa
				$calculdroitsrsa = array( 'Calculdroitrsa' => (array)Hash::get( $data, 'Calculdroitrsa' ) );
				$this->Orientstruct->Personne->Calculdroitrsa->create( $calculdroitsrsa );
				$success = $this->Orientstruct->Personne->Calculdroitrsa->save() && $success;

				// PersonneReferent
				if( !empty( $referent_id ) && ( $statut_orient == 'Orienté' ) ) {
					$success = $this->Orientstruct->Referent->PersonneReferent->referentParModele( $data, $this->Orientstruct->alias, 'date_valid' ) && $success;
				}
			}

			return $success;
		}

		/**
		 * Retourne un querydata permettant de connaître la liste des orientations
		 * d'un allocataire, en fonction du département.
		 *
		 * @see Configure::read( 'Cg.departement' )
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function getIndexQuery( $personne_id ) {
			$cacheKey = implode( '_', array( $this->Orientstruct->useDbConfig, $this->Orientstruct->alias, __FUNCTION__ ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				// Il n'est possible d'imprimer une orientation que suivant certaines conditions
				$sqPrintable = $this->getPrintableSq( 'printable' );

				// Il n'est possible de supprimer une orientation que si elle n'est pas liée à d'autres enregistrements
				$sqLinkedRecords = $this->Orientstruct->getSqLinkedModelsDepartement( 'linked_records' );

				// La requête
				$query = array(
					'fields' => array_merge(
						$this->Orientstruct->fields(),
						$this->Orientstruct->Personne->fields(),
						$this->Orientstruct->Typeorient->fields(),
						$this->Orientstruct->Structurereferente->fields(),
						$this->Orientstruct->Referent->fields(),
						array(
							$this->Orientstruct->Fichiermodule->sqNbFichiersLies( $this->Orientstruct, 'nombre' ),
							$sqPrintable,
							$sqLinkedRecords
						)
					),
					'conditions' => array(),
					'joins' => array(
						$this->Orientstruct->join( 'Personne' ),
						$this->Orientstruct->join( 'Typeorient' ),
						$this->Orientstruct->join( 'Structurereferente' ),
						$this->Orientstruct->join( 'Referent' ),
					),
					'contain' => false,
					'order' => array(
						'COALESCE( "Orientstruct"."rgorient", \'0\') DESC',
						'"Orientstruct"."date_valid" DESC',
						'"Orientstruct"."id" DESC'
					)
				);

				// On complète le querydata suivant le CG:
				// 1. Au CG 58, on veut savoir quelle COV a réalisé l'orientation
				if(  Configure::read( 'Cg.departement' ) == 58 ) {
					$query = $this->Orientstruct->Personne->Dossiercov58->getCompletedQueryOrientstruct( $query );
				}
				// 2. Au CG 66, on ne peut cliquer sur certains liens que sous certaines conditions
				else if( Configure::read( 'Cg.departement' ) == 66 ) {
					$Dbo = $this->Orientstruct->getDataSource();
					$sql = $Dbo->conditions( array( 'Typeorient.parentid' => (array)Configure::read( 'Orientstruct.typeorientprincipale.SOCIAL' ) ), true, false );
					$query['fields'][] = "( {$sql} ) AS \"{$this->Orientstruct->alias}__notifbenefcliquable\"";
				}

				// Sauvegarde dans le cache
				Cache::write( $cacheKey, $query );
			}

			$query['conditions']['Orientstruct.personne_id'] = $personne_id;

			return $query;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$query = $this->getIndexQuery( null );
			return !empty( $query );
		}

		/**
		 * Construit les conditions pour ajout possible à partir de la configuration,
		 * du webrsa.inc, en prenant en compte le traitement spécial à appliquer
		 * pour la valeur NULL.
		 * ATTENTION: in_array confond null et 0
		 * @see http://fr.php.net/manual/en/function.in-array.php#99676
		 *
		 * @param type $key
		 * @param type $values
		 * @return array
		 */
		protected function _conditionsAjoutOrientationPossible( $key, $values ) {
			$hasNull = false;

			if( !is_array( $values ) ) {
				$values = array( $values );
			}

			foreach( $values as $value ) {
				if( $value === null ) {
					$hasNull = true;
				}
			}

			$conditions = array( $key => array_diff( $values, array( null ) ) );

			if( $hasNull ) {
				$conditions = array(
					'OR' => array(
						$conditions,
						"{$key} IS NULL"
					)
				);
			}
			return $conditions;
		}

		/**
		 * FIXME -> aucun dossier en cours, pour certains thèmes:
		 * 		- CG 93
		 * 			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		 * 			* Reorientationep93 -> peut déboucher sur une réorientation
		 * 			* Nonorientationproep93 -> peut déboucher sur une orientation
		 * 		- CG 66
		 * 			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		 * 			* Saisinebilanparcoursep66 -> peut déboucher sur une réorientation
		 * 			* Saisinepdoep66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
		 * 		- CG 58
		 * 			* Nonorientationproep58 -> peut déboucher sur une orientation
		 * FIXME -> CG 93: s'il existe une procédure de relance, on veut faire signer un contrat,
		  mais on veut peut-être aussi demander une réorientation.
		 * FIXME -> doit-on vérifier si:
		 * 			- la personne est soumise à droits et devoirs (oui)
		 * 			- la personne est demandeur ou conjoint RSA (oui) ?
		 * 			- le dossier est dans un état ouvert (non) ?
		 */
		public function ajoutPossible( $personne_id ) {
			$nbDossiersep = $this->Orientstruct->Personne->Dossierep->find(
				'count',
				$this->Orientstruct->Personne->Dossierep->qdDossiersepsOuverts( $personne_id )
			);

			// Quelles sont les valeurs de Calculdroitrsa.toppersdrodevorsa pour lesquelles on peut ajouter une orientation ?
			// Si la valeur null est dans l'array, il faut un traitement un peu spécial
			$conditionsToppersdrodevorsa = array( 'Calculdroitrsa.toppersdrodevorsa' => '1' );
			if( Configure::read( 'AjoutOrientationPossible.toppersdrodevorsa' ) != NULL ) {
				$conditionsToppersdrodevorsa = $this->_conditionsAjoutOrientationPossible(
					'Calculdroitrsa.toppersdrodevorsa',
					Configure::read( 'AjoutOrientationPossible.toppersdrodevorsa' )
				);
			}

			$conditionsSituationetatdosrsa = array( 'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' ) );
			if( Configure::read( 'AjoutOrientationPossible.situationetatdosrsa' )  != NULL ) {
				$conditionsSituationetatdosrsa = $this->_conditionsAjoutOrientationPossible(
					'Situationdossierrsa.etatdosrsa',
					Configure::read( 'AjoutOrientationPossible.situationetatdosrsa' )
				);
			}


			$nbPersonnes = $this->Orientstruct->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'joins' => array(
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest = \'RSA\'',
								'Prestation.rolepers' => array( 'DEM', 'CJT' )
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => Set::merge(
								array( 'Personne.id = Calculdroitrsa.personne_id' ),
								$conditionsToppersdrodevorsa
							)
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						),
						array(
							'table'      => 'dossiers',
							'alias'      => 'Dossier',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => Set::merge(
								array( 'Situationdossierrsa.dossier_id = Dossier.id' ),
								$conditionsSituationetatdosrsa
							)
						),
					),
					'recursive' => -1
				)
			);

			return ( ( $nbDossiersep == 0 ) && ( $nbPersonnes == 1 ) );
		}

		/**
		 * Vérifie si pour une personne donnée la nouvelle orientation est une régression ou nonrespectssanctionseps93
		 * Orientation du pro vers le social
		 *
		 * @param integer $personne_id
		 * @param integer $newtypeorient_id
		 * @return boolean
		 */
		public function isRegression( $personne_id, $newtypeorient_id ) {
			$return = false;

			if( !$this->Orientstruct->Typeorient->isProOrientation( $newtypeorient_id ) ) {
				$lastOrient = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personne_id
						),
						'contain' => array(
							'Typeorient'
						),
						'order' => array(
							'date_valid DESC'
						)
					)
				);

				if( !empty($lastOrient) && ( Configure::read( 'Typeorient.emploi_id' ) == $lastOrient['Typeorient']['id'] ) ) {
					$return = true;
				}
			}

			return $return;
		}


		/**
		 * Permet de savoir si un allocataire est en cours de procédure de
		 * relance pour une de ses orientations, en fonction du CG.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		public function enProcedureRelance( $personne_id ) {
			return (
				Configure::read( 'Cg.departement' ) == 93
				&& $this->Orientstruct->Nonrespectsanctionep93->enProcedureRelance( $personne_id )
			);
		}

		/**
		 * Lorsqu'on crée une nouvelle orientation via les EP (CG 93) et qu'il
		 * s'agit d'une réelle réorientation (changement de structure référente
		 * et/ou de type d'orientaion) et que l'allocataire est suivi par un PDV,
		 * sans questionnaire D2 lié, il faut en créer un de manière automatique
		 * pour cette réorientation.
		 *
		 * @param array $dossierep
		 * @param string $modeleDecision
		 * @param integer $nvorientstruct_id
		 * @return boolean
		 */
		public function reorientationEpQuestionnaired2pdv93Auto( $dossierep, $modeleDecision, $nvorientstruct_id ) {
			$success = true;

			$orientstructPcd = $this->Orientstruct->find(
				'first',
				array(
					'contain' => false,
					'conditions' => array(
						'Orientstruct.personne_id' => $dossierep['Dossierep']['personne_id'],
						'Orientstruct.statut_orient' => 'Orienté',
						'NOT' => array(
							'Orientstruct.id' => $nvorientstruct_id,
						)
					),
					'order' => array( 'Orientstruct.date_valid DESC' )
				)
			);

			$reorientation = (
				empty( $orientstructPcd )
				|| $orientstructPcd['Orientstruct']['typeorient_id'] != $dossierep[$modeleDecision]['typeorient_id']
				|| $orientstructPcd['Orientstruct']['structurereferente_id'] != $dossierep[$modeleDecision]['structurereferente_id']
			);

			if( $reorientation ) {
				$success = $this->Orientstruct->Personne->Questionnaired2pdv93->saveAuto( $dossierep['Dossierep']['personne_id'], 'reorientation' ) && $success;
			}

			return $success;
		}


		/**
		 * Retourne une sous-requête, aliasée si le paramètre $fieldName n'est
		 * pas vide, permettant de savoir si un enregistrement est imprimable,
		 * suivant l'état de l'orientation et le CG connecté.
		 *
		 * @see Configure Cg.departement
		 *
		 * @param string $fieldName
		 * @return string
		 */
		public function getPrintableSq( $fieldName = 'printable' ) {
			$departement = Configure::read( 'Cg.departement' );

			if( $departement == 976 ) {
				$sqPrintable = "\"{$this->Orientstruct->alias}\".\"statut_orient\" IN ( 'En attente', 'Orienté' )";
			}
			else if( $departement == 66 ) {
				$sqPrintable = "\"{$this->Orientstruct->alias}\".\"statut_orient\" = 'Orienté'";
			}
			else {
				$Pdf = ClassRegistry::init( 'Pdf' );
				$sqPrintable = $Pdf->sqImprime( $this->Orientstruct, null );
			}

			if( !empty( $fieldName ) ) {
				$sqPrintable = "( {$sqPrintable} ) AS \"{$this->Orientstruct->alias}__{$fieldName}\"";
			}

			return $sqPrintable;
		}

		/**
		 *
		 * @param integer $orientstruct_id
		 * @param integer $user_id
		 * @return boolean
		 */
		public function getChangementReferentOrientation( $orientstruct_id, $user_id ) {
			$orientation = $this->Orientstruct->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Orientstruct->fields(),
						$this->Orientstruct->Personne->fields(),
						$this->Orientstruct->Typeorient->fields(),
						$this->Orientstruct->Structurereferente->fields(),
						$this->Orientstruct->Referent->fields(),
						$this->Orientstruct->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Orientstruct->Personne->Foyer->fields(),
						$this->Orientstruct->Personne->Foyer->Dossier->fields()
					),
					'joins' => array(
						$this->Orientstruct->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Orientstruct->join( 'Typeorient', array( 'type' => 'INNER' ) ),
						$this->Orientstruct->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->Orientstruct->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->Orientstruct->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Orientstruct->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Orientstruct->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Orientstruct->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Orientstruct.id' => $orientstruct_id,
                        'Adressefoyer.id IN ( '.$this->Orientstruct->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).' )'
					),
					'contain' => false
				)
			);

			if( empty( $orientation ) ) {
				return false;
			}

			$structurereferentePrecedente = $this->Orientstruct->find(
				'first',
				array(
					'fields' => array(
						'Structurereferente.typestructure'
					),
					'conditions' => array(
						'Orientstruct.personne_id' => $orientation['Orientstruct']['personne_id'],
						'Orientstruct.date_valid <' => $orientation['Orientstruct']['date_valid'],
						'Orientstruct.statut_orient' => 'Orienté',
						'Orientstruct.id <>' => $orientation['Orientstruct']['id']
					),
					'joins' => array(
						$this->Orientstruct->join( 'Structurereferente', array( 'type' => 'INNER') )
					),
					'order' => array( 'Orientstruct.date_valid DESC' ),
					'contain' => false
				)
			);

			// Options pour les traductions
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Personne' => array(
					'qual' => $Option->qual()
				),
				'Referent' => array(
					'qual' => $Option->qual()
				),
				'Structurereferente' => array(
					'type_voie' => $Option->typevoie()
				),
				'type' => array(
					'voie' => $Option->typevoie()
				)
			);

			$user = $this->Orientstruct->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Serviceinstructeur'
					)
				)
			);
			$orientation = Set::merge( $orientation, $user );
			// Choix du modèle de document
			$typestructure = Set::classicExtract( $orientation, 'Structurereferente.typestructure' );
			$typestructurepassee = Set::classicExtract( $structurereferentePrecedente, 'Structurereferente.typestructure' );

			if( $typestructure == $typestructurepassee ) {
				if( $typestructure == 'oa' ) {
					// INFO: Réponse du CG66 : d'expérience cela se fait à la marge donc pour le moment
					// aucun traitement particulier
					$modeleodt = "Orientation/changement_referent_cgcg.odt"; // FIXME: devrait être paoa
				}
				else {
					$modeleodt = "Orientation/changement_referent_cgcg.odt";
				}
			}
			else {
				if( $typestructure == 'oa' ) {
					$modeleodt = "Orientation/changement_referent_cgoa.odt";
				}
				else {
					$modeleodt = "Orientation/changement_referent_oacg.odt";
				}
			}

			// Génération du PDF
			return $this->Orientstruct->ged( $orientation, $modeleodt, false, $options );
		}
	}
?>