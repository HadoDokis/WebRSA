<?php
    class Detaildifdisp extends AppModel
    {
        var $name = 'Detaildifdisp';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Dsp' );

        var $enumFields = array(
			'difdisp' => array( 'domain' => 'dsp' )
		);
    }
?>