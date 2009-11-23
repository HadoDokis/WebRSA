<?php
    class Detaildiflog extends AppModel
    {
        var $name = 'Detaildiflog';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'diflog' => array( 'domain' => 'dsp' )
		);
    }
?>