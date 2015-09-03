<?php
	/**
	 * Code source de la classe WebrsaCohortesDossierspcgs66Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRecherchesDossierspcgs66Component', 'Controller/Component' );
	App::uses( 'WebrsaAbstractCohortesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaCohortesDossierspcgs66Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaCohortesDossierspcgs66Component extends WebrsaAbstractCohortesComponent
	{
		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Cohortes',
			'WebrsaRecherches',
			'WebrsaRecherchesDossierspcgs66'
		);
		
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$Dossierpcg66 = ClassRegistry::init('Dossierpcg66');
			$options = $this->WebrsaRecherchesDossierspcgs66->options( $params );
			$options += parent::options($params);
			
			switch( $Controller->action ) {
				case 'cohorte_enattenteaffectation':
					$gestionnaires = $Dossierpcg66->User->find(
						'all', array(
							'fields' => array(
								'User.nom_complet',
								'( "Poledossierpcg66"."id" || \'_\'|| "User"."id" ) AS "User__gestionnaire"',
							),
							'conditions' => array(
								'User.isgestionnaire' => 'O'
							),
							'joins' => array(
								$Dossierpcg66->User->join('Poledossierpcg66', array('type' => 'INNER')),
							),
							'order' => array('User.nom ASC', 'User.prenom ASC'),
							'contain' => false
						)
					);
					$options['Dossierpcg66']['user_id'] = Hash::combine($gestionnaires, '{n}.User.gestionnaire', '{n}.User.nom_complet');
					break;
			
				case 'cohorte_atransmettre':
					$options['Decdospcg66Orgdospcg66']['orgtransmisdossierpcg66_id'] = 
						$Dossierpcg66->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
							'list', array(
								'conditions' => array( 'Orgtransmisdossierpcg66.isactif' => '1' ),
								'order' => array('Orgtransmisdossierpcg66.name ASC')
							)
						)
					;
					break;
			}
			
			$options['Dossierpcg66']['originepdo_id'] = $Dossierpcg66->Originepdo->find('list');
			$options['Dossierpcg66']['serviceinstructeur_id'] = $Dossierpcg66->Serviceinstructeur->listOptions();
			$options['Dossierpcg66']['typepdo_id'] = $Dossierpcg66->Typepdo->find('list');
			
			return $options;
		}
	}
?>