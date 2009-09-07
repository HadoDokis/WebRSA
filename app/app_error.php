<?php
    class AppError extends ErrorHandler
    {
        /** ********************************************************************
        *
        *** *******************************************************************/

        function __construct($method, $messages) {
            App::import('Core', 'Sanitize');
            static $__previousError = null;

            if ($__previousError != array($method, $messages)) {
                $__previousError = array($method, $messages);
                $this->controller =& new CakeErrorController();
            } else {
                $this->controller =& new Controller();
                $this->controller->viewPath = 'errors';
            }

            $options = array('escape' => false);
            $messages = Sanitize::clean($messages, $options);

            if (!isset($messages[0])) {
                $messages = array($messages);
            }

            if (method_exists($this->controller, 'apperror')) {
                return $this->controller->appError($method, $messages);
            }

            if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
                $method = 'error';
            }

            if ($method !== 'error') {
                if( Configure::read( 'debug' ) == 0 ) {
                    switch( $method ) {
                        case 'dateHabilitationUser':
                            $method = 'dateHabilitationUser';
                        break;
                        case 'incompleteUser':
                            $method = 'incompleteUser';
                        break;
                        case 'lockedDossier':
                            $method = 'lockedDossier';
                        break;
                        case 'error403':
                            $method = 'error403';
                        break;
                        case 'invalidParameter':
                        case 'missingController':
                        case 'missingAction':
                        case 'missingView':
                        case 'privateAction':
                        case 'error404':
                            $method = 'error404';
                        break;
                        case 'invalidParamForToken':
                        default:
                            $method = 'error500';
                        break;
                    }
                }
            }

            if( !isset( $url ) ) {
                $url = $this->controller->here;
            }
            else {
                $url = $messages['url'];
            }
            $url = Router::normalize( $url );
            $this->controller->set(
                array(
                    'url'   => $url,
                    'base'  => $this->controller->base
                )
            );

            $this->dispatchMethod($method, $messages);
            $this->_stop();
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function incompleteUser( $params ) {
            extract( $params, EXTR_OVERWRITE );

            if (!isset($url)) {
                $url = $this->controller->here;
            }
            $url = Router::normalize($url);
            header("HTTP/1.0 401 Unauthorized");
            $this->controller->set(array(
                'code' => '401',
                'name' => __( 'Unauthorized', true ),
                'message' => $url,
                'base' => $this->controller->base,
                'params' => $params
            ));
            $this->_outputMessage( 'incomplete_user' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function lockedDossier( $params ) {
            extract( $params, EXTR_OVERWRITE );

            if (!isset($url)) {
                $url = $this->controller->here;
            }
            $url = Router::normalize($url);
            header("HTTP/1.0 401 Unauthorized");
            $this->controller->set(array(
                'code' => '401',
                'name' => __('Unauthorized', true),
                'message' => $url,
                'base' => $this->controller->base,
                'params' => $params
            ));
            $this->_outputMessage( 'locked_dossier' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function dateHabilitationUser( $params ) {
            extract( $params, EXTR_OVERWRITE );

            if (!isset($url)) {
                $url = $this->controller->here;
            }
            $url = Router::normalize($url);
            header("HTTP/1.0 401 Unauthorized");
            $this->controller->set(array(
                'code' => '401',
                'name' => __( 'Unauthorized', true ),
                'message' => $url,
                'base' => $this->controller->base,
                'params' => $params
            ));
            $this->_outputMessage( 'date_habilitation_user' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function error403( $params ) {
            extract($params, EXTR_OVERWRITE);

            if (!isset($url)) {
                $url = $this->controller->here;
            }
            $url = Router::normalize($url);
            header("HTTP/1.0 403 Forbidden");
            $this->controller->set(array(
                'code' => '403',
                'name' => __('Forbidden', true),
                'message' => $url,
                'base' => $this->controller->base
            ));
            $this->_outputMessage('error403');
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function error500( $params ) {
            extract($params, EXTR_OVERWRITE);

            if (!isset($url)) {
                $url = $this->controller->here;
            }
            $url = Router::normalize($url);
            header("HTTP/1.0 500 Internal server error");
            $this->controller->set(array(
                'code' => '500',
                'name' => __('Internal server error', true),
                'message' => $url,
                'base' => $this->controller->base
            ));
            $this->_outputMessage('error500');
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function invalidParameter( $params ) {
            extract( $params, EXTR_OVERWRITE );

            $this->controller->set(
                array(
                    'controller'    => $className,
                    'action'        => $action,
                    'file'          => $file,
                    'line'          => $line,
                    'title'         => __( 'Invalid Parameter', true )
                )
            );

            $this->_outputMessage( 'invalidParameter' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function invalidParamForToken( $params ) {
            extract( $params, EXTR_OVERWRITE );

            $this->controller->set(
                array(
                    'controller'    => $className,
                    'action'        => $action,
                    'file'          => $file,
                    'line'          => $line,
                    'title'         => __( 'Invalid token', true )
                )
            );

            $this->_outputMessage( 'invalidParamForToken' );
        }
    }
?>