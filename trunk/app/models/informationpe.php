<?php
	class Informationpe extends AppModel
	{
		public $name = 'Informationpe';

        public $recursive = -1;

        // FIXME: validation
		// FIXME ?
		/*public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => false,
				'conditions' => array(
					'OR' => array(
						array(
							'Personne.nir IS NOT NULL',
							'Informationpe.nir IS NOT NULL',
							'Personne.nir = Informationpe.nir',
						),
						array(
							'Personne.nom = Informationpe.nom',
							'Personne.prenom = Informationpe.prenom',
							'Personne.dtnai = Informationpe.dtnai',
						),
					)
				),
				'fields' => '',
				'order' => ''
			)
		);*/

		public $hasMany = array(
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'informationpe_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

		/**
		*
		*/

		public function qdRadies() {
			$queryData['fields'][] = 'Historiqueetatpe.id';
			$queryData['fields'][] = 'Historiqueetatpe.informationpe_id';
			$queryData['fields'][] = 'Historiqueetatpe.etat';
			$queryData['fields'][] = 'Historiqueetatpe.identifiantpe';
			$queryData['fields'][] = 'Historiqueetatpe.date';
			
			$queryData['joins'][] = array(
				'table'      => 'informationspe', // FIXME:
				'alias'      => 'Informationpe',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'OR' => array(
						array(
							'Informationpe.nir IS NOT NULL',
							'Personne.nir IS NOT NULL',
							'Informationpe.nir = Personne.nir',
						),
						array(
							'Informationpe.nom = Personne.nom',
							'Informationpe.prenom = Personne.prenom',
							'Informationpe.dtnai = Personne.dtnai',
						)
					)
				)
			);
			$queryData['joins'][] = array(
				'table'      => 'historiqueetatspe', // FIXME:
				'alias'      => 'Historiqueetatpe',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Historiqueetatpe.informationpe_id = Informationpe.id',
					'Historiqueetatpe.id IN (
								SELECT h.id
									FROM historiqueetatspe AS h
									WHERE h.informationpe_id = Informationpe.id
									ORDER BY h.date DESC
									LIMIT 1
					)'
				)
			);

			// FIXME: seulement pour certains motifs
			$queryData['conditions']['Historiqueetatpe.etat'] = 'radiation';
			$queryData['order'] = array( 'Historiqueetatpe.date ASC' );

			return $queryData;
		}
		
		public function qdNonInscrits() {
			// FIXME: à pouvoir paramétrer dans le webrsa.inc
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData['fields'][] = 'Orientstruct.date_valid';
			$queryData['fields'][] = 'Typeorient.lib_type_orient';
			$queryData['fields'][] = 'Structurereferente.lib_struc';
			
			//$queryData['conditions'][] = 'Orientstruct.date_valid < \''.date( 'Y-m-d', strtotime( '-2 month' ) ).'\'';
			$queryData['conditions'][] = 'Orientstruct.date_valid + INTERVAL \''.Configure::read( 'Selectionnoninscritspe.intervalleDetection' ).'\' < DATE_TRUNC( \'day\', NOW() )';

			$queryData['conditions'][] = 'Personne.id NOT IN (
				SELECT
						personnes.id
					FROM informationspe
						INNER JOIN historiqueetatspe ON (
							informationspe.id = historiqueetatspe.informationpe_id
							AND historiqueetatspe.id IN (
										SELECT h.id
											FROM historiqueetatspe AS h
											WHERE h.informationpe_id = informationspe.id
											ORDER BY h.date DESC
											LIMIT 1
							)
						)
						INNER JOIN personnes ON (
							(
								personnes.nir IS NOT NULL
								AND informationspe.nir IS NOT NULL
								AND personnes.nir = informationspe.nir
							)
							OR (
								personnes.nom = informationspe.nom
								AND personnes.prenom = informationspe.prenom
								AND personnes.dtnai = informationspe.dtnai
							)
						)
					WHERE
						personnes.id = Personne.id
						AND historiqueetatspe.etat = \'inscription\'
						AND historiqueetatspe.date >= Orientstruct.date_valid
			)';
