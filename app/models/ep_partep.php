<?php
    class EpPartep extends AppModel
    {
        var $name = 'EpPartep';

		var $belongsTo = array(
			'Ep',
			'Partep',
			'Rolepartep',
			'Parteprempl'
		);

		var $actsAs = array (
			'Formattable',
			'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'presenceeff' => array(
                        'values' => array( 'absent', 'present', 'remplace', 'excuse' )
                    )
                )
            )
		);

		var $validate = array(
			'ep_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'partep_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'rolepartep_id' => array(
				array( 'rule' => 'notEmpty' )
			),
		);
    }
?>