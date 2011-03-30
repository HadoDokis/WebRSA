<?php
	class CommissionsepsMembresepsController extends AppController
	{
		var $name = 'CommissionsepsMembreseps';
		
		public $helpers = array( 'Default2' );
		
		var $uses = array( 'CommissionepMembreep');

		public function beforeFilter() {
		}		

		
		protected function _setOptions() {
			$options = Set::merge(
				$this->CommissionepMembreep->enums()
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
		protected function _add_edit( $commissionep_id = null ) {
			$membres = $this->CommissionepMembreep->find('all', array(
				'conditions' => array(
					//'Membreep.ep_id' => 1
				)
			));	
			
			debug(count($membres));
			debug(count(Set::extract( $membres, "/Commissionep[id={$commissionep_id}]" )));				
			debug($membres);
			debug(Set::extract( $membres, "/Commissionep[id={$commissionep_id}]" ));
			$this->_setOptions();
//			debug($membres);

//			
//			
//			if( $this->action == 'add') {
//				$commissionep_id = $id;
//			}
//			else {
//				$commissionep_id = $this->CommissionepMembreep->field('commissionep_id', array('id'=>$id));	
//			}
//			
//			if( !empty( $this->data ) ) {
//				$this->CommissionepMembreep->create( $this->data );
//				$success = $this->CommissionepMembreep->save();
//				$this->_setFlashResult( 'Save', $success );
//				if( $success ) {
//					$this->redirect( array( 'action' => 'index', $commissionep_id  ) );
//				}
//			}
//			else if( $this->action == 'edit' ) {
//				$this->data = $this->CommissionepMembreep->find(
//					'first',
//					array(
//						'contain' => true,
//						'conditions' => array( 'CommissionepMembreep.id' => $id )
//					)
//				);
//				$this->assert( !empty( $this->data ), 'error404' );
//			}
//
//			$this->_setOptions($commissionep_id);
			$this->render( null, null, 'add_edit' );
		}

		/**
		 * 
		 * @param unknown_type $id
		 */
		public function delete( $id ) {
			$commissionep_id = $this->CommissionepMembreep->field('commissionep_id', array('id'=>$id));
			$success = $this->CommissionepMembreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index', $commissionep_id) );
		}		
		
		

		
		
	}
?>