<?php

    class Propopdo extends AppModel
    {
        var $name = 'Propopdo';

        var $actsAs = array(
            'Enumerable' => array(
				'fields' => array(
					'statutdecision' => array(  'domain' => 'propopdo' ),
                    'choixpdo' => array( 'domain' => 'propopdo' ),
                    'nonadmis' => array( 'domain' => 'propopdo' ),
                    'iscomplet' => array( 'domain' => 'propopdo' ),
                    'validationdecision' => array( 'domain' => 'propopdo' ),
                    'decisionop' => array( 'domain' => 'propopdo' ),
                    'etatdossierpdo'
				)
            ),
            'Formattable',
            'Autovalidate',
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente'
        );

//         var $hasOne = array(
//             'Structurereferente'
//         );

        var $hasMany = array(
            'Piecepdo' => array(
                'classname'     => 'Piecepdo',
                'foreignKey'    => 'propopdo_id'
            ),
            'Traitementpdo' => array(
                'classname'     => 'Traitementpdo',
                'foreignKey'    => 'propopdo_id'
            )
        );

        var $hasAndBelongsToMany = array(
            'Situationpdo' => array( 'with' => 'PropopdoSituationpdo' ),
            'Statutpdo' => array( 'with' => 'PropopdoStatutpdo' ),
            'Statutdecisionpdo' => array( 'with' => 'PropopdoStatutdecisionpdo' )
        );

        var $validate = array(
            'structurereferente_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'typepdo_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'choixpdo' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire',
                'allowEmpty' => true
            ),
            'originepdo_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'decisionpdo_id' => array(
                'rule' => array( 'allEmpty', 'decision' ),
                'message' => 'Si prise de décision, choisir un type de décision'
            ),
            'iscomplet' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'validationdecision' => array(
                'rule' => array( 'allEmpty', 'isvalidation' ),
                'message' => 'Si validation, choisir une valeur'
            ),
        );


        var $_types = array(
//             'etat' => array(
//                 'fields' => array(
//                     '"Dossier"."id"',
//                     '"Situationdossierrsa"."etatdosrsa"',
//                     '"Dossier"."numdemrsa"',
//                     '"Dossier"."matricule"',
//                     '"Dossier"."dtdemrsa"',
//                     '"Situationdossierrsa"."dtrefursa"',
//                     '"Situationdossierrsa"."dtclorsa"',
//                     '"Suiviinstruction"."typeserins"'
//                 ),
//                 'recursive' => -1,
//                 'joins' => array(
//                     array(
//                         'table'      => 'dossiers_rsa',
//                         'alias'      => 'Dossier',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id' )
//                     ),
//                     array(
//                         'table'      => 'suivisinstruction',
//                         'alias'      => 'Suiviinstruction',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Suiviinstruction.dossier_rsa_id = Dossier.id' )
//                     ),
//                     array(
//                         'table'      => 'foyers',
//                         'alias'      => 'Foyer',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Dossier.id = Foyer.dossier_rsa_id' )
//                     )/*,
//                     array(
//                         'table'      => 'adresses_foyers',
//                         'alias'      => 'Adressefoyer',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
//                     ),
//                     array(
//                         'table'      => 'adresses',
//                         'alias'      => 'Adresse',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
//                     )*/
//                 )
//             ),

            'propopdo' => array(
                'fields' => array(
                    '"Propopdo"."id"',
                    '"Propopdo"."personne_id"',
                    '"Propopdo"."typepdo_id"',
                    '"Propopdo"."decisionpdo_id"',
                    '"Propopdo"."typenotifpdo_id"',
                    '"Propopdo"."datedecisionpdo"',
                    '"Propopdo"."motifpdo"',
                    '"Propopdo"."commentairepdo"',
                    '"Propopdo"."etatdossierpdo"',
                    '"Decisionpdo"."libelle"',
                    '"Typenotifpdo"."id"',
                    '"Typenotifpdo"."libelle"',
//                     '"Typepdo"."id"',
                    '"Typepdo"."libelle"',

                    '"Personne"."id"',
                    '"Personne"."pieecpres"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.personne_id = Personne.id' )
                    ),
                    array(
                        'table'      => 'typesnotifspdos',
                        'alias'      => 'Typenotifpdo',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.typenotifpdo_id = Typenotifpdo.id' )
                    ),
//                     array(
//                         'table'      => 'propospdos_typesnotifspdos',
//                         'alias'      => 'PropopdoTypenotifpdo',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'PropopdoTypenotifpdo.propopdo_id = Propopdo.id' )
//                     ),
//                     array(
//                         'table'      => 'piecespdos',
//                         'alias'      => 'Piecepdo',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Piecepdo.propopdo_id = Propopdo.id' )
//                     ),
                    array(
                        'table'      => 'decisionspdos',
                        'alias'      => 'Decisionpdo',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.decisionpdo_id = Decisionpdo.id' )
                    ),
                    array(
                        'table'      => 'typespdos',
                        'alias'      => 'Typepdo',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.typepdo_id = Typepdo.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Foyer.id = Adressefoyer.foyer_id',
                            'Adressefoyer.rgadr = \'01\'',
                            // FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
//                             'Adressefoyer.id IN '.ClassRegistry::init( 'Adressefoyer' )->sqlFoyerActuelUnique()
                        )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
//                     array(
//                         'table'      => 'personnes',
//                         'alias'      => 'Personne',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Foyer.id = Personne.foyer_id' )
//                     ),
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
                    )
                ),
                'order' => 'Propopdo.datedecisionpdo ASC',
            )
        );

        function prepare( $type, $params = array() ) {
            $types = array_keys( $this->_types );
            if( !in_array( $type, $types ) ) {
                trigger_error( 'Invalid parameter "'.$type.'" for '.$this->name.'::prepare()', E_USER_WARNING );
            }
            else {
                $query = $this->_types[$type];

                switch( $type ) {
                    case 'etat':
                        $query = Set::merge( $query, $params );
                        break;
                    case 'propopdo':
                        $query = Set::merge( $query, $params );
                        break;
                }

                return $query;
            }
        }

        function etatPdo( $pdo ) {
            $pdo = XSet::bump( Set::filter( /*Set::flatten*/( $pdo ) ) );

            $typepdo_id = Set::classicExtract( $pdo, 'Propopdo.typepdo_id' );
            $decision = Set::classicExtract( $pdo, 'Propopdo.decision' );
        }

       /**
        *
        */

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );


            $typepdo_id = Set::extract( $this->data, 'Propopdo.typepdo_id' );
            $iscomplet = Set::extract( $this->data, 'Propopdo.iscomplet' );
            $decisionpdo_id = Set::extract( $this->data, 'Propopdo.decisionpdo_id' );
            $isvalidation = Set::extract( $this->data, 'Propopdo.isvalidation' );
            $isdecisionop = Set::extract( $this->data, 'Propopdo.isdecisionop' );

            if( !empty( $typepdo_id ) && empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
                $etat = '1';
                $this->data['Propopdo']['etatdossierpdo'] = $etat;
            }
            else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
                $etat = '2';
                $this->data['Propopdo']['etatdossierpdo'] = $etat;
            }
            else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && empty( $isvalidation ) && empty( $isdecisionop ) ){
                $etat = '3';
                $this->data['Propopdo']['etatdossierpdo'] = $etat;
            }
            else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && ( $isvalidation == 'O' ) && empty( $isdecisionop ) ){
                $etat = '4';
                $this->data['Propopdo']['etatdossierpdo'] = $etat;
            }
            else if ( !empty( $typepdo_id ) && ( $iscomplet == 'COM' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
                $etat = '5';
                $this->data['Propopdo']['etatdossierpdo'] = $etat;
            }
            else if ( !empty( $typepdo_id ) && ( $iscomplet == 'INC' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) && !empty( $isdecisionop ) ){
                $etat = '6';
                $this->data['Propopdo']['etatdossierpdo'] = $etat;
            }

            return $return;
        }
    }
?>