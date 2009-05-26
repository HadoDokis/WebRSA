<?php
    class Typeorient extends AppModel
    {
        var $name = 'Typeorient';
        var $useTable = 'typesorients';

        var $hasMany = array(
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'typeorient_id'
            )
        );
	
	
        function listOptions() {
            $options = $this->find( 
                'list',
                array (
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array( 'Typeorient.parentid' => NULL ),
                    'order'  => array( 'Typeorient.lib_type_orient ASC' )
                )
            );

            if( $this->find( 'count', array( 'conditions' => array( 'Typeorient.parentid NOT' => NULL ) ) ) > 0 ) {
                $list = array();
                foreach( $options as $key => $option ) {
                    $innerOptions = $this->find( 
                        'list',
                        array (
                            'fields' => array(
                                'Typeorient.id',
                                'Typeorient.lib_type_orient'/*,
                                'Typeorient.parentid'*/
                            ),
                            'conditions' => array( 'Typeorient.parentid' => $key ),
                            'order'  => array( 'Typeorient.lib_type_orient ASC' )
                        )
                    );
                    $list[$option] = $innerOptions ;
                }
                return $list;
            }
            else {
                return $options;
            }
        }

    }
?>