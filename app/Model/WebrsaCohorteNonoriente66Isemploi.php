<?php
	/**
	 * Code source de la classe WebrsaCohorteNonoriente66Isemploi.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaCohorteNonoriente66', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaCohorteNonoriente66Isemploi ...
	 *
	 * @package app.Model
	 */
	class WebrsaCohorteNonoriente66Isemploi extends AbstractWebrsaCohorteNonoriente66
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaCohorteNonoriente66Isemploi';
		
		/**
		 * Liste des champs de formulaire à inserer dans le tableau de résultats
		 * 
		 * @var array
		 */
		public $cohorteFields = array(
			'Personne.id' => array( 'type' => 'hidden' ),
			'Historiqueetatpe.id' => array( 'type' => 'hidden' ),
			'Nonoriente66.id' => array( 'type' => 'hidden' ),
			'Nonoriente66.personne_id' => array( 'type' => 'hidden' ),
			'Nonoriente66.origine' => array( 'type' => 'hidden' ),
			'Nonoriente66.historiqueetatpe_id' => array( 'type' => 'hidden' ),
			'Nonoriente66.user_id' => array( 'type' => 'hidden' ),
			'Orientstruct.origine' => array( 'type' => 'hidden' ),
			'Orientstruct.personne_id' => array( 'type' => 'hidden' ),
			'Orientstruct.statut_orient' => array( 'type' => 'hidden' ),
			'Nonoriente66.selection' => array( 'type' => 'checkbox' ),
			'Orientstruct.typeorient_id',
			'Orientstruct.structurereferente_id',
			'Orientstruct.date_valid' => array( 'type' => 'date' )
		);
		
		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types += array(
				// INNER JOIN
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'INNER',
				'Calculdroitrsa' => 'INNER',
				'Dossier' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				'Adresse' => 'INNER',
				
				// LEFT OUTER JOIN
				'Orientstruct' => 'LEFT OUTER',
				'Structurereferente' => 'LEFT OUTER',
				'Typeorient' => 'LEFT OUTER',
				'Nonorient66' => 'LEFT OUTER',
				'Informationpe' => 'LEFT OUTER',
				'Canton' => 'LEFT OUTER',
				'PersonneReferent' => 'LEFT OUTER',
				'Referentparcours' => 'LEFT OUTER',
				'Structurereferenteparcours' => 'LEFT OUTER',
			);
			
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = parent::searchQuery($types);
				
				$query['conditions']['Historiqueetatpe.etat'] = 'inscription';
				$query['conditions'][] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" AND orientsstructs.statut_orient = \'Orienté\' ) = 0';
				$query['conditions'][] = 'Personne.id NOT IN (
						SELECT nonorientes66.personne_id
						FROM nonorientes66
						WHERE nonorientes66.personne_id = Personne.id
					)'
				;
			}
			
			return $query;
		}
		
		/**
		 * Logique de sauvegarde de la cohorte
		 * 
		 * @param type $data
		 * @param type $params
		 * @return boolean
		 */
		public function saveCohorte( array $data, array $params = array(), $user_id = null ) {
			foreach ( $data as $key => $value ) {
				// Si non selectionné, on retire tout
				if ( $value['Nonoriente66']['selection'] === '0' ) {
					unset($data[$key]);
					continue;
				}
				
				if ( empty($value['Nonoriente66']['id']) ) {
					$data[$key]['Nonoriente66'] = array(
						'personne_id' => Hash::get($value, 'Personne.id'),
						'origine' => 'isemploi',
						'dateimpression' => null,
						'historiqueetatpe_id' => Hash::get($value, 'Historiqueetatpe.id'),
						'user_id' => $user_id
					);
				}
				
				$data[$key]['Orientstruct']['personne_id'] = Hash::get($value, 'Personne.id');
				$data[$key]['Orientstruct']['origine'] = 'cohorte';
				$data[$key]['Orientstruct']['statut_orient'] = 'Orienté';
			}
			
			$success = !empty($data) && $this->Nonoriente66->saveAll( $data ) 
				&& $this->Nonoriente66->Personne->Orientstruct->saveAll($data)
			;
			
			return $success;
		}
	}
?>