<?php
	/**
	 * Code source de la classe Allocataire.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractSearch', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'Utility' );

	/**
	 * La classe Allocataire comporte des méthodes de base pour les recherches,
	 * les formaulaires, les exportcsv, .. liées à des allocataires du RSA.
	 *
	 * @package app.Model
	 */
	class Allocataire extends AbstractSearch
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Allocataire';

		/**
		 * Ce modèle n'est pas lié à une table.
		 *
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Conditionnable'
		);

		/**
		 * Retourne le querydata de base à utiliser dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$Personne = ClassRegistry::init( 'Personne' );

			$types += array(
				'Calculdroitrsa' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'INNER',
				'Dossier' => 'INNER',
				'Adresse' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'INNER',
			);

			$cacheKey = Inflector::underscore( $Personne->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = array(
					'fields' => ConfigurableQueryFields::getModelsFields(
						array(
							$Personne,
							$Personne->Calculdroitrsa,
							$Personne->Foyer,
							$Personne->Prestation,
							$Personne->Foyer->Adressefoyer,
							$Personne->Foyer->Adressefoyer->Adresse,
							$Personne->Foyer->Dossier,
							$Personne->Foyer->Dossier->Situationdossierrsa,
							$Personne->Foyer->Dossier->Detaildroitrsa
						)
					),
					'joins' => array(
						$Personne->join( 'Calculdroitrsa', array( 'type' => $types['Calculdroitrsa'] ) ),
						$Personne->join( 'Foyer', array( 'type' => $types['Foyer'] ) ),
						$Personne->join(
							'Prestation',
							array(
								'type' => $types['Prestation'],
								'conditions' => array(
									'Prestation.rolepers' => array( 'DEM', 'CJT' )
								)
							)
						),
						$Personne->Foyer->join(
							'Adressefoyer',
							array(
								'type' => $types['Adressefoyer'],
								'conditions' => array(
									'Adressefoyer.id IN( '.$Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
								)
							)
						),
						$Personne->Foyer->join( 'Dossier', array( 'type' => $types['Dossier'] ) ),
						$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => $types['Adresse'] ) ),
						$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => $types['Situationdossierrsa'] ) ),
						$Personne->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => $types['Detaildroitrsa'] ) ),
					),
					'contain' => false,
					'conditions' => array(),
				);

				$query = $Personne->PersonneReferent->completeSearchQueryReferentParcours( $query );

				// Enregistrement dans le cache
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
			$query['conditions'] = $this->conditionsAdresse( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsPersonneFoyerDossier( $query['conditions'], $search );
			$query['conditions'] = $this->conditionsDernierDossierAllocataire( $query['conditions'], $search );

			$Personne = ClassRegistry::init( 'Personne' );
			$query = $Personne->PersonneReferent->completeSearchConditionsReferentParcours( $query, $search );

			return $query;
		}

		/**
		 * Retourne les options nécessaires au formulaire de recherche, aux
		 * impressions, ...
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Option = ClassRegistry::init( 'Option' );

			$options = array(
				'Adresse' => array(
					'pays' => $Option->pays(),
					'typeres' => $Option->typeres()
				),
				'Adressefoyer' => array(
					'rgadr' => $Option->rgadr(),
					'typeadr' => $Option->typeadr(),
				),
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => $Option->toppersdrodevorsa(true),
				),
				'Detailcalculdroitrsa' => array(
					'natpf' => $Option->natpf(),
				),
				'Detaildroitrsa' => array(
					'oridemrsa' => $Option->oridemrsa(),
					'topfoydrodevorsa' => $Option->topfoydrodevorsa(),
					'topsansdomfixe' => $Option->topsansdomfixe(),
				),
				'Dossier' => array(
					'fonorgcedmut' => $Option->fonorgcedmut(),
					'fonorgprenmut' => $Option->fonorgprenmut(),
					'numorg' => $Option->numorg(),
					'statudemrsa' => $Option->statudemrsa(),
					'typeparte' => $Option->typeparte(),
				),
				'Foyer' => array(
					'sitfam' => $Option->sitfam(),
					'typeocclog' => $Option->typeocclog(),
				),
				'Personne' => array(
					'pieecpres' => $Option->pieecpres(),
					'qual' => $Option->qual(),
					'sexe' => $Option->sexe(),
					'typedtnai' => $Option->typedtnai(),
				),
				'Prestation' => array(
					'rolepers' => $Option->rolepers(),
				),
				'Referentparcours' => array(
					'qual' => $Option->qual(),
				),
				'Structurereferenteparcours' => array(
					'type_voie' => $Option->typevoie(),
				),
				'Situationdossierrsa' => array(
					'etatdosrsa' => $Option->etatdosrsa(),
					'moticlorsa' => $Option->moticlorsa(),
				),
			);

			return $options;
		}

		/**
		 * Permet de test l'ajout de conditions supplémentaires à la requête de
		 * base.
		 *
		 * @param string|array $conditions
		 * @return array
		 */
		public function testSearchConditions( $conditions = null ) {
			$query = $this->searchQuery();
			$query['conditions'][] = $conditions;

			$Personne = ClassRegistry::init( 'Personne' );
			try {
				$Personne->find( 'first', $query );
				$return = array(
					'success' => true,
					'message' => null,
					'sql' => $Personne->sq( $query )
				);
			} catch( PDOException $Exception ) {
				$return = array(
					'success' => false,
					'message' => $Exception->getMessage(),
					'sql' => $Personne->sq( $query )
				);
			}

			return $return;
		}
	}
?>