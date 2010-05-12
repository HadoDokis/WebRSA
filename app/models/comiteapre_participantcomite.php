<?php
    class ComiteapreParticipantcomite extends AppModel
    {
        var $name = 'ComiteapreParticipantcomite';
        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
					'presence' => array(
                        'type' => 'presenceca',
                        'domain' => 'apre'
                    )
				)
            )
        );


        var $belongsTo = array(
            'Participantcomite',
            'Comiteapre'
        );

        var $validate = array(
            'id' => array( 'rule' => 'notEmpty' )
        );
    }
?>