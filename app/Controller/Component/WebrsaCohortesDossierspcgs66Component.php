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
			$User = ClassRegistry::init('User');
			$options = $this->WebrsaRecherchesDossierspcgs66->options( $params );
			$options += parent::options($params);
			
			$gestionnaires = $User->find(
				'all', array(
					'fields' => array(
						'User.nom_complet',
						'( "Poledossierpcg66"."id" || \'_\'|| "User"."id" ) AS "User__gestionnaire"',
					),
					'conditions' => array(
						'User.isgestionnaire' => 'O'
					),
					'joins' => array(
						$User->join('Poledossierpcg66', array('type' => 'INNER')),
					),
					'order' => array('User.nom ASC', 'User.prenom ASC'),
					'contain' => false
				)
			);
			$gestionnaires = Hash::combine($gestionnaires, '{n}.User.gestionnaire', '{n}.User.nom_complet');
			
			$options['Dossierpcg66']['user_id'] = $gestionnaires;
			
			return $options;
		}
	}
?>