<?php
    class Detailconfort extends AppModel
    {
        var $name = 'Detailconfort';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'confort' => array(
						'type' => 'confort', 'domain' => 'dsp'
					),
				)
			)
		);

        var $belongsTo = array( 'Dsp' );
    }
?>
