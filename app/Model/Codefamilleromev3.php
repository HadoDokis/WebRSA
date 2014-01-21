<?php

/**
 * Code source de la classe Codefamilleromev3.
 *
 * PHP 5.3
 *
 * @package app.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Codefamilleromev3 ...
 *
 * @package app.Model
 */
class Codefamilleromev3 extends AppModel {

    /**
     * Nom.
     *
     * @var string
     */
    public $name = 'Codefamilleromev3';

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
      * Associations "Has Many".
      *
      * @var array
    */
    public $hasMany = array(
        'Codedomaineproromev3' => array(
            'className' => 'Codedomaineproromev3',
            'foreignKey' => 'codefamilleromev3_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    
    public $virtualFields = array(
        'fullname' => array(
            'type' => 'string',
            'postgres' => '( "%s"."code" || \' - \' || "%s"."name" )'
        )
    );
}
?>