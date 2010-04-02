<?php
	class EpsPartsepsController extends AppController
	{
		var $name = 'EpsPartseps';
		var $uses = array( 'EpPartep', 'Ep', 'Partep', 'Rolepartep' );
        var $components = array( 'Jetonsfonctions' );


		/**
		*
		*/

		function beforeFilter() {
			$return = parent::beforeFilter();

			$options = array();
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}


			foreach( array( 'Ep', 'Partep', 'Rolepartep' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}
			$this->set( compact( 'options' ) );

            $this->set( 'participants', $this->Partep->find( 'all' ) );
            $this->set( 'roles', $this->Rolepartep->find( 'list' ) );

			return $return;
		}

		/**
		*
		*/

		public function index() {
			$this->Default->index();
		}

        /**
        *   Ordre du jour
        */

        public function ordre( $ep_id = null ) {

            $ep = $this->Ep->findById( $ep_id, null, null, 2 );
            $this->assert( !empty( $ep ), 'invalidParameter' );

// debug( $ep);


            $participants = $this->Ep->Partep->find( 'list' );
            $roles = $this->Ep->Rolepartep->find( 'list' );

            $this->set( compact( 'ep', 'participants', 'roles' ) );
//             $this->Default->index();
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

		/**
		*
		*/
//             $args = func_get_args();
// 


        function _add_edit( $id = null ){

            if( Set::check( $this->params, 'form.cancel' ) ) {
                $this->Session->setFlash( __( 'Save->cancel', true ), 'flash/information' );
                $this->redirect( array( 'action' => 'index' ) );
            }

            $this->Ep->begin();

            if( $this->Jetonsfonctions->get( $this->name, $this->action ) ) {

                if( $this->action == 'add' ) {
                    $ep_id = $id;
                    $nbrEps = $this->Ep->find( 'count', array( 'conditions' => array( 'Ep.id' => $ep_id ), 'recursive' => -1 ) );
                    $this->assert( ( $nbrEps == 1 ), 'invalidParameter' );
                }
                else if( $this->action == 'edit' ) {
                    $ep_id = $id;
                    $eppart = $this->EpPartep->find(
                        'all',
                        array(
                            'conditions' => array(
                                'EpPartep.ep_id' => $ep_id
                            )
                        )
                    );
                    $this->assert( !empty( $eppart ), 'invalidParameter' );
                }

                if( !empty( $this->data ) ) {
                    $this->Jetonsfonctions->release( $this->name, $this->action );

                    $data = Set::extract( $this->data, '/EpPartep' );
                    foreach( $data as $k => $v ) {
                        $value = Set::classicExtract( $v, 'EpPartep.rolepartep_id' );
                        if( empty( $value ) ) {
                            unset( $data[$k] );
                            $this->EpPartep->del( Set::classicExtract( $v, 'EpPartep.id' ) );
                        }
                    }

                    if( $this->EpPartep->saveAll( $data, array( 'atomic' => false ) ) ) {
                        $this->Ep->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'eps', 'action' => 'ordre', $ep_id ) );
                    }
                    else{
                        $this->Ep->rollback();
                    }
                }
                else {
                    if( $this->action == 'edit' ) {
                        $this->data = array( 'EpPartep' => Set::classicExtract( $eppart, '{n}.EpPartep' ) );
                        /*$this->data = array(
                            'Ep' => array(
                                'id' => $ep_id,
                            ),
                            'Partep' => array(
                                'Partep' => Set::extract( $eppart, '/EpPartep/partep_id' )
                            ),
                            'Rolepartep' => array(
                                'Rolepartep' => Set::extract( $eppart, '/EpPartep/rolepartep_id' )
                            )
                        );*/
                    }
                }
//                $this->set( compact( 'ep_id' ) );
//                 $this->Ep->commit();
                $this->render( $this->action, null, 'add_edit' );
            }
        }

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->Default->view( $id );
		}
	}
?>