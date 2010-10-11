<?php
    class Detaildiflog extends AppModel
    {
        var $name = 'Detaildiflog';

        var $belongsTo = array( 'Dsp' );

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'diflog' => array(
						'type' => 'diflog', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false)
		);
    }
?>
