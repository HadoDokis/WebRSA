<?php 
    class Relanceapre extends AppModel
    {
        var $name = 'Relanceapre';
        var $actsAs = array( 'Enumerable' );

        var $validate = array(
            'etatdossierapre' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
        );

        var $enumFields = array(
            'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' )
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
                        $piecesPresentes = $this->Apre->AprePieceapre->find(
                            'all',
                            array(
                                'conditions' => array( 'AprePieceapre.apre_id' => $result['apre_id'] ),
                                'recursive' => -1
                            )
                        );
                        $piecesAbsentes = $this->Apre->Pieceapre->find(
                            'all',
                            array(
                                'conditions' => array( 'Pieceapre.id NOT' => Set::extract( $piecesPresentes, '/AprePieceapre/pieceapre_id' ) ),
                                'recursive' => -1
                            )
                        );

                        $results['Relanceapre'][$key] ['Piecemanquante'] = Set::classicExtract( $piecesAbsentes, '{n}.Pieceapre' );
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