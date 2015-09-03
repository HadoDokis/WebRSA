<?php
	/**
	 * Code source de la classe WebrsaRecherchesTransfertspdvs93Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesTransfertspdvs93Component ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesTransfertspdvs93Component extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * Surcharge pour permettre de limiter les résultats de la recherche à
		 * ceux dont l'adresse de rang 02 uniquement est sur une des zones
		 * géographiques couverte par la structure référente de l'utilisateur
		 * connecté lorsque celui-ci est un externe (CG 93).
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		protected function _getQueryConditions( array $query, array $params = array() ) {
			$query = parent::_getQueryConditions( $query, $params );

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$Controller = $this->_Collection->getController();
				$type = $Controller->Session->read( 'Auth.User.type' );

				if( stristr( $type, 'externe_' ) !== false ) {
					$query['conditions'][] = array( 'Adressefoyer.rgadr' => '02' );
				}
			}

			return $query;
		}

		/**
		 * Retourne les options à envoyer dans la vue pour les champs du moteur
		 * de recherche et les traductions de valeurs de certains champs.
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$options = parent::options( $params );

			//TODO: options des nouveaux modèles liés et aliasés

			$options['Orientstruct']['typeorient_id'] = $Controller->InsertionsAllocataires->typesorients();
			$options['Orientstruct']['structurereferente_id'] = $Controller->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) );

			return $options;
		}
	}
?>