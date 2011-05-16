<?php
    class Detailfreinform extends AppModel
    {
        var $name = 'Detailfreinform';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'freinform' => array(
						'type' => 'freinform', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false),
			'Autovalidate'
		);

        var $belongsTo = array( 'Dsp' );
    }
?>
