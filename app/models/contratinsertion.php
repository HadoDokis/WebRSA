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

        var $hasAndBelongsToMany = array(
            'User' => array(
                'classname' => 'User',
                'joinTable' => 'users_contratsinsertion',
                'foreignKey' => 'contratinsertion_id',
                'associationForeignKey' => 'user_id'
            )
        );


        var $hasMany = array(
            'Actioninsertion'
        );


        var $validate = array(
            'typocontrat_id' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
	    'structurereferente_id' => array(
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
            ),
            'diplomes' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'form_compl' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'expr_prof' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'aut_expr_prof' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'actions_prev' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'obsta_renc' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'service_soutien' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'pers_charg_suivi' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'objectifs_fixes' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'engag_object' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'sect_acti_emp' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'emp_occupe' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'duree_hebdo_emp' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nat_cont_trav' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'duree_cdd' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'duree_engag' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nature_projet' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'decision_ci' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'datevalidation_ci' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide',
		    'allowEmpty'    => true
                )
            )

        );




    }
?>
