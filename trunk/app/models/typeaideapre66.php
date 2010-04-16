<?php
    class Typeaideapre66 extends AppModel
    {
        public $name = 'Typeaideapre66';

        var $belongsTo = array( 'Themeapre66' );

        var $actsAs = array( 'Autovalidate' );

        var $hasAndBelongsToMany = array(
            'Pieceaide66' => array(
                'classname'             => 'Pieceaide66',
                'joinTable'             => 'typesaidesapres66_piecesaides66',
                'foreignKey'            => 'typeaideapre66_id',
                'associationForeignKey' => 'pieceaide66_id',
                'with'                  => 'Typeaideapre66Pieceaide66'
            )
        );

        function listOptions() {
            $tmp = $this->find(
                'all',
                array (
                    'fields' => array(
                        'Typeaideapre66.id',
                        'Typeaideapre66.themeapre66_id',
                        'Typeaideapre66.name'
                    ),
                    'recursive' => -1,
                    'order' => 'Typeaideapre66.name ASC',
                )
            );

            $return = array();
            foreach( $tmp as $key => $value ) {
                $return[$value['Typeaideapre66']['themeapre66_id'].'_'.$value['Typeaideapre66']['id']] = $value['Typeaideapre66']['name'];
            }
            return $return;
        }
    }
?>