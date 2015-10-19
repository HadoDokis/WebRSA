<?php
	/**
	 * Code source de la classe WebrsaRecherchesDspsNewComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesDspsNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesDspsNewComponent extends WebrsaAbstractRecherchesNewComponent
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
				$Controller->Dsp->options( array( 'find' => false, 'allocataire' => false, 'alias' => 'Donnees', 'nums' => true ) )
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

			$options = Hash::merge(
				parent::_optionsRecords( $params ),
				$Controller->Dsp->options( array( 'find' => true, 'allocataire' => false, 'alias' => 'Donnees', 'nums' => false ) )
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
				 array( 'Familleromev3', 'Domaineromev3', 'Metierromev3', 'Appellationromev3' )
			);

			$departement = (int)Configure::read( 'Cg.departement' );
			if( $departement === 66 ) {
				$result = array_merge(
					$result,
					array( 'Libderact66Metier', 'Libsecactderact66Secteur' )
				);
			}

			return $result;
		}
	}
?>