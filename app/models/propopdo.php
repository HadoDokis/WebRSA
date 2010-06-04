<?php

    class Propopdo extends AppModel
    {
        var $name = 'Propopdo';

        var $actsAs = array(
            'Enumerable' => array(
				'fields' => array(
					'statutdecision' => array(  'domain' => 'propopdo' ),
                    'choixpdo' => array( 'domain' => 'propopdo' ),
                    'nonadmis' => array( 'domain' => 'propopdo' ),
				)
            )
        );

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
            ),
            'Situationpdo' => array( 'with' => 'PropopdoSituationpdo' ),
            'Statutpdo' => array( 'with' => 'PropopdoStatutpdo' )
        );

        var $validate = array(
            'typepdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'motifpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire',
                'allowEmpty' => true
            ),
            'decisionpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
        );

        function dossierId( $id ) {
//             $this->unbindModelAll();
//             $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
            $propopdo = $this->findById( $id, null, null, 0 );
            if( !empty( $propopdo ) ) {
                return $propopdo['Propopdo']['dossier_rsa_id'];
            }
            else {
                return null;
            }
//             debug($propopdo);
        }
    }
?>