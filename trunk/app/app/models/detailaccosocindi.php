<?php
    class Detailaccosocindi extends AppModel
    {
        var $name = 'Detailaccosocindi';

        var $belongsTo = array( 'Dsp' );

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'nataccosocindi' => array(
						'type' => 'nataccosocindi', 'domain' => 'dsp'
					),
				)
			)
		);
    }
?>