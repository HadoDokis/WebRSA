<?php
	/**
	* Types enumerables
	* FIXME: autrement qu'avec le configure ?
	*/

	Configure::write(
		'Enumerable',
		array(
			'presence' => array(
				'domain' => 'default',
				'type' => 'presence',
				'values' => array(
					'absent',
					'present',
					'remplace',
					'excuse'
				)
			),
			'no' => array(
				'domain' => 'default',
				'type' => 'no',
				'values' => array(
					'O',
					'N'
				)
			),
			'booleannumber' => array(
				'domain' => 'default',
				'type' => 'booleannumber',
				'values' => array(
					'1',
					'0'
				)
			)
		)
	);
?>