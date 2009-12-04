<?php
    class ComiteapreParticipantcomite extends AppModel
    {
        var $name = 'ComiteapreParticipantcomite';
        var $actsAs = array( 'Enumerable' );

        var $enumFields = array(
            'presence' => array( 'type' => 'presence', 'domain' => 'apre' )
        );

        var $belongsTo = array(
            'Participantcomite'
        );
    }
?>