/*			
			$queryData['joins'][] = array(
				'table'      => 'informationspe', // FIXME:
				'alias'      => 'Informationpe',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'OR' => array(
						array(
							'Informationpe.nir IS NOT NULL',
							'Personne.nir IS NOT NULL',
							'Informationpe.nir = Personne.nir',
						),
						array(
							'Informationpe.nom = Personne.nom',
							'Informationpe.prenom = Personne.prenom',
							'Informationpe.dtnai = Personne.dtnai',
						)
					)
				)
			);
			$queryData['joins'][] = array(
				'table'      => 'historiqueetatspe', // FIXME:
				'alias'      => 'Historiqueetatpe',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Historiqueetatpe.informationpe_id = Informationpe.id',
					'Historiqueetatpe.id IN (
								SELECT h.id
									FROM historiqueetatspe AS h
									WHERE h.informationpe_id = Informationpe.id
									ORDER BY h.date DESC
									LIMIT 1
					)'
				)
			);*/
			
			$queryData['joins'][] = array(
				'table'      => 'typesorients',
				'alias'      => 'Typeorient',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Typeorient.id = Orientstruct.typeorient_id'
				)
			);
			$queryData['joins'][] = array(
				'table'      => 'structuresreferentes',
				'alias'      => 'Structurereferente',
				'type'       => 'INNER',
				'foreignKey' => false,
				'conditions' => array(
					'Structurereferente.id = Orientstruct.structurereferente_id'
				)
			);

			$queryData['order'] = array( 'Orientstruct.date_valid ASC' );

			return $queryData;
		}
		
		
		/**
		 * Récupère le dernier identifiant Pôle Emploi d'une personne donnée.
		 * Note : l'utilisation de l'identifiant Personne.idassedic est déconseillé.
		 * @param $personneId 
		 */
		public function dernierIdentifiantpe( $personneId)
		{
			$query = "
				SELECT
					historiqueetatspe.identifiantpe
					FROM informationspe
						INNER JOIN historiqueetatspe ON (
							informationspe.id = historiqueetatspe.informationpe_id
							AND historiqueetatspe.id IN (
								SELECT h.id
									FROM historiqueetatspe AS h
									WHERE h.informationpe_id = informationspe.id
									ORDER BY h.date DESC
									LIMIT 1
							)
						)
						INNER JOIN personnes ON (
							(
								personnes.nir IS NOT NULL
								AND personnes.dtnai IS NOT NULL
								AND nir_correct( personnes.nir )
								AND informationspe.nir IS NOT NULL
								AND personnes.nir = informationspe.nir
								AND personnes.dtnai = informationspe.dtnai
							)
							OR (
								personnes.nom IS NOT NULL
								AND personnes.prenom IS NOT NULL
								AND personnes.dtnai IS NOT NULL
								AND TRIM( BOTH ' ' FROM personnes.nom ) = TRIM( BOTH ' ' FROM informationspe.nom )
								AND TRIM( BOTH ' ' FROM personnes.prenom ) = TRIM( BOTH ' ' FROM informationspe.prenom )
								AND personnes.dtnai = informationspe.dtnai
							)
						)
					WHERE personnes.id = {$personneId}
				;";
			$result = $this->query( $query );
			return array('Informationpe'=> Set::classicExtract( $result, '0.0' ) );
		}

		public function derniereInformation( $personne ) {
			$infope = $this->find(
				'first',
				array(
					'contain' => array(
						'Historiqueetatpe' => array(
							'order' => "Historiqueetatpe.date DESC",
							'limit' => 1
						)
					),
					'conditions' => array(
						'OR' => array(
							array(
								'Informationpe.nir' => Set::classicExtract( $personne, 'Personne.nir' ),
								'Informationpe.nir IS NOT NULL',
								'LENGTH(Informationpe.nir) = 15',
								'Informationpe.dtnai' => Set::classicExtract( $personne, 'Personne.dtnai' ),
							),
							array(
								'Informationpe.nom' => Set::classicExtract( $personne, 'Personne.nom' ),
								'Informationpe.prenom' => Set::classicExtract( $personne, 'Personne.prenom' ),
								'Informationpe.dtnai' => Set::classicExtract( $personne, 'Personne.dtnai' ),
							)
						)

					)
				)
			);
			return $infope;
		}

		/**
		* Retourne une array à utiliser comme jointure entre la table personnes
		* et la table informationspe.
		*
		* @param string $aliasPersonne Alias pour la table personnes
		* @param string $aliasInformationpe Alias pour la table informationspe
		* @param string $type Type de jointure à effectuer
		* @return array
		*/

		public function joinPersonneInformationpe( $aliasPersonne = 'Personne', $aliasInformationpe = 'Informationpe', $type = 'LEFT OUTER' ) {
			return array(
				'table'      => 'informationspe',
				'alias'      => $aliasInformationpe,
				'type'       => $type,
				'foreignKey' => false,
				'conditions' => array(
					'OR' => array(
						array(
							"{$aliasInformationpe}.nir IS NOT NULL",
							"LENGTH({$aliasInformationpe}.nir) = 15",
							"{$aliasInformationpe}.nir = {$aliasPersonne}.nir",
							"{$aliasInformationpe}.dtnai = {$aliasPersonne}.dtnai",
						),
						array(
							"UPPER({$aliasInformationpe}.nom) = UPPER({$aliasPersonne}.nom)",
							"UPPER({$aliasInformationpe}.prenom) = UPPER({$aliasPersonne}.prenom)",
							"{$aliasInformationpe}.dtnai = {$aliasPersonne}.dtnai"
						)
					)
				)
			);
		}

		/**
		* Vérifie le délai (intervalle) accordé pour la détection des allocataires
		* non inscrits au Pôle Emploi par rapport à leur date de validation d'orientation
		*/

		public function checkConfigUpdateIntervalleDetectionNonInscritsPe() {
			return $this->_checkSqlIntervalSyntax( Configure::read( 'Selectionnoninscritspe.intervalleDetection' ) );
		}
	}
?>