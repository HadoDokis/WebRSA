<?php
    class AppModel extends Model
    {
        // début champs virtuels
        public $virtualFields = array();
        public $_findQueryType = null;


        /**
        * INFO: avec PostgreSQL, on s'assure un accès exclusif à la table "jetons"
		* INFO: Valeble seulement pour PostgreSQL
        */

        public function begin() {
            $return = $this->getDataSource()->begin($this);
//             $this->query( 'LOCK TABLE "jetons" IN ACCESS EXCLUSIVE MODE;' );
            return $return;
        }

        public function commit() {
            $return = $this->getDataSource()->commit($this);
            return $return;
        }

        public function rollback() {
            $return = $this->getDataSource()->rollback($this);
            return $return;
        }

        /**
        * Remplace le caractère * par le caractère % por les requêtes SQL
        * FIXME ? ne fonctionne pas avec Sanitize::paranoid
        */

        public function wildcard( $value ) {
            //debug( str_replace( '*', '%', $value ) );
            return str_replace( '*', '%', $value );
        }

        /**
        *
        */

        protected function queryFields( $queryData = array() ) {
            $recursive = Set::classicExtract( $queryData, 'recursive' );
            if( $recursive === null ) {
                $recursive = $this->recursive;
            }

            $fields = array();

            foreach( array_keys( $this->schema() ) as $modelField ) {
                $fields[] = "{$this->alias}.{$modelField}";
            }

            if( $recursive >= 0 ) {
                $associationModels = Set::merge( $this->belongsTo, $this->hasOne );

                foreach( array_keys( $associationModels ) as $associationModel ) {
                    $associationFields = $this->joinModel( $associationModel );
                    $associationFields = $associationFields[1];

                    $associationFields2 = array();
                    $associationModelInstance = ClassRegistry::init( $associationModel );
                    if( !empty( $associationModelInstance->virtualFields ) && is_array( $associationModelInstance->virtualFields ) ) {
                        $associationFields2 = Set::merge( $fields, Set::extract( $associationModelInstance->virtualFields, '{s}.alias' ) );

                        // FIXME: Set::combine ?
                        $replacements = array_combine(
                            Set::extract( $associationModelInstance->virtualFields, '{s}.regex' ),
                            Set::extract( $associationModelInstance->virtualFields, '{s}.alias' )
                        );
                        $associationFields2 = recursive_key_value_preg_replace( $associationFields2, $replacements );
                    }

                    foreach( $associationFields as $associationField ) {
                        $fields[] = "{$associationModel}.{$associationField}";
                    }

                    $fields = Set::merge( $fields, $associationFields2 );
                }
            }

            return $fields;
        }

        /**
        *
        */

        public function initVirtualFields( $virtualFields ) {
            if( is_array( $virtualFields ) && !empty( $virtualFields ) ) {
                $this->dbo = ConnectionManager::getInstance()->getDataSource( $this->useDbConfig );
                $driver = ConnectionManager::getInstance()->config->{$this->useDbConfig}['driver'];

                foreach( $virtualFields as $fieldName => $params ) {
                    foreach( array( 'query', $driver ) as $key ) {
                        if( Set::check( $params, $key ) ) {
                            $occurences = count( strallpos( $params[$key], '%s' ) );
                            if( $occurences > 0 ) {
                                $aliases = array_fill( 0, $occurences, $this->alias );
                                $params[$key] = vsprintf( $params[$key], $aliases );
                            }
                        }
                    }

                    if( empty( $params['query'] ) && !empty( $params[$driver] ) ) {
                        $virtualFields[$fieldName]['query'] = $params[$driver];
                    }
                    else if( ( $driver == 'mysqli' ) && empty( $params['query'] ) && !empty( $params['mysql'] ) ) {
                        $virtualFields[$fieldName]['query'] = $params['mysql'];
                    }
                    /// Empty query or empty type -> error, etc ...
                    if( !in_array( 'query', array_keys( $virtualFields[$fieldName] ) ) || empty( $virtualFields[$fieldName]['query'] ) ) {
                        trigger_error( "No query available for field {$fieldName}", E_USER_WARNING );
                        return null;
                    }
                    if( !in_array( 'type', array_keys( $virtualFields[$fieldName] ) ) || empty( $virtualFields[$fieldName]['type'] ) ) {
                        trigger_error( "No type specified for field {$fieldName}", E_USER_WARNING );
                        return null;
                    }
                    /// TODO: ... = `User`.`id` + () et espaces
                    $virtualFields[$fieldName]['regex'] = "/(?<!\w)({$this->alias}\.){0,1}{$fieldName}(?!\w)/";
                    $virtualFields[$fieldName]['alias'] = "{$virtualFields[$fieldName]['query']} {$this->dbo->alias} {$this->dbo->startQuote}{$this->alias}__{$fieldName}{$this->dbo->endQuote}";
                }
            }

            return $virtualFields;
        }

        /**
        *
        */

        public function __construct( $id = false, $table = NULL, $ds = NULL ) {
            parent::__construct( $id, $table, $ds);

            /// FIXME: protected _virtualFieldsIni = false
            $this->virtualFields = $this->initVirtualFields( $this->virtualFields );
        }

        /**
        *
        */

        protected function _beforeFindVirtualFields( $queryData ) {
            if( is_array( $this->virtualFields ) && !empty( $this->virtualFields ) ) {
                $this->dbo = ConnectionManager::getInstance()->getDataSource( $this->useDbConfig );

                /*
                * Get the list of fields and virtual fields
                */

                if( $this->findQueryType != 'count' ) {
                    if( empty( $queryData['fields'] ) ) {
                        //$fields = $this->dbo->fields( $this, null, null, true );
                        $fields = $this->queryFields( $queryData );
                        $aliases = Set::extract( $this->virtualFields, '{s}.alias' );
                        $fields = Set::merge( $fields, $aliases );
                        $queryData['fields'] = $fields;
                    }
                }
                /*
                    INFO:
                    var $belongsTo = array(
                        'Ep',
                        'Motifdemreorient',
                        'Reforigine' => array(
                            'className' => 'Referent'
                        ),
                        'Refaccueil' => array(
                            'className' => 'Referent'
                        ),
                    );
                    avec un champ virtuel dans Referent -> n'aliase pas
                */
                $linkedModels = array( $this );
                $associated = array_keys( $this->getAssociated() );
                foreach( $associated as $associatedModel ) {
                    $linkedModels[] = ClassRegistry::init( $associatedModel );
                }

                /*
                * Translate virtual fields fieldnames
                */

                if( $this->findQueryType != 'count' ) {
                    foreach( $linkedModels as $linkedModel ) {
                        $regexes = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.regex' ) );
                        $aliases = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.alias' ) );

                        if( !empty( $regexes ) && !empty( $aliases ) ) {
                            if( count( $regexes ) == count( $aliases ) ) {
                                // FIXME: Set::combine ?
                                $replacements = array_combine( $regexes, $aliases );
                                if( is_array( $queryData['fields'] ) ) {
                                    $queryData['fields'] = recursive_key_value_preg_replace( $queryData['fields'], $replacements );
                                }
                                else {
                                    $queryData['fields'] = preg_replace( $regexes, $replacements, $queryData['fields'] );
                                }
                            }
                            else {
                                trigger_error( "...", E_USER_WARNING );
                                return false;
                            }
                        }
                    }
                }

                /*
                * Translate virtual fields conditions, order and group
                */

                foreach( $linkedModels as $linkedModel ) {
                    $regexes = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.regex' ) );
                    $queries = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.query' ) );
                    if( !empty( $regexes ) && !empty( $queries ) ) {
                        if( count( $regexes ) == count( $queries ) ) {
                            // FIXME: Set::combine ?
                            $replacements = array_combine( $regexes, $queries );

                            foreach( array( 'conditions', 'order', 'group' ) as $type ) {
                                if( isset( $queryData[$type] ) ) {
                                    if( is_array( $queryData[$type] ) ) {
                                        $queryData[$type] = recursive_key_value_preg_replace( $queryData[$type], $replacements );
                                    }
                                    else {
                                        foreach( $replacements as $pattern => $replacement ) {
                                            $queryData[$type] = preg_replace( $pattern, $replacement, $queryData[$type] );
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            trigger_error( "...", E_USER_WARNING );
                            return false;
                        }
                    }
                }
            }

            return $queryData;
        }

        /**
        *
        */

        public function beforeFind( $queryData ) {
            $this->_findQueryType = $this->findQueryType;

            if( $return = parent::beforeFind( $queryData ) ) {

                return $this->_beforeFindVirtualFields( $queryData );
            }

            return $return;
        }

        /**
        *
        */

        protected function _afterFindVirtualFields( $results, $primary = false ) {
            /// Virtual fields
            if( !in_array( $this->_findQueryType, array( 'count' ) ) && is_array( $this->virtualFields ) && !empty( $this->virtualFields ) ) {
                $results = Xset::flatten( $results );

                foreach( $results as $key => $result ) {
                    if( preg_match( "/^(.+)\.0\.(\w+)__(\w+)$/", $key, $matches ) ) {
                        unset( $results[$key] );
                        $key = "{$matches[1]}.{$matches[2]}.{$matches[3]}";
                        $key = str_replace( ".{$matches[2]}.{$matches[2]}.", '.', $key );
                        $results[$key] = $result;
                    }
                }

                $results = Xset::bump( $results );
            }

            return $results;
        }

        /**
        *
        */

        public function afterFind( $results, $primary = false ) {
            $results = $this->_afterFindVirtualFields( $results, $primary = false );

            return $results;
        }

        /**
        *
        */

        public function getColumnType( $column, $virtual = false ) {
            $columnParams = $this->schema( $column, $virtual );
            if( !empty( $columnParams ) && is_array( $columnParams ) && isset( $columnParams['type'] ) ) {
                return $columnParams['type'];
            }

            return null;
        }

        /**
        *
        */

        public function getColumnTypes( $virtual = false ) {
            $columnsTypes = array();

            $columnsParams = $this->schema( false, $virtual );
            if( !empty( $columnsParams ) && is_array( $columnsParams ) ) {
                foreach( $columnsParams as $columnName => $columnParams )
                $columnsTypes[$columnName] = $columnParams['type'];
            }

            return $columnsTypes;
        }

        /**
        * Returns an array of table metadata (column names and types) from the database.
        * $field => keys(type, null, default, key, length, extra)
        *
        * @param mixed $field Set to true to reload schema, or a string to return a specific field
        * @return array Array of table metadata
        * @access public
        */
        // BEGIN cbuffin
		function schema($field = false, $virtual = false) {
            if (!is_array($this->_schema) || $field === true) {
                $db =& ConnectionManager::getDataSource($this->useDbConfig);
                $db->cacheSources = ($this->cacheSources && $db->cacheSources);
                if ($db->isInterfaceSupported('describe') && $this->useTable !== false) {
                    $this->_schema = $db->describe($this, $field);
                } elseif ($this->useTable === false) {
                    $this->_schema = array();
                }
            }

            $schema = $this->_schema;
            if( $virtual ) {
                if( !empty( $this->virtualFields ) && is_array( $this->virtualFields ) ) {
                    foreach( $this->virtualFields as $fieldName => $params ) {
                        $schema[$fieldName] = array( 'type' => $params['type'], 'virtual' => true );
                    }
                }
            }

            if (is_string($field)) {
                if (isset($schema[$field])) {
                    return $schema[$field];
                } else {
                    return null;
                }
            }
            return $schema;
        }
        /*function schema($field = false, $virtual = false) {
            if (!is_array($this->_schema) || $field === true) {
                $db =& ConnectionManager::getDataSource($this->useDbConfig);
                $db->cacheSources = ($this->cacheSources && $db->cacheSources);
                if ($db->isInterfaceSupported('describe') && $this->useTable !== false) {
                    $this->_schema = $db->describe($this, $field);
                } elseif ($this->useTable === false) {
                    $this->_schema = array();
                }
            }

            $schema = $this->_schema;

            if( $virtual ) {
                if( !empty( $this->virtualFields ) && is_array( $this->virtualFields ) ) {
                    foreach( $this->virtualFields as $fieldName => $params ) {
                        $this->_schema[$fieldName] = array( 'type' => $params['type'], 'virtual' => true );
                    }
                }
            }

            if (is_string($field)) {
                if (isset($this->_schema[$field])) {
                    return $this->_schema[$field];
                } else {
                    return null;
                }
            }
            return $this->_schema;
        }*/
        // END cbuffin

        /**
        * Returns true if the supplied field exists in the model's database table.
        *
        * @param mixed $name Name of field to look for, or an array of names
        * @return mixed If $name is a string, returns a boolean indicating whether the field exists.
        *               If $name is an array of field names, returns the first field that exists,
        *               or false if none exist.
        * @access public
        */

        function hasField( $name, $virtual = false ) {
            if (is_array($name)) {
                foreach ($name as $n) {
                    if ($this->hasField( $n, $virtual )) {
                        return $n;
                    }
                }
                return false;
            }

            /// cbuffin begin
            if( $virtual ) {
                $schema = $this->schema( null, $virtual );
            }
            else {
                $schema = $this->schema();
            }

            if( $schema != null) {
                return isset( $schema[$name] );
            }
            /// cbuffin end
            return false;
        }

        /**
        * Build an array-based association from string.
        *
        * @param string $type 'belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'
        * @return void
        * @access private
        */

        function __generateAssociation($type) {
            foreach ($this->{$type} as $assocKey => $assocData) {
                $class = $assocKey;
                $dynamicWith = false;

                foreach ($this->__associationKeys[$type] as $key) {
                    if (!isset($this->{$type}[$assocKey][$key]) || $this->{$type}[$assocKey][$key] === null) {
                        $data = '';

                        switch ($key) {
                            case 'fields':
                                // BEGIN cbuffin
                                $associationModelInstance = ClassRegistry::init( $assocKey );
                                $fields = array_keys( $associationModelInstance->schema() );

                                if( !empty( $associationModelInstance->virtualFields ) && is_array( $associationModelInstance->virtualFields ) ) {
                                    $associationModelInstance->virtualFields = $associationModelInstance->initVirtualFields( $associationModelInstance->virtualFields );
                                    $associationFields2 = Set::merge( $fields, Set::extract( $associationModelInstance->virtualFields, '{s}.alias' ) );
                                    $replacements = array_combine(
                                        Set::extract( $associationModelInstance->virtualFields, '{s}.regex' ),
                                        Set::extract( $associationModelInstance->virtualFields, '{s}.alias' )
                                    );
                                    $associationFields2 = recursive_key_value_preg_replace( $associationFields2, $replacements );
                                    $fields = $associationFields2;
                                }

                                $data = $fields;
                                // END cbuffin
                            break;

                            case 'foreignKey':
                                $data = (($type == 'belongsTo') ? Inflector::underscore($assocKey) : Inflector::singularize($this->table)) . '_id';
                            break;

                            case 'associationForeignKey':
                                $data = Inflector::singularize($this->{$class}->table) . '_id';
                            break;

                            case 'with':
                                $data = Inflector::camelize(Inflector::singularize($this->{$type}[$assocKey]['joinTable']));
                                $dynamicWith = true;
                            break;

                            case 'joinTable':
                                $tables = array($this->table, $this->{$class}->table);
                                sort ($tables);
                                $data = $tables[0] . '_' . $tables[1];
                            break;

                            case 'className':
                                $data = $class;
                            break;

                            case 'unique':
                                $data = true;
                            break;
                        }
                        $this->{$type}[$assocKey][$key] = $data;
                    }
                }

                if (!empty($this->{$type}[$assocKey]['with'])) {
                    $joinClass = $this->{$type}[$assocKey]['with'];
                    if (is_array($joinClass)) {
                        $joinClass = key($joinClass);
                    }
                    $plugin = null;

                    if (strpos($joinClass, '.') !== false) {
                        list($plugin, $joinClass) = explode('.', $joinClass);
                        $plugin = $plugin . '.';
                        $this->{$type}[$assocKey]['with'] = $joinClass;
                    }

                    if (!ClassRegistry::isKeySet($joinClass) && $dynamicWith === true) {
                        $this->{$joinClass} = new AppModel(array(
                            'name' => $joinClass,
                            'table' => $this->{$type}[$assocKey]['joinTable'],
                            'ds' => $this->useDbConfig
                        ));
                    } else {
                        $this->__constructLinkedModel($joinClass, $plugin . $joinClass);
                        $this->{$type}[$assocKey]['joinTable'] = $this->{$joinClass}->table;
                    }

                    if (count($this->{$joinClass}->schema()) <= 2 && $this->{$joinClass}->primaryKey !== false) {
                        $this->{$joinClass}->primaryKey = $this->{$type}[$assocKey]['foreignKey'];
                    }
                }
            }
        }

        // fin champs virtuels

        /**
        *
        */

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

        // TODO: http://teknoid.wordpress.com/2008/09/29/dealing-with-calculated-fields-in-cakephps-find/

        /**
        *   Vérification que la date saisie n'est aps inférieure à celle du jour
        */

        function futureDate( $check ){
            $return = true;
            foreach( $check as $field => $value ) {
                $return = ( strtotime( $value ) >= strtotime( date( 'Y-m-d' ) ) ) && $return;
            }

            return $return;
        }

        /**
        *
        */

        public function integer( $check ) {
            // TODO: meilleure validation ?
            if( !is_array( $check ) ) {
                return false;
            }

            // TODO: meilleure validation ?
            $result = true;
            foreach( Set::normalize( $check ) as $key => $value ) {
                $result = preg_match( '/^[0-9]+$/', $value ) && $result;
            }
            return $result;
        }

        /**
        *
        */

        function phoneFr( $check ){
            $return = true;
            foreach( $check as $field => $value ) {
                $value = preg_replace( array( '/\./', '/ /' ), array(), $value );
                $return = preg_match( '/^([0-9]{10})$/', $value ) && $return;
            }

            return $return;
        }

        /**
        *
        */

        function allEmpty( array $data, $reference ) { // FIXME + $reference2, ....
            $data = array_values( $data );
            $value = ( isset( $data[0] ) ? $data[0] : null );

            $reference = Set::extract( $this->data, $this->name.'.'.$reference );

            return ( empty( $value ) == empty( $reference )  );
        }

        /**
        * INFO: http://bakery.cakephp.org/articles/view/unbindall
        */

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

        /** ********************************************************************
        *   FIXME: renommer, mettre où ? (modèle)
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

        /**
        * On retourne un booléen true / false dans tous les cas.
        */

		function saveAll( $data = NULL, $options = array() ) {
            $return = parent::saveAll( $data, $options );

            if( is_array( $return ) && !empty( $return ) ) {
                $return = Set::flatten( $return );
                $return = ( array_sum( $return ) == count( $return ) );
            }
			else if( is_array( $return ) && empty( $return ) ) {
				return false;
			}

            return $return;
        }

		/**
		* Validate that a number is in specified range.
		* if $lower and $upper are not set, will return true if
		* $check is a legal finite on this platform
		* FIXME: signature + retour
		*
		* @param string $check Value to check
		* @param integer $lower Lower limit
		* @param integer $upper Upper limit
		* @return boolean Success
		* @access public
		*/

		function inclusiveRange( $check, $lower = null, $upper = null ) {
            $return = true;
            foreach( $check as $field => $value ) {
				if (isset($lower) && isset($upper)) {
					$return = ( $value >= $lower && $value <= $upper ) && $return;
				}
			}
			return $return;
		}
    }
?>