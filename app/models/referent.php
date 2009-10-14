<?php
    class Referent extends AppModel
    {

        var $name = 'Referent';
        var $useTable = 'referents';

        var $belongsTo = array(
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )
        );

        var $validate = array(
            'numero_poste' => array(
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce N° de téléphone est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le N° de poste est composé de 10 chiffres'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'qual' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nom' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'prenom' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'fonction' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'email' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'structurereferente_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );



        /** ********************************************************************
        *   Retourne la liste des Referents
        ** ********************************************************************/

        function _referentsListe( $structurereferente_id = null ) {
            // Population du select référents liés aux structures
            $conditions = array();
            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = $structurereferente_id;
            }

            $referents = $this->find(
                'all',
                array(
                    'recursive' => -1,
                    'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
                    'conditions' => $conditions
                )
            );

            if( !empty( $referents ) ) {
                $ids = Set::extract( $referents, '/Referent/id' );
                $values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
                $referents = array_combine( $ids, $values );
            }
            return $referents;
        }
    }
?>