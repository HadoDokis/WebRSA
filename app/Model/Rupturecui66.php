<?php
/**
 * Code source de la classe Rupturecui66.
 *
 * PHP 5.3
 *
 * @package app.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Rupturecui66 ...
 *
 * @package app.Model
 */
class Rupturecui66 extends AppModel
{
    /**
     * Nom.
     *
     * @var string
     */
    public $name = 'Rupturecui66';

    public $recursive = -1;

    public $actsAs = array(
        'Formattable',
        'Pgsqlcake.PgsqlAutovalidate'
    );


    /**
     * Associations "Belongs To".
     *
     * @var array
     */
    public $belongsTo = array(
        'Cui' => array(
            'className' => 'Cui',
            'foreignKey' => 'cui_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Associations "Has many".
     *
     * @var array
     */

    public $hasMany = array(
        'Fichiermodule' => array(
            'className' => 'Fichiermodule',
            'foreignKey' => false,
            'dependent' => false,
            'conditions' => array(
                'Fichiermodule.modele = \'Rupturecui66\'',
                'Fichiermodule.fk_value = {$__cakeID__$}'
            ),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    /**
     * * Associations "Has And Belongs To Many".
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Motifrupturecui66' => array(
            'className' => 'Motifrupturecui66',
            'joinTable' => 'motifsrupturescuis66_rupturescuis66',
            'foreignKey' => 'rupturecui66_id',
            'associationForeignKey' => 'motifrupturecui66_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
            'with' => 'Motifrupturecui66Rupturecui66'
        )
    );
}
?>