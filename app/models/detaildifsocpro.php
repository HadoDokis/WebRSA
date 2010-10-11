<?php
    class Detaildifsocpro extends AppModel
    {
        var $name = 'Detaildifsocpro';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'difsocpro' => array(
						'type' => 'difsocpro', 'domain' => 'dsp'
					),
				)
			),
			'Revision' => array('auto'=>false)
		);

        var $belongsTo = array( 'Dsp' );
    }
?>
