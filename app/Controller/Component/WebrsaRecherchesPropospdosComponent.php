<?php
	/**
	 * Code source de la classe WebrsaRecherchesPropospdosComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesPropospdosComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesPropospdosComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * @todo: faire la distinction search(index)/exportcsv (notamment dans Allocataires)
		 * @todo: mise en cache ?
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$params = $this->_params( $params );

			$Controller->loadModel( 'Decisionpropopdo' ); // TODO: autres champs enum, cf. contrôleur, champs
			$Controller->loadModel( 'Decisionpdo' ); // TODO: autres champs enum, cf. contrôleur, champs

			$options = Hash::merge(
				parent::options( $params ),
				$Controller->Decisionpropopdo->enums(),
				$Controller->Decisionpdo->enums(),
				array(
					'Propopdo' => array(
						'typepdo_id' => $Controller->Propopdo->Typepdo->find( 'list' ),
						'typenotifpdo_id' => $Controller->Propopdo->Typenotifpdo->find( 'list' ),
						'originepdo_id' => $Controller->Propopdo->Originepdo->find( 'list' ),
						'serviceinstructeur_id' => $Controller->Propopdo->Serviceinstructeur->listOptions(),
						'user_id' => $Controller->Propopdo->User->find( 'list', array( 'fields' => array( 'User.nom_complet' ), 'conditions' => array( 'User.isgestionnaire' => 'O' ) ) )
					),
					'Decisionpropopdo' => array(
						'decisionpdo_id' => $Controller->Propopdo->Decisionpropopdo->Decisionpdo->find( 'list' )
					)
				)
			);

			return $options;
		}
	}
?>