<?php
	class Budgetapre extends AppModel
	{
		var $name = 'Budgetapre';

		var $displayField = 'exercicebudgetai';

        var $validate = array(
            'exercicebudgetai' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'montantattretat' => array(
                array(
                    'rule' => array( 'inclusiveRange', 0, 99999999 ),
                    'message' => 'Veuillez saisir un montant compris entre 0 et 99 999 999 € maximum.'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                ),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
			// FIXME: faire les autres
        );

		var $hasMany = array(
			'Etatliquidatif'
		);
	}
?>