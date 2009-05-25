<?php
    class Contratinsertion extends AppModel
    {
        var $name = 'Contratinsertion';
        
        var $useTable = 'contratsinsertion';
        
        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            ),
	    'Typocontrat' => array(
		'classname' => 'Typocontrat',
		'foreignKey' => 'typocontrat_id'
	    )
        );

        var $hasOne = array(
            'Actioninsertion'
        );


        var $validate = array(
            'type_ci' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dd_ci' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),
            'df_ci' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            )/*,
            'decision_ci' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )*/
        );




    }
?>
