<?php
    class Aideapre66 extends AppModel
    {
        public $name = 'Aideapre66';

        var $belongsTo = array( 'Themeapre66' );

        var $actsAs = array( 'Autovalidate' );

        var $hasAndBelongsToMany = array(
            'Pieceaide66' => array(
                'classname'             => 'Pieceaide66',
                'joinTable'             => 'aidesapres66_piecesaides66',
                'foreignKey'            => 'aideapre66_id',
                'associationForeignKey' => 'pieceaide66_id',
                'with'                  => 'Aideapre66Pieceaide66'
            )
        );

        /**
        *
        */

        function beforeSave( $options = array() ) {

            $return = parent::beforeSave( $options );

//             $this->data[$this->alias]['apre_id'] = 14;
// debug($this->data);

            if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'typeaideapre66_id', $this->data[$this->name] ) ) {
                $this->data = Set::insert( $this->data, "{$this->alias}.typeaideapre66_id", suffix( Set::extract( $this->data, "{$this->alias}.typeaideapre66_id" ) ) );
            }

            return $return;
        }
    }
?>