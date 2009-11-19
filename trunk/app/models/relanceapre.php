<?php 
    class Relanceapre extends AppModel
    {
        var $name = 'Relanceapre';
        var $actsAs = array( 'Enumerable' );

        var $validate = array(
            'etatdossierapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );

        var $enumFields = array(
            'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' )
        );


        var $belongsTo = array(
            'Apre' => array(
                'classname' => 'Apre',
                'foreignKey' => 'apre_id'
            ),
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            )
        );


    }
?>