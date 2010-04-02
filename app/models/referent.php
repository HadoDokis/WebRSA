<?php
    class Referent extends AppModel
    {

        var $name = 'Referent';
        var $useTable = 'referents';

        var $displayField = 'full_name';

        var $actsAs = array(
            'MultipleDisplayFields' => array(
                'fields' => array( 'qual', 'nom', 'prenom' ),
                'pattern' => '%s %s %s'
            )
        );

        var $hasAndBelongsToMany = array(
            'Actioncandidat' => array( 'with' => 'ActioncandidatPersonne' ),
//             'Personne' => array( 'with' => 'ActioncandidatPersonne' ), // FIXME
            'Personne' => array( 'with' => 'PersonneReferent' )
        );

        var $belongsTo = array(
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )
        );

        var $hasMany = array(
            'Demandereorient' => array(
				'foreignKey' => 'reforigine_id'
			)
        );

        public $virtualFields = array(
            'nom_complet' => array(
                'type'      => 'string',
                'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
            ),
        );

        function listOptions() {
            $tmp = $this->find(
                'all',
                array (
                    'fields' => array(
                        'Referent.id',
                        'Referent.structurereferente_id',
                        'Referent.qual',
                        'Referent.nom',
                        'Referent.prenom'
                    ),
                    'recursive' => -1,
                    'order' => 'Referent.nom ASC',
                )
            );

            $return = array();
            foreach( $tmp as $key => $value ) {
                $return[$value['Referent']['structurereferente_id'].'_'.$value['Referent']['id']] = $value['Referent']['qual'].' '.$value['Referent']['nom'].' '.$value['Referent']['prenom'];
            }
            return $return;
        }

        var $validate = array(
            'numero_poste' => array(
                array(
                    'rule' => 'numeric',
                    'message' => 'Le numéro de téléphone est composé de chiffres'
                ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le N° de poste doit être composé de 10 chiffres'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
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
            'fonction' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'email' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'email',
                    'message' => 'Veuillez entrer une adresse email valide'
                )
            ),
            'structurereferente_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );



        /** ********************************************************************
        *   Retourne la liste des Referents
        ** ********************************************************************/

        function referentsListe( $structurereferente_id = null ) {
            // Population du select référents liés aux structures
            $conditions = array();
            if( !empty( $structurereferente_id ) ) {
                $conditions['Referent.structurereferente_id'] = $structurereferente_id;
            }

            $referents = $this->find(
                'all',
                array(
                    'recursive' => -1,
                    'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom' ),
                    'conditions' => $conditions
                )
            );

            if( !empty( $referents ) ) {
                $ids = Set::extract( $referents, '/Referent/id' );
                $values = Set::format( $referents, '{0} {1} {2}', array( '{n}.Referent.qual', '{n}.Referent.nom', '{n}.Referent.prenom' ) );
                $referents = array_combine( $ids, $values );
            }
            return $referents;
        }
    }
?>