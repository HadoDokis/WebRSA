<?php
	/**
	 * Code source de la classe WebrsaRecherchesTraitementspcgs66Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesTraitementspcgs66Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesTraitementspcgs66Component extends WebrsaRecherchesComponent
	{
		/**
		 * Modèles utilisé par le component
		 * 
		 * @var array
		 */
		public $uses = array(
			'Traitementpcg66',
		);
		
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$this->Traitementpcg66 = ClassRegistry::init( 'Traitementpcg66' );
			
			$options['Dossierpcg66']['poledossierpcg66_id'] = $this->Traitementpcg66->Personnepcg66->Dossierpcg66->User->Poledossierpcg66->find(
				'list', 
				array(
                    'conditions' => array('Poledossierpcg66.isactif' => '1'),
                    'order' => array('Poledossierpcg66.name ASC', 'Poledossierpcg66.id ASC')
				)
			);
			$options['Dossierpcg66']['user_id'] = $this->Traitementpcg66->Personnepcg66->Dossierpcg66->User->find(
				'list', 
				array(
                    'fields' => array('User.nom_complet'),
                    'conditions' => array('User.isgestionnaire' => 'O'),
                    'order' => array('User.nom ASC', 'User.prenom ASC')
				)
			);
			$options['Traitementpcg66']['situationpdo_id'] = $this->Traitementpcg66->Personnepcg66->Situationpdo->find(
				'list', 
				array(
					'order' => array('Situationpdo.libelle ASC'), 
					'conditions' => array('Situationpdo.isactif' => '1')
				)
			);
			$options['Traitementpcg66']['statutpdo_id'] = $this->Traitementpcg66->Personnepcg66->Statutpdo->find(
				'list', 
				array(
					'order' => array('Statutpdo.libelle ASC'), 
					'conditions' => array('Statutpdo.isactif' => '1')
				)
			);
			$options['Traitementpcg66']['descriptionpdo_id'] = $this->Traitementpcg66->Descriptionpdo->find('list');
			$options['Fichiermodule']['exists'] = array( 'Non', 'Oui' );
			$options['Dossier']['locked'] = array( 1 => '<img src="/img/icons/lock.png" alt="" title="Dossier verrouillé">' );
			
			return $options;
		}
	}
?>