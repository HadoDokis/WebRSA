<?php

    class User extends AppModel {

        var $name = 'User';


        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'isgestionnaire',
                    'sensibilite',
                )
            )
        );

        var $belongsTo = array(
            'Group'=> array(
                'className'  => 'Group',
                'conditions' => '',
                'order'      => '',
                'dependent'  => false,
                'foreignKey' => 'group_id'
            ),
            'Serviceinstructeur'=> array(
                'className'  => 'Serviceinstructeur',
                'conditions' => '',
                'order'      => '',
                'dependent'  => false,
                'foreignKey' => 'serviceinstructeur_id'
            )
        );

        public $virtualFields = array(
            'nom_complet' => array(
                'type'      => 'string',
                'postgres'  => '( "%s"."nom" || \' \' || "%s"."prenom" )'
            ),
        );
        //*********************************************************************

        var $hasAndBelongsToMany = array(
            'Zonegeographique' => array(
                'classname'             => 'Zonegeographique',
                'joinTable'             => 'users_zonesgeographiques',
                'foreignKey'            => 'user_id',
                'associationForeignKey' => 'zonegeographique_id'
            ),
            'Contratinsertion' => array(
                'classname'             => 'Contratinsertion',
                'joinTable'             => 'users_contratsinsertion',
                'foreignKey'            => 'user_id',
                'associationForeignKey' => 'contratinsertion_id'
            )
        );

        //*********************************************************************

        // FIXME:SQL Error: ERREUR:  une valeur NULL viole la contrainte NOT NULL de la colonne « password » [CORE/cake/libs/model/datasources/dbo_source.php, line 525]

        var $validate = array(
            'username' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'Cet identifiant est déjà utilisé'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'passwd' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'group_id' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'serviceinstructeur_id' => array(
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
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numtel' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce numéro est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le numéro de téléphone est composé de 10 chiffres'
                )
            ),
            'date_deb_hab' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),
            'date_fin_hab' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),
            'isgestionnaire' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'sensibilite' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );

        //*********************************************************************

        function beforeSave() {
            if( !empty( $this->data['User']['passwd'] ) ) {
                $this->data['User']['password'] = Security::hash( $this->data['User']['passwd'], null, true );
            }

            parent::beforeSave();
            return true;
        }

        //*********************************************************************

        function beforeDelete() {
            debug( $this->data );
            die();
        }
    }
?>
