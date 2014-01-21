<?php

/**
 * Code source de la classe Codeappellationromev3.
 *
 * PHP 5.3
 *
 * @package app.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Codeappellationromev3 ...
 *
 * @package app.Model
 */
class Codeappellationromev3 extends AppModel {

    /**
     * Nom.
     *
     * @var string
     */
    public $name = 'Codeappellationromev3';

    /**
     * Récursivité par défaut de ce modèle.
     *
     * @var integer
     */
    public $recursive = -1;

    /**
     * Behaviors utilisés.
     *
     * @var array
     */
    public $actsAs = array(
        'Pgsqlcake.PgsqlAutovalidate',
        'Formattable'
    );
    
    /**
     * Associations "Belongs to".
     *
     * @var array
    */
    public $belongsTo = array(
        'Codemetierromev3' => array(
            'className' => 'Codemetierromev3',
            'foreignKey' => 'codemetierromev3_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
?>