<?php
	/**
	 * Code source de la classe WebrsaOptionTag.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */

	/**
	 * La classe WebrsaOptionTag ...
	 *
	 * @package app.Model
	 */
	class WebrsaOptionTag extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaOptionTag';

		/**
		 * On n'utilise pas de table.
		 *
		 * @var mixed
		 */
		public $useTable = false;
		
		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Tag',
			'Requestmanager',
			'Zonegeographique'
		);
		
		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * La mise en cache se fera dans ma méthode _options().
		 *
		 * @return array
		 */
		public function optionsEnums( array $options = array() ) {
			$options['Personne']['trancheage'] = array(
				'0_24' => '< 25',
				'25_30' => '25 - 30',
				'31_55' => '31 - 55',
				'56_65' => '56 - 65',
				'66_999' => '> 65',
			);
			
			$options['Foyer']['composition'] = array(
				'cpl_sans_enf' => 'Couple sans enfant',
				'cpl_avec_enf' => 'Couple avec enfant(s)',
				'iso_sans_enf' => 'Isolé sans enfant',
				'iso_avec_enf' => 'Isolé avec enfant(s)'
			);
			
			$options['Adresse']['heberge'] = array(
				0 => 'Non',
				1 => 'Oui',
			);
			
			$options['Foyer']['nb_enfants'] = array(
				'0',
				'>= 1',
				'>= 2',
				'>= 3',
				'>= 4',
				'>= 5',
			);
			
			$options = array_merge(
				$options,
				$this->Tag->Personne->Prestation->enums(),
				$this->Tag->Personne->Dsp->enums()
			);
			
			$options['DspRev'] = $options['Dsp'];
			
			$accepted = array( 'DEM', 'CJT' );
			foreach( array_keys($options['Prestation']['rolepers']) as $value ) {
				if( !in_array( $value, $accepted ) ) {
					unset( $options['Prestation']['rolepers'][$value] );
				}
			}
			
			return $options;
		}
		
		/**
		 * Retourne les options stockées liées à des enregistrements en base de
		 * données, ne dépendant pas de l'utilisateur connecté.
		 *
		 * @return array
		 */
		public function optionsRecords( array $options = array() ) {
			$modeles = $this->Tag->find('list', array('fields' => 'modele', 'order' => 'modele'));
			foreach ( $modeles as $value ) {
				$options['Tag']['modele'][$value] = $value;
			}
			
			// Valeur tag / catégorie
			$results = $this->Tag->Valeurtag->find('all', array(
				'fields' => array(
					'Categorietag.name',
					'Valeurtag.id',
					'Valeurtag.name'
				),
				'joins' => array(
					$this->Tag->Valeurtag->join('Categorietag')
				),
			));
			
			foreach ($results as $value) {
				$categorie = Hash::get($value, 'Categorietag.name') ? Hash::get($value, 'Categorietag.name') : 'Sans catégorie';
				$valeur = Hash::get($value, 'Valeurtag.name');
				$valeurtag_id = Hash::get($value, 'Valeurtag.id');
				$options['Tag']['valeurtag_id'][$categorie][$valeurtag_id] = $valeur;
			}
			
			$options['Zonegeographique']['id'] = $this->Zonegeographique->find( 'list' );
			
			$options['Requestgroup']['name'] = $this->Requestmanager->Requestgroup->find('list', array('order' => 'name'));
			$requestManager = $this->Requestmanager->find('all', array('conditions' => array( 'actif' => '1' )));
			
			foreach ($options['Requestgroup']['name'] as $group_id => $group) {
				foreach ($requestManager as $value) {
					if ( $value['Requestmanager']['requestgroup_id'] === $group_id
						&& $this->Requestmanager->checkModelPresence($value, 'Foyer')
						&& $this->Requestmanager->checkModelPresence($value, 'Personne') 
						&& $this->Requestmanager->checkModelPresence($value, 'Dossier') 
					) {
						$options['Requestmanager']['name'][$group][$value['Requestmanager']['id']] = $value['Requestmanager']['name'];
					}
				}
			}
			
			return $options;
		}

		/**
		 * Retourne les noms des modèles dont des enregistrements seront mis en
		 * cache après l'appel à la méthode _optionsRecords() afin que la clé de
		 * cache générée par la méthode _options() se trouve associée dans
		 * ModelCache.
		 *
		 * @return array
		 */
		public function optionsRecordsModels( array $result = array() ) {
			return array_merge($result, 
				array(
					'Valeurtag',
					'Categorietag',
					'Zonegeographique',
					'Requestmanager',
					'Dossierpcg66',
					'Serviceinstructeur',
					'Originepdo',
					'Typepdo',
					'User',
					'Poledossierpcg66',
					'Situationpdo',
					'Statutpdo',
					'Typecourrierpcg66',
				)
			);
		}
	}
?>