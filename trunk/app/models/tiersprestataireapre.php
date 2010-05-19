<?php
    class Tiersprestataireapre extends AppModel
    {
        var $name = 'Tiersprestataireapre';
        var $useTable = 'tiersprestatairesapres';

        var $displayField = 'full_name';

        var $actsAs = array(
            'Enumerable', // FIXME ?
            'MultipleDisplayFields' => array(
                'fields' => array( 'nomtiers' ),
                'pattern' => '%s'
            )
        );

        var $order = 'Tiersprestataireapre.id ASC';


        var $hasMany = array(
            'Formqualif' => array(
                'classname' => 'Formqualif',
                'foreignKey' => 'tiersprestataireapre_id',
            ),
            'Formpermfimo' => array(
                'classname' => 'Formpermfimo',
                'foreignKey' => 'tiersprestataireapre_id',
            ),
            'Actprof' => array(
                'classname' => 'Actprof',
                'foreignKey' => 'tiersprestataireapre_id',
            ),
            'Permisb' => array(
                'classname' => 'Permisb',
                'foreignKey' => 'tiersprestataireapre_id',
            )
        );

        var $modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );

        var $validate = array(
            'nomtiers' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'siret' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'isUnique',
                    'message' => 'Ce numéro SIRET existe déjà'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Le numéro SIRET est composé de 14 chiffres'
                )
            ),
//             'numvoie' => array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//             ),
            'typevoie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'nomvoie' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'codepos' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'ville' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numtel' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le numéro de téléphone est composé de 10 chiffres'
                )
            ),
            'adrelec' => array(
                'rule' => 'email',
                'message' => 'Email non valide',
                'allowEmpty' => true
            ),
            'nomtiturib' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'etaban' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'guiban' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numcomptban' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'clerib' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'La clé RIB est composée de 2 chiffres'
                )
            ),
            'aidesliees' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
        );

        /**
        *   Fonction permettant de récupérer la liste des tiers prestataires ainsi
        *   qu'un champ virtuel 'deletable' qui indique si le tiers est lié à une aide de l'APRE
        */

        function adminList() {
            $tiersprestatairesapres = $this->find( 'all', array( 'recursive' => -1 ) );

            foreach( $tiersprestatairesapres as $key => $tiersprestataireapre ) {
                $subQueries = array();
                foreach( $this->modelsFormation as $model ) {
                    $tableName = Inflector::tableize( $model );
                    $subQueries[] = "( SELECT COUNT(*) FROM {$tableName} WHERE tiersprestataireapre_id = {$tiersprestatairesapres[$key]['Tiersprestataireapre']['id']} )";
                }
                $result = $this->query( 'SELECT ( '.implode( '+', $subQueries ).' ) AS count' );
                $result = Set::classicExtract( $result, '0.0.count' );

                $tiersprestatairesapres[$key]['Tiersprestataireapre']['deletable'] = empty( $result );
            }

            return $tiersprestatairesapres;
        }
    }
?>