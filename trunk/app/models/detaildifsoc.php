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
			),
			'Revision' => array('auto'=>false),
			'Autovalidate'
		);

        var $belongsTo = array( 'Dsp' );
    }
?>
