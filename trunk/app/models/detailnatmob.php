<?php
    class Detailnatmob extends AppModel
    {
        var $name = 'Detailnatmob';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'natmob' => array( 'domain' => 'dsp' )
		);
    }
?>