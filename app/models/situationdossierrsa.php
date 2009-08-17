<?php
    class Situationdossierrsa extends AppModel
    {
        var $name = 'Situationdossierrsa';
        var $useTable = 'situationsdossiersrsa';

        //*********************************************************************

        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Suspensiondroit' => array(
                'classname'     => 'Suspensiondroit',
                'foreignKey'    => 'situationdossierrsa_id'
            ),
            'Suspensionversement' => array(
                'classname'     => 'Suspensionversement',
                'foreignKey'    => 'situationdossierrsa_id'
            ),
        );
        //*********************************************************************

        var $validate = array(
            'etatdosrsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtrefursa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'moticlorsa' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtclorsa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

        //*********************************************************************

        function etatOuvert() {
            return array( 'Z', 2, 3, 4 ); // Z => dossier ajouté avec le formulaire "Préconisation ..."
        }

        //---------------------------------------------------------------------

        function droitsOuverts( $dossier_rsa_id ) {
            if( valid_int( $dossier_rsa_id ) ) {
                $situation = $this->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );
                return in_array( Set::extract( $situation, 'Situationdossierrsa.etatdosrsa' ), $this->etatOuvert() );
            }
            else {
                return false;
            }
        }
    }
?>