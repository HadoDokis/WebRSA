<?php
	class MembresepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public function beforeFilter() {
		}

		
		protected function _setOptions() {
			$options = $this->Membreep->enums();
			if( $this->action != 'index' ) {
				$options['Membreep']['fonctionmembreep_id'] = $this->Membreep->Fonctionmembreep->find( 'list' );
				$options['Membreep']['ep_id'] = $this->Membreep->Ep->find( 'list' );
			}
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Membreep.id',
					'Fonctionmembreep.name',
					'Ep.name',
					'Membreep.qual',
					'Membreep.nom',
					'Membreep.prenom',
					'Membreep.suppleant_id',
					'Suppleant.qual',
					'Suppleant.nom',
					'Suppleant.prenom'
				),
				'contain' => array(
					'Fonctionmembreep',
					'Ep',
					'Suppleant'
				),
				'limit' => 10
			);
			$membreseps = $this->paginate( $this->Membreep );
			foreach( $membreseps as &$membreep) {
				if (isset($membreep['Suppleant']['id']) && !empty($membreep['Suppleant']['id']))
					$membreep['Membreep']['nomcompletsuppleant'] = $membreep['Suppleant']['qual'].' '.$membreep['Suppleant']['nom'].' '.$membreep['Suppleant']['prenom'];
			}

			$this->_setOptions();
			$this->set( compact( 'membreseps' ) );
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
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Membreep->create( $this->data );
				$success = $this->Membreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $this->Membreep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Membreep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}
			
			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Membreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}

		public function ajaxfindsuppleant( $ep_id = null, $defaultvalue = '', $membreEp_id = 0 ) {
            Configure::write( 'debug', 0 );
            $suppleants = $this->Membreep->find(
            	'all',
            	array(
            		'conditions'=>array(
            			'Membreep.ep_id'=>$ep_id,
            			'Membreep.id <>'=>$membreEp_id
            		),
            		'contain'=>false
            	)
            );
			$listeSuppleant = array();
			foreach($suppleants as $suppleant) {
				$listeSuppleant[$suppleant['Membreep']['id']] = $suppleant['Membreep']['qual'].' '.$suppleant['Membreep']['nom'].' '.$suppleant['Membreep']['prenom'];
			}
            $this->set( compact( 'listeSuppleant' ) );
            $this->set( compact( 'defaultvalue' ) );
            $this->render( $this->action, 'ajax', '/membreseps/ajaxfindsuppleant' );
		}

		/**
		 * Dresse la liste de tous les membres de l'EP pour enregistrer ceux, parmis-eux, qui participeront à la séance.
		 * @param integer $ep_id Index de l'EP dont on veut récupérer tous les membres.
		 */
		public function editliste( $ep_id, $seance_id )
		{
			$membres = $this->Membreep->find('all', array(
				'conditions' => array(
					'Membreep.ep_id' => $ep_id
				),
				'contain' => array(
					'Seanceep',
					'Fonctionmembreep'
				)
			));
//			debug($membres);
			$this->set('membres', $membres);
			$this->set('seance_id', $seance_id);
			$this->_setOptions();
			if( !empty( $this->data ) )
			{
				debug($this->data);
				
			}
			
		}

	}
?>
