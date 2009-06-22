<?php
    class AppModel extends Model
    {
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

        // INFO: http://bakery.cakephp.org/articles/view/unbindall
        function unbindModelAll() {
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
            parent::unbindModel( $unbind, true );
        }
    }
?>