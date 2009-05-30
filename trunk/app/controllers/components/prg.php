<?php
    // TODO: config parameters
    // FIXME: security ? -> http://groups.google.com/group/cake-php/browse_thread/thread/351b57905ada78dc/76bfdd3d8ade4291

    class PrgComponent extends Component
    {
        var $prgActions = array();
        var $realGetParams = false;

        /**
            @input  multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
            @output unisized array (eg. array( 'Foo__Bar' => 'value' ) )
        */
        function _unisize( array $array, $prefix = null ) {
            $newArray = array();
            foreach( $array as $key => $value ) {
                $newKey = ( !empty( $prefix ) ? $prefix.'__'.$key : $key );
                if( is_array( $value ) ) {
                    $newArray = Set::merge( $newArray, self::_unisize( $value, $newKey ) );
                }
                else {
                    $newArray[$newKey] = $value;
                }
            }
            return $newArray;
        }

        /**
            @output multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
            @input  unisized array (eg. array( 'Foo__Bar' => 'value' ) )
        */
        function _multisize( array $array, $prefix = null ) {
            $newArray = array();
            foreach( $array as $key => $value ) {
                $newArray = Set::insert( $newArray, implode( '.', explode( '__', $key ) ), $value );
            }
            return $newArray;
        }

        /**
            Actions for which this component is to be used
        */
        function actions() {
            $args = func_get_args();
            if( empty( $args ) ) {
                $this->prgActions = array('*');
            }
            else {
                if( isset( $args[0] ) && is_array( $args[0] ) ) {
                    $args = $args[0];
                }
                $this->prgActions = array_merge( $this->prgActions, $args );
            }
        }

        /**
            Configuration:
                * "true"    -> url like ..controller/action?name=value...
                * "false"   -> url like ..controller/action/name:value...
        */
        function realGetParams() {
            $args = func_get_args();
            $this->realGetParams = true;
        }

        /**
        */
        function startup( &$controller ) {
            $controller->data = Set::merge(
                $controller->data,
                ( !empty( $controller->params['form'] ) ? $controller->params['form'] : array() )
            );

            if( in_array( '*', $this->prgActions ) || in_array( $controller->action, $this->prgActions ) ) {
                if( !empty( $controller->data ) ) {
                    $params = $this->_unisize( $controller->data );

                    // Real get params
                    if( $this->realGetParams ) {
                        $params = array_filter( $params );
                        $getUrl = Router::url( array( 'action' => $controller->action, '?' => $params ) );
                    }
                    // Cakephp "named params"
                    else {
                        // INFO: those caracters not permitted in string or else, get params are breaked
                        foreach( $params as $key => $param ) {
                            foreach( array( '?', '/', ':', '&' ) as $forbidden ) {
                                $param = str_replace( $forbidden, ' ', $param );
                            }
                            $params[$key] =  urlencode( $param );
                        }
                        $getUrl = Router::url( array_merge( array( 'action' => $controller->action ), $params ) );
                    }

                    header( 'Location: '.$getUrl );

                    // INFO: this doesn't work
                    // $controller->redirect( $getUrl, true );
                }
                else {
                    // Real get params
                    if( $this->realGetParams ) {
                        $urlParams = $controller->params['url'];
                        unset( $urlParams['url'] );
                    }
                    // Cakephp "named params"
                    else {
                        $urlParams = $controller->params['named'];
                    }

                    $params = Set::merge(
                        ( !empty( $controller->data ) ? $controller->data : array() ),
                        ( !empty( $urlParams ) ? $urlParams : array() )
                    );

                    $controller->data = $this->_multisize( $params );
                }
            }
        }
    }
?>