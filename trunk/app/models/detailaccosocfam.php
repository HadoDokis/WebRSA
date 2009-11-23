<?php
    class Detailaccosocfam extends AppModel
    {
        var $name = 'Detailaccosocfam';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'nataccosocfam' => array( 'domain' => 'dsp' )
		);
    }
?>