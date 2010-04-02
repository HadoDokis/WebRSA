<?php
    // TODO: config parameters
    // FIXME: security ? -> http://groups.google.com/group/cake-php/browse_thread/thread/351b57905ada78dc/76bfdd3d8ade4291
    // INFO: http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components

    class PrgComponent extends Component
    {
        var $_prgActions = array();
        var $_realGetParams = false;

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
            $this->_prgActions = Set::extract( $settings, 'actions' );

			if( !is_array( $this->_prgActions ) ) {
				$this->_prgActions = array( $this->_prgActions );
			}

			$this->_prgActions = Set::normalize( $this->_prgActions );
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

			if( !empty( $controller->params['form'] ) ) {
				return;
			}

            if( in_array( '*', array_keys( $this->_prgActions ) ) || in_array( $controller->action, array_keys( $this->_prgActions ) ) ) {
				$filter = Set::extract( $this->_prgActions, "{$controller->action}.filter" );
				if( !empty( $filter ) ) {
					$datas = Set::extract( $controller->data, $filter );
				}
				else {
					$datas = $controller->data;
				}
                if( !empty( $datas ) ) {
                    $params = Set::flatten( $datas, '__' );

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
                        ( !empty( $datas ) ? $datas : array() ),
                        ( !empty( $urlParams ) ? $urlParams : array() )
                    );

                    $controller->data = Xset::bump( $params, '__' );
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