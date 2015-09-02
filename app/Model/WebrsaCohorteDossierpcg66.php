<?php
	/**
	 * Code source de la classe WebrsaCohorteDossierpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaCohorte', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaCohorteDossierpcg66 ...
	 *
	 * @package app.Model
	 */
	class WebrsaCohorteDossierpcg66 extends AbstractWebrsaCohorte
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaCohorteDossierpcg66';

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array(
			'ConfigurableQueryDossierspcgs66.cohorte_enattenteaffectation.fields',
			'ConfigurableQueryDossierspcgs66.cohorte_enattenteaffectation.innerTable',
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
			'Dossierpcg66.atraiter' => array( 'type' => 'checkbox', 'label' => '' ),
			'Dossierpcg66.poledossierpcg66_id' => array( 'type' => 'select', 'label' => '', 'empty' => true ),
			'Dossierpcg66.user_id' => array( 'type' => 'select', 'label' => '', 'empty' => true ),
			'Dossierpcg66.dateaffectation' => array( 'type' => 'date', 'label' => '', 'format' => 'DMY' ),
			'Dossierpcg66.id' => array( 'type' => 'hidden', 'label' => '', 'hidden' => true ),
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
				'Typepdo' => 'INNER',
				'Originepdo' => 'INNER',
				'Serviceinstructeur' => 'LEFT OUTER',
			);
			
			$query = $this->WebrsaRechercheDossierpcg66->searchQuery( $types );
			
			$query['fields'] = array_merge(
				$query['fields'],
				ConfigurableQueryFields::getModelsFields(
					array(
						$this->Dossierpcg66->Typepdo,
						$this->Dossierpcg66->Originepdo,
						$this->Dossierpcg66->Serviceinstructeur,
					)
				),
				// Champs nécessaires au traitement de la cohorte
				array(
					'Dossier.id',
					'Dossierpcg66.poledossierpcg66_id',
					'Dossierpcg66.dateaffectation',
				)
			);
			
			$query['joins'] = array_merge(
				$query['joins'],
				array(
					$this->Dossierpcg66->join( 'Typepdo', array( 'type' => $types['Typepdo'] ) ),
					$this->Dossierpcg66->join( 'Originepdo', array( 'type' => $types['Originepdo'] ) ),
					$this->Dossierpcg66->join( 'Serviceinstructeur', array( 'type' => $types['Serviceinstructeur'] ) ),
				)
			);
			
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
			$query = $this->WebrsaRechercheDossierpcg66->searchConditions( $query, $search );
			
			$query['conditions'][] = array(
				'Dossierpcg66.etatdossierpcg' => 'attaffect',
				'Dossierpcg66.user_id IS NULL'
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
				if ( $value['Dossierpcg66']['atraiter'] === '0' ) {
					unset($data[$key]);
				}
				else {
					unset($data[$key]['Dossierpcg66']['atraiter']);
					$data[$key]['Dossierpcg66']['user_id'] = suffix(Hash::get($value, 'Dossierpcg66.user_id'));
				}
			}
			
			$success = !empty($data) && $this->Dossierpcg66->saveAll( $data );
			
			return $success;
		}
	}
?>