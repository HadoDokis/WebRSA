<?php
    class Detaildifsoc extends AppModel
    {
        var $name = 'Detaildifsoc';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'difsoc' => array(
						'type' => 'difsoc', 'domain' => 'dsp'
					),
				)
			)
		);

        var $belongsTo = array( 'Dsp' );
    }
?>