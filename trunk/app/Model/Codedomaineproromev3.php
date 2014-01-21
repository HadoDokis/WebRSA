<?php

/**
 * Code source de la classe Codedomaineproromev3.
 *
 * PHP 5.3
 *
 * @package app.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Codedomaineproromev3 ...
 *
 * @package app.Model
 */
class Codedomaineproromev3 extends AppModel {

    /**
     * Nom.
     *
     * @var string
     */
    public $name = 'Codedomaineproromev3';

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
        'Codefamilleromev3' => array(
            'className' => 'Codefamilleromev3',
            'foreignKey' => 'codefamilleromev3_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
     /**
      * Associations "Has Many".
      *
      * @var array
    */
    public $hasMany = array(
        'Codemetierromev3' => array(
            'className' => 'Codemetierromev3',
            'foreignKey' => 'codedomaineproromev3_id',
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
    
    /**
     * Fonction permettant d'alimenter la liste déroulante d'ajout.édition 
     * de la table de paramétrages codesmetiersromev3.
     * Ceci eprmet d'afficher les valeurs des domaines sous la forme :
     *  code_famille_rome + code_domaine_rome + intitule_domaine_rome
     * @return type
     */
    public function findListParametrage () {
        $results = $this->find(
            'all',
            array(
                'fields' => array(
                    'Codefamilleromev3.code',
                    'Codedomaineproromev3.id',
                    'Codedomaineproromev3.code',
                    'Codedomaineproromev3.name'
                ),
                'joins' => array(
                    $this->join( 'Codefamilleromev3' )
                ),
                'contain' => false
            )
        );
        
        $results = Hash::combine(
            $results,
            '{n}.Codedomaineproromev3.id',
            array('%s%s - %s', '{n}.Codefamilleromev3.code', '{n}.Codedomaineproromev3.code', '{n}.Codedomaineproromev3.name' )
        );
        return $results;
    }
}
?>