<?php
    class Detailaccosocindi extends AppModel
    {
        var $name = 'Detailaccosocindi';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'nataccosocindi' => array( 'domain' => 'dsp' )
		);
    }
?>