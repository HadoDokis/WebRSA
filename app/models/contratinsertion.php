<?php
    class Contratinsertion extends AppModel
    {
        var $name = 'Contratinsertion';

        var $useTable = 'contratsinsertion';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'type_demande' => array( 'type' => 'type_demande', 'domain' => 'contratinsertion' ),
                    'num_contrat' => array( 'type' => 'num_contrat', 'domain' => 'contratinsertion' ),
                    'typeinsertion' => array( 'type' => 'insertion', 'domain' => 'contratinsertion' )
                )
            ),
            'Formattable' => array(
                'suffix' => array( 'structurereferente_id', 'referent_id' ),
            )
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
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
            'Actioninsertion' => array(
                'classname' => 'Actioninsertion',
                'foreignKey' => 'contratinsertion_id',
				'dependent' => true
            )/*,
            'Typocontrat' => array(
                'classname' => 'Typocontrat',
                'foreignKey' => 'contratinsertion_id',
				'dependent' => true
            )*/
        );


        var $validate = array(
            'actions_prev' => array(
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
            'aut_expr_prof' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'forme_ci' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'emp_trouv' => array(
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
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                            'message' => 'Veuillez entrer une valeur numérique.'
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
            ),
            'lieu_saisi_ci' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'niveausalaire' => array(
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.'
                ),
                array(
                    'rule' => array( 'comparison', '>=', 0 ),
                    'message' => 'Veuillez entrer un nombre positif.'
                )
            ),
            /**
            * Régle ajoutée suite à la demande du CG66
            */
//             'sitfam_ci' => array(
//                 'maxLength' => array(
//                     'rule' => array( 'maxLength', 500 ),
//                     'message' => '500 carac. max'
//                 )
//             ),
//             'sitpro_ci' => array(
//                 'maxLength' => array(
//                     'rule' => array( 'maxLength', 500 ),
//                     'message' => '500 carac. max'
//                 )
//             ),
//             'observ_benef' => array(
//                 'maxLength' => array(
//                     'rule' => array( 'maxLength', 500 ),
//                     'message' => '500 carac. max'
//                 )
//             ),
            'nature_projet' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )/*,
                'maxLength' => array(
                    'rule' => array( 'maxLength', 500 ),
                    'message' => '500 carac. max'
                )*/
            )
        );

        // ********************************************************************

        var $queries = array(
            'criteresci' => array(
                'fields' => array(
                    '"Contratinsertion"."id"',
                    '"Contratinsertion"."personne_id"',
                    '"Contratinsertion"."num_contrat"',
                    '"Contratinsertion"."structurereferente_id"',
                    '"Contratinsertion"."rg_ci"',
                    '"Contratinsertion"."decision_ci"',
                    '"Contratinsertion"."dd_ci"',
                    '"Contratinsertion"."df_ci"',
                    '"Contratinsertion"."datevalidation_ci"',
                    '"Contratinsertion"."date_saisi_ci"',
                    '"Contratinsertion"."pers_charg_suivi"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Contratinsertion.personne_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
                )
            )
        );

        /** ********************************************************************
        *   BeforeSave
        *** *******************************************************************/
        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

            if( array_key_exists( $this->name, $this->data ) && array_key_exists( 'structurereferente_id', $this->data[$this->name] ) ) {
                $this->data[$this->name]['structurereferente_id'] = suffix( $this->data[$this->name]['structurereferente_id'] ) ;
            }

            ///Ajout pour obtenir referent lié à structure
            $hasMany = ( array_depth( $this->data ) > 2 );

            if( !$hasMany ) { // INFO: 1 seul enregistrement
                if( array_key_exists( 'referent_id', $this->data[$this->name] ) ) {
                    $this->data[$this->name]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data[$this->name]['referent_id'] );
                }
            }
            else { // INFO: plusieurs enregistrements
                foreach( $this->data[$this->name] as $key => $value ) {
                    if( is_array( $value ) && array_key_exists( 'referent_id', $value ) ) {
                        $this->data[$this->name][$key]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['referent_id'] );
                    }
                }
            }
            ///Fin ajout pour récupération referent lié a structure

            /// FIXME: faire un behavior
            foreach( array( 'actions_prev' ) as $key ) {
                if( isset( $this->data[$this->name][$key] ) ) {
                    $this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( 'O' => '1', 'N' => '0' ) );
                }
            }

            foreach( array( 'emp_trouv' ) as $key ) {
                if( isset( $this->data[$this->name][$key] ) ) {
                    $this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( 'O' => true, 'N' => false ) );
                }
            }


            return $return;
        }

        /** ********************************************************************
        *   AfterSave
        *** *******************************************************************/
        function afterSave( $created ) {
            $return = parent::afterSave( $created );

            $return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id']." AND apres.etatdossierapre = 'COM';" ) && $return;

            $return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id']." AND apres.etatdossierapre = 'INC';" ) && $return;

            return $return;
        }
    }
?>
