<?php
    class Detailmoytrans extends AppModel
    {
        var $name = 'Detailmoytrans';

        var $belongsTo = array( 'Dsp' );

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'moytrans' => array(
						'type' => 'moytrans', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false)
		);
    }
?>
