<?php
	/**
	 * Code source de la classe WebrsaCohorteDossierpcg66Atransmettre.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaCohorteDossierpcg66', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaCohorteDossierpcg66Atransmettre ...
	 *
	 * @package app.Model
	 */
	class WebrsaCohorteDossierpcg66Atransmettre extends AbstractWebrsaCohorteDossierpcg66
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaCohorteDossierpcg66Atransmettre';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'ConfigurableQueryDossierspcgs66.cohorte_atransmettre.fields',
			'ConfigurableQueryDossierspcgs66.cohorte_atransmettre.innerTable',
		);

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'WebrsaRechercheDossierpcg66',
			'Dossierpcg66'
		);
		
		/**
		 * Liste des champs de formulaire à inserer dans le tableau de résultats
		 * 
		 * @var array
		 */
		public $cohorteFields = array(
			'Dossierpcg66.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Dossierpcg66.istransmis' => array( 'type' => 'checkbox', 'label' => '&nbsp;' ),
			'Decdospcg66Orgdospcg66.decisiondossierpcg66_id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id' => array( 'type' => 'select', 'multiple' => 'checkbox', 'label' => '', 'class' => 'largeColumn' ),
			'Decisiondossierpcg66.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
			'Decisiondossierpcg66.datetransmissionop' => array( 'type' => 'date', 'label' => '' ),
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
			$query = parent::searchQuery($types);
			
			$query['fields'][] = 'Decisiondossierpcg66.id';
			
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
			$orgTransmissisonBaseQuery = array(
				'fields' => array(
					'Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id'
				),
				'contain' => false
			);
			
			for ($i=0; $i<count($results); $i++) {
				$data[$i] = $results[$i];
				
				$condition['conditions'] = array(
					'Decdospcg66Orgdospcg66.decisiondossierpcg66_id' => $results[$i]['Decisiondossierpcg66']['id']
				);
				
				$data[$i]['Decdospcg66Orgdospcg66']['orgtransmisdossierpcg66_id'] = Hash::extract(
					$this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66->find( 
						'all', $orgTransmissisonBaseQuery + $condition
					),
					'{n}.Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id'
				);
				
				$data[$i]['Decdospcg66Orgdospcg66']['decisiondossierpcg66_id'] = $results[$i]['Decisiondossierpcg66']['id'];
				
				$data[$i] += $this->defaultValues;
			}
			
			return $data;
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
			$query = $this->WebrsaRechercheDossierpcg66->searchConditions( $query, $search );
			
			$query['conditions'][] = array(
				'Dossierpcg66.etatdossierpcg' => 'atttransmisop',
				'Dossierpcg66.dateimpression IS NOT NULL',
				'Dossierpcg66.istransmis' => '0',
			);
			
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
				if ( $value['Dossierpcg66']['istransmis'] === '0' ) {
					unset($data[$key]);
				}
			}
			
			$success = !empty($data);
			if ( $success ) {
				foreach( $data as $key => $value ) {
					$success = $this->Dossierpcg66->saveAll(array($key => $value['Dossierpcg66'])) && $success;
					$success = $this->Dossierpcg66->Decisiondossierpcg66->saveAll(array($key => $value['Decisiondossierpcg66'])) && $success;
					
					$orgs = Hash::get($value, 'Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id');
					$datasDec = array();
					foreach ( $orgs !== '' ? (array)$orgs : array() as $orgKey => $orgtransmisdossierpcg66_id ) {
						$datasDec[$key] = array(
							'orgtransmisdossierpcg66_id' => $orgtransmisdossierpcg66_id,
							'decisiondossierpcg66_id' => $value['Decdospcg66Orgdospcg66']['decisiondossierpcg66_id']
						);
						$success = $this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66
							->deleteAllUnbound( $datasDec[$key], false ) && $success
						;
					}
					
					if ( empty($datasDec) ) {
						$datasDec[$key] = array(
							'orgtransmisdossierpcg66_id' => null,
							'decisiondossierpcg66_id' => $value['Decdospcg66Orgdospcg66']['decisiondossierpcg66_id']
						);
					}
					
					$success = $this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66->saveAll($datasDec) && $success;
				}
			}
			
			return $success;
		}
	}
?>