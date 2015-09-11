<?php
	/**
	 * Code source de la classe WebrsaCohorteOrientstructNouvelle.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaCohorte', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaCohorteOrientstructNouvelle ...
	 *
	 * @package app.Model
	 */
	class WebrsaCohorteOrientstructNouvelle extends AbstractWebrsaCohorte
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaCohorteOrientstructNouvelle';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'ConfigurableQueryOrientsstructs.cohorte_nouvelle.fields',
			'ConfigurableQueryOrientsstructs.cohorte_nouvelle.innerTable',
			//'ConfigurableQueryOrientsstructs.exportcsv'
		);

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Allocataire', 'Personne' );

		/**
		 * Liste des champs de formulaire à inserer dans le tableau de résultats
		 *
		 * @var array
		 */
		public $cohorteFields = array(
			// TODO
			'Dossier.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Orientstruct.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Orientstruct.propo_algo' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			// TODO: forcer dans la sauvegarde
			'Orientstruct.origine' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			//
			'Adresse.numcom' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Orientstruct.personne_id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Orientstruct.typeorient_id' => array( 'type' => 'select', 'label' => '', 'empty' => true, 'required' => false ),
			'Orientstruct.structurereferente_id' => array( 'type' => 'select', 'label' => '', 'empty' => true, 'required' => false ),
			'Orientstruct.statut_orient' => array( 'type' => 'radio', 'fieldset' => false, 'legend' => false, 'div' => false ),
		);

		/**
		 * Valeurs par défaut pour le préremplissage des champs du formulaire de cohorte
		 * array(
		 *		'Mymodel' => array( 'Myfield' => 'MyValue' ) )
		 * )
		 *
		 * @var array
		 */
		public $defaultValues = array();

		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types += array(
				'Prestation' => 'INNER',
				'Calculdroitrsa' => 'INNER',
				'Dsp' => 'LEFT OUTER',
				'Suiviinstruction' => 'LEFT OUTER',
				'Adressefoyer' => 'INNER',
				'Adresse' => 'INNER',
				'Orientstruct' => 'INNER',
				'Typeorient' => 'LEFT OUTER',
				'Structurereferente' => 'LEFT OUTER',
				'Detaildroitrsa' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				// TODO: dernier CER ?
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = $this->Allocataire->searchQuery( $types );

				// 1. Ajout des champs supplémentaires
				$query['fields'] = array_merge(
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Personne->Dsp,
							$this->Personne->Foyer->Dossier->Suiviinstruction,
							$this->Personne->Orientstruct,
							$this->Personne->Orientstruct->Typeorient,
							$this->Personne->Orientstruct->Structurereferente,
						)
					),
					array(
						'Dossier.id',
						'Orientstruct.id',
						'Orientstruct.personne_id',
						'Orientstruct.propo_algo',
						'Adresse.numcom',
						// TODO: virtualField de Dossier
						'Dossier.statut' => '( CASE WHEN dtdemrsa >= \'2009-06-01 00:00:00\' THEN \'Nouvelle demande\' ELSE \'Diminution des ressources\' END ) AS "Dossier__statut"',
						'Personne.has_dsp' => '( "Dsp"."id" IS NOT NULL ) AS "Personne__has_dsp"',
					)
				);

				// 2. Jointures
				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Personne->join(
							'Dsp',
							array(
								'type' => $types['Dsp'],
								'conditions' => array(
									'Dsp.id IN ( '.$this->Personne->Dsp->sqDerniereDsp().' )'
								)
							)
						),
						$this->Personne->join( 'Orientstruct', array( 'type' => $types['Orientstruct'] ) ),
						$this->Personne->Foyer->Dossier->join(
							'Suiviinstruction',
							array(
								'type' => $types['Suiviinstruction'],
								'conditions' => array(
									'Suiviinstruction.id IN ( '.$this->Personne->Foyer->Dossier->Suiviinstruction->sqDernier2().' )'
								)
							)
						),
						$this->Personne->Orientstruct->join( 'Typeorient', array( 'type' => $types['Typeorient'] ) ),
						$this->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => $types['Structurereferente'] ) ),
					)
				);

				// 3. Conditions
				$query['conditions'][] = array( 'Orientstruct.statut_orient' => 'Non orienté' );

				// 4. Tri par défaut
				$query['order'] = array( 'Dossier.dtdemrsa' => 'ASC' );

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
			/*// On force certaines valeurs si besoin (elles ne figurent pas dans le formulaire)
			$search['Calculdroitrsa']['toppersdrodevorsa'] = '1';
			$search['Dossier']['dernier'] = '1';
			$search['Situationdossierrsa']['etatdosrsa_choice'] = '1';
			$search['Situationdossierrsa']['etatdosrsa'] = $this->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert();*/

			$query = $this->Allocataire->searchConditions( $query, $search );

			$propo_algo = Hash::get( $search, 'Orientstruct.propo_algo' );
			if( !empty( $propo_algo ) ) {
				if( $propo_algo === 'NULL' ) {
					$query['conditions'][] = array( 'Orientstruct.propo_algo IS NULL' );
				}
				else if( $propo_algo === 'NOTNULL' ) {
					$query['conditions'][] = array( 'Orientstruct.propo_algo IS NOT NULL' );
				}
				else {
					$query['conditions'][] = array( 'Orientstruct.propo_algo' => $propo_algo );
				}
			}


			return $query;
		}

		/**
		 * Préremplissage du formulaire en cohorte
		 *
		 * @param type $results
		 * @param type $params
		 * @return array
		 */
		public function prepareFormDataCohorte( array $results, array $params = array() ) {
			$data = array();

			foreach( $results as $key => $result ) {
				$data[$key]['Orientstruct']['typeorient_id'] = $result['Orientstruct']['propo_algo'];
				$data[$key]['Orientstruct']['statut_orient'] = 'Orienté';
			}

			return $data;
		}

		/**
		 * ...
		 *
		 * @param array $data
		 * @param array $params
		 * @param integer $user_id
		 * @return boolean
		 */
		public function saveCohorte( array $data, array $params = array(), $user_id = null ) {
			$success = true;

			foreach( array_keys( $data ) as $key ) {
				if( $data[$key]['Orientstruct']['statut_orient'] === 'Orienté' ) {
					$data[$key]['Orientstruct']['structurereferente_id'] = suffix( $data[$key]['Orientstruct']['structurereferente_id'] );
					$data[$key]['Orientstruct']['origine'] = 'cohorte';
					$data[$key]['Orientstruct']['user_id'] = $user_id;
					$data[$key]['Orientstruct']['date_valid'] = date( 'Y-m-d' );
				}
				else {
					$data[$key]['Orientstruct']['origine'] = null;
					$data[$key]['Orientstruct']['user_id'] = null;

					if( $data[$key]['Orientstruct']['statut_orient'] === 'Non orienté' ) {
						$data[$key]['Orientstruct']['date_propo'] = date( 'Y-m-d' );
					}
				}
			}

			return $this->saveResultAsBool( $this->Personne->Orientstruct->saveAll( Hash::extract( $data, '{n}.Orientstruct' ), array( 'validate' => 'first', 'atomic' => true ) ) );
		}

	}
?>