<?php 
    class Apre extends AppModel
    {
        var $name = 'Apre';
        var $useTable = 'apres';
//         var $displayField = 'libelle';

        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            )
        );

        var $hasMany = array(
            'Referentapre' => array(
                'classname' => 'Referentapre',
                'foreignKey' => 'apre_id',
            ),
            'Acqmatprof' => array(
                'classname' => 'Acqmatprof',
                'foreignKey' => 'apre_id',
            ),
            'Formqualif' => array(
                'classname' => 'Formqualif',
                'foreignKey' => 'apre_id',
            ),
            'Actprof' => array(
                'classname' => 'Actprof',
                'foreignKey' => 'apre_id',
            ),
            'Acccreaentr' => array(
                'classname' => 'Acccreaentr',
                'foreignKey' => 'apre_id',
            ),
            'Amenaglogt' => array(
                'classname' => 'Amenaglogt',
                'foreignKey' => 'apre_id',
            ),
            'Permisb' => array(
                'classname' => 'Permisb',
                'foreignKey' => 'apre_id',
            ),
            'Locvehicinsert' => array(
                'classname' => 'Locvehicinsert',
                'foreignKey' => 'apre_id',
            )
        );


        function dossierId( $apre_id ){
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Apre.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $apre = $this->findById( $apre_id, null, null, 0 );

            if( !empty( $apre ) ) {
                return $apre['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }


    }

?>