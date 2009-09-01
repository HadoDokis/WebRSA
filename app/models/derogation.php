<?php
    class Derogation extends AppModel
    {
        var $name = 'Derogation';

        //var $belongsTo = array( 'Avispcgpersonne' );

        /**
        *
        */

        function dossierId( $derogation_id ) {
            $query = array(
                'fields' => array(
                    '"Foyer"."dossier_rsa_id"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'avispcgpersonnes',
                        'alias'      => 'Avispcgpersonne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Derogation.avispcgpersonne_id = Avispcgpersonne.id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Avispcgpersonne.personne_id = Personne.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    )
                )
            );

            $result = $this->find( 'first', $query );

            if( !empty( $result ) ) {
                return $result['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }
    }
?>