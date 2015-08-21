<?php
	/**
	 * Code source de la classe WebrsaRechercheIndu.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRecherche', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRechercheIndu ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheIndu extends AbstractWebrsaRecherche
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheIndu';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'Indus.search.fields',
			'Indus.search.innerTable',
			'Indus.exportcsv'
		);
		
		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 
			'Allocataire', 
			'Infofinanciere', 
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

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types, 'Dossier' );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Infofinanciere,
							$this->Infofinanciere->Dossier->Foyer->Personne->PersonneReferent,
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
						$this->Infofinanciere->Dossier->Detaildroitrsa->join('Detailcalculdroitrsa', array('type' => $types['Detailcalculdroitrsa']))
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
			$mtmoucompta = Hash::get( $search, 'Infofinanciere.mtmoucompta' );
			$compare = Hash::get( $search, 'Infofinanciere.compare' );
			if ( in_array($compare, array('<', '>', '<=', '>=')) ) {
				$query['conditions'][] = array(
					'OR' => array(
						"IndusConstates.mtmoucompta " . $compare => $mtmoucompta,
						"IndusTransferesCG.mtmoucompta " . $compare => $mtmoucompta,
						"RemisesIndus.mtmoucompta " . $compare => $mtmoucompta,
					)
				);
			}
			elseif( $mtmoucompta ) {
				$query['conditions'][] = array(
					'OR' => array(
						"IndusConstates.mtmoucompta" => $mtmoucompta,
						"IndusTransferesCG.mtmoucompta" => $mtmoucompta,
						"RemisesIndus.mtmoucompta" => $mtmoucompta,
					)
				);
			}

			$natpfcre = Hash::get( $search, 'Infofinanciere.natpfcre' );
			if( $natpfcre ) {
				$query['conditions'][] = array(
					'OR' => array(
						"IndusConstates.natpfcre" => $natpfcre,
						"IndusTransferesCG.natpfcre" => $natpfcre,
						"RemisesIndus.natpfcre" => $natpfcre,
					)
				);
			}

			return $query;
		}
	}
?>