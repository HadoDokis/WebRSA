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

        //*********************************************************************

        function dossierId( $adressefoyer_id ) {
            $adressefoyer = $this->findById( $adressefoyer_id, null, null, 0 );
            if( !empty( $adressefoyer ) ) {
                return $adressefoyer['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************
    }
?>