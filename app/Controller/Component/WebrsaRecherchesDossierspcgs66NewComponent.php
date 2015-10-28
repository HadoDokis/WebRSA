<?php
	/**
	 * Code source de la classe WebrsaRecherchesDossierspcgs66NewComponent.
	 *
	 * @package app.Controller.Component
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesDossierspcgs66NewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesDossierspcgs66NewComponent extends WebrsaAbstractRecherchesNewComponent
	{
		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * @return array
		 */
		protected function _optionsEnums( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$options = Hash::merge(
				parent::_optionsEnums( $params ),
				// FIXME N'aparait pas dans
				$Controller->Dossierpcg66->enums()
			);


			return $options;
		}

		/**
		 * Retourne les options stockées liées à des enregistrements en base de
		 * données, ne dépendant pas de l'utilisateur connecté.
		 *
		 * @return array
		 */
		protected function _optionsRecords( array $params = array() ) {
			$Controller = $this->_Collection->getController();

			$options = parent::_optionsRecords( $params );
			
			if( !isset( $Controller->Catalogueromev3 ) ) {
				$Controller->loadModel( 'Catalogueromev3' );
			}
			
			$catalogueromev3 = $Controller->Catalogueromev3->dependantSelects();
			$options['Categorieromev3'] = $catalogueromev3['Catalogueromev3'];
			$options['Dossierpcg66']['originepdo_id'] = $Controller->Dossierpcg66->Originepdo->find('list');
			$options['Dossierpcg66']['typepdo_id'] = $Controller->Dossierpcg66->Typepdo->find('list');
			$options['Dossierpcg66']['poledossierpcg66_id'] = $Controller->Dossierpcg66->User->Poledossierpcg66->find(
				'list', 
				array(
                    'conditions' => array('Poledossierpcg66.isactif' => '1'),
                    'order' => array('Poledossierpcg66.name ASC', 'Poledossierpcg66.id ASC')
				)
			);
			$options['Dossierpcg66']['user_id'] = $Controller->Dossierpcg66->User->find(
				'list', 
				array(
                    'fields' => array('User.nom_complet'),
                    'conditions' => array('User.isgestionnaire' => 'O'),
                    'order' => array('User.nom ASC', 'User.prenom ASC')
				)
			);
			$options['Decisiondossierpcg66']['org_id'] = $Controller->Dossierpcg66->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
				'list', 
				array(
					'conditions' => array('Orgtransmisdossierpcg66.isactif' => '1'),
					'order' => array('Orgtransmisdossierpcg66.name ASC')
				)
			);
			$options['Traitementpcg66']['situationpdo_id'] = $Controller->Dossierpcg66->Personnepcg66->Situationpdo->find(
				'list', 
				array(
					'order' => array('Situationpdo.libelle ASC'), 
					'conditions' => array('Situationpdo.isactif' => '1')
				)
			);
			$options['Traitementpcg66']['statutpdo_id'] = $Controller->Dossierpcg66->Personnepcg66->Statutpdo->find(
				'list', 
				array(
					'order' => array('Statutpdo.libelle ASC'), 
					'conditions' => array('Statutpdo.isactif' => '1')
				)
			);
			$options['Decisiondossierpcg66']['decisionpdo_id'] = $Controller->Dossierpcg66->Decisiondossierpcg66->Decisionpdo->find(
				'list', 
				array(
					'conditions' => array('Decisionpdo.isactif' => '1')
				)
			);

			return $options;
		}

		/**
		 * Retourne les noms des modèles dont des enregistrements seront mis en
		 * cache après l'appel à la méthode _optionsRecords() afin que la clé de
		 * cache générée par la méthode _options() se trouve associée dans
		 * ModelCache.
		 *
		 * @see _optionsRecords(), _options()
		 *
		 * @return array
		 */
		protected function _optionsRecordsModels( array $params ) {
			$result = array_merge(
				parent::_optionsRecordsModels( $params ),
				array(
					'Familleromev3',
					'Domaineromev3',
					'Metierromev3',
					'Appellationromev3',
					'Originepdo',
					'Typepdo',
					'Poledossierpcg66',
					'User',
					'Orgtransmisdossierpcg66',
					'Situationpdo',
					'Statutpdo',
					'Decisionpdo'
				)
			);

			return $result;
		}
	}
?>
