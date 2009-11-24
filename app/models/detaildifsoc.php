<?php
    class Detaildifsoc extends AppModel
    {
        var $name = 'Detaildifsoc';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'difsoc' => array( 'type' => 'difsoc', 'domain' => 'dsp' )
		);
    }
?>