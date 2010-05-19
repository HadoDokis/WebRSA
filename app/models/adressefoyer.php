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


        //*********************************************************************
        /**
        *   Foyers avec plusieurs adresses_foyers.rgadr = 01
        *   donc on s'assure de n'en prendre qu'un seul et celui dont l'ID est le + élevé
        *   FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
        */

        function sqlFoyerActuelUnique() {
            return '(
                SELECT tmpadresses_foyers.id FROM (
                    SELECT MAX(adresses_foyers.id) AS id, adresses_foyers.foyer_id
                        FROM adresses_foyers
                        WHERE adresses_foyers.rgadr = \'01\'
                        GROUP BY adresses_foyers.foyer_id
                        ORDER BY adresses_foyers.foyer_id
                ) AS tmpadresses_foyers
            )';
        }

        //*********************************************************************
    }
?>