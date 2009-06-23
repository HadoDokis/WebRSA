<?php
    class Modecontact extends AppModel
    {
        var $name = 'Modecontact';
        var $useTable = 'modescontact';

        var $belongsTo = array(
            'Foyer' => array(
                'classname'     => 'Foyer',
                'foreignKey'    => 'foyer_id'
            )
        );

        //*********************************************************************

        function dossierId( $modecontact_id ) {
            $modecontact = $this->findById( $modecontact_id, null, null, 0 );
            if( !empty( $modecontact ) ) {
                return $modecontact['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************
    }
?>