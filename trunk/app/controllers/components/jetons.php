<?php
    class JetonsComponent extends Component
    {
        var $components = array( 'Session' );
        var $_userId;

        /** *******************************************************************
            The initialize method is called before the controller's beforeFilter method.
        ******************************************************************** */
        function initialize( &$controller, $settings = array() ) {
            $this->controller = &$controller;
            // FIXME
            $this->_userId = $this->Session->read( 'Auth.User.id' );
            $this->controller->assert( valid_int( $this->_userId ), 'error500' );

            $this->User = ClassRegistry::init( 'User' );
            $this->Dossier = ClassRegistry::init( 'Dossier' );
            $this->Jeton = ClassRegistry::init( 'Jeton' );
        }

        // ********************************************************************

        function _dossierId( $params = array() ) {
            if( array_key_exists( 'Personne.id', $params ) ) {
                $this->Personne = ClassRegistry::init( 'Personne' );
                $personne = $this->Personne->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Personne.id' => $params['Personne.id']
                        )
                    )
                );
                $this->controller->assert( !empty( $personne ), 'error500' );
                return $personne['Foyer']['dossier_rsa_id'];
            }
            else if( array_key_exists( 'Dossier.id', $params ) ) {
                return $params['Dossier.id'];
            }
        }

        // ********************************************************************

        function _dossierExists( $dossier_id ) { // FIXME: si multiples!
            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array( 'Dossier.id' => $dossier_id ),
                    'recursive' => -1
                )
            );
            return ( !empty( $dossier ) );
        }

        // ********************************************************************

        function _clean() { // FIXME ?
            return $this->Jeton->deleteAll(
                array(
                    '"Jeton"."modified" <' => strftime( '%Y-%m-%d %H:%M:%S', strtotime( '-'.readTimeout().' seconds' ) )
                )
            );
        }

        // ********************************************************************

//         function free( $user_id ) { // FIXME ? + php_sid ?
//             return $this->Jeton->deleteAll(
//                 array(
//                     '"Jeton"."user_id"' => $user_id
//                 )
//             );
//         }

        // ********************************************************************

//         function removeByUid( $user_id ) {
//             $this->controller->assert( valid_int( $user_id ), 'error500' );
//
//             return $this->Jeton->deleteAll(
//                 array(
//                     '"Jeton"."user_id"' => $user_id
//                 )
//             );
//         }

        // *******************************************************************

        function ids() {
            $this->_clean();

            $jetons = $this->Jeton->find(
                'list',
                array(
                    'fields' => array(
                        'Jeton.dossier_id',
                        'Jeton.dossier_id'
                    ),
                    'conditions' => array(
                        'NOT' => array(
                            '"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
                            '"Jeton"."user_id"'     => $this->_userId
                        )
                    )
                )
            );

            return $jetons;
        }

        // ********************************************************************

        function check( $params ) {
            $dossier_id = $this->_dossierId( $params );
            $this->controller->assert( $this->_dossierExists( $dossier_id ) );
            $this->_clean();

            $jeton = $this->Jeton->find(
                'first',
                array(
                    'conditions' => array(
                        '"Jeton"."dossier_id"'  => $dossier_id,
                        'and NOT' => array(
                            '"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
                            '"Jeton"."user_id"'     => $this->_userId
                        )
                    )
                )
            );

            if( !empty( $jeton ) ) {
                $lockingUser = $this->User->find(
                    'first',
                    array(
                        'conditions' => array(
                            'User.id' => $jeton['Jeton']['user_id']
                        ),
                        'recursive' => -1
                    )
                );
                $this->controller->assert( !empty( $lockingUser ), 'error500' );
                $this->controller->cakeError(
                    'lockedDossier',
                    array(
                        'time' => ( strtotime( $jeton['Jeton']['modified'] ) + readTimeout() ),
                        'user' => $lockingUser['User']['username']
                    )
                ); // FIXME: paramètres ?
            }

            return empty( $jeton );
        }

        // ********************************************************************

        function has( $params ) {
            $dossier_id = $this->_dossierId( $params );
            $this->controller->assert( $this->_dossierExists( $dossier_id ) );
            $this->_clean();

            $nJetons = $this->Jeton->find(
                'count',
                array(
                    'conditions' => array(
                        '"Jeton"."dossier_id"'  => $dossier_id,
                        '"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
                        '"Jeton"."user_id"'     => $this->_userId
                    )
                )
            );

            $this->controller->assert( ( $nJetons != 0 ), 'error401', array( 'type' => 'locked' ) ); // FIXME: pas 401
            // FIXME: si on n'a plus la main, mais qu'on  peut la reprendre, on le fait
            return ( $nJetons != 0 );
        }

        // ********************************************************************

        function get( $params ) {
            $dossier_id = $this->_dossierId( $params );
            $this->controller->assert( $this->_dossierExists( $dossier_id ) );

            if( $this->check( $params ) ) {
                $jeton = array(
                    'Jeton' => array(
                        'dossier_id'    => $dossier_id,
                        'php_sid'       => session_id(), // FIXME: ou pas -> config
                        'user_id'       => $this->_userId
                    )
                );

                $vieuxJeton = $this->Jeton->find(
                    'first',
                    array(
                        'conditions' => array(
                            '"Jeton"."dossier_id"'  => $dossier_id,
                            '"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
                            '"Jeton"."user_id"'     => $this->_userId
                        )
                    )
                );
// debug( $vieuxJeton );
                if( !empty( $vieuxJeton ) ) {
                    $jeton['Jeton']['id'] = $vieuxJeton['Jeton']['id'];
                    $jeton['Jeton']['created'] = $vieuxJeton['Jeton']['created'];
                }

                return ( $this->Jeton->save( $jeton ) !== false );
            }
            else {
                return false;
            }
        }

        // ********************************************************************

        function release( $params ) {
            $dossier_id = $this->_dossierId( $params );
            $this->controller->assert( $this->_dossierExists( $dossier_id ) );

            return $this->Jeton->deleteAll(
                array(
                    'Jeton.dossier_id'    => $dossier_id,
                    'Jeton.php_sid'       => session_id(), // FIXME: ou pas -> config
                    'Jeton.user_id'       => $this->_userId
                )
            );
        }

        /** *******************************************************************
            The beforeRedirect method is invoked when the controller's redirect method
            is called but before any further action. If this method returns false the
            controller will not continue on to redirect the request.
            The $url, $status and $exit variables have same meaning as for the controller's method.
        ******************************************************************** */
        function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
            parent::beforeRedirect( $controller, $url, $status , $exit );
            return $url;
        }
    }
?>