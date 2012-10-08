<?php
	/**
	*
	*/

	class FrenchfloatBehavior extends ModelBehavior
	{
		/**
		*
		*/

		public function setup( Model $model, $settings = array() ) {
			if (!isset($this->settings[$model->alias])) {
				$this->settings[$model->alias] = array(
					'fields' => array()
				);
			}
			$this->settings[$model->alias] = array_merge( $this->settings[$model->alias], (array)$settings);
		}

		/**
		*
		*/

		public function beforeValidate( Model $model ){
			// INFO: ne fonctionne pas avec un ensemble de ...
			// FIXME: faire fonctionner avec un ensemble de ...
			$fields = Set::classicExtract( $this->settings, "{$model->alias}.fields" );
			if( !empty( $fields ) ) {
				foreach( $fields as $field ) {
					$value = Set::classicExtract( $model->data, "{$model->alias}.{$field}" );
					if( !empty( $value ) ) {
						$model->data[$model->alias][$field] = preg_replace( '/^(.*),([0-9]+)$/', '\1.\2', $model->data[$model->alias][$field] );
					}
				}
			}
		}
	}
?>