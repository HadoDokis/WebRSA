<?php
	class EpsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		/**
		* FIXME: evite les droits
		*/

		public function beforeFilter() {
		}

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Ep->enums();
			if( $this->action != 'index' ) {
				$options['Ep']['regroupementep_id'] = $this->Ep->Regroupementep->find( 'list' );
				$options['Zonegeographique']['Zonegeographique'] = $this->Ep->Zonegeographique->find( 'list' );
			}
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$fields = array(
				'Ep.id',
				'Ep.name',
				'Ep.identifiant',
				'Regroupementep.name'
			);

			$themes = array();
			foreach( $this->Ep->themes() as $theme ) {
				if( strstr( $theme, Configure::read( 'Ep.departement' ) ) ) {
					$fields[] = "Ep.{$theme}";
					$themes[] = $theme;
				}
			}

			$this->paginate = array(
				'fields' => $fields,
				'contain' => array(
					'Regroupementep'
				),
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'eps', $this->paginate( $this->Ep ) );
			$this->set( compact( 'themes' ) );
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
				$this->Ep->begin();
				$this->Ep->create( $this->data );
				$success = $this->Ep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Ep->commit();
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Ep->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Ep->find(
					'first',
					array(
						'contain' => array(
							'Zonegeographique' => array(
								'fields' => array( 'id', 'libelle' )
							),
							'Membreep'
						),
						'conditions' => array( 'Ep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
				$this->set('ep_id', $id);
				$listeFonctionsMembres = $this->Ep->Membreep->Fonctionmembreep->find(
					'list'
				);
				$this->set(compact('listeFonctionsMembres'));
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Ep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
		
		/**
		 *
		 */
		 
		public function addparticipant($ep_id, $fonction_id) {
			if (!empty($this->data)) {
				//debug($this->data);
				$this->Ep->EpMembreep->begin();
				$this->Ep->EpMembreep->create( $this->data );
				$success = $this->Ep->EpMembreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Ep->EpMembreep->commit();
					$this->redirect( array( 'action' => 'edit', $ep_id ) );
				}
				else {
					$this->Ep->EpMembreep->rollback();
				}
			}
			
			$participants = $this->Ep->Membreep->find(
				'all',
				array(
					'conditions'=>array(
						'Membreep.fonctionmembreep_id'=>$fonction_id
					),
					'contain'=>false
				)
			);
			foreach($participants as $key=>$participant) {
				$count = $this->Ep->EpMembreep->find(
					'count',
					array(
						'conditions'=>array(
							'EpMembreep.membreep_id'=>$participant['Membreep']['id'],
							'EpMembreep.ep_id'=>$ep_id
						)
					)
				);
				if ($count>0)
					unset($participants[$key]);
			}
			$listeParticipants = array();
			foreach($participants as $participant) {
				$listeParticipants[$participant['Membreep']['id']] = implode(' ', array($participant['Membreep']['qual'], $participant['Membreep']['nom'], $participant['Membreep']['prenom']));
			}
			$this->set(compact('listeParticipants'));
			$this->set('ep_id', $ep_id);
		}
		
		/**
		 *
		 */
		 
		public function deleteparticipant($ep_id, $participant_id) {
			$success = $this->Ep->EpMembreep->deleteAll(
				array(
					'EpMembreep.ep_id'=>$ep_id,
					'EpMembreep.membreep_id'=>$participant_id
				)
			);
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'edit', $ep_id ) );
		}
		
	}
?>
