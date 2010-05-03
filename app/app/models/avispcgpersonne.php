<?php
    class Avispcgpersonne extends AppModel
    {
        var $name = 'Avispcgpersonne';
        var $useTable = 'avispcgpersonnes';

        //*********************************************************************//

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );

        var $hasMany = array(
            'Derogation' => array(
                'classname'     => 'Derogation',
                'foreignKey'    => 'avispcgpersonne_id'
            ),
            'Liberalite' => array(
                'classname'     => 'Liberalite',
                'foreignKey'    => 'avispcgpersonne_id'
            )
        );


        function idFromDossierId( $dossier_rsa_id ){
            $options = array(
                'fields' => array(
                    'Personne.id'
                ),
                'joins' => array(
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            'Prestation.rolepers = \'DEM\'',
                        )
                    ),
                ),
                'conditions' => array(
                    'Foyer.dossier_rsa_id' => $dossier_rsa_id
                ),
                'recursive' => -1
            );
            $personne = $this->Personne->find( 'first', $options );
            if( empty( $personne ) ) {
                return null;
            }

            $avispcgpersonne = $this->findByPersonneId( $personne['Personne']['id'], null, null, -1 );
            if( empty( $avispcgpersonne ) ) {
                return null;
            }

            return Set::extract( $avispcgpersonne, 'Avispcgpersonne.id' );
        }
    }
?>