<?php
    class Ressource extends AppModel
    {
        var $name = 'Ressource';
        var $useTable = 'ressources';

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            )
        );

        var $hasMany = array(
            'Ressourcemensuelle' => array(
                'classname'     => 'Ressourcemensuelle',
                'foreignKey'    => 'ressource_id'
            ),
//             'Detailressourcemensuelle' => array(
//                 'classname'     => 'Detailressourcemensuelle',
//                 'foreignKey'    => 'ressource_id'
//             )
        );

        var $validate = array(
//             'topressnotnul' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             ),
//             'topressnul' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             ),
            'mtpersressmenrsa' => array(
                array(
                    // FIXME INFO ailleurs aussi => 123,25 ne passe pas
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
            ),
            'ddress' => array(
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            ),
            'dfress' => array(
                'rule' => 'date',
                'message' => 'Veuillez entrer une date valide'
            )
        );

        //*********************************************************************

        function afterFind( $results, $primary = false ) {
            $return = parent::afterFind( $results, $primary );

            if( !empty( $results ) ) {
                foreach( $results as $key => $result ) {
                    if( isset( $result['Ressource'] ) ) {
                        if( isset( $result['Ressource']['topressnul'] ) ) {
                            $result['Ressource']['topressnotnul'] = !$result['Ressource']['topressnul'];
                        }
                    }
                    $results[$key] = $result;
                }
            }

            return $results;
        }

        //*********************************************************************

        function moyenne( $ressource ) {
            $somme = 0;
            $moyenne = 0;

            $montants = Set::extract( $ressource, '/Ressourcemensuelle/Detailressourcemensuelle/mtnatressmen' );
			if( empty( $montants ) ) {
				$montants = Set::extract( $ressource, '/Detailressourcemensuelle/mtnatressmen' );
			}

            if( count( $montants ) > 0 ) {
                foreach( $montants as $montant ) {
                    $somme += $montant;
                }
                $moyenne = ( $somme / count( $montants ) );
            }

            return $moyenne;
        }

        //*********************************************************************

        function refresh( $personne_id ) {
            $this->unbindModel( array( 'belongsTo' => array( 'Personne' ) ) );

            $ressource  = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'Ressource.personne_id' => $personne_id
                    ),
                    'order' => 'Ressource.dfress DESC',
                    'recursive' => 2
                )
            );

            if( !empty( $ressource ) ) {
                $moyenne = $this->moyenne( $ressource );
                $ressource['Ressource']['topressnotnul'] = ( $moyenne != 0 );
                $ressource['Ressource']['topressnul'] = !$ressource['Ressource']['topressnotnul'];
                $ressource['Ressource']['mtpersressmenrsa'] = number_format( $moyenne, 2, '.', '' );

                $this->create( $ressource );
                return $this->save();
            }

            return true;
        }

        //*********************************************************************

//         function beforeValidate( $options = array() ) {
//             $return = parent::beforeValidate( $options );
// debug( $this->data );
//             $moyenne = $this->moyenne( $this->data );
//             $this->data['Ressource']['topressnotnul'] = ( $moyenne != 0 );
//             $this->data['Ressource']['topressnul'] = ( $moyenne == 0 );
//             $this->data['Ressource']['mtpersressmenrsa'] = number_format( $moyenne, 2 );
//
// //             if( !empty( $this->data['Ressource']['topressnotnul'] ) ) {
// //                 $this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];
// //             }
// //
// //             $this->data['Ressource']['mtpersressmenrsa'] = 0;
// //             if( ( !empty( $this->data['Ressource']['topressnul'] ) ) && ( $this->data['Ressource']['topressnul'] != 0 ) && !empty( $this->data['Detailressourcemensuelle'] ) ) {
// //                 $this->data['Ressource']['mtpersressmenrsa'] = number_format( array_sum( Set::extract( $this->data['Detailressourcemensuelle'], '{n}.mtnatressmen' ) ) / 3, 2 );
// //             }
//
//             return $return;
//         }


        //*********************************************************************

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

            $moyenne = $this->moyenne( $this->data );
            $this->data['Ressource']['topressnotnul'] = ( $moyenne != 0 );
            $this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];
            $this->data['Ressource']['mtpersressmenrsa'] = number_format( $moyenne, 2, '.', '' );

//             if( !empty( $this->data['Ressource']['topressnotnul'] ) ) {
//                 $this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];
//             }
//
//             $this->data['Ressource']['mtpersressmenrsa'] = 0;
//             if( ( $this->data['Ressource']['topressnul'] == false ) && !empty( $this->data['Detailressourcemensuelle'] ) ) {
//                 $this->data['Ressource']['mtpersressmenrsa'] = number_format( array_sum( Set::extract( $this->data['Detailressourcemensuelle'], '{n}.mtnatressmen' ) ) / 3, 2, '.', '' );
//             }

            return $return;
        }

        //*********************************************************************

        function afterSave( $created ) {
            $return = parent::afterSave( $created );

            $thisPersonne = $this->Personne->findById( $this->data['Ressource']['personne_id'], null, null, -1 );
            $this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

            return $return;
        }

        //*********************************************************************

        function dossierId( $ressource_id ) {
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Ressource.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $ressource = $this->findById( $ressource_id, null, null, 1 );

            if( !empty( $ressource ) ) {
                return $ressource['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }
    }
?>
