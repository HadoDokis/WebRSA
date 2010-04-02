<?php
    class Suiviaideapre extends AppModel
    {
        var $name = 'Suiviaideapre';

        var $useTable = 'suivisaidesapres';

        var $displayField = 'full_name';

        var $order = array( 'nom ASC', 'prenom ASC' );

        var $actsAs = array(
            'MultipleDisplayFields' => array(
                'fields' => array( 'qual', 'nom', 'prenom' ),
                'pattern' => '%s %s %s'
            ),
            'SoftDeletable' => array( 'find' => false )/*,
			'Autovalidate'*/
        );

        var $validate = array(
            'numtel' => array(
                array(
                    'rule' => 'phoneFr',
					'allowEmpty' => true,
					'message' => 'Veuillez entrer un n° de téléphone français'
                ),
            ),
            'qual' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nom' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'prenom' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );
    }
?>