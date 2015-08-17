<?php
	/**
	 * Code source de la classe WebrsaRechercheInfofinanciere.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheInfofinanciere ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheInfofinanciere extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheInfofinanciere';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Infofinancieres.search.fields',
			'Infofinancieres.search.innerTable',
			'Infofinancieres.exportcsv'
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
				'Calculdroitrsa' => 'INNER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'LEFT OUTER',
				'Dossier' => 'INNER',
				'Adresse' => 'LEFT OUTER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Personne' => 'INNER',
				'Detailcalculdroitrsa' => 'LEFT OUTER',
			);
			
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$Infofinanciere = ClassRegistry::init( 'Infofinanciere' );

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $Allocataire->searchQuery( $types, 'Dossier' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$Infofinanciere,
							$Infofinanciere->Dossier->Foyer->Personne->PersonneReferent,
						)
					),
					// Champs nécessaires au traitement de la search
					array(
						'Dossier.id',
						'IndusConstates.mtmoucompta',
						'IndusTransferesCG.mtmoucompta',
						'RemisesIndus.mtmoucompta',
						'Personne.nom',
						'Personne.prenom',
						'COALESCE("IndusConstates"."moismoucompta","IndusTransferesCG"."moismoucompta","RemisesIndus"."moismoucompta") AS "Indu__moismoucompta"',
					)
				);
				
				// 2. Jointure
				$indusConstate = array(
					'table' => '"infosfinancieres"',
					'alias' => 'IndusConstates',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'IndusConstates.dossier_id = Dossier.id',
						'IndusConstates.type_allocation' => 'IndusConstates'
					)
				);
				
				$transfertCg = array(
					'table' => '"infosfinancieres"',
					'alias' => 'IndusTransferesCG',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'IndusTransferesCG.dossier_id = Dossier.id',
						'IndusTransferesCG.type_allocation' => 'IndusTransferesCG'
					)
				);
				
				$remiseCg = array(
					'table' => '"infosfinancieres"',
					'alias' => 'RemisesIndus',
					'type' => 'LEFT OUTER',
					'conditions' => array(
						'RemisesIndus.dossier_id = Dossier.id',
						'RemisesIndus.type_allocation' => 'RemisesIndus'
					)
				);
				
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$indusConstate,
						$transfertCg,
						$remiseCg,
						$Infofinanciere->Dossier->Detaildroitrsa->join('Detailcalculdroitrsa', array('type' => $types['Detailcalculdroitrsa']))
					)
				);

				// 3. Tri par défaut: date, heure, id
				$query['order'] = array(
				);

				// 4. Si on utilise les cantons, on ajoute une jointure
				if( Configure::read( 'CG.cantons' ) ) {
					$Canton = ClassRegistry::init( 'Canton' );
					$query['fields']['Canton.canton'] = 'Canton.canton';
					$query['joins'][] = $Canton->joinAdresse();
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
			$Infofinanciere = ClassRegistry::init( 'Infofinanciere' );

			$query = $Allocataire->searchConditions( $query, $search );
			
			/**
			 * Conditions obligatoire
			 */
			$query['conditions'][] = array(
				'Prestation.rolepers' => 'DEM',
				array(
					'OR' => array(
						'IndusConstates.type_allocation IS NOT NULL',
						'IndusTransferesCG.type_allocation IS NOT NULL',
						'RemisesIndus.type_allocation IS NOT NULL',
					),
				),
				array(
					'OR' => array(
						array(
							'OR' => array(
								'IndusConstates.moismoucompta = IndusTransferesCG.moismoucompta',
								'IndusConstates.moismoucompta IS NULL',
								'IndusTransferesCG.moismoucompta IS NULL',
							)
						),
						array(
							'OR' => array(
								'IndusConstates.moismoucompta = RemisesIndus.moismoucompta',
								'IndusConstates.moismoucompta IS NULL',
								'RemisesIndus.moismoucompta IS NULL',
							)
						),
						array(
							'OR' => array(
								'IndusTransferesCG.moismoucompta = RemisesIndus.moismoucompta',
								'IndusTransferesCG.moismoucompta IS NULL',
								'RemisesIndus.moismoucompta IS NULL',
							)
						)
					)
				),
			);
			
			/**
			 * Generateur de conditions
			 */
			$paths = array(
				'Dossier.typeparte'
			);

			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( $value !== null && $value !== '' ) {
					$query['conditions'][$path] = $value;
				}
			}
			
			/**
			 * Conditions spéciales
			 */
			if (in_array($search['Infofinanciere']['compare'], array('<', '>', '<=', '>='))) {
				$query['conditions'][] = array(
					'OR' => array(
						"IndusConstates.mtmoucompta " . $search['Infofinanciere']['compare'] => $search['Infofinanciere']['mtmoucompta'],
						"IndusTransferesCG.mtmoucompta " . $search['Infofinanciere']['compare'] => $search['Infofinanciere']['mtmoucompta'],
						"RemisesIndus.mtmoucompta " . $search['Infofinanciere']['compare'] => $search['Infofinanciere']['mtmoucompta'],
					)
				);
			}
			elseif ($search['Infofinanciere']['mtmoucompta']) {
				$query['conditions'][] = array(
					'OR' => array(
						"IndusConstates.mtmoucompta" => $search['Infofinanciere']['mtmoucompta'],
						"IndusTransferesCG.mtmoucompta" => $search['Infofinanciere']['mtmoucompta'],
						"RemisesIndus.mtmoucompta" => $search['Infofinanciere']['mtmoucompta'],
					)
				);
			}
			
			if ($search['Infofinanciere']['natpfcre']) {
				$query['conditions'][] = array(
					'OR' => array(
						"IndusConstates.natpfcre" => $search['Infofinanciere']['natpfcre'],
						"IndusTransferesCG.natpfcre" => $search['Infofinanciere']['natpfcre'],
						"RemisesIndus.natpfcre" => $search['Infofinanciere']['natpfcre'],
					)
				);
			}

			return $query;
		}
	}
?>