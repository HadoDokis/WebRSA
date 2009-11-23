<?php
    class Difsoc extends AppModel
    {
        var $name = 'Difsoc';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'difsoc' => array( 'type' => 'difsoc', 'domain' => 'dsp' )
		);
    }
?>