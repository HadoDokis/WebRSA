<?php
    class Modecontact extends AppModel
    {
        var $name = 'Modecontact';
        var $useTable = 'modescontact';

        var $belongsTo = array(
            'Foyer' => array(
                'classname'     => 'Foyer',
                'foreignKey'    => 'foyer_id'
            )
        );

        //*********************************************************************

        function dossierId( $modecontact_id ) {
            $modecontact = $this->findById( $modecontact_id, null, null, 0 );
            if( !empty( $modecontact ) ) {
                return $modecontact['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************

        var $validate = array(
            // Role personne
            'numtel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce numéro est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le numéro de téléphone est composé de 10 chiffres'
                )
            ),
            'numposte' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce numéro est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 4, 4 ),
                    'message' => 'Le numéro de poste est composé de 4 chiffres'
                )
            ),
//             'nattel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'matetel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'autorutitel' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'adrelec' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
//             'autorutiadrelec' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             )
        );
    }
?>