<?php

/**
 * Code source de la classe Codemetierromev3.
 *
 * PHP 5.3
 *
 * @package app.Model
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe Codemetierromev3 ...
 *
 * @package app.Model
 */
class Codemetierromev3 extends AppModel {

    /**
     * Nom.
     *
     * @var string
     */
    public $name = 'Codemetierromev3';

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
        'Codedomaineproromev3' => array(
            'className' => 'Codedomaineproromev3',
            'foreignKey' => 'codedomaineproromev3_id',
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
        'Codeappellationromev3' => array(
            'className' => 'Codeappellationromev3',
            'foreignKey' => 'codemetierromev3_id',
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
    
    
    /**
     * Fonction permettant d'alimenter la liste déroulante d'ajout.édition 
     * de la table de paramétrages codesmetiersromev3.
     * Ceci eprmet d'afficher les valeurs des domaines sous la forme :
     *  code_famille_rome + code_domaine_rome + code_metier + intitule_metier_rome
     * @return type
     */
    public function findListParametrage() {
        $results = $this->find(
            'all',
            array(
                'fields' => array(
                    'Codefamilleromev3.code',
                    'Codemetierromev3.id',
                    'Codemetierromev3.code',
                    'Codemetierromev3.name',
                    'Codedomaineproromev3.code',
                    'Codedomaineproromev3.name'
                ),
                'joins' => array(
                    $this->join( 'Codedomaineproromev3' ),
                    $this->Codedomaineproromev3->join( 'Codefamilleromev3' )
                ),
                'contain' => false
            )
        );

        $results = Hash::combine(
            $results,
            '{n}.Codemetierromev3.id',
            array('%s%s%s - %s', '{n}.Codefamilleromev3.code', '{n}.Codedomaineproromev3.code', '{n}.Codemetierromev3.code', '{n}.Codemetierromev3.name' )
        );
        return $results;
    }
}
?>