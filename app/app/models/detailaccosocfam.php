<?php
    class Detailaccosocfam extends AppModel
    {
        var $name = 'Detailaccosocfam';

        var $belongsTo = array( 'Dsp' );

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'nataccosocfam' => array(
						'type' => 'nataccosocfam', 'domain' => 'dsp'
					),
				)
			)
		);
    }
?>