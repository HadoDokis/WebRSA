<?php
	/**
	 * Code source de la classe Cataloguepdifp93Behavior.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cataloguepdifp93Behavior ...
	 *
	 * FIXME: c'est un copié/collé/adapté de ImportcsvCataloguespdisfps93Shell::_createOrUpdate()
	 *
	 * @package app.Model.Behavior
	 */
	class Cataloguepdifp93Behavior extends ModelBehavior
	{
		/**
		 * Recherche l'enregistrement répondant aux conditions. Si celui-ci existe,
		 * sa clé primaire est renvoyée; sinon, on tente d'enregistrer les données.
		 *
		 * En cas de succès, la clé primaire du nouvel enregistrement est retournée,
		 * sinon on peuple l'attribut $_validationErrors
		 *
		 * @param AppModel $Model
		 * @param array $conditions
		 * @return integer
		 */
		public function createOrUpdate( AppModel $Model, array $conditions ) {
			$conditions = Hash::flatten( $Model->doFormatting( Hash::expand( $conditions ) ) );

			$primaryKeyField = "{$Model->alias}.{$Model->primaryKey}";

			$query = array(
				'fields' => array( $primaryKeyField ),
				'conditions' => $conditions
			);

			foreach( $query['conditions'] as $path => $value ) {
				if( !is_numeric( $value ) ) {
					unset( $query['conditions'][$path] );
					list( $m, $f ) = model_field( $path );
					$query['conditions']["NOACCENTS_UPPER( \"{$m}\".\"{$f}\" )"] = noaccents_upper( $value );
				}
			}

			$record = $Model->find( 'first', $query );

			if( empty( $record ) ) {
				$record = Hash::expand( $conditions );
				$Model->create( $record );

				if( !$Model->save() ) {
					// FIXME
					/*$this->_validationErrors = Hash::merge(
						$this->_validationErrors,
						Hash::flatten( array( $Model->alias => $Model->validationErrors ) )
					);*/

					return null;
				}
				else {
					return $Model->{$Model->primaryKey};
				}
			}
			else {
				return Hash::get( $record, $primaryKeyField );
			}
		}
	}
?>