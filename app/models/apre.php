<?php
    class Apre extends AppModel
    {
        var $name = 'Apre';
        var $actsAs = array( 'Enumerable' );
        var $displayField = 'numeroapre';

        var $validate = array(
            'typedemandeapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'activitebeneficiaire' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
//             'referentapre_id' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             ),
            'secteurprofessionnel' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );

        var $enumFields = array(
            'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
            'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
            'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
            'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
            'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
            'ajoutcomiteexamen' => array( 'type' => 'no', 'domain' => 'apre' ),
            'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' ),
            'eligibiliteapre' => array( 'type' => 'eligibiliteapre', 'domain' => 'apre' ),
            'presence' => array( 'type' => 'presence', 'domain' => 'apre' )
        );


        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            ),
            'Referentapre'
        );

        var $hasOne = array(
            'Formqualif',
            'Formpermfimo',
            'Actprof',
            'Permisb',
            'Amenaglogt',
            'Acccreaentr',
            'Acqmatprof',
            'Locvehicinsert'
        );

        var $aidesApre = array( 'Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' );

        var $hasMany = array(
//             'Formqualif' => array(
//                 'classname' => 'Formqualif',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Formpermfimo' => array(
//                 'classname' => 'Formpermfimo',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Actprof' => array(
//                 'classname' => 'Actprof',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Permisb' => array(
//                 'classname' => 'Permisb',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Amenaglogt' => array(
//                 'classname' => 'Amenaglogt',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Acccreaentr' => array(
//                 'classname' => 'Acccreaentr',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Acqmatprof' => array(
//                 'classname' => 'Acqmatprof',
//                 'foreignKey' => 'apre_id',
//             ),
//             'Locvehicinsert' => array(
//                 'classname' => 'Locvehicinsert',
//                 'foreignKey' => 'apre_id',
//             ),
            'Montantconsomme' => array(
                'classname' => 'Montantconsomme',
                'foreignKey' => 'apre_id',
            ),
            'Relanceapre' => array(
                'classname' => 'Relanceapre',
                'foreignKey' => 'apre_id',
            )
        );

        var $hasAndBelongsToMany = array(
            'Pieceapre' => array(
                 'className'              => 'Pieceapre',
                 'joinTable'              => 'apres_piecesapre',
                 'foreignKey'             => 'apre_id',
                 'associationForeignKey'  => 'pieceapre_id'
            ),
            'Comiteapre' => array(
                'className'              => 'Comiteapre',
                'joinTable'              => 'apres_comitesapres',
                'foreignKey'             => 'apre_id',
                'associationForeignKey'  => 'comiteapre_id',
                'with'                   => 'ApreComiteapre'
            )
        );


        function dossierId( $apre_id ){
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Apre.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $apre = $this->findById( $apre_id, null, null, 0 );

            if( !empty( $apre ) ) {
                return $apre['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        /**
        *
        */

        function _nbrNormalPieces() {
            $nbNormalPieces = array();
            $nbNormalPieces['Apre'] = $this->Pieceapre->find( 'count' );
            foreach( $this->aidesApre as $model ) {
                $nbNormalPieces[$model] = $this->{$model}->{'Piece'.strtolower( $model )}->find( 'count' );
            }
            return $nbNormalPieces;
        }

		/**
		*
		*/

		function _details( $apre_id ) {
			$nbNormalPieces = $this->_nbrNormalPieces();
			$details['Piecepresente'] = array();
			$details['Piecemanquante'] = array();

			// Nombre de pièces trouvées par-rapport au nombre de pièces prévues - Apre
			$details['Piecepresente']['Apre'] = $this->AprePieceapre->find( 'count', array( 'conditions' => array( 'apre_id' => $apre_id ) ) );
			$details['Piecemanquante']['Apre'] = abs( $details['Piecepresente']['Apre'] - $nbNormalPieces['Apre'] );

            // Quelles sont les pièces manquantes
            $piecesPresentes = Set::extract( $this->AprePieceapre->find( 'all', array( 'conditions' => array( 'apre_id' => $apre_id ) ) ), '/AprePieceapre/pieceapre_id' );
            $conditions = array();
            if( !empty( $piecesPresentes ) ) {
                $conditions['Pieceapre.id NOT'] = $piecesPresentes;
            }
            $piecesAbsentes = $this->Pieceapre->find( 'list', array( 'conditions' => $conditions, 'recursive' => -1 ) );
            $details['Piece']['Manquante']['Apre'] = $piecesAbsentes;

// debug( $details );

			/// Essaie de récupération des pièces des aides liées
			foreach( $this->aidesApre as $model ) {
				// Nombre de pièces trouvées par-rapport au nombre de pièces prévues pour chaque type d'aide
				$aides = $this->{$model}->find(
					'all',
					array(
						'conditions' => array(
							"$model.apre_id" => $apre_id
						)
					)
				);

				// Combien d'aides liées à l'APRE sont présentes pour chaque type d'aide
				$details['Natureaide'][$model] = count( $aides );

				if( !empty( $aides ) ) {
					$details['Piecepresente'][$model] = count( Set::extract( $aides, '/Piece'.strtolower( $model ) ) );
					$details['Piecemanquante'][$model] = abs( $nbNormalPieces[$model] - $details['Piecepresente'][$model] );
				}
			}

            $details['etatdossierapre'] = ( ( array_sum( $details['Piecemanquante'] ) == 0 ) ? 'COM' : 'INC' );
			return $details;
		}

//         function beforeFind( $queryData ) {
//             $return = parent::beforeFind( $queryData );
// 
//             if( in_array( $this->findQueryType, array( 'all', 'first', 'last' ) ) ) {
//                 if( empty( $queryData['fields'] ) ) {
//                     $queryData['fields'][] = '"Apre"."*"';
//                 }
// 
//                 $countAidesComplementaires = array();
//                 foreach( $this->aidesApre as $model ) {
//                     $table = Inflector::tableize( $model );
//                     $countAidesComplementaires[] = '( SELECT COUNT( '.$table.'.id ) FROM '.$table.' WHERE '.$table.'.apre_id = "Apre"."id" )';
//                 }
// 
//                 $queryData['fields'][] = '( SELECT COALESCE( '.implode( ', ', $countAidesComplementaires ).' ) ) AS "Apre__Coalesce"';
// 
//                 debug( $queryData );
//             }
// 
//             return $queryData;
//         }

        /**
        *
        */

        function afterFind( $results, $primary = false ) {
            parent::afterFind( $results, $primary );

            if( !empty( $results ) && Set::check( $results, '0.Apre' ) ) {
                foreach( $results as $key => $result ) {
					if( isset( $result['Apre']['id'] ) ) {
						$results[$key]['Apre'] = Set::merge(
							$results[$key]['Apre'],
							$this->_details( $result['Apre']['id'] )
						);
					}
					else if( isset( $result['Apre'][0]['id'] ) ) {
						foreach( $result['Apre'] as $key2 => $result2 ) {
							$results[$key]['Apre'][$key2] = Set::merge(
								$results[$key]['Apre'][$key2],
								$this->_details( $result2['id'] )
							);
						}
					}
                }
            }

            return $results;
        }

        /**
        *
        */

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

            // FIXME: à mettre dans le behavior
            foreach( array_keys( $this->enumFields ) as $enum ) {
                if( empty( $this->data[$this->name][$enum] ) ) {
                    $this->data[$this->name][$enum] = null;
                }
            }

            return $return;
        }

        /**
        *
        */

        function afterSave( $created ) {
            $return = parent::afterSave( $created );

			$details = $this->_details( $this->id );
			$this->query( "UPDATE apres SET etatdossierapre = '".$details['etatdossierapre']."' WHERE id = {$this->id};" ) && $return;

            $return = $this->query( "UPDATE apres SET eligibiliteapre = 'O' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id']." AND apres.etatdossierapre = 'COM' AND ( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = ".$this->data[$this->name]['personne_id']." ) > 0;" ) && $return;

            $return = $this->query( "UPDATE apres SET eligibiliteapre = 'N' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id']." AND NOT ( apres.etatdossierapre = 'COM' AND ( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = ".$this->data[$this->name]['personne_id']." ) > 0 );" ) && $return;

            $return = $this->query( "UPDATE apres SET statutapre = 'C' WHERE apres.personne_id = ".$this->data[$this->name]['personne_id'].";" ) && $return;

			// FIXME: return ?
            return $return;
        }
    }
?>