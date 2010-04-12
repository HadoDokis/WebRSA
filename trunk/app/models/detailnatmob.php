<?php
    class Detailnatmob extends AppModel
    {
        var $name = 'Detailnatmob';

        var $belongsTo = array( 'Dsp' );

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'natmob' => array(
						'type' => 'natmob', 'domain' => 'dsp'
					),
				)
			)
		);
    }
?>