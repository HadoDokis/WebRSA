<?php
	/**
	 * Code source de la classe WebrsaCohorteDossierpcg66Heberge.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'AbstractWebrsaCohorte', 'Model/Abstractclass' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaCohorteDossierpcg66Heberge ...
	 *
	 * @package app.Model
	 */
	class WebrsaCohorteDossierpcg66Heberge extends AbstractWebrsaCohorte
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaCohorteDossierpcg66Heberge';

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Allocataire',
			'Canton',
			'Dossierpcg66',
			'Tag',
			'WebrsaCohorteTag' // A besoin du module tag
		);
		
		/**
		 * Liste des champs de formulaire à inserer dans le tableau de résultats
		 * 
		 * @var array
		 */
		public $cohorteFields = array();
		
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
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$query = $this->WebrsaCohorteTag->searchConditions($query, $search);
			
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
			$success = true;
			$dataDossierpcg66 = array();
			$dataPersonnepcg66 = array();
			$dataTraitementpcg66 = array();
			$dataTag = array();
			
			foreach ($data as $key => $value) {
				$dataDossierpcg66 = Hash::get($value, 'Dossierpcg66');
				$dataTraitementpcg66 = Hash::get($value, 'Traitementpcg66');
				$dataModeletraitementpcg66 = Hash::get($value, 'Modeletraitementpcg66');
				$dataTag = Hash::get($value, 'Tag');
				
				$dataDossierpcg66['foyer_id'] = Hash::get($value, 'Foyer.id');
				$dataDossierpcg66['etatdossierpcg'] = 'attinstr';
				$dataPersonnepcg66['personne_id'] = Hash::get($value, 'Personne.id');
				$dataPersonnepcg66['user_id'] = $user_id;
				$dataTag['fk_value'] = Hash::get($value, 'Foyer.id');
				
				$success = $this->Dossierpcg66->save($dataDossierpcg66) && $success;
				$dossierpcg66_id = $this->Dossierpcg66->id;
				
				$dataPersonnepcg66['dossierpcg66_id'] = $dossierpcg66_id;
				$success = $this->Dossierpcg66->Personnepcg66->save($dataPersonnepcg66) && $success;
				$personnepcg66_id = $this->Dossierpcg66->Personnepcg66->id;
				
				foreach ((array)Hash::get($value, 'Situationpdo.Situationpdo') as $situationpdo_id) {
					$dataPersonnepcg66Situationpdo = array(
						'personnepcg66_id' => $personnepcg66_id,
						'situationpdo_id' => $situationpdo_id
					);
					$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->create($dataPersonnepcg66Situationpdo);
					$success = $this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->save() && $success;
					
					if (Hash::get($dataTraitementpcg66, 'personnepcg66_situationpdo_id') === $situationpdo_id) {
						$dataTraitementpcg66['personnepcg66_situationpdo_id'] = $this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->id;
					}
				}
				
				foreach ((array)Hash::get($value, 'Statutpdo.Statutpdo') as $statutpdo_id) {
					$dataPersonnepcg66Statutpdo = array(
						'personnepcg66_id' => $personnepcg66_id,
						'statutpdo_id' => $statutpdo_id
					);
					$this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->create($dataPersonnepcg66Statutpdo);
					$success = $this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->save() && $success;
				}
				
				$dataTraitementpcg66['personnepcg66_id'] = $personnepcg66_id;
				$success = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->save($dataTraitementpcg66) && $success;
				$traitementpcg66_id = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->id;
				
				$dataModeletraitementpcg66['traitementpcg66_id'] = $traitementpcg66_id;
				
				// Note : commentaire est dans une clef numérique égal à modeletypecourrierpcg66_id
				$modeletypecourrierpcg66_id = Hash::get($dataModeletraitementpcg66, 'modeletypecourrierpcg66_id');
				$dataModeletraitementpcg66['commentaire'] = Hash::get($dataModeletraitementpcg66, $modeletypecourrierpcg66_id.'.commentaire');
				unset($dataModeletraitementpcg66[$modeletypecourrierpcg66_id], $dataModeletraitementpcg66['id']);
				
				$success = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->Modeletraitementpcg66->save($dataModeletraitementpcg66);
				$modeletraitementpcg66_id = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->Modeletraitementpcg66->id;
				
				foreach (Hash::flatten((array)Hash::get($value, 'Piecemodeletypecourrierpcg66')) as $piecemodeletypecourrierpcg66_id) {
					if (!$piecemodeletypecourrierpcg66_id) {
						continue;
					}

					$dataMtpcg66Pmtcpcg66 = array(
						'piecemodeletypecourrierpcg66_id' => $piecemodeletypecourrierpcg66_id,
						'modeletraitementpcg66_id' => $modeletraitementpcg66_id
					);
					$this->Dossierpcg66->Personnepcg66->Traitementpcg66->Modeletraitementpcg66->Mtpcg66Pmtcpcg66->create( $dataMtpcg66Pmtcpcg66 );
					$success = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->Modeletraitementpcg66->Mtpcg66Pmtcpcg66->save() && $success;
				}
				
				foreach ((array)Hash::get($value, 'Tag.valeurtag_id') as $valeurtag_id) {
					$dataTag['valeurtag_id'] = $valeurtag_id;
					$this->Dossierpcg66->Foyer->Tag->create($dataTag);
					$success = $this->Dossierpcg66->Foyer->Tag->save() && $success;
				}
			}
			
			return $success;
		}
		
		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$query = $this->WebrsaCohorteTag->searchQuery($types);
			
			return $query;
		}
	}
?>