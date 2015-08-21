<?php
	/**
	 * Code source de la classe AbstractWebrsaRechercheDefautinsertionep66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );
	
	/**
	 * La classe AbstractWebrsaRechercheDefautinsertionep66 ...
	 *
	 * @package app.Model
	 */
	abstract class AbstractWebrsaRechercheDefautinsertionep66 extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'AbstractWebrsaRechercheDefautinsertionep66';
		
		/**
		 * Modèles utilisés
		 * @var array
		 */
		public $uses = array(
			'Allocataire', 
			'Personne', 
			'Canton',
			'Historiqueetatpe',
			'Dossierep'
		);

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Defautsinsertionseps66.search_noninscrits.fields',
			'Defautsinsertionseps66.search_noninscrits.innerTable',
			'Defautsinsertionseps66.search_radies.fields',
			'Defautsinsertionseps66.search_radies.innerTable',
		);

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$cgDepartement = Configure::read( 'Cg.departement' );
			
			$types += array(
				'Calculdroitrsa' => 'RIGHT',
				'Foyer' => 'INNER',
				'Prestation' => 'LEFT OUTER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'LEFT OUTER',
				'Structurereferente' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
				'Orientstruct' => 'LEFT OUTER',
			);
			
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Personne' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Personne,
							$this->Personne->Orientstruct,
							$this->Personne->Foyer,
							$this->Personne->Foyer->Dossier->Situationdossierrsa,
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Personne.id',
					)
				);
				
				// 2. Jointures
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Personne->join('Orientstruct', array(
							'type' => $types['Orientstruct'],
							'conditions' => array(
								'"Orientstruct"."id" IN (SELECT "o"."id"
											FROM orientsstructs AS o
											WHERE
												"o"."personne_id" = "Personne"."id"
												AND "o"."date_valid" IS NOT NULL
											ORDER BY "o"."date_valid" DESC
											LIMIT 1) AND "Orientstruct"."typeorient_id" IN (SELECT "t"."id"
									FROM typesorients AS t
									WHERE "t"."lib_type_orient" LIKE \'Emploi %\')'
							)
						))
					)
				);

				// 3. Si on utilise les cantons, on ajoute une jointure
				if( Configure::read( 'CG.cantons' ) ) {
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $this->Canton->joinAdresse();
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
			$query = $this->getConditionEp( $this->Allocataire->searchConditions( $query, $search ) );
			
			/**
			 * Conditions spéciales
			 */
			$month = Hash::get($search, 'Orientstruct.date_valid.month');
			if ( $month ) {
				$query['conditions']['EXTRACT(MONTH FROM Orientstruct.date_valid) = '] = $month;
			}
			
			$year = Hash::get($search, 'Orientstruct.date_valid.year');
			if ( $year ) {
				$query['conditions']['EXTRACT(YEAR FROM Orientstruct.date_valid) = '] = $year;
			}
			
			return $query;
		}
		
		/**
		 * Conditions de defaut d'insertion EP
		 * 
		 * @param array $query
		 * @return array
		 */
		public function getConditionEp( $query = array() ) {
			$query['conditions'][] = array(
				// On ne veut pas les personnes ayant un dossier d'EP en cours de traitement
				'Personne.id NOT IN ('.$this->Dossierep->sq(
					array(
						'fields' => array( 'dossierseps1.personne_id' ),
						'alias' => 'dossierseps1',
						'conditions' => array(
							'dossierseps1.actif' => '1',
							'dossierseps1.personne_id = Personne.id',
							'dossierseps1.themeep' => 'defautsinsertionseps66',
							'dossierseps1.id IN ('.$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array( 'passagescommissionseps1.dossierep_id' ),
									'alias' => 'passagescommissionseps1',
									'conditions' => array(
										'passagescommissionseps1.dossierep_id = dossierseps1.id',
										'passagescommissionseps1.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'reporte' )
									)
								)
							).')'
						)
					)
				).')',
				// Ni celles qui ont un dossier d'EP pour la thématique ayant été traité en commission plus récemment que 2 mois
				'Personne.id NOT IN ('.$this->Dossierep->sq(
					array(
						'fields' => array( 'dossierseps2.personne_id' ),
						'alias' => 'dossierseps2',
						'conditions' => array(
							'dossierseps2.personne_id = Personne.id',
							'dossierseps2.themeep' => 'defautsinsertionseps66',
							'dossierseps2.id IN ('.$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array( 'passagescommissionseps2.dossierep_id' ),
									'alias' => 'passagescommissionseps2',
									'conditions' => array(
										'passagescommissionseps2.dossierep_id = dossierseps2.id',
										'passagescommissionseps2.etatdossierep' => array( 'traite', 'annule' )
									),
									'joins' => array(
										array(
											'table' => 'commissionseps',
											'alias' => 'commissionseps',
											'type' => 'INNER',
											'conditions' => array(
												'commissionseps.id = passagescommissionseps2.commissionep_id',
												'commissionseps.dateseance >=' => date( 'Y-m-d', strtotime( '-2 mons' ) ) // FIXME: paramétrage
											)
										)
									)
								)
							).')'
						)
					)
				).')',
				// Ni celles dont le dossier n'a pas encore été associé à une commission
				'Personne.id NOT IN ('.$this->Dossierep->sq(
					array(
						'fields' => array( 'dossierseps3.personne_id' ),
						'alias' => 'dossierseps3',
						'conditions' => array(
							'dossierseps3.actif' => '1',
							'dossierseps3.personne_id = Personne.id',
							'dossierseps3.themeep' => 'defautsinsertionseps66',
							'dossierseps3.id NOT IN ('.$this->Dossierep->Passagecommissionep->sq(
								array(
									'fields' => array( 'passagescommissionseps3.dossierep_id' ),
									'alias' => 'passagescommissionseps3',
									'conditions' => array(
										'passagescommissionseps3.dossierep_id = dossierseps3.id',
									)
								)
							).')'
						)
					)
				).')',
			);
			
			return $query;
		}
	}
?>