<?php
    class Detaildifdisp extends AppModel
    {
        var $name = 'Detaildifdisp';

        var $belongsTo = array( 'Dsp' );

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'difdisp' => array(
						'type' => 'difdisp', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false)
		);
    }
?>
