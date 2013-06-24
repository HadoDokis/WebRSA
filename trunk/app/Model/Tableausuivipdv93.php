<?php
	/**
	 * Code source de la classe Tableausuivipdv93.
	 *
	 * FIXME: limiter sur les zones géographiques (?)
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Tableausuivipdv93 ...
	 *
	 * @package app.Model
	 */
	class Tableausuivipdv93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Tableausuivipdv93';

		/**
		 * Récursivité par défaut de ce modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Validation.Autovalidate',
			'Formattable',
		);

		public $belongsTo = array(
			'Pdv' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
			'Photographe' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
		);

		/**
		 * Problématiques à utiliser dans le tableau 1 B3
		 *
		 * @var array
		 */
		public $problematiques = array(
			'sante',
			'logement',
			'familiales',
			'modes_gardes',
			'surendettement',
			'administratives',
			'linguistiques',
			'mobilisation',
			'qualification_professionnelle',
			'acces_emploi',
			'autres',
		);

		/**
		 * Problématiques à utiliser dans le tableau 1 B3
		 *
		 * @var array
		 */
		public $acteurs = array(
			'acteurs_sociaux',
			'acteurs_sante',
			'acteurs_culture',
		);

		/**
		 * Liste des tableaux disponibles
		 *
		 * @var array
		 */
		public $tableaux = array(
			'tableau1b3',
			'tableau1b4',
			'tableau1b5',
		);

		/**
		 * TODO: documentation
		 *
		 * @param string $field
		 * @return string
		 */
		protected function _conditionStatutRdv( $field = 'statutrdv_id' ) {
			$values = "'".implode( "', '", (array)Configure::read( 'Tableausuivipdv93.statutrdv_id' ) )."'";
			return "statutrdv_id IN ( {$values} )";
		}

		/**
		 * TODO: documentation
		 *
		 * @param string $field
		 * @return string
		 */
		protected function _conditionNumcodefamille( $field = 'numcodefamille', $typeacteur = null ) {
			$configureKey = 'Tableausuivipdv93.numcodefamille';
			if( !is_null( $typeacteur ) ) {
				$configureKey = "{$configureKey}.{$typeacteur}";
			}

			$values = "'".implode( "', '", Hash::flatten( (array)Configure::read( $configureKey ) ) )."'";
			return "numcodefamille IN ( {$values} )";
		}

		/**
		 * Volet I problématiques 1-B-3: problématiques des bénéficiaires de
		 * l'opération.
		 *
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b3( array $search ) {
			$Dsp = ClassRegistry::init( 'Dsp' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Filtre sur les DSP mises à jour dans l'année
			$conditionmaj = null;
			$dsp_maj = Hash::get( $search, 'Search.dsps_maj_dans_annee' );
			if( !empty( $dsp_maj ) ) {
				$conditionmaj = "AND dsps_revs.id IS NOT NULL AND EXTRACT( 'YEAR' FROM dsps_revs.modified ) = '{$annee}'";
			}

			$sql = "SELECT CASE
				-- dsps : si pas de DSP CG, on prend la DSP CAF
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc IN ('0402','0403')	THEN 'sante'
				WHEN dsps_revs.id IS NULL AND detailsdiflogs.diflog IN ('1004', '1005', '1006', '1007', '1008', '1009') THEN 'logement'
				WHEN dsps_revs.id IS NULL AND detailsaccosocfams.nataccosocfam = '0412' THEN 'familiales'
				WHEN dsps_revs.id IS NULL AND detailsdifdisps.difdisp IN ('0502', '0503', '0504')  THEN 'modes_gardes'
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc = '0406'		THEN 'surendettement'
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc = '0405'		THEN 'administratives'
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc = '0404'		THEN 'linguistiques'
				WHEN dsps_revs.id IS NULL AND dsps.nivetu IN ('1206','1207')		THEN 'qualification_professionnelle'
				WHEN dsps_revs.id IS NULL AND dsps.topengdemarechemploi ='0'		THEN 'acces_emploi'
				WHEN dsps_revs.id IS NULL AND detailsaccosocindis.nataccosocindi = '0420' THEN 'autres'
				--dsps_revs : si DSP CG, on la prend
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc IN ('0402','0403')	THEN 'sante'
				WHEN dsps_revs.id IS NOT NULL AND detailsdiflogs_revs.diflog IN ('1004', '1005', '1006', '1007', '1008', '1009')	THEN 'logement'
				WHEN dsps_revs.id IS NOT NULL AND detailsaccosocfams_revs.nataccosocfam = '0412' THEN 'familiales'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifdisps_revs.difdisp IN ('0502', '0503', '0504')  THEN 'modes_gardes'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc = '0406'		THEN 'surendettement'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc = '0405'		THEN 'administratives'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc = '0404'		THEN 'linguistiques'
				WHEN dsps_revs.id IS NOT NULL AND dsps_revs.nivetu IN ('1206','1207')		THEN 'qualification_professionnelle'
				WHEN dsps_revs.id IS NOT NULL AND dsps_revs.topengdemarechemploi ='0'		THEN 'acces_emploi'
				WHEN dsps_revs.id IS NOT NULL AND detailsaccosocindis_revs.nataccosocindi = '0420' THEN 'autres'
				END AS \"difficultés exprimées par les bénéficiaires\",
				COUNT(*)
			FROM dsps
				INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
				LEFT OUTER JOIN detailsdifsocs ON (dsps.id = detailsdifsocs.dsp_id)
				LEFT OUTER JOIN detailsdiflogs ON (dsps.id = detailsdiflogs.dsp_id)
				LEFT OUTER JOIN detailsaccosocfams ON (dsps.id = detailsaccosocfams.dsp_id)
				LEFT OUTER JOIN detailsdifdisps ON (dsps.id = detailsdifdisps.dsp_id)
				LEFT OUTER JOIN detailsnatmobs ON (dsps.id = detailsnatmobs.dsp_id)
				LEFT OUTER JOIN detailsaccosocindis ON (dsps.id = detailsaccosocindis.dsp_id)
				LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id
					AND (dsps_revs.personne_id, dsps_revs.id) IN (
						SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
				LEFT OUTER JOIN detailsdifsocs_revs ON (dsps_revs.id = detailsdifsocs_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsdiflogs_revs ON (dsps_revs.id = detailsdiflogs_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsaccosocfams_revs ON (dsps_revs.id = detailsaccosocfams_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsdifdisps_revs ON (dsps.id = detailsdifdisps_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsnatmobs_revs ON (dsps_revs.id = detailsnatmobs_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsaccosocindis_revs ON (dsps_revs.id = detailsaccosocindis_revs.dsp_rev_id)
			WHERE
				-- avec un RDV honoré durant l'année N
				EXTRACT('YEAR' FROM daterdv) = '{$annee}'
				AND ".$this->_conditionStatutRdv()."
				-- pour la structure referente X (éventuellement)
				{$conditionpdv}
				{$conditionmaj}
			GROUP BY \"difficultés exprimées par les bénéficiaires\";";

			$results = $Dsp->query( $sql );
			$results = Hash::combine( $results, '{n}.0.difficultés exprimées par les bénéficiaires', '{n}.0.count' );

			unset( $results[''] );
			$results['total'] = array_sum( array_values( $results ) );

			return $results;
		}

		/**
		 * Tableau 1-B-4: prescriptions vers les acteurs sociaux,
		 * culturels et de sante
		 *
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b4( array $search ) {
			$ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// FIXME: le PDV du RDV doit être le même que la SR du référent de la fiche de candidature ?
			// FIXME: on ne se préoccupe d'aucune date de la fiche de prescription (se baser sur datesignature ?)

			$sql = "
				(
					SELECT
						CASE
							WHEN ".$this->_conditionNumcodefamille( 'numcodefamille', 'acteurs_sociaux' )." THEN 'acteurs_sociaux'
							WHEN ".$this->_conditionNumcodefamille( 'numcodefamille', 'acteurs_sante' )." THEN 'acteurs_sante'
							WHEN ".$this->_conditionNumcodefamille( 'numcodefamille', 'acteurs_culture' )." THEN 'acteurs_culture'
						END AS libelle,
						COUNT(*) AS \"nombre\",
						COUNT(DISTINCT personne_id) AS \"nombre_unique\"
					FROM actionscandidats_personnes
						INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
					WHERE
						".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
						".str_replace( 'structurereferente_id', 'referents.structurereferente_id', $conditionpdv )."
						AND actionscandidats_personnes.personne_id IN (
							SELECT DISTINCT personne_id FROM rendezvous
							WHERE
								-- avec un RDV honoré durant l'année N
								EXTRACT('YEAR' FROM daterdv) = '{$annee}'
								AND ".$this->_conditionStatutRdv()."
								{$conditionpdv}
						)
					GROUP BY libelle
				)
				UNION
				(
					SELECT
							'total' AS libelle,
							COUNT(*) AS \"nombre\",
							COUNT(DISTINCT personne_id) AS \"nombre_unique\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- FIXME
							".str_replace( 'structurereferente_id', 'referents.structurereferente_id', $conditionpdv )."
							AND actionscandidats_personnes.personne_id IN (
								SELECT DISTINCT personne_id FROM rendezvous
								WHERE
									-- avec un RDV honoré durant l'année N
									EXTRACT('YEAR' FROM daterdv) = '{$annee}'
									AND ".$this->_conditionStatutRdv()."
									{$conditionpdv}
							)
				);";

			$results = array();
			$tmp_results = $ActioncandidatPersonne->query( $sql );
			if( !empty( $tmp_results ) ) {
				foreach( $tmp_results as $tmp_result ) {
					$tmp_result = $tmp_result[0];

					$results[$tmp_result['libelle']] = array(
						'nombre' => $tmp_result['nombre'],
						'nombre_unique' => $tmp_result['nombre_unique']
					);
				}
			}

			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableau1b5totaux( array $search ) {
			$ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );
			$results = array();

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Requête 0: total
			$sql = "
				SELECT
					(
						SELECT COUNT(DISTINCT personne_id)
							FROM actionscandidats_personnes
								INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							WHERE
								actionscandidats_personnes.personne_id IN (
									SELECT DISTINCT personne_id
									FROM rendezvous
									WHERE
										-- avec un RDV honoré durant l'année N
										EXTRACT('YEAR' FROM daterdv) = '{$annee}'
										AND ".$this->_conditionStatutRdv()."
										-- pour la structure referente X
										{$conditionpdv}
								)
					) AS \"Tableau1b5__distinct_personnes_prescription\",
					(
						SELECT COUNT(DISTINCT personne_id)
							FROM actionscandidats_personnes
								INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
								WHERE
									bilanvenu = 'VEN'
									AND actionscandidats_personnes.personne_id IN (
										SELECT DISTINCT personne_id
											FROM rendezvous
											WHERE
												-- avec un RDV honoré durant l'année N
												EXTRACT('YEAR' FROM daterdv) = '{$annee}'
												AND ".$this->_conditionStatutRdv()."
												-- pour la structure referente X
												{$conditionpdv}
									)
					) AS \"Tableau1b5__distinct_personnes_action\";";
			$results = Hash::merge( $results, $ActioncandidatPersonne->query( $sql ) );


			// Requête 5: Motifs pour lesquels la prescription n'est pas effective
			$sql = "SELECT
						--nbre de bénéficiaires qui ne se sont pas déplacés : retenu + non venu
						(
							SELECT COUNT(*)
								FROM actionscandidats_personnes
								INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
								WHERE
									actionscandidats_personnes.personne_id IN (
											SELECT DISTINCT personne_id
												FROM rendezvous
												WHERE
													-- avec un RDV honoré durant l'année N
													EXTRACT('YEAR' FROM daterdv) = '{$annee}'
													AND ".$this->_conditionStatutRdv()."
													-- pour la structure referente X
													{$conditionpdv}
									)
									AND bilanretenu = 'RET'
									AND bilanvenu != 'VEN'
						) AS \"Tableau1b5__beneficiaires_pas_deplaces\",
						--nbre de fiches de prescription en attente d'un retour : venu + dfaction IS NULL
						(
							SELECT COUNT(*)
								FROM actionscandidats_personnes
									INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
								WHERE
									actionscandidats_personnes.personne_id IN (
										SELECT DISTINCT personne_id
											FROM rendezvous
											WHERE
												-- avec un RDV honoré durant l'année N
												EXTRACT('YEAR' FROM daterdv) = '{$annee}'
												AND ".$this->_conditionStatutRdv()."
												-- pour la structure referente X
												{$conditionpdv}
									)
									AND bilanvenu = 'VEN'
									AND actionscandidats_personnes.dfaction IS NULL
						) AS \"Tableau1b5__nombre_fiches_attente\";";
			$results = Hash::merge( $results, $ActioncandidatPersonne->query( $sql ) );

			return $results[0];
		}

		/**
		 * TOD: nom de la fonction
		 *
		 * @param array $results
		 * @param string $sql
		 * @param array $map
		 * @param string $nameKey
		 * @param string $valueKey
		 * @return array
		 */
		protected function _foo( array $results, $sql, $map, $nameKey, $valueKey ) {
			$Actioncandidat = ClassRegistry::init( array( 'class' => 'Actioncandidat', 'alias' => 'Tableau1b5' ) );
			list( $modelName, $fieldName ) = model_field( $valueKey );

			$tmpresults = $Actioncandidat->query( $sql );
			$keysDiff = array_diff( array_keys( $map ), Hash::extract( $tmpresults, "{n}.{$nameKey}" ) );

			if( !empty( $tmpresults ) ) {
				foreach( $tmpresults as $tmpresult ) {
					$name = Hash::get( $tmpresult, $nameKey );
					$value = Hash::get( $tmpresult, $valueKey );
					$index = Hash::get( $map, $name );

					$results = Hash::insert( $results, "{$index}.Tableau1b5.{$fieldName}", $value ); // FIXME: nom du modèle
				}
			}

			if( !empty( $keysDiff ) ) {
				foreach( $keysDiff as $name ) {
					$index = Hash::get( $map, $name );

					$results = Hash::insert( $results, "{$index}.Tableau1b5.{$fieldName}", 0 ); // FIXME: nom du modèle
				}
			}

			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * FIXME: on ne prend pas en compte la date de la prescription ?
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b5( array $search ) {
			$Actioncandidat = ClassRegistry::init( array( 'class' => 'Actioncandidat', 'alias' => 'Tableau1b5' ) );

			// On obtient la liste des actions
			$results = $Actioncandidat->find(
				'all',
				array(
					'fields' => array(
						'Tableau1b5.id',
						'Tableau1b5.name',
					),
					'contain' => false,
					'order' => array( 'Tableau1b5.name ASC' )
				)
			);
			$map = array_flip( Hash::extract( $results, '{n}.Tableau1b5.name' ) );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Requête 1: Nb de prescriptions effectuées : total des prescriptions
			$sql = "
					SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescription_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						WHERE
							actionscandidats_personnes.personne_id IN (
								SELECT DISTINCT personne_id
									FROM rendezvous
									WHERE
										-- avec un RDV honoré durant l'année N
										EXTRACT('YEAR' FROM daterdv) = '{$annee}'
										AND ".$this->_conditionStatutRdv()."
										-- pour la structure referente X
										{$conditionpdv}
							)
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescription_count' );

			// Requête 2: nombre de prescription effectives : venu
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_effectives_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						WHERE
							bilanvenu = 'VEN'
							AND actionscandidats_personnes.personne_id IN (
								SELECT DISTINCT personne_id
									FROM rendezvous
									WHERE
										-- avec un RDV honoré durant l'année N
										EXTRACT('YEAR' FROM daterdv) = '{$annee}'
										AND ".$this->_conditionStatutRdv()."
										-- pour la structure referente X
										{$conditionpdv}
							)
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_effectives_count' );

			// Requête 3: Raisons de la non participation,
			// Requête 3.1: Refus du bénéficiaire -> FIXME: la requête n'a pas été fournie
			$sql = "SELECT
						NULL AS \"Tableausuivipdv93__prescription_name\",
						NULL AS \"Tableausuivipdv93__prescriptions_refus_beneficiaire_count\"
					FROM actionscandidats_personnes
					WHERE false;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_refus_beneficiaire_count' );

			// Requête 3.2: Refus de l'organisme : non retenu + non venu
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_refus_organisme_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						WHERE
							bilanretenu != 'RET'
							AND bilanvenu != 'VEN'
							AND actionscandidats_personnes.personne_id IN (
								SELECT DISTINCT personne_id
									FROM rendezvous
									WHERE
										-- avec un RDV honoré durant l'année N
										EXTRACT('YEAR' FROM daterdv) = '{$annee}'
										AND ".$this->_conditionStatutRdv()."
										-- pour la structure referente X
										{$conditionpdv}
							)
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_refus_organisme_count' );

			// Requête 3.3: En attente : ddaction > now ?
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_en_attente_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						WHERE
							actionscandidats_personnes.ddaction > NOW()
							AND actionscandidats_personnes.personne_id IN (
								SELECT DISTINCT personne_id
									FROM rendezvous
									WHERE
										-- avec un RDV honoré durant l'année N
										EXTRACT('YEAR' FROM daterdv) = '{$annee}'
										AND ".$this->_conditionStatutRdv()."
										-- pour la structure referente X
										{$conditionpdv}
							)
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_en_attente_count' );

			// Requête 3.3: En attente : ddaction > now ?
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_retenu_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						WHERE
							bilanretenu = 'RET'
							AND actionscandidats_personnes.personne_id IN (
								SELECT DISTINCT personne_id
									FROM rendezvous
									WHERE
										-- avec un RDV honoré durant l'année N
										EXTRACT('YEAR' FROM daterdv) = '{$annee}'
										AND ".$this->_conditionStatutRdv()."
										-- pour la structure referente X
										{$conditionpdv}
							)
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_retenu_count' );

			// Requête 4 : Abandon en cours d'action -> FIXME n'a pas été fournie
			$sql = "SELECT
						NULL AS \"Tableausuivipdv93__prescription_name\",
						NULL AS \"Tableausuivipdv93__prescriptions_abandon_count\"
					FROM actionscandidats_personnes
					WHERE false;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_abandon_count' );

			return array(
				'totaux' => $this->_tableau1b5totaux( $search ),
				'results' => $results
			);
		}

		/**
		 * Tableau 1-B-6: Actions collectives
		 *
		 * FIXME: RDV individuel
		 * FIXME: thématiques
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b6( array $search ) {
		}

		/**
		 * Retourne une liste ordonnée et traduite.
		 *
		 * @param string $type
		 * @param string $tableauName
		 * @return array
		 */
		protected function _listes( $type, $tableauName ) {
			$options = array();
			$domain = Inflector::tableize( $this->name );

			foreach( $this->{$type} as $intitule ) {
				$options[$intitule] = __d( $domain, "{$tableauName}.{$intitule}" );
			}

			return $options;
		}

		/**
		 * Retourne la liste des problématiques, ordonnées et traduites.
		 *
		 * @return array
		 */
		public function problematiques() {
			return $this->_listes( 'problematiques', 'Tableau1b3' );
		}

		/**
		 * Retourne la liste des types d'acteurs, ordonnées et traduites.
		 *
		 * @return array
		 */
		public function acteurs() {
			return $this->_listes( 'acteurs', 'Tableau1b4' );
		}

		/**
		 *
		 * @param string $action
		 * @param array $search
		 * @param integer $user_id
		 * @return boolean
		 */
		public function historiciser( $action, $search, $user_id = null ) {
			$results = $this->{$action}( $search );

			$tableausuivipdv93 = array(
				'Tableausuivipdv93' => array(
					'name' => $action,
					'annee' => Hash::get( $search, 'Search.annee' ),
					'structurereferente_id' => Hash::get( $search, 'Search.structurereferente_id' ),
					'version' => app_version(),
					'search' => serialize( $search ),
					'results' => serialize( $results ),
					'user_id' => $user_id
				)
			);

			// On sauvegarde au maximum une fois par jour les mêmes requêtes et résultats
			$conditions = Hash::flatten( $tableausuivipdv93 );
			$conditions["DATE_TRUNC( 'day', \"Tableausuivipdv93\".\"modified\" )"] = date( 'Y-m-d' );

			// A-t'on déjà sauvegardé exactement ce résultat ?
			$found = $this->find( 'first', array( 'conditions' => $conditions ) );

			// Si c'est le cas, on se contente de le réenregistrer pour qe la date de modifcation soit mise à jour
			if( !empty( $found ) ) {
				$tableausuivipdv93 = $found;
				unset(
					$tableausuivipdv93['Tableausuivipdv93']['created'],
					$tableausuivipdv93['Tableausuivipdv93']['modified']
				);
			}

			$this->create( $tableausuivipdv93 );
			return $this->save();
		}
	}
?>