<?php
	/**
	*
	*/

	class FrenchfloatBehavior extends ModelBehavior
	{
		/**
		*
		*/

		public function setup( &$Model, $settings ) {
			if (!isset($this->settings[$Model->alias])) {
				$this->settings[$Model->alias] = array(
					'fields' => array()
				);
			}
			$this->settings[$Model->alias] = array_merge( $this->settings[$Model->alias], (array)$settings);
		}

		/**
		*
		*/

		public function beforeValidate( &$Model ){
			// INFO: ne fonctionne pas avec un ensemble de ...
			// FIXME: faire fonctionner avec un ensemble de ...
			$fields = Set::classicExtract( $this->settings, "{$Model->alias}.fields" );
			if( !empty( $fields ) ) {
				foreach( $fields as $field ) {
					$value = Set::classicExtract( $Model->data, "{$Model->alias}.{$field}" );
					if( !empty( $value ) ) {
						$Model->data[$Model->alias][$field] = preg_replace( '/^(.*),([0-9]+)$/', '\1.\2', $Model->data[$Model->alias][$field] );
					}
				}
			}
		}
	}
?>