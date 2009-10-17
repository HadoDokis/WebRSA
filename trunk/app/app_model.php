<?php
    class AppModel extends Model
    {
		/** ********************************************************************
		*
		*** *******************************************************************/

        function alphaNumeric($check) {
            $_this =& Validation::getInstance();
            $_this->__reset();
            $_this->check = $check;

            if (is_array($check)) {
                $_this->_extract($check);
                // FIXME: WTF 1 ?
                $t = array_values($check);
                $check = $t[0];
                $_this->check = $check;
            }

            if (empty($_this->check) && $_this->check != '0') {
                return false;
            }

            // FIXME: WTF 2 ?
            //$_this->regex = '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/mu';
            $_this->regex = '/^[a-z0-9]+$/mui';
            return $_this->_check();
        }

		/** ********************************************************************
		*
		*** *******************************************************************/

        // INFO: http://bakery.cakephp.org/articles/view/unbindall
        function unbindModelAll( $reset = true ) {
            $unbind = array();
            foreach ($this->belongsTo as $model=>$info) {
                $unbind['belongsTo'][] = $model;
            }
            foreach ($this->hasOne as $model=>$info) {
                $unbind['hasOne'][] = $model;
            }
            foreach ($this->hasMany as $model=>$info) {
                $unbind['hasMany'][] = $model;
            }
            foreach ($this->hasAndBelongsToMany as $model=>$info) {
                $unbind['hasAndBelongsToMany'][] = $model;
            }
            parent::unbindModel( $unbind, $reset );
        }

        // TODO: http://teknoid.wordpress.com/2008/09/29/dealing-with-calculated-fields-in-cakephps-find/

		/** ********************************************************************
		*
		*** *******************************************************************/

        function allEmpty( array $data, $reference ) { // FIXME + $reference2, ....
            $data = array_values( $data );
            $value = ( isset( $data[0] ) ? $data[0] : null );

            $reference = Set::extract( $this->data, $this->name.'.'.$reference );

            return ( empty( $value ) == empty( $reference )  );
        }

		/** ********************************************************************
		*	FIXME: renommer, mettre où ? (modèle)
		*** *******************************************************************/

		function nullify( $params ) {
			$fields = array_keys( $this->schema() );
			$fields = array_combine( $fields, array_fill( 0, count( $fields ), null ) );
			$this->data[$this->name] = Set::merge( $fields, nullify_empty_values( $this->data[$this->name] ) );

			$exceptions = Set::classicExtract( $params, 'exceptions' );
			if( !empty( $exceptions ) ) {
				foreach( $exceptions as $exception ) {
					$this->data = Set::remove( $this->data, $exception );
				}
			}
		}
    }
?>