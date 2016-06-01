<?php
	/**
	 * Code source de la classe WebrsaAccompagnementbeneficiaire.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe WebrsaAccompagnementbeneficiaire ...
	 *
	 * @package app.Model
	 */
	class WebrsaAccompagnementbeneficiaire extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaAccompagnementbeneficiaire';

		/**
		 * Ce modèle ne possède pas de table liée.
		 *
		 * @var bool
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array();

		/**
		 * Modèles utilisés par ce modéle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Informationpe',
			'Option', // TODO: à mettre dans les modèles concernés, à déprécier dans Option
			'Pdf',
			'Personne'
		);

		public function qdDetails( array $conditions = array() ) {
			$cacheKey = $this->useDbConfig.'_'.$this->alias.'_'.__FUNCTION__;
			$query = Cache::read( $cacheKey );

			if( false === $query ) {
				$query = array(
					'fields' => array(
						// -----------------------------------------------------
						// I. Bloc "Droits"
						// Date de demande RMI
						'Dossier.dtdemrmi',
						// Date de demande RSA
						'Dossier.dtdemrsa',
						// Type de RSA (champ composé)
						'Detailcalculdroitrsa.natpf_activite',
						'Detailcalculdroitrsa.natpf_majore',
						'Detailcalculdroitrsa.natpf_socle',
						//'Detailcalculdroitrsa.natpf_jeune', // TODO ?
						//Etat du droit
						'Situationdossierrsa.etatdosrsa',
						// CMU: Oui/Non/Inconnu
						'Cer93.cmu',
						// CMUC : Oui/Non/Inconnu
						'Cer93.cmuc',
						// Pôle Emploi
						'Historiqueetatpe.identifiantpe',
						// Soumis à Droits et Devoirs
						'Calculdroitrsa.toppersdrodevorsa',
						// Orientation
						'Typeorient.lib_type_orient',
						'Structurereferente.lib_struc',
						// -----------------------------------------------------
						// II. Bloc "Compétences"
						// Niveau de formation -> CER date de validation du CER; DSP date de création; D1 date de création, DSP
						// INFO: suivant le champ, on n'a pas les memes valeurs d'ENUM
						'CASE
							WHEN
								"Cer93"."nivetu" IS NOT NULL
								AND ( "DspRev"."nivetu" IS NULL OR "Contratinsertion"."datedecision" >= "DspRev"."modified"::DATE )
								AND ( "Questionnaired1pdv93"."nivetu" IS NULL OR "Contratinsertion"."datedecision" >= "Questionnaired1pdv93"."created"::DATE )
								THEN "Cer93"."nivetu"
							ELSE NULL
						END AS "Cer93__nivetu"',
						'CASE
							WHEN
								"DspRev"."nivetu" IS NOT NULL
								AND ( "Cer93"."nivetu" IS NULL OR "Contratinsertion"."datedecision" < "DspRev"."modified"::DATE )
								AND ( "Questionnaired1pdv93"."nivetu" IS NULL OR "DspRev"."modified"::DATE >= "Questionnaired1pdv93"."created"::DATE )
								THEN "DspRev"."nivetu"
							ELSE NULL
						END AS "DspRev__nivetu"',
						'CASE
							WHEN
								"Questionnaired1pdv93"."nivetu" IS NOT NULL
								AND ( "Cer93"."nivetu" IS NULL OR "Contratinsertion"."datedecision" < "Questionnaired1pdv93"."created"::DATE )
								AND ( "DspRev"."nivetu" IS NULL OR "DspRev"."modified"::DATE < "Questionnaired1pdv93"."created"::DATE )
								THEN "Questionnaired1pdv93"."nivetu"
							ELSE NULL
						END AS "Questionnaired1pdv93__nivetu"',
						'CASE
							WHEN
								"Cer93"."nivetu" IS NULL
								AND "DspRev"."nivetu" IS NULL
								AND "Questionnaired1pdv93"."nivetu" IS NULL
								THEN "Dsp"."nivetu"
							ELSE NULL
						END AS "Dsp__nivetu"',
						// -----------------------------------------------------
						// Diplôme  (Dernier diplôme à partir du dernier CER validé)
						'Diplomecer93.name',
						// Emploi recherché: Appelation métier code rome V3 du champs "Votre contrat porte sur l'emploi" du dernier CER validé
						'Appellationromev3.name',
						// Mobilité (correspondant à Disposez-vous d’un moyen de transport collectif ou individuel à partir de la dernière MAJ DSP)
						'( CASE WHEN "DspRev"."id" IS NOT NULL THEN "DspRev"."topmoyloco" = \'1\' WHEN "Dsp"."id" IS NOT NULL THEN "Dsp"."topmoyloco" = \'1\' ELSE NULL END ) AS "Accompagnement__topmoyloco"',
						// Permis (Permis B ou autre à partir de la DSP)
						'( CASE
							WHEN "DspRev"."id" IS NOT NULL THEN ( ( "DspRev"."toppermicondub" IS NOT NULL AND "DspRev"."toppermicondub" = \'1\' ) OR ( "DspRev"."topautrpermicondu" IS NOT NULL AND "DspRev"."topautrpermicondu" = \'1\' ) )
							WHEN "Dsp"."id" IS NOT NULL THEN ( ( "Dsp"."toppermicondub" IS NOT NULL AND "Dsp"."toppermicondub" = \'1\' ) OR ( "Dsp"."topautrpermicondu" IS NOT NULL AND "Dsp"."topautrpermicondu" = \'1\' ) )
							ELSE NULL
						END ) AS "Accompagnement__toppermicondu"',
						// -----------------------------------------------------
						// III. Bloc "Suivi"
						// Nom du référent de parcours (actuel)
						'Referent.nom_complet',
					),
					'joins' => array(
						$this->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join(
							'Contratinsertion',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Contratinsertion.decision_ci' => 'V',
									'Contratinsertion.id IN ( '.$this->Personne->Contratinsertion->sqDernierContrat( 'Contratinsertion.personne_id', true ).' )',
								)
							)
						),
						$this->Personne->join(
							'Dsp',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Dsp.id IN ( '.$this->Personne->Dsp->sqDerniereDsp( 'Dsp.personne_id' ).' )'
								)
							)
						),
						$this->Personne->join(
							'DspRev',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'DspRev.id IN ( '.$this->Personne->DspRev->sqDerniere( 'DspRev.personne_id' ).' )'
								)
							)
						),
						$this->Personne->join(
							'Orientstruct',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Orientstruct.id IN ( '.$this->Personne->Orientstruct->sqDerniere().' )'
								)
							)
						),
						$this->Personne->join(
							'Questionnaired1pdv93',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									$this->Personne->sqLatest( 'Questionnaired1pdv93', 'modified' ),
								)
							)
						),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->join(
							'PersonneReferent',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'PersonneReferent.id IN ( '.$this->Personne->PersonneReferent->sqDerniere( 'Personne.id', false ).' )'
								)
							)
						),
						$this->Personne->Contratinsertion->join( 'Cer93', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Contratinsertion->Cer93->join(
							'Diplomecer93',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Diplomecer93.id IN ( '.$this->Personne->Contratinsertion->Cer93->Diplomecer93->sqDernier().' )'
								)
							)
						),
						$this->Personne->Contratinsertion->Cer93->join( 'Sujetromev3', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Contratinsertion->Cer93->Sujetromev3->join( 'Appellationromev3', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(),
					'contain' => false
				);

				// Jointures spéciales concernant les derniers éléments transmis par Pôle Emploi
				// 1. Dernière information Pole Emploi
				$joinPersonneInformationpe = $this->Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' );
				$joinPersonneInformationpe['conditions'][] = 'Informationpe.id IN ( '.$this->Informationpe->sqDerniere().' )';
				$query['joins'][] = $joinPersonneInformationpe;
				// 2. Dernier historique Pole Emploi
				$query['joins'][] = $this->Informationpe->join(
					'Historiqueetatpe',
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Historiqueetatpe.id IN ( '.$this->Informationpe->Historiqueetatpe->sqDernier().' )'
						)
					)
				);

				Cache::write( $cacheKey, $query );
			}

			$query['conditions'] = array_merge( $query['conditions'], $conditions );

			return $query;
		}

		/**
		 * Récupère la liste des actions liées au bénéficiaire.
		 *
		 * @fixme
		 *	- droits d'accès (contrôleur / component)
		 *  - mise en cache
		 *
		 * @param integer $personne_id L'id du bénéficiaire
		 * @return array
		 */
		public function actions( $personne_id ) {
			// Début "paramétrage"
			$models = array(
				'Rendezvouscollectif' => array(
					'modelName' => 'Rendezvous',
					'fields' => array(
						'date' => array(
							'path' => 'Rendezvous.daterdv',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
						'statut' => 'Statutrdv.libelle',
						'informations' => array(
							'path' => 'Rendezvous.thematiques_virgules',
							'virtual' => true,
						),
						'commentairerdv' => array(
							'path' => 'Rendezvous.commentairerdv'
						)
					),
					'joins' => array(
						'Structurereferente' => array( 'type' => 'INNER' ),
						'Referent' => array( 'type' => 'LEFT OUTER' ),
						'Statutrdv' => array( 'type' => 'INNER' )
					),
					'conditions' => array(
						'Rendezvouscollectif.typerdv_id' => Configure::read( 'Rendezvous.Typerdv.collectif_id' )
					)
				),
				'Rendezvousindividuel' => array(
					'modelName' => 'Rendezvous',
					'fields' => array(
						'date' => array(
							'path' => 'Rendezvous.daterdv',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
						'statut' => 'Statutrdv.libelle',
						'informations' => array(
							'path' => 'Rendezvous.thematiques_virgules',
							'virtual' => true,
						),
						'commentairerdv' => array(
							'path' => 'Rendezvous.commentairerdv'
						)
					),
					'joins' => array(
						'Structurereferente' => array( 'type' => 'INNER' ),
						'Referent' => array( 'type' => 'LEFT OUTER' ),
						'Statutrdv' => array( 'type' => 'INNER' )
					),
					'conditions' => array(
						'Rendezvousindividuel.typerdv_id' => Configure::read( 'Rendezvous.Typerdv.individuel_id' )
					)
				),
				'Contratinsertion' => array(
					'fields' => array(
						'date' => array(
							'path' => 'Contratinsertion.created',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
						'statut' => 'Cer93.positioncer',
						'dd_ci' => array(
							'path' => 'Contratinsertion.dd_ci',
							'type' => 'date',
						),
						'df_ci' => array(
							'path' => 'Contratinsertion.df_ci',
							'type' => 'date',
						),
						'duree' => array(
							'path' => 'Cer93.duree',
							'type' => 'integer',
						),
						'sujets_virgules' => array(
							'path' => 'Cer93.sujets_virgules',
							'virtual' => true
						),
						'prevu' => array(
							'path' => 'Cer93.prevu'
						),
					),
					'joins' => array(
						'Structurereferente' => array( 'type' => 'INNER' ),
						'Referent' => array( 'type' => 'LEFT OUTER' ),
						'Cer93' => array( 'type' => 'INNER' )
					),
					// FIXME: le controleur est cers93
				),
				'Ficheprescription93' => array(
					'fields' => array(
						'date' => array(
							'path' => 'Ficheprescription93.created',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
						'statut' => 'Ficheprescription93.statut',
						'categoriefp' => 'Categoriefp93.name',
						'thematiquefp' => 'Thematiquefp93.name',
						'prestatairefphorspdi' => 'Prestatairehorspdifp93.name',
						'prestatairefppdi' => 'Prestatairefp93.name',
					),
					'joins' => array(
						'Adresseprestatairefp93' => array(
							'type' => 'LEFT OUTER',
							'joins' => array(
								'Prestatairefp93' => array(
									'type' => 'LEFT OUTER',
								)
							)
						),
						'Filierefp93' => array(
							'type' => 'LEFT OUTER',
							'joins' => array(
								'Categoriefp93' => array(
									'type' => 'LEFT OUTER',
									'joins' => array(
										'Thematiquefp93' => array( 'type' => 'LEFT OUTER' )
									)
								),
							)
						),
						'Prestatairehorspdifp93' => array(
							'type' => 'LEFT OUTER'
						),
						'Referent' => array(
							'type' => 'INNER',
							'joins' => array(
								'Structurereferente' => array(
									'type' => 'INNER'
								)
							)
						),
					),
				),
				'Questionnaired1pdv93' => array(
					'fields' => array(
						'date' => array(
							'path' => 'Questionnaired1pdv93.created',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
					),
					'joins' => array(
						'Rendezvous' => array(
							'type' => 'INNER',
							'joins' => array(
								'Structurereferente' => array( 'type' => 'INNER' ),
								'Referent' => array( 'type' => 'LEFT OUTER' )
							)
						)
					),
				),
				'Questionnaired2pdv93' => array(
					'fields' => array(
						'date' => array(
							'path' => 'Questionnaired2pdv93.created',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
						'informations' => 'Questionnaired2pdv93.situationaccompagnement',
					),
					'joins' => array(
						'Questionnaired1pdv93' => array(
							'type' => 'INNER',
							'joins' => array(
								'Rendezvous' => array(
									'type' => 'INNER',
									'joins' => array(
										'Structurereferente' => array( 'type' => 'INNER' ),
										'Referent' => array( 'type' => 'LEFT OUTER' )
									)
								)
							)
						)
					),
				),
				'DspRev' => array(
					'fields' => array(
						'date' => array(
							'path' => 'DspRev.created',
							'type' => 'date',
						),
//						'view' => '/Dsps/view_revs/#DspRev.id#',
//						'edit' => '/Dsps/edit/#DspRev.dsp_id#/#DspRev.id#'
					)
				),
				'Entretien' => array(
					'fields' => array(
						'date' => array(
							'path' => 'Entretien.dateentretien',
							'type' => 'date',
						),
						'lib_struc' => 'Structurereferente.lib_struc',
						'nom_complet' => array(
							'path' => 'Referent.nom_complet',
							'virtual' => true,
						),
						'informations' => 'Objetentretien.name',
					),
					'joins' => array(
						'Objetentretien' => array( 'type' => 'INNER' ),
						'Structurereferente' => array( 'type' => 'INNER' ),
						'Referent' => array( 'type' => 'LEFT OUTER' )
					)
				)
			);
			// Fin "paramétrage"

			$models = Hash::normalize( $models );
			$sqls = array();

			// -----------------------------------------------------------------

			$fieldsList = array();

			// Normalisation
			foreach( $models as $alias => $params ) {
				$params = (array)$params
					+ array(
						'modelName' => $alias,
						'fields' => array(),
						'joins' => array(),
						'conditions' => array(),
					);
				$params['fields'] = Hash::normalize( $params['fields'] );

				// 1. Champs
				foreach( $params['fields'] as $key => $value ) {
					if( is_string( $value ) ) {
						$value = array( 'path' => $value, 'type' => 'text' );
					}
					$params['fields'][$key] = (array)$value + array( 'type' => 'text' );

					$fieldsList[$key] = null;
				}

				// 2. Jointures
				$joins = (array)Hash::get( $params, 'joins' );
				if( !empty( $joins ) ) {
					$joins = $this->Personne->{$params['modelName']}->joins( $joins );
				}
				$params['joins'] = $joins;

				$models[$alias] = $params;
			}

			$fieldsList = array_keys( $fieldsList );

			// -----------------------------------------------------------------

			// Unions
			foreach( $models as $alias => $params ) {
				$modelName = $params['modelName'];

				$replacements = array( $modelName => $alias );

				$query = array(
					'alias' => $modelName,
					'fields' => array(
						"\"{$modelName}\".\"id\" AS \"Action__id\"",
						"'{$modelName}' AS \"Action__name\""
					),
					'contain' => false,
					'joins' => array(),
					'conditions' => array(
						"{$modelName}.personne_id = '#Personne.id#'"
					)
				);

				// Ajout de conditions supplémentaires ?
				if( !empty( $params['conditions'] ) ) {
					$query['conditions'][] = $params['conditions'];
				}

				// Ajout de jointures supplémentaires ?
				if( !empty( $params['joins'] ) ) {
					$joins = $params['joins'];
					$query['joins'] = array_merge( $query['joins'], $joins );
				}

				// Ajout de champs supplémentaires
				foreach( $fieldsList as $fieldName ) {
					$fieldNameParams = (array)Hash::get( $params, "fields.{$fieldName}" );

					$field = Hash::get( $fieldNameParams, 'path' );
					$type = Hash::get( $fieldNameParams, 'type' );
					$virtual = Hash::get( $fieldNameParams, 'virtual' );
					$subquery = Hash::get( $fieldNameParams, 'subquery' );

					if( !empty( $field ) ) {
						if( strpos( $field, '/' ) === 0 ) {
							$query['fields'][] = "'{$field}' AS \"Action__{$fieldName}\"";
						}
						else {
							if( $subquery ) {
								$sq = $field;
							}
							else {
								list($model, $field) = model_field( $field );

								if( $virtual ) {
									$this->loadModel( $model );
									$sq = $this->{$model}->sqVirtualField( $field, false );
								}
								else {
									$sq = "\"{$model}\".\"{$field}\"";
								}
							}

							if( 'date' === $type ) {
								$query['fields'][] = "DATE({$sq})::TEXT AS \"Action__{$fieldName}\"";
							}
							else if( 'integer' === $type ) {
								$query['fields'][] = "{$sq}::TEXT AS \"Action__{$fieldName}\"";
							}
							else {
								$query['fields'][] = "{$sq} AS \"Action__{$fieldName}\"";
							}
						}
					}
					else {
						if( in_array( $fieldName, array( 'view', 'edit' ) ) ) {
							$query['fields'][] = "'/".Inflector::tableize( $modelName )."/{$fieldName}/#Action.id#' AS \"Action__{$fieldName}\"";
						}
						else {
							$query['fields'][] = "NULL AS \"Action__{$fieldName}\"";
						}
					}
				}

				$query = array_words_replace( $query, $replacements );

				$this->Personne->{$modelName}->forceVirtualFields = true;
				$sqls[] = $this->Personne->{$modelName}->sq( $query );
			}

			$sql = implode( ' UNION ', $sqls )." ORDER BY \"Action__date\" DESC"; // FIXME: liens de tri
			// Fin mise en cache - modulo le tri
			$sql = str_replace( '#Personne.id#', $personne_id, $sql );

			$results = $this->Personne->query( $sql );

			return $results;
		}

		/**
		 * Retourne les options à utiliser dans la vue.
		 *
		 * @todo Nom de la méthode en paramètre + cache
		 *
		 * @return array
		 */
		public function options() {
			$result = array(
				'Accompagnement' => array(
					'topmoyloco' => $this->Personne->Dsp->enum( 'topmoyloco' ),
					'toppermicondu' => $this->Personne->Dsp->enum( 'toppermicondu' )
				),
				'Action' => array(
					'name' => array(
						'Rendezvouscollectif' => 'RDV collectif',
						'Rendezvousindividuel' => 'RDV individuel',
						'Contratinsertion' => 'CER',
						'Ficheprescription93' => 'Prescription',
						'Questionnaired1pdv93' => 'D1',
						'Questionnaired2pdv93' => 'D2',
						'DspRev' => 'MAJ DSP',
						'Entretien' => 'Entretien'
					)
				),
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => $this->Option->toppersdrodevorsa()
				),
				'Cer93' => array(
					'nivetu' => $this->Personne->Contratinsertion->Cer93->enum( 'nivetu' )
				),
				'Detailcalculdroitrsa' => array(
					'natpf_activite' => array( 0 => 'Non', 1 => 'Oui' ),
					'natpf_majore' => array( 0 => 'Non', 1 => 'Oui' ),
					'natpf_socle' => array( 0 => 'Non', 1 => 'Oui' )
				),
				'Dsp' => array(
					'nivetu' => $this->Personne->Dsp->enum( 'nivetu' )
				),
				'DspRev' => array(
					'nivetu' => $this->Personne->Dsp->enum( 'nivetu' )
				),
				'Questionnaired1pdv93' => array(
					'nivetu' => $this->Personne->Questionnaired1pdv93->enum( 'nivetu' )
				),
				'Situationdossierrsa' => array(
					'etatdosrsa' => $this->Option->etatdosrsa()
				)
			);

			return $result;
		}

		/**
		 * Récupère la liste des fichiers liés aux enregistrements d'un
		 * bénéficiaire.
		 *
		 * @fixme
		 *	- parfois, le fichier est lié à un enregistrement lié (ex. Passagecommissionep, <Thematiqueep>, ...)
		 *	- droits d'accès (contrôleur / component)
		 *	- mise en cache
		 *
		 * @param integer $personne_id L'id du bénéficiaire
		 * @return array
		 */
		public function fichiersmodules( $personne_id ) {
			// FIXME: "Fichiermodule"."modele" = 'Dsp' AND "Fichiermodule"."fk_value" = "DspRev"."id"
			$departement = (int)Configure::read( 'Cg.departement' );

			$join = $this->Personne->join( 'Fichiermodule', array( 'type' => 'LEFT OUTER' ) );
			// FIXME: primaryKey -> plus nécessaire après avoir réparé les relations .
			$condition = str_replace( '{$__cakeID__$}', "\"Personne\".\"id\"", $join['conditions'] );

			$fields = $this->Personne->Fichiermodule->fields();
			array_remove( $fields, 'Fichiermodule.document' );
			array_remove( $fields, 'Fichiermodule.cmspath' );

			$query = array(
				'fields' => $fields,
				'contain' => false,
				'joins' => array(),
				'conditions' => array(
					'Personne.id' => $personne_id,
					'OR' => array(
						$condition
					)
				),
				'order' => array(
					'Fichiermodule.modified DESC'
				),
				'group' => $fields
			);

			// TODO: fix Module / Fichiermodule relations
			// @see app/Model/Cui.php, app/Model/Fichiermodule.php

			foreach( array( 'hasMany', 'hasOne', 'hasAndBelongsToMany' ) as $relation ) {
				foreach( array_keys( $this->Personne->{$relation} ) as $alias ) {
					if( $relation === 'hasAndBelongsToMany' ) {
						if( isset( $this->Personne->{$relation}[$alias]['with'] ) ) {
							$alias = $this->Personne->{$relation}[$alias]['with'];
						}
						else { // FIXME
							debug( array( $alias => $this->Personne->{$relation}[$alias] ) );
						}
					}

					try {
						if(
							// TODO: config
							// FIXME: Dsp, DspRev
							( !preg_match( '/[0-9]{2,3}$/', $alias ) || preg_match( "/[^0-9]{$departement}\$/", $alias ) )
							&& isset( $this->Personne->{$alias}->Fichiermodule )
						) {
							$query['joins'][] = $this->Personne->join( $alias, array( 'type' => 'LEFT OUTER' ) );
							$join = $this->Personne->{$alias}->join( 'Fichiermodule', array( 'type' => 'INNER' ) );
							$query['conditions']['OR'][] = str_replace( '{$__cakeID__$}', "\"{$alias}\".\"id\"", $join['conditions'] ); // FIXME: primaryKey
						}
					} catch( Exception $e ) {
						debug($alias);
						debug($e);
					}
				}
			}

			// Jointure spéciale sur Fichiermodule
			$join = $this->Personne->join( 'Fichiermodule', array( 'type' => 'INNER' ) );
			$join['conditions'] = array( 'OR' => $query['conditions']['OR'] );
			unset( $query['conditions']['OR'] );
			$query['joins'][] = $join;

//			debug( $query );

			$fichiersmodules = $this->Personne->find( 'all', $query );
			// TODO: afterFind de Fichiermodule ?
			foreach( array_keys( $fichiersmodules ) as $key ) {
				$fichiersmodules[$key]['Fichiermodule']['controller'] = Inflector::tableize( $fichiersmodules[$key]['Fichiermodule']['modele'] );
			}

			return $fichiersmodules;
		}

		/**
		 * Récupère la liste des impressions liées aux enregistrements d'un
		 * bénéficiaire.
		 *
		 * @fixme
		 *	- ici, on ne prend que les PDF stockés
		 *	- parfois, le PDF est lié à un enregistrement lié (ex. Passagecommissionep, <Thematiqueep>, ...)
		 *	- droits d'accès (contrôleur / component)
		 *	- mise en cache
		 *
		 * @param integer $personne_id L'id du bénéficiaire
		 * @return array
		 */
		public function impressions( $personne_id ) {
			$departement = (int)Configure::read( 'Cg.departement' );

			$fields = $this->Pdf->fields();
			array_remove( $fields, 'Pdf.document' );
			array_remove( $fields, 'Pdf.cmspath' );

			$query = array(
				'fields' => $fields,
				'contain' => false,
				'joins' => array(),
				'conditions' => array(
					'OR' => array()
				),
				'order' => array(
					'Pdf.modified DESC'
				),
				'group' => $fields
			);

			foreach( array( 'hasMany', 'hasOne', 'hasAndBelongsToMany' ) as $relation ) {
				foreach( array_keys( $this->Personne->{$relation} ) as $alias ) {
					if( $relation === 'hasAndBelongsToMany' ) {
						if( isset( $this->Personne->{$relation}[$alias]['with'] ) ) {
							$alias = $this->Personne->{$relation}[$alias]['with'];
						}
						else { // FIXME
							debug( array( $alias => $this->Personne->{$relation}[$alias] ) );
						}
					}

					try {
						if(
							( !preg_match( '/[0-9]{2,3}$/', $alias ) || preg_match( "/[^0-9]{$departement}\$/", $alias ) )
							&& $this->Personne->{$alias}->Behaviors->attached( 'StorablePdf' )
						) {
							$join = $this->Personne->join( $alias, array( 'type' => 'LEFT OUTER' ) );
							$join['conditions'] = str_replace( '"Personne"."id"', $personne_id, $join['conditions'] );
							$query['joins'][] = $join;
							$query['conditions']['OR'][] = array(
								"\"Pdf\".\"fk_value\" = \"{$alias}\".\"id\" AND \"Pdf\".\"modele\" = '{$alias}'",
								"{$alias}.personne_id" => $personne_id
							);
						}
					} catch( Exception $e ) {
						debug($alias);
						debug($e);
					}
				}
			}

			$pdfs = $this->Pdf->find( 'all', $query );
			// TODO: afterFind de Pdf ?
			foreach( array_keys( $pdfs ) as $key ) {
				$pdfs[$key]['Pdf']['controller'] = Inflector::tableize( $pdfs[$key]['Pdf']['modele'] );
			}

			return $pdfs;
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			$departement = (int)Configure::read( 'Cg.departement' );

			if( 93 === $departement ) {
				$qdDetails = $this->qdDetails();
				$success = !empty( $qdDetails );
			}
			else {
				$success = null;
			}

			return $success;
		}
	}
?>