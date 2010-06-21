<?php
    class Typeaideapre66 extends AppModel
    {
        public $name = 'Typeaideapre66';

		public $order = 'Typeaideapre66.name ASC';

        public $belongsTo = array( 'Themeapre66' );

        public $actsAs = array( 'Autovalidate' );

        public $hasAndBelongsToMany = array(
            'Pieceaide66' => array(
                'classname'             => 'Pieceaide66',
                'joinTable'             => 'typesaidesapres66_piecesaides66',
                'foreignKey'            => 'typeaideapre66_id',
                'associationForeignKey' => 'pieceaide66_id',
                'with'                  => 'Typeaideapre66Pieceaide66'
            ),
            'Piececomptable66' => array(
                'classname'             => 'Piececomptable66',
                'joinTable'             => 'typesaidesapres66_piecescomptables66',
                'foreignKey'            => 'typeaideapre66_id',
                'associationForeignKey' => 'piececomptable66_id',
                'with'                  => 'Typeaideapre66Piececomptable66'
            )
        );

        public function listOptions() {
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