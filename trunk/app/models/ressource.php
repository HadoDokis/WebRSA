<?php
    class Ressource extends AppModel
    {
        var $name = 'Ressource';
        var $useTable = 'ressources';

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );

        var $hasMany = array(
            'Ressourcemensuelle' => array(
                'classname'     => 'Ressourcemensuelle',
                'foreignKey'    => 'ressource_id'
            ),
//             'Detailressourcemensuelle' => array(
//                 'classname'     => 'Detailressourcemensuelle',
//                 'foreignKey'    => 'ressource_id'
//             )
        );

        var $validate = array(
            'topressnul' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'mtpersressmenrsa' => array(
                array(
                    // FIXME INFO ailleurs aussi => 123,25 ne passe pas
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
            ),
            'ddress' => array(
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            ),
            'dfress' => array(
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            )
        );

        function beforeValidate( $options = array() ) {
            $return = parent::beforeValidate( $options );
            $this->data['Ressource']['mtpersressmenrsa'] = 0;
            if( ( !empty( $this->data['Ressource']['topressnul'] ) ) && ( $this->data['Ressource']['topressnul'] != 0 ) && !empty( $this->data['Detailressourcemensuelle'] ) ) {
                $this->data['Ressource']['mtpersressmenrsa'] = number_format( array_sum( Set::extract( $this->data['Detailressourcemensuelle'], '{n}.mtnatressmen' ) ) / 3, 2 );
            }
            return $return;
        }
    }
?>
