<?php
	class ReferentsController extends AppController
	{

		var $name = 'Referents';
		var $uses = array( 'Referent', 'Structurereferente', 'Option' );
		var $helpers = array( 'Xform' );

		var $commeDroit = array(
			'add' => 'Referents:edit'
		);

		public function beforeFilter() {
			$return = parent::beforeFilter();

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'fonction_pers', $this->Option->fonction_pers() );
			$this->set( 'referent', $this->Referent->find( 'list' ) );

			$options = array();
			foreach( array( 'Structurereferente' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}

			$this->set( compact( 'options' ) );

			return $return;
		}


		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$sr = $this->Structurereferente->find(
				'list',
				array(
					'fields' => array(
						'Structurereferente.lib_struc'
					),
				)
			);
			$this->set( 'sr', $sr );
			$referents = $this->Referent->find(
				'all',
				array(
					'recursive' => -1
				)

			);
			$this->set('referents', $referents);
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

		public function _add_edit() {
			$sr = $this->Structurereferente->find(
				'list',
				array(
					'fields' => array(
						'Structurereferente.lib_struc'
					),
				)
			);
			$this->set( 'sr', $sr );

			$args = func_get_args();
			call_user_func_array( array( $this->Default, $this->action ), $args );
		}

		public function delete( $referent_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $referent_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$referent = $this->Referent->find(
				'first',
				array( 'conditions' => array( 'Referent.id' => $referent_id )
				)
			);

			// Mauvais paramètre
			if( empty( $referent_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Referent->delete( array( 'Referent.id' => $referent_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
			}
		}
	}
?>
