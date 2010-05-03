<?php
	class Parametrefinancier extends AppModel
	{
		var $name = 'Parametrefinancier';

        var $validate = array(
            'entitefi' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'tiers' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'codecdr' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'libellecdr' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'natureanalytique' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'lib_natureanalytique' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'programme' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'lib_programme' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'apreforfait' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'natureimput' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
        );
	}
?>