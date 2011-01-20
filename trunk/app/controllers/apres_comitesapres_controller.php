<?php
    class ApresComitesapresController extends AppController
    {

        var $name = 'ApresComitesapres';
        var $uses = array( 'ApreComiteapre', 'Apre', 'Comiteapre' );
        var $components = array( 'Jetonsfonctions' );
        var $helpers = array( 'Xform' );
        
		var $commeDroit = array(
			'add' => 'Actionscandidats:edit'
		);

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $options = $this->ApreComiteapre->allEnumLists();
//             debug( $options );
            $this->set( 'options', $options );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _add_edit( $id = null ){


//debug($this);
            $this->Comiteapre->begin();

            if( $this->Jetonsfonctions->get( $this->name, $this->action ) ) {
                $isRecours = Set::classicExtract( $this->params, 'named.recours' );
                $isRapport = Set::classicExtract( $this->params, 'named.rapport' );



                if( $isRecours ) {
                    $conditions = array( 'Apre.id IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres WHERE apres_comitesapres.decisioncomite = \'REF\' ) AND Apre.id NOT IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres WHERE apres_comitesapres.comite_pcd_id IS NOT NULL ) AND Apre.statutapre = \'C\'' );
                }
                else {
                    $conditions = array(
                        '( Apre.id NOT IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres WHERE apres_comitesapres.decisioncomite IS NOT NULL )
                        OR Apre.id IN ( SELECT apres_comitesapres.apre_id FROM apres_comitesapres WHERE apres_comitesapres.decisioncomite = \'AJ\' ) )
                        AND Apre.statutapre = \'C\'
			AND Apre.etatdossierapre = \'COM\''
                    );
                }


                $this->Apre->deepAfterFind = false;
                $apres = $this->Apre->find(
                    'all',
                    array(
                        'fields' => array(
                            'Apre.id',
                            'Apre.numeroapre',
                            'Apre.datedemandeapre',
                            'Personne.qual',
                            'Personne.nom',
                            'Personne.prenom',
                        ),
                       'conditions' => $conditions,
                       'contain' => array(
                            'Personne'
                        )/*,
                        'limit' => 10*/
                    )
                );

                /*$hasAide = Set::extract( $apres, '/Apre/Natureaide' );
                foreach( $hasAide as $key => $hasAideComplementaire ){
                    $sumAides = ( array_sum( $hasAideComplementaire['Natureaide'] ) );
                    if( $sumAides == 0 ){
                        unset( $apres[$key] );
                    }
                }*/

                $this->set( 'apres', $apres );

                if( $this->action == 'add' ) {
                    $comiteapre_id = $id;

                    $nbrComites = $this->Comiteapre->find( 'count', array( 'conditions' => array( 'Comiteapre.id' => $comiteapre_id ), 'recursive' => -1 ) );
                    $this->assert( ( $nbrComites == 1 ), 'invalidParameter' );
                }
                else if( $this->action == 'edit' ) {
                    $comiteapre_id = $id;
                    $aprecomite = $this->ApreComiteapre->find(
                        'all',
                        array(
                            'conditions' => array(
                                'ApreComiteapre.comiteapre_id' => $comiteapre_id
                            )
                        )
                    );
                    $this->assert( !empty( $aprecomite ), 'invalidParameter' );

                }

                // Formulaire renvoyé
                if( !empty( $this->data ) ) {
                    if( isset( $this->data['Apre'] ) && isset( $this->data['Apre']['Apre'] ) ) {
                        $success = true;
                        $ids = array();
                        foreach( $this->data['Apre']['Apre'] as $i => $apreId ) {
                            if( !empty( $apreId ) ) {
                                $aprecomiteapre = array(
                                    'ApreComiteapre' => array(
                                        'comiteapre_id' => $comiteapre_id,
                                        'apre_id' => $apreId
                                    )
                                );
                                if( $isRecours ) {
                                    $ancienneDecision = $this->ApreComiteapre->find(
                                        'first',
                                        array(
                                            'fields' => array( 'ApreComiteapre.comiteapre_id' ),
                                            'conditions' => array(
                                                'ApreComiteapre.comiteapre_id <>' => $comiteapre_id,
                                                'ApreComiteapre.apre_id' => $apreId,
                                            ),
                                            'joins' => array(
                                                array(
                                                    'table'      => 'comitesapres',
                                                    'alias'      => 'Comiteapre',
                                                    'type'       => 'INNER',
                                                    'foreignKey' => false,
                                                    'conditions' => array( 'ApreComiteapre.comiteapre_id = Comiteapre.id' )
                                                ),
                                            ),
                                            'order' => 'Comiteapre.datecomite DESC',
                                            'recursive' => -1,
                                        )
                                    );
                                    $this->assert( !empty( $ancienneDecision ), 'error500' ); // FIXME -> préciser l'erreur
                                    $aprecomiteapre['ApreComiteapre']['comite_pcd_id'] = Set::classicExtract( $ancienneDecision, 'ApreComiteapre.comiteapre_id' );
                                }
                                $this->ApreComiteapre->validate = array();
                                $this->ApreComiteapre->create( $aprecomiteapre );
                                if( $tmpSuccess = $this->ApreComiteapre->save() ) {
                                    $ids[] = $this->ApreComiteapre->id;
                                }
                                $success =  $tmpSuccess && $success;
                            }
                        }

                        if( $success ) {
                            $conditions = array(
                                'ApreComiteapre.comiteapre_id' => $comiteapre_id,
                            );
                            if( !empty( $ids ) ) {
                                $conditions[] = 'ApreComiteapre.id NOT IN ('.implode( ',', $ids ).' )';
                            }
                            $pvApreComiteapre = Set::extract( $apres, '/Apre/id' );
                            if( !empty( $pvApreComiteapre ) ) {
                                $conditions[] = 'ApreComiteapre.apre_id IN ('.implode( ',', $pvApreComiteapre ).' )';
                            }
                            $success = $this->ApreComiteapre->deleteAll( $conditions ) && $success;
                        }

                        if( $success ) {
                            $this->Jetonsfonctions->release( $this->name, $this->action );
                            $this->Comiteapre->commit();
                            if( !$isRapport ){
                                $this->redirect( array( 'controller' => 'comitesapres', 'action' => 'view', $comiteapre_id ) );
                            }
                            else if( $isRapport ){
                                $this->redirect( array( 'controller' => 'comitesapres', 'action' => 'rapport', $comiteapre_id ) );
                            }
                        }
                        else {
                            $this->Comiteapre->rollback();
                        }
                    }
                }
                else {
                    if( $this->action == 'edit' ) {
                        $this->data = array(
                            'Comiteapre' => array(
                                'id' => $comiteapre_id,
                            ),
                            'Apre' => array(
                                'Apre' => Set::extract( $aprecomite, '/ApreComiteapre/apre_id' )
                            )
                        );

                    }
                    else {
                        $this->data['Comiteapre']['id'] = $comiteapre_id;
                    }
                }
                $this->Comiteapre->commit();
                $this->render( $this->action, null, 'add_edit' );
            }
//debug($this);
        }
    }
?>
