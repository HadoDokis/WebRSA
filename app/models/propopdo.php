<?php 

    class Propopdo extends AppModel
    {
        var $name = 'Propopdo';

        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Piecepdo' => array(
                'classname'     => 'Piecepdo',
                'foreignKey'    => 'propopdo_id'
            )
        );

        var $hasAndBelongsToMany = array(
            'Typenotifpdo' => array(
                'classname' => 'Typenotifpdo',
                'joinTable' => 'propospdos_typesnotifspdos',
                'foreignKey' => 'propopdo_id',
                'associationForeignKey' => 'typenotifpdo_id'
            )
        );

        var $validate = array(
            'typepdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'motifpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'decisionpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

        function dossierId( $propopdo_id ) {
//             $this->unbindModelAll();
//             $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
            $propopdo = $this->findById( $propopdo_id, null, null, 0 );
            if( !empty( $propopdo ) ) {
                return $propopdo['Propopdo']['dossier_rsa_id'];
            }
            else {
                return null;
            }
            debug($propopdo);
        }
    }
?>