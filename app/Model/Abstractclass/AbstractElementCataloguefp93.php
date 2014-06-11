<?php
	/**
	 * Code source de l'interface IElementCataloguefp93 et de la classe abstraite
	 * AbstractElementCataloguefp93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Abstractclass
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Interface pour les classes d'éléments du catalogue des fiches de prescription
	 * (CG 93).
	 *
	 * @package app.Model.Abstractclass
	 */
	interface IElementCataloguefp93
	{
		/**
		 * Tentative de sauvegarde d'un élément du catalogue à partir de la
		 * partie paramétrage.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveParametrage( array $data );

		/**
		 * Retourne la liste des champs à utiliser dans le formulaire d'ajout / de
		 * modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageFields();

		/**
		 * Retourne les données à utiliser dans le formulaire de modification de
		 * la partie paramétrage.
		 *
		 * @param integer $id
		 * @return array
		 */
		public function getParametrageFormData( $id );

		/**
		 * Retourne les options à utiliser dans le formulaire d'ajout / de
		 * modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageOptions();

		/**
		 * Retourne la liste des champs dépendants à utiliser dans le formulaire
		 * d'ajout / de modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageDependantFields();
	}

	/**
	 * Classe abstraite parente pour les classes d'éléments du catalogue des
	 * fiches de prescription (CG 93).
	 *
	 * @package app.Model.Abstractclass
	 */
	abstract class AbstractElementCataloguefp93 extends AppModel implements IElementCataloguefp93
	{
		/**
		 * Tentative de sauvegarde d'un élément du catalogue à partir de la
		 * partie paramétrage.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveParametrage( array $data ) {
			$this->create( $data );
			return $this->save();
		}

		/**
		 * Retourne la liste des champs à utiliser dans le formulaire d'ajout / de
		 * modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageFields() {
			$fields = array_keys( $this->schema() );

			array_remove( $fields, 'created' );
			array_remove( $fields, 'modified' );

			$fields = Hash::normalize( array_keys( Hash::flatten( array( $this->alias => Hash::normalize( $fields ) ) ) ) );
			$enums = $this->enums();

			foreach( $fields as $path => $params ) {
				$params = (array)$params;
				if( Hash::check( $enums, $path ) || ( strrpos( $path, '_id' ) === ( strlen( $path ) - 3 ) ) ) {
					$params['empty'] = true;
				}
				$fields[$path] = $params;
			}

			return $fields;
		}

		/**
		 * Retourne les données à utiliser dans le formulaire de modification de
		 * la partie paramétrage.
		 *
		 * @param integer $id
		 * @return array
		 */
		public function getParametrageFormData( $id ) {
			$query = array(
				'conditions' => array(
					"{$this->alias}.{$this->primaryKey}" => $id
				)
			);
			return $this->find( 'first', $query );
		}

		/**
		 * Retourne les options à utiliser dans le formulaire d'ajout / de
		 * modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageOptions() {
			return $this->enums();
		}

		/**
		 * Retourne la liste des champs dépendants à utiliser dans le formulaire
		 * d'ajout / de modification de la partie paramétrage.
		 *
		 * @return array
		 */
		public function getParametrageDependantFields() {
			$return = array();
			$fields = $this->getParametrageFields();

			$lastDependant = null;
			foreach( array_keys( $fields ) as $fieldName ) {
				$length = strlen( $fieldName );
				if( strrpos( $fieldName, '_id' ) === ( $length - 3 ) ) {
					if( $lastDependant !== null ) {
						$return[$lastDependant] = $fieldName;
					}
					$lastDependant = $fieldName;
				}
			}

			return $return;
		}
	}
?>