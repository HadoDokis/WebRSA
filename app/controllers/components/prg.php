<?php
    // TODO: config parameters
    // FIXME: security ? -> http://groups.google.com/group/cake-php/browse_thread/thread/351b57905ada78dc/76bfdd3d8ade4291
    // INFO: http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components

    class PrgComponent extends Component
    {
        var $_prgActions = array();
        var $_realGetParams = false;

        /** *******************************************************************
            @input  multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
            @output unisized array (eg. array( 'Foo__Bar' => 'value' ) )
        ******************************************************************** */
        function _unisize( array $array, $prefix = null ) {
			$newArray = array();
			if( is_array( $array ) && !empty( $array ) ) {
				foreach( $array as $key => $value ) {
					$newKey = ( !empty( $prefix ) ? $prefix.'__'.$key : $key );
					if( is_array( $value ) ) {
						$newArray = Set::merge( $newArray, self::_unisize( $value, $newKey ) );
					}
					else {
						$newArray[$newKey] = $value;
					}
				}
			}
			return $newArray;
        }

        /** *******************************************************************
            @output multisized array (eg. array( 'Foo' => array( 'Bar' => 'value' ) ) )
            @input  unisized array (eg. array( 'Foo__Bar' => 'value' ) )
        ******************************************************************** */
        function _multisize( array $array, $prefix = null ) {
            $newArray = array();
			if( is_array( $array ) && !empty( $array ) ) {
				foreach( $array as $key => $value ) {
					$newArray = Set::insert( $newArray, implode( '.', explode( '__', $key ) ), $value );
				}
			}
            return $newArray;
        }

        /** *******************************************************************
            Actions for which this component is to be used
        ******************************************************************** */
//         function actions() {
//             $args = func_get_args();
//             if( empty( $args ) ) {
//                 $this->_prgActions = array( '*' );
//             }
//             else {
//                 if( isset( $args[0] ) && is_array( $args[0] ) ) {
//                     $args = $args[0];
//                 }
//                 $this->_prgActions = array_merge( $this->_prgActions, $args );
//             }
//         }

        /** *******************************************************************
            Configuration:
                * "true"    -> url like ..controller/action?name=value...
                * "false"   -> url like ..controller/action/name:value...
        ******************************************************************** */
        function _realGetParams() {
            $args = func_get_args();
            $this->_realGetParams = true;
        }

        /** *******************************************************************
            The initialize method is called before the controller's beforeFilter method.
        ******************************************************************** */
        function initialize( &$controller, $settings = array() ) {
            $this->controller = &$controller;
            $this->_prgActions = ( !empty( $settings['actions'] ) ? $settings['actions'] : array() );
        }

        /** *******************************************************************
            The startup method is called after the controller's beforeFilter
            method but before the controller executes the current action handler.
        ******************************************************************** */
        function startup( &$controller ) {
            $controller->data = Set::merge(
                $controller->data,
                ( !empty( $controller->params['form'] ) ? $controller->params['form'] : array() )
            );

            if( in_array( '*', $this->_prgActions ) || in_array( $controller->action, $this->_prgActions ) ) {
                if( !empty( $controller->data ) ) {
                    $params = $this->_unisize( $controller->data );

                    // Real get params
                    if( $this->_realGetParams ) {
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
                        // FIXME: donne des url erronées
                        //$getUrl = Router::url( array( 'controller' => strtolower( $controller->name ), 'action' => $controller->action ) ).'/'.implode_assoc( '/', ':', $params );
                    }

                    header( 'Location: '.$getUrl );

                    // INFO: this doesn't work
                    // $controller->redirect( $getUrl, true );
                }
                else {
                    // Real get params
                    if( $this->_realGetParams ) {
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

        /** *******************************************************************
            The beforeRedirect method is invoked when the controller's redirect method
            is called but before any further action. If this method returns false the
            controller will not continue on to redirect the request.
            The $url, $status and $exit variables have same meaning as for the controller's method.
        ******************************************************************** */
        function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
            parent::beforeRedirect( $controller, $url, $status , $exit );
        }
    }
?>