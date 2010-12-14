<?php
	class MembresepsSeancesepsController extends AppController
	{
		var $name = 'MembresepsSeanceseps';
		
		public $helpers = array( 'Default2' );
		
		var $uses = array( 'MembreepSeanceep');

		public function beforeFilter() {
		}		

		
		protected function _setOptions() {
			$options = Set::merge(
				$this->MembreepSeanceep->enums()
			);
			$this->set( compact( 'options' ) );
		}		
		
		
		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		/**
		 * Ajout, modification des membres d'une séance d'EP.
		 * @param integer $ep_id
		 */
		protected function _add_edit( $seanceep_id = null ) {
			$membres = $this->MembreepSeanceep->find('all', array(
				'conditions' => array(
					//'Membreep.ep_id' => 1
				)
			));	
			
			debug(count($membres));
			debug(count(Set::extract( $membres, "/Seanceep[id={$seanceep_id}]" )));				
			debug($membres);
			debug(Set::extract( $membres, "/Seanceep[id={$seanceep_id}]" ));
			$this->_setOptions();
//			debug($membres);

//			
//			
//			if( $this->action == 'add') {
//				$seanceep_id = $id;
//			}
//			else {
//				$seanceep_id = $this->MembreepSeanceep->field('seanceep_id', array('id'=>$id));	
//			}
//			
//			if( !empty( $this->data ) ) {
//				$this->MembreepSeanceep->create( $this->data );
//				$success = $this->MembreepSeanceep->save();
//				$this->_setFlashResult( 'Save', $success );
//				if( $success ) {
//					$this->redirect( array( 'action' => 'index', $seanceep_id  ) );
//				}
//			}
//			else if( $this->action == 'edit' ) {
//				$this->data = $this->MembreepSeanceep->find(
//					'first',
//					array(
//						'contain' => true,
//						'conditions' => array( 'MembreepSeanceep.id' => $id )
//					)
//				);
//				$this->assert( !empty( $this->data ), 'error404' );
//			}
//
//			$this->_setOptions($seanceep_id);
			$this->render( null, null, 'add_edit' );
		}

		/**
		 * 
		 * @param unknown_type $id
		 */
		public function delete( $id ) {
			$seanceep_id = $this->MembreepSeanceep->field('seanceep_id', array('id'=>$id));
			$success = $this->MembreepSeanceep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index', $seanceep_id) );
		}		
		
		

		
		
	}
?>