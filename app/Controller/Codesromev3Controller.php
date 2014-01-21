<?php

/**
 * Code source de la classe Codesromev3Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Codesromev3Controller ...
 *
 * @package app.Controller
 */
class Codesromev3Controller extends AppController {

    public $name = 'Codesromev3';
    public $uses = array( 'Codefamilleromev3' );

    /**
     * Helpers utilisés.
     *
     * @var array
     */
    public $helpers = array(
        'Default3' => array(
            'className' => 'Default.DefaultDefault'
        ),
    );

    /**
     * Premier niveau du paramétrage, suivant le département.
     */
    public function index() {
        $links = array(
            'Codes familles' => array( 'controller' => 'codesfamillesromev3', 'action' => 'index' ),
            'Codes domaines professionnels' => array( 'controller' => 'codesdomainesprosromev3', 'action' => 'index' ),
            'Codes métiers' => array( 'controller' => 'codesmetiersromev3', 'action' => 'index' ),
            'Appellations métiers' => array( 'controller' => 'codesappellationsromev3', 'action' => 'index' )
        );

        $links = Hash::filter( $links );
        $this->set( compact( 'links' ) );
    }

}

?>