<?php
    class Permanence extends AppModel
    {
        var $name = 'Permanence';

        var $useTable = 'permanences';
        var $displayField = 'libpermanence';

        var $belongsTo = array(
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            )
        );

        function listOptions() {
            $tmp = $this->find(
                'all',
                array (
                    'fields' => array(
                        'Permanence.id',
                        'Permanence.structurereferente_id',
                        'Permanence.libpermanence'
                    ),
                    'recursive' => -1,
                    'order' => 'Permanence.libpermanence ASC',
                )
            );

            $return = array();
            foreach( $tmp as $key => $value ) {
                $return[$value['Permanence']['structurereferente_id'].'_'.$value['Permanence']['id']] = $value['Permanence']['libpermanence'];
            }
            return $return;
        }


        var $validate = array(
            'structurereferente_id' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'libpermanence' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typevoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
//             'numvoie' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
            'nomvoie' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'codepos' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
//             'canton' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//             ),
            'ville' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'numtel' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
        );
    }
?>