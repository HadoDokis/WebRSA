<?php
    class PermissionsHelper extends AppHelper {
        var $helpers = array( 'Session' );

        // INFO: utiliser grep -R "permissions->" * dans le dossier views pour voir ce qui est déjà traité
        /**
            FIXME: http://localhost/adullact/webrsa/adressesfoyers/edit/1
            avec
                [Module:Adressefoyers] =>
                [Adressefoyers:index] => 1
                [Adressefoyers:view] => 1
        */

        function check( $controller, $action ) {
            /*$controller = ucfirst( $controller );
            $action = strtolower( $action );*/
			$controller = Inflector::camelize( $controller );
            $action = strtolower( $action );
// debug( array( $controller, $action ) );

            $permissions = $this->Session->read( 'Auth.Permissions' );

//             $foundController = array_key_exists( $controller, $permissions );
//             $returnController = ( $foundController ? $permissions[$controller] : null );
//
//             $foundAction = array_key_exists( $controller.':'.$action, $permissions );
//             $returnAction = ( $foundAction ? $permissions[$controller.':'.$action] : null );

            $returnController = Set::extract( $permissions, "Module:{$controller}" );
            $returnAction = Set::extract( $permissions, $controller.':'.$action );

            if( $returnAction !== null ) {
                return $returnAction;
            }
            else if( $returnController !== null ) {
                return $returnController;
            }
            else {
                return false;
            }

            // FIXME: Dossiers -> true, Dossiers:edit -> false + dans la gestion normale ?
//             return ( ( $returnController == true || $returnAction == true ) && $returnAction );
        }
    }
?>
