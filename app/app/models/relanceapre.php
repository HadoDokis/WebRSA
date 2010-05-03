<?php
    class Relanceapre extends AppModel
    {
        var $name = 'Relanceapre';
        var $actsAs = array(
            'Enumerable' => array(
				'fields' => array(
					'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' )
				)
            )
        );

        var $validate = array(
            'etatdossierapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );



        var $belongsTo = array(
            'Apre' => array(
                'classname' => 'Apre',
                'foreignKey' => 'apre_id'
            )
        );

        function afterFind( $results, $primary = false ) {
            $resultset = parent::afterFind( $results, $primary );

            if( !empty( $resultset ) ) {
                foreach( $resultset as $i => $results ) {

                    $isArray = true;
                    if( isset( $results['Relanceapre']['id'] ) ) {
                        //$results = array( 'Relanceapre' => array( $results['Relanceapre'] ) );
                        $results['Relanceapre'] = array( $results['Relanceapre'] );
                        $isArray = false;
                    }

                    foreach( $results['Relanceapre'] as $key => $result ) {
                        $conditions = array();
                        if( isset( $result['apre_id'] ) &&  !empty( $result['apre_id'] ) ) {
                            $conditions = array( 'AprePieceapre.apre_id' => $result['apre_id'] );
                        }

                        $piecesPresentes = $this->Apre->AprePieceapre->find(
                            'all',
                            array(
                                'conditions' => $conditions,
                                'recursive' => -1
                            )
                        );

                        $conditions = array();
                        $piecesApreIds = Set::extract( $piecesPresentes, '/AprePieceapre/pieceapre_id' );
                        if( !empty( $piecesApreIds ) ) {
                            $conditions = array( 'NOT' => array( 'Pieceapre.id' => $piecesApreIds ) );
//                             $conditions['Pieceapre.id NOT'] = $piecesApreIds;
                        }
                        $piecesAbsentes = $this->Apre->Pieceapre->find( 'all', array( 'conditions' => $conditions, 'recursive' => -1 ) );

                        $results['Relanceapre'][$key]['Piecemanquante'] = Set::classicExtract( $piecesAbsentes, '{n}.Pieceapre' );
//                         if( array_sum( $results['Relanceapre'][$key]['Piecemanquante'] ) == 0 ) {
// //                             debug($results);
//                         }

                    }

                    if( !$isArray ) {
                        $results['Relanceapre'] = $results['Relanceapre'][0];
                    }



                    $resultset[$i] = $results;
                }
            }

            return $resultset;
        }
    }
?>