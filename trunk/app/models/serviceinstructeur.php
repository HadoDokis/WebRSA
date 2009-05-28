<?php
    class Serviceinstructeur extends AppModel
    {
        var $name = 'Serviceinstructeur';
        var $useTable = 'servicesinstructeurs';

        function listOptions() {
               return  $this->find('list', array ('fields' => array(
                                                      'id',
                                                      'lib_service' ),
                                                  'order'  => array( 'lib_service ASC' )
                                                  )
                                   );
        }

    };

?>
