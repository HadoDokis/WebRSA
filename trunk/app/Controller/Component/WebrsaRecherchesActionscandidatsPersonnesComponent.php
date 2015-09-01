<?php
	/**
	 * Code source de la classe WebrsaRecherchesActionscandidatsPersonnesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesActionscandidatsPersonnesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesActionscandidatsPersonnesComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$this->ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );
			
			$options['ActioncandidatPersonne']['referent_id'] = $this->ActioncandidatPersonne->Referent->find( 'list', array( 'recursive' => -1, 'order' => array( 'nom', 'prenom' ) ) );
			$options['Contactpartenaire']['partenaire_id'] = $this->ActioncandidatPersonne->Actioncandidat->Partenaire->find( 'list', array( 'fields' => array( 'libstruc' ), 'order' => array( 'Partenaire.libstruc ASC' ) ) );
			$options['ActioncandidatPersonne']['actioncandidat_id'] = $this->ActioncandidatPersonne->Actioncandidat->listActionParPartenaire();
			
			return $options;
		}
	}
?>