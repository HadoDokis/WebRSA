<?php
    class Adressefoyer extends AppModel
    {
        var $name = 'Adressefoyer';
        var $useTable = 'adresses_foyers';
        var $order = array( '"Adressefoyer"."rgadr" ASC' );

        //*********************************************************************

        /**
            Associations
        */
        var $belongsTo = array(
            'Adresse' => array(
                'className'     => 'Adresse',
                'foreignKey'    => 'adresse_id'
            ),
            'Foyer' => array(
                'className'     => 'Foyer',
                'foreignKey'    => 'foyer_id'
            )
        );

        //*********************************************************************

        /**
            Validation ... TODO
        */
        var $validate = array(
            'rgadr' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'typeadr' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );
    }
?>