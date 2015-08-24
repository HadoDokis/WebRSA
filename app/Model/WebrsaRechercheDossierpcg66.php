<?php
	/**
	 * Code source de la classe WebrsaRechercheDossierpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheDossierpcg66 ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheDossierpcg66 extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheDossierpcg66';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'ConfigurableQueryDossierspcgs66.search.fields',
			'ConfigurableQueryDossierspcgs66.search.innerTable',
			'ConfigurableQueryDossierspcgs66.exportcsv',
			'ConfigurableQueryDossierspcgs66.search_gestionnaire.fields',
			'ConfigurableQueryDossierspcgs66.search_gestionnaire.innerTable',
			'ConfigurableQueryDossierspcgs66.exportcsv_gestionnaire'
		);

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Allocataire',
			'Dossierpcg66',
			'Canton',
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
				'Decisiondossierpcg66' => 'LEFT OUTER',
				'Personnepcg66' => 'LEFT OUTER',
				'Traitementpcg66' => 'LEFT OUTER',
				'Detailcalculdroitrsa' => 'LEFT OUTER',
				'Categorieromev3' => 'LEFT OUTER',
				'User' => 'LEFT OUTER',
				'Poledossierpcg66' => 'LEFT OUTER',
				'Decisionpdo' => 'LEFT OUTER',
				'Familleromev3' => 'LEFT OUTER',
				'Domaineromev3' => 'LEFT OUTER',
				'Metierromev3' => 'LEFT OUTER',
				'Appellationromev3' => 'LEFT OUTER',
				'Categoriemetierromev2' => 'LEFT OUTER',
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Dossierpcg66' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Dossierpcg66,
							$this->Dossierpcg66->Foyer->Personne->PersonneReferent,
							$this->Dossierpcg66->User,
							$this->Dossierpcg66->Poledossierpcg66,
							$this->Dossierpcg66->Decisiondossierpcg66,
							$this->Dossierpcg66->Personnepcg66->Traitementpcg66,
							$this->Dossierpcg66->Decisiondossierpcg66->Decisionpersonnepcg66->Decisionpdo,
							$this->Dossierpcg66->Personnepcg66->Categorieromev3,
							$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3,
							$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3->Domaineromev3,
							$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3->Domaineromev3->Metierromev3,
							$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3->Domaineromev3->Metierromev3->Appellationromev3,
							$this->Dossierpcg66->Personnepcg66->Categoriemetierromev2,
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Dossierpcg66.id',
						'Dossierpcg66.foyer_id',

						'Decisiondossierpcg66.org_id' => '(\'<ul>\' || ARRAY_TO_STRING(ARRAY('
						. 'SELECT \'<li>\' || "Orgtransmisdossierpcg66"."name" || \'</li>\' AS "Orgtransmisdossierpcg66__name" '
						. 'FROM "decisionsdossierspcgs66" AS "Decisiondossierpcg66" '
						. 'LEFT JOIN "public"."decsdospcgs66_orgsdospcgs66" AS "Decdospcg66Orgdospcg66" '
						. 'ON ("Decdospcg66Orgdospcg66"."decisiondossierpcg66_id" = "Decisiondossierpcg66"."id") '
						. 'LEFT JOIN "public"."orgstransmisdossierspcgs66" AS "Orgtransmisdossierpcg66" '
						. 'ON ("Decdospcg66Orgdospcg66"."orgtransmisdossierpcg66_id" = "Orgtransmisdossierpcg66"."id") '
						. 'WHERE "Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id" '
						. 'ORDER BY "Decisiondossierpcg66"."created" DESC), \'\') || \'</ul>\') '
						. 'AS "Decisiondossierpcg66__org_id"',

						'Traitementpcg66.situationpdo_id' => '(\'<ul>\' || ARRAY_TO_STRING(ARRAY('
						. 'SELECT \'<li>\' || "Situationpdo"."libelle" || \'</li>\' AS "Situationpdo__libelle" '
						. 'FROM "personnespcgs66" AS "Personnepcg66" '
						. 'LEFT OUTER JOIN "public"."personnespcgs66_situationspdos" AS "Personnepcg66Situationpdo" '
						. 'ON ("Personnepcg66Situationpdo"."personnepcg66_id" = "Personnepcg66"."id") '
						. 'LEFT OUTER JOIN "public"."situationspdos" AS "Situationpdo" '
						. 'ON ("Personnepcg66Situationpdo"."situationpdo_id" = "Situationpdo"."id") '
						. 'WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id"), \'\') || \'</ul>\') '
						. 'AS "Traitementpcg66__situationpdo_id"',

						'Traitementpcg66.statutpdo_id' => '(\'<ul>\' || ARRAY_TO_STRING(ARRAY('
						. 'SELECT \'<li>\' || "Statutpdo"."libelle" || \'</li>\' AS "Statutpdo__libelle" '
						. 'FROM "personnespcgs66" AS "Personnepcg66" '
						. 'LEFT OUTER JOIN "public"."personnespcgs66_statutspdos" AS "Personnepcg66Statutpdo" '
						. 'ON ("Personnepcg66Statutpdo"."personnepcg66_id" = "Personnepcg66"."id") '
						. 'LEFT OUTER JOIN "public"."statutspdos" AS "Statutpdo" '
						. 'ON ("Personnepcg66Statutpdo"."statutpdo_id" = "Statutpdo"."id") '
						. 'WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id"), \'\') || \'</ul>\') '
						. 'AS "Traitementpcg66__statutpdo_id"',

						'Dossierpcg66.listetraitements' => '(\'<ul>\' || ARRAY_TO_STRING(ARRAY('
						. 'SELECT \'<li>\' || "Traitementpcg66"."typetraitement" || \'</li>\' AS "Traitementpcg66__typetraitement" '
						. 'FROM "traitementspcgs66" AS "Traitementpcg66" '
						. 'INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" '
						. 'ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") '
						. 'WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id"), \'\') || \'</ul>\') '
						. 'AS "Dossierpcg66__listetraitements"',

						'Fichiermodule.nb_fichiers_lies' => '(SELECT COUNT("fichiermodule"."id") '
						. 'FROM "fichiersmodules" AS "fichiermodule" '
						. 'WHERE "fichiermodule"."modele" = \'Foyer\' '
						. 'AND "fichiermodule"."fk_value" = "Foyer"."id") '
						. 'AS "Fichiermodule__nb_fichiers_lies"',

						'Personnepcg66.nbtraitements' => '(SELECT COUNT(*) '
						. 'FROM traitementspcgs66 '
						. 'WHERE traitementspcgs66.personnepcg66_id = "Personnepcg66"."id") '
						. 'AS "Personnepcg66__nbtraitements"',
					)
				);

				// 2. Jointures
				$traitementSq = 'SELECT "traitementspcgs66"."id" AS traitementspcgs66__id FROM traitementspcgs66 AS traitementspcgs66 WHERE "traitementspcgs66"."personnepcg66_id" = "Personnepcg66"."id" ORDER BY "traitementspcgs66"."created" DESC LIMIT 1';
				$conditionTraitement = array(
					'OR' => array(
						'Traitementpcg66.id' => null,
						array(
							'Traitementpcg66.id IN ('.$traitementSq.')',
							'Traitementpcg66.typetraitement' => 'documentarrive',
							'Traitementpcg66.datereception IS NOT NULL',
							'Dossierpcg66.etatdossierpcg' => 'attinstrdocarrive'
						)
					)
				);
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Dossierpcg66->Foyer->Dossier->Detaildroitrsa->join('Detailcalculdroitrsa', array('type' => $types['Detailcalculdroitrsa'])),
						$this->Dossierpcg66->join('Decisiondossierpcg66', array('type' => $types['Decisiondossierpcg66'])),
						$this->Dossierpcg66->join('User', array('type' => $types['User'])),
						$this->Dossierpcg66->join('Poledossierpcg66', array('type' => $types['Poledossierpcg66'])),
						$this->Dossierpcg66->join('Personnepcg66', array(
							'type' => $types['Personnepcg66'],
							'conditions' => 'Personnepcg66.personne_id = Personne.id'
						)),
						$this->Dossierpcg66->Personnepcg66->join('Categorieromev3', array('type' => $types['Categorieromev3'])),
						$this->Dossierpcg66->Personnepcg66->Categorieromev3->join('Familleromev3', array('type' => $types['Familleromev3'])),
						$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3->join('Domaineromev3', array('type' => $types['Domaineromev3'])),
						$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3->Domaineromev3->join('Metierromev3', array('type' => $types['Metierromev3'])),
						$this->Dossierpcg66->Personnepcg66->Categorieromev3->Familleromev3->Domaineromev3->Metierromev3->join('Appellationromev3', array('type' => $types['Appellationromev3'])),
						$this->Dossierpcg66->Personnepcg66->join('Categoriemetierromev2', array('type' => $types['Categoriemetierromev2'])),
						$this->Dossierpcg66->Personnepcg66->join('Traitementpcg66', array(
							'type' => $types['Traitementpcg66'],
							'conditions' => $conditionTraitement
						)),
						$this->Dossierpcg66->Decisiondossierpcg66->join('Decisionpdo', array('type' => $types['Decisionpdo'])),
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
			$query = $this->Allocataire->searchConditions( $query, $search );

			// Conditions obligatoire
			$query['conditions'][] = array(
				'Prestation.rolepers' => 'DEM',
				array(
					'OR' => array(
						'Detailcalculdroitrsa.id' => null,
						'Detailcalculdroitrsa.id IN ('
						. 'SELECT "detailscalculsdroitsrsa"."id" AS detailscalculsdroitsrsa__id '
						. 'FROM detailscalculsdroitsrsa AS detailscalculsdroitsrsa '
						. 'WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" '
						. 'ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC '
						. 'LIMIT 1)'
					)
				),
				array(
					'OR' => array(
						'Decisiondossierpcg66.id' => null,
						'Decisiondossierpcg66.id IN ('
						. 'SELECT "decisionsdossierspcgs66"."id" '
						. 'FROM decisionsdossierspcgs66 '
						. 'WHERE "decisionsdossierspcgs66"."dossierpcg66_id" = "Dossierpcg66"."id" '
						. 'ORDER BY "decisionsdossierspcgs66"."created" DESC '
						. 'LIMIT 1)'
					)
				),
				array(
					'OR' => array(
						'Categorieromev3.familleromev3_id IS NULL',
						'Familleromev3.id = Categorieromev3.familleromev3_id',
					)
				),
				array(
					'OR' => array(
						'Categorieromev3.domaineromev3_id IS NULL',
						'Domaineromev3.id = Categorieromev3.domaineromev3_id',
					)
				),
				array(
					'OR' => array(
						'Categorieromev3.metierromev3_id IS NULL',
						'Metierromev3.id = Categorieromev3.metierromev3_id',
					)
				),
				array(
					'OR' => array(
						'Categorieromev3.appellationromev3_id IS NULL',
						'Appellationromev3.id = Categorieromev3.appellationromev3_id',
					)
				),
				array(
					'OR' => array(
						'Personnepcg66.categoriedetail IS NULL',
						'Categoriemetierromev2.id = Personnepcg66.categoriedetail',
					)
				),
			);

			/**
			 * Generateur de conditions
			 */
			$paths = array(
				'Dossierpcg66.originepdo_id',
				'Dossierpcg66.typepdo_id',
				'Dossierpcg66.orgpayeur',
				'Dossierpcg66.poledossierpcg66_id',
				'Dossierpcg66.user_id',
				'Dossierpcg66.etatdossierpcg',
				'Dossierpcg66.nbpropositions',
				'Decisiondossierpcg66.useravistechnique_id',
				'Decisiondossierpcg66.userproposition_id',
				'Decisiondossierpcg66.decisionpdo_id',
				'Categorieromev3.familleromev3_id',
			);

			$pathsToExplode = array(
				'Categorieromev3.domaineromev3_id',
				'Categorieromev3.metierromev3_id',
				'Categorieromev3.appellationromev3_id',
			);

			$pathsDate = array(
				'Dossierpcg66.datereceptionpdo',
				'Dossierpcg66.dateaffectation',
				'Decisiondossierpcg66.datevalidation',
				'Decisiondossierpcg66.datetransmissionop'
			);

			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			foreach( $pathsToExplode as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' && strpos($value, '_') > 0 ) {
					list(,$value) = explode('_', $value);
					$query['conditions'][$path] = $value;
				}
			}

			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $pathsDate );

			/**
			 * Conditions spéciales
			 */
			$nbproposition = Hash::get($search, 'Decisiondossierpcg66.nbproposition');
			if ( $nbproposition ) {
				$query['conditions'][] = array(
					'(SELECT COUNT(*) '
					. 'FROM decisionsdossierspcgs66 '
					. 'WHERE "decisionsdossierspcgs66"."dossierpcg66_id" = "Dossierpcg66"."id")' => $nbproposition
				);
			}

			$org_id = Hash::get($search, 'Decisiondossierpcg66.org_id');
			if ( $org_id ) {
				$query['conditions'][] = array(
					'Decisiondossierpcg66.id IN ('
					. 'SELECT "decsdospcgs66_orgsdospcgs66"."decisiondossierpcg66_id" AS decsdospcgs66_orgsdospcgs66__decisiondossierpcg66_id '
					. 'FROM decsdospcgs66_orgsdospcgs66 AS decsdospcgs66_orgsdospcgs66 '
					. 'INNER JOIN "public"."orgstransmisdossierspcgs66" AS orgstransmisdossierspcgs66 '
					. 'ON ("decsdospcgs66_orgsdospcgs66"."orgtransmisdossierpcg66_id" = "orgstransmisdossierspcgs66"."id") '
					. 'WHERE "decsdospcgs66_orgsdospcgs66"."orgtransmisdossierpcg66_id" IN ('.implode(', ', $org_id).'))'
				);
			}

			$situationpdo_id = Hash::get($search, 'Traitementpcg66.situationpdo_id');
			if ( $situationpdo_id ) {
				$query['conditions'][] = array(
					 'Personnepcg66.id IN ('
					. 'SELECT "personnespcgs66_situationspdos"."personnepcg66_id" AS personnespcgs66_situationspdos__personnepcg66_id '
					. 'FROM personnespcgs66_situationspdos AS personnespcgs66_situationspdos '
					. 'INNER JOIN "public"."situationspdos" AS situationspdos '
					. 'ON ("personnespcgs66_situationspdos"."situationpdo_id" = "situationspdos"."id") '
					. 'WHERE "personnespcgs66_situationspdos"."situationpdo_id" IN ('.implode(', ', $situationpdo_id).'))'
				);
			}

			$statutpdo_id = Hash::get($search, 'Traitementpcg66.statutpdo_id');
			if ( $statutpdo_id ) {
				$query['conditions'][] = array(
					 '"Personnepcg66"."id" IN ('
					. 'SELECT "personnespcgs66_statutspdos"."personnepcg66_id" AS personnespcgs66_statutspdos__personnepcg66_id '
					. 'FROM personnespcgs66_statutspdos AS personnespcgs66_statutspdos '
					. 'INNER JOIN "public"."statutspdos" AS statutspdos '
					. 'ON ("personnespcgs66_statutspdos"."statutpdo_id" = "statutspdos"."id") '
					. 'WHERE "personnespcgs66_statutspdos"."statutpdo_id" IN ('.implode(', ', $statutpdo_id).'))'
				);
			}

			if ( Hash::get($search, 'Dossierpcg66.dossierechu') ) {
				$query['conditions'][] = 'Traitementpcg66.id IN ( ' . $this->Dossierpcg66->Personnepcg66->Traitementpcg66->sqTraitementpcg66Echu('Personnepcg66.id') . ' )';
			}

			return $query;
		}
	}
?>