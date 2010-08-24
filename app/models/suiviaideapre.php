<?php
    class Suiviaideapre extends AppModel
    {
        var $name = 'Suiviaideapre';

        var $useTable = 'suivisaidesapres';

        var $displayField = 'nom_complet';

        var $order = array( 'nom ASC', 'prenom ASC' );

        var $actsAs = array(
            'SoftDeletable' => array( 'find' => false )/*,
			'Autovalidate'*/
        );

        public $virtualFields = array(
            'nom_complet' => array(
                'type'      => 'string',
                'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
            ),
